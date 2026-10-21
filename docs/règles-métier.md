## Règles métier

Cette section formalise les règles qui définissent les contraintes de fonctionnement de MiniShop et de ses données. Chaque règle est identifiée par un code `RMn`, précise les données ou fonctionnalités concernées, et indique comment elle est garantie **dans la base de données** (BDD) et **dans l'application**.

### Sommaire

- [Règles sur les clients](#règles-sur-les-clients)
- [Règles sur les produits et les catégories](#règles-sur-les-produits-et-les-catégories)
- [Règles sur les commandes](#règles-sur-les-commandes)
- [Récapitulatif des contraintes en base de données](#récapitulatif-des-contraintes-en-base-de-données)
- [Cohérence avec le modèle de données et les fonctionnalités](#cohérence-avec-le-modèle-de-données-et-les-fonctionnalités)

### Règles sur les clients

| Code | Règle | Données / fonctionnalités concernées | Garantie en BDD | Garantie dans l'application |
|---|---|---|---|---|
| RM1 | L'adresse e-mail d'un client doit être unique. | `client.email` ; inscription, modification du profil, connexion | Contrainte `UNIQUE` sur `email`, colonne `NOT NULL` | E-mail mis en minuscules et sans espaces avant l'enregistrement ; message d'erreur clair si l'adresse existe déjà |

### Règles sur les produits et les catégories

| Code | Règle | Données / fonctionnalités concernées | Garantie en BDD | Garantie dans l'application |
|---|---|---|---|---|
| RM2 | Le prix d'un produit doit être strictement positif. | `produit.prix` ; création et modification d'un produit (administrateur) | `CHECK (prix > 0)` | Validation du formulaire avant l'envoi ; refus du prix nul ou négatif avec un message explicite |
| RM3 | Le stock d'un produit ne doit jamais être négatif. | `produit.stock` ; ajout au panier, validation de commande, réapprovisionnement | `CHECK (stock >= 0)` | Vérification du stock à l'ajout au panier et à la validation ; décrémentation faite dans une transaction (`UPDATE ... WHERE stock >= quantité`) pour éviter les conflits entre deux commandes simultanées |
| RM4 | Un produit appartient à une seule catégorie. | `produit.categorie_id` ; création et modification d'un produit | Clé étrangère `NOT NULL` vers `categorie(id)` | Liste déroulante à choix unique dans le formulaire produit |
| RM5 | Une catégorie peut contenir plusieurs produits. | Relation `categorie` 1 — N `produit` ; affichage du catalogue par catégorie | Aucune contrainte limitant le nombre de produits par catégorie | Page catégorie qui liste tous ses produits |

### Règles sur les commandes

| Code | Règle | Données / fonctionnalités concernées | Garantie en BDD | Garantie dans l'application |
|---|---|---|---|---|
| RM6 | Une commande doit contenir au moins une ligne de commande. | `commande`, `ligne_commande` ; validation du panier | Non exprimable par une simple contrainte : vérification dans la transaction de création, ou déclencheur différé (`DEFERRABLE`) en complément | Création de la commande et de ses lignes dans **une seule transaction** ; validation refusée si le panier est vide |
| RM7 | La quantité d'un produit dans une ligne de commande doit être strictement positive. | `ligne_commande.quantite` ; panier, validation | `CHECK (quantite > 0)` | Validation de la quantité à la saisie dans le panier |
| RM8 | Le prix enregistré dans une ligne de commande doit correspondre au prix du produit au moment de l'achat. | `ligne_commande.prix_unitaire` ; validation de commande, historique des commandes | Colonne `prix_unitaire` **copiée** à la création de la ligne (le prix n'est pas relu dans `produit` ensuite), avec `CHECK (prix_unitaire > 0)` | Au moment de la validation, le prix courant du produit est recopié dans la ligne ; un changement de prix ultérieur ne modifie pas les commandes passées |
| RM9 | Un client peut avoir plusieurs commandes. | Relation `client` 1 — N `commande` ; historique des commandes du client | Aucune contrainte limitant le nombre de commandes par client | Page « Mes commandes » listant toutes les commandes du client connecté |
| RM10 | Une commande appartient à un seul client. | `commande.client_id` ; validation de commande | Clé étrangère `NOT NULL` vers `client(id)` | Le client est celui de la session ; il n'est pas modifiable depuis l'interface |
| RM11 | Une commande possède un statut permettant de suivre son état. | `commande.statut` ; suivi de commande, gestion des commandes (administrateur) | Colonne `NOT NULL`, valeur par défaut `EN_ATTENTE`, `CHECK` limitant aux valeurs autorisées | Seules les transitions prévues sont acceptées (voir ci-dessous) |

**Statuts d'une commande** (à adapter) : `EN_ATTENTE` → `PAYEE` → `EXPEDIEE` → `LIVREE`, avec `ANNULEE` possible tant que la commande n'est pas expédiée.

### Récapitulatif des contraintes en base de données

Exemple de schéma (syntaxe PostgreSQL) montrant comment les règles sont garanties :

```sql
CREATE TABLE categorie (
    id  SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE client (
    id    SERIAL PRIMARY KEY,
    nom   VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE                          -- RM1
);

CREATE TABLE produit (
    id           SERIAL PRIMARY KEY,
    nom          VARCHAR(150) NOT NULL,
    prix         NUMERIC(10,2) NOT NULL CHECK (prix > 0),       -- RM2
    stock        INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0), -- RM3
    categorie_id INTEGER NOT NULL REFERENCES categorie(id)      -- RM4, RM5
);

CREATE TABLE commande (
    id            SERIAL PRIMARY KEY,
    client_id     INTEGER NOT NULL REFERENCES client(id),       -- RM9, RM10
    date_commande TIMESTAMP NOT NULL DEFAULT now(),
    statut        VARCHAR(20) NOT NULL DEFAULT 'EN_ATTENTE'     -- RM11
        CHECK (statut IN ('EN_ATTENTE','PAYEE','EXPEDIEE','LIVREE','ANNULEE'))
);

CREATE TABLE ligne_commande (
    id            SERIAL PRIMARY KEY,
    commande_id   INTEGER NOT NULL REFERENCES commande(id) ON DELETE CASCADE,
    produit_id    INTEGER NOT NULL REFERENCES produit(id),
    quantite      INTEGER NOT NULL CHECK (quantite > 0),        -- RM7
    prix_unitaire NUMERIC(10,2) NOT NULL CHECK (prix_unitaire > 0) -- RM8
);
```

### Cohérence avec le modèle de données et les fonctionnalités

- **Règles garanties par la base seule** : RM1, RM2, RM3, RM4, RM7, RM10, RM11 (contraintes `UNIQUE`, `CHECK`, clés étrangères).
- **Règles nécessitant l'application** : RM6 (une commande non vide) et RM8 (copie du prix à l'achat) ne peuvent pas être déduites d'une simple contrainte et sont assurées par la logique de validation de commande.
- **Double protection** : les règles sont vérifiées dans l'application pour donner un message clair à l'utilisateur, puis protégées dans la base pour qu'aucune donnée incohérente ne puisse être enregistrée, même en cas de bug ou d'accès direct.
- **Fonctionnalités liées** : inscription (RM1), gestion du catalogue (RM2 à RM5), panier et validation de commande (RM3, RM6 à RM8, RM10), suivi des commandes (RM9, RM11). Elles correspondent aux cas d'utilisation décrits dans la documentation des cas d'utilisation.