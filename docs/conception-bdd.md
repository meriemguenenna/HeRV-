# MiniShop — Analyse et conception de la base de données

## 1. Modèle conceptuel de données (MCD)

Le modèle conceptuel de données permet d'identifier les entités et les associations nécessaires au fonctionnement de MiniShop.

Les principales entités du système sont :

- CLIENT
- CATEGORIE
- PRODUIT
- COMMANDE
- LIGNE_COMMANDE

Les associations et les cardinalités doivent être déterminées et justifiées à partir des règles métier du projet.

### 1.1 Entité CLIENT

L'entité CLIENT représente les utilisateurs ayant créé un compte et pouvant passer des commandes.

### 1.2 Entité CATEGORIE

L'entité CATEGORIE représente les catégories auxquelles les produits sont associés.

### 1.3 Entité PRODUIT

L'entité PRODUIT représente les produits disponibles dans le catalogue.

### 1.4 Entité COMMANDE

L'entité COMMANDE représente les commandes passées par les clients.

### 1.5 Entité LIGNE_COMMANDE

L'entité LIGNE_COMMANDE représente les produits et les quantités associés à une commande.

## 2. Modèle logique de données (MLD)

Le modèle logique de données permet de transformer le modèle conceptuel en relations.

Les relations devront notamment prendre en compte :

- les clés primaires ;
- les clés étrangères ;
- les relations entre les différentes tables ;
- les contraintes nécessaires au respect des règles métier.

## 3. Normalisation

La base de données devra être normalisée afin de limiter les redondances et d'assurer la cohérence des données.

L'analyse devra notamment présenter :

- les dépendances fonctionnelles ;
- la première forme normale (1FN) ;
- la deuxième forme normale (2FN) ;
- la troisième forme normale (3FN) ;
- la justification des clés primaires ;
- la justification des clés étrangères ;
- les éventuelles redondances.

## 2. Modèle logique de données (MLD)

### 2.1 Relation CLIENT

CLIENT(
    id_client PK,
    nom,
    prenom,
    email UNIQUE,
    password,
    date_creation
)

### 2.2 Relation CATEGORIE

CATEGORIE(
    id_categorie PK,
    nom
)

### 2.3 Relation PRODUIT

PRODUIT(
    id_produit PK,
    nom,
    description,
    prix,
    stock,
    id_categorie FK
)

### 2.4 Relation COMMANDE

COMMANDE(
    id_commande PK,
    id_client FK,
    date_commande,
    statut
)

### 2.5 Relation LIGNE_COMMANDE

LIGNE_COMMANDE(
    id_commande PK, FK,
    id_produit PK, FK,
    quantite,
    prix_unitaire
)

### 2.6 Relations entre les tables

- Un client peut passer plusieurs commandes.
- Une commande appartient à un seul client.
- Une catégorie peut contenir plusieurs produits.
- Un produit appartient à une seule catégorie.
- Une commande contient une ou plusieurs lignes de commande.
- Une ligne de commande correspond à un produit.
- Le prix enregistré dans une ligne de commande correspond au prix du produit au moment de l'achat.

## 3. Normalisation de la base de données

La normalisation permet de structurer les données afin de limiter les redondances et d'éviter les anomalies lors des opérations sur la base de données.

### 3.1 Première forme normale (1NF)

Les relations respectent la première forme normale lorsque chaque attribut contient une valeur atomique et qu'il n'existe pas de groupe de valeurs répétitives.

Dans MiniShop :
- les informations du client sont stockées dans CLIENT ;
- les informations du produit sont stockées dans PRODUIT ;
- les informations d'une commande sont stockées dans COMMANDE ;
- les produits associés à une commande sont stockés dans LIGNE_COMMANDE.

### 3.2 Deuxième forme normale (2NF)

La deuxième forme normale impose que chaque attribut non-clé dépende de toute la clé primaire.

La table LIGNE_COMMANDE possède une clé composée de `id_commande` et `id_produit`. Les attributs `quantite` et `prix_unitaire` dépendent de l'association entre une commande et un produit.

Les autres tables possèdent une clé primaire simple, leurs attributs dépendent donc directement de cette clé.

### 3.3 Troisième forme normale (3NF)

La troisième forme normale impose que les attributs non-clés ne dépendent pas d'un autre attribut non-clé.

Dans MiniShop :
- les informations du client dépendent de `id_client` ;
- les informations du produit dépendent de `id_produit` ;
- les informations de la catégorie dépendent de `id_categorie` ;
- les informations de la commande dépendent de `id_commande`.

Les informations sont ainsi réparties dans les tables correspondant à leur domaine afin de limiter la redondance.

### 3.4 Dépendances fonctionnelles

Les principales dépendances fonctionnelles sont :

- `id_client → nom, prenom, email, password, date_creation`
- `id_categorie → nom`
- `id_produit → nom, description, prix, stock, id_categorie`
- `id_commande → id_client, date_commande, statut`
- `(id_commande, id_produit) → quantite, prix_unitaire`

### 3.5 Conclusion

La structure proposée sépare les différentes informations du système et permet de respecter les principes de normalisation jusqu'à la troisième forme normale (3NF).

Cette organisation permet notamment de réduire les redondances et de faciliter la gestion des données.