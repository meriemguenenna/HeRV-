
# MiniShop — Analyse et conception de la base de données

## 1. Introduction

Dans le cadre de la SAE MiniShop, une application web de commerce électronique développée en PHP et MySQL, une base de données relationnelle est nécessaire pour assurer la gestion des clients, des catégories, des produits et des commandes.

La conception de cette base repose sur trois étapes principales :

- Le **modèle conceptuel de données (MCD)**, qui représente les entités, leurs attributs et leurs associations.
- Le **modèle logique de données (MLD)**, qui traduit le MCD en relations avec des clés primaires et étrangères.
- La **normalisation**, qui permet de limiter les redondances et de garantir la cohérence des données.

L'objectif est d'obtenir une structure fiable, cohérente et adaptée aux fonctionnalités de MiniShop.

## 2. Modèle conceptuel de données (MCD)

### 2.1 Identification des entités

Le modèle conceptuel de MiniShop repose sur cinq entités principales.

### 2.1.1 Entité CLIENT

L'entité CLIENT représente les utilisateurs ayant créé un compte sur MiniShop.

Ses principaux attributs sont :

- `id_client` : identifiant unique du client ;
- `nom` : nom du client ;
- `prenom` : prénom du client ;
- `email` : adresse e-mail unique ;
- `password` : mot de passe haché ;
- `role` : rôle du compte ;
- `date_creation` : date de création du compte.

Chaque client possède un identifiant unique et peut passer plusieurs commandes.

### 2.1.2 Entité CATEGORIE

L'entité CATEGORIE représente les catégories auxquelles les produits sont associés.

Ses attributs sont :

- `id_categorie` : identifiant unique de la catégorie ;
- `nom` : nom de la catégorie.

MiniShop possède actuellement trois catégories :

- Informatique ;
- Téléphonie ;
- Accessoires.

### 2.1.3 Entité PRODUIT

L'entité PRODUIT représente les articles proposés dans le catalogue.

Ses principaux attributs sont :

- `id_produit` : identifiant unique du produit ;
- `nom` : nom du produit ;
- `description` : description du produit ;
- `prix` : prix de vente ;
- `stock` : quantité disponible ;
- `actif` : indique si le produit est actif.

Chaque produit appartient à une catégorie.

### 2.1.4 Entité COMMANDE

L'entité COMMANDE représente les achats enregistrés par les clients.

Ses principaux attributs sont :

- `id_commande` : identifiant unique de la commande ;
- `date_commande` : date de création de la commande ;
- `statut` : état de traitement de la commande.

Chaque commande est associée à un client.

Les informations de livraison devront également être prises en compte selon les colonnes définies dans le schéma SQL du projet.

### 2.1.5 Entité LIGNE_COMMANDE

L'entité LIGNE_COMMANDE représente les produits présents dans une commande.

Ses attributs sont :

- `quantite` : nombre d'unités commandées ;
- `prix_unitaire` : prix du produit au moment de l'achat.

Une ligne de commande relie un produit à une commande.

Son identification repose sur le couple `(id_commande, id_produit)`.

### 2.2 Associations et cardinalités

#### 2.2.1 Association CLIENT — COMMANDE

Un client peut passer aucune, une ou plusieurs commandes.

Une commande appartient obligatoirement à un seul client.

**Cardinalités :**

- CLIENT : `(0,N)`
- COMMANDE : `(1,1)`

#### 2.2.2 Association CATEGORIE — PRODUIT

Une catégorie peut contenir aucun, un ou plusieurs produits.

Chaque produit appartient obligatoirement à une seule catégorie.

**Cardinalités :**

- CATEGORIE : `(0,N)`
- PRODUIT : `(1,1)`

#### 2.2.3 Association COMMANDE — LIGNE_COMMANDE

Une commande validée doit contenir au moins une ligne de commande.

Une ligne de commande appartient à une seule commande.

**Cardinalités métier :**

- COMMANDE : `(1,N)`
- LIGNE_COMMANDE : `(1,1)`

Cette contrainte doit être respectée lors de la validation d'une commande, car une clé étrangère ne garantit pas à elle seule la présence d'une ligne.

#### 2.2.4 Association PRODUIT — LIGNE_COMMANDE

Un produit peut n'avoir jamais été commandé ou apparaître dans plusieurs commandes.

Chaque ligne de commande concerne un seul produit.

**Cardinalités :**

- PRODUIT : `(0,N)`
- LIGNE_COMMANDE : `(1,1)`

### 2.3 Justification des associations

La séparation entre CLIENT et COMMANDE permet de conserver les informations d'un client sans les répéter dans chaque commande.

La séparation entre CATEGORIE et PRODUIT évite de stocker plusieurs fois le nom d'une même catégorie.

La relation entre COMMANDE et PRODUIT est de type plusieurs-à-plusieurs :

- une commande peut contenir plusieurs produits ;
- un produit peut apparaître dans plusieurs commandes.

Cette relation est représentée par LIGNE_COMMANDE, qui conserve également la quantité et le prix unitaire au moment de l'achat.

## 3. Modèle logique de données (MLD)

Le modèle logique de données transforme les entités et les associations du MCD en relations.

Les clés primaires permettent d'identifier les enregistrements de manière unique.

Les clés étrangères permettent d'établir les relations entre les tables et de garantir l'intégrité référentielle.

### 3.1 Relation CLIENT

**MLD :**

`CLIENT(id_client PK, nom, prenom, email UNIQUE, password, role, date_creation)`

**Contraintes :**

- `id_client` est la clé primaire ;
- `email` doit être unique ;
- `password` contient un mot de passe haché ;
- `role` distingue les clients des administrateurs.

### 3.2 Relation CATEGORIE

**MLD :**

`CATEGORIE(id_categorie PK, nom)`

**Contraintes :**

- `id_categorie` est la clé primaire ;
- `nom` contient le nom de la catégorie.

### 3.3 Relation PRODUIT

**MLD :**

`PRODUIT(id_produit PK, nom, description, prix, stock, actif, id_categorie FK)`

**Contraintes :**

- `id_produit` est la clé primaire ;
- `id_categorie` est une clé étrangère vers CATEGORIE ;
- `prix` ne doit pas être négatif ;
- `stock` ne doit pas être négatif ;
- `actif` indique si le produit est disponible dans le catalogue public.

### 3.4 Relation COMMANDE

**MLD :**

`COMMANDE(id_commande PK, id_client FK, date_commande, statut)`

**Contraintes :**

- `id_commande` est la clé primaire ;
- `id_client` est une clé étrangère vers CLIENT ;
- `date_commande` correspond à la date d'enregistrement ;
- `statut` indique l'état de traitement de la commande.

**Remarque :** les attributs de livraison devront être ajoutés conformément à leur définition exacte dans `database/schema.sql`.

### 3.5 Relation LIGNE_COMMANDE

**MLD :**

`LIGNE_COMMANDE(id_commande PK/FK, id_produit PK/FK, quantite, prix_unitaire)`

**Clé primaire composée :**

`PK(id_commande, id_produit)`

**Clés étrangères :**

- `id_commande` référence `COMMANDE(id_commande)` ;
- `id_produit` référence `PRODUIT(id_produit)`.

**Contraintes :**

- `quantite` doit être strictement positive ;
- `prix_unitaire` conserve le prix au moment de la commande ;
- un même produit ne peut apparaître qu'une seule fois dans une commande avec cette clé composée.

### 3.6 Synthèse des clés étrangères

| Table | Clé étrangère | Table référencée |
|---|---|---|
| PRODUIT | id_categorie | CATEGORIE |
| COMMANDE | id_client | CLIENT |
| LIGNE_COMMANDE | id_commande | COMMANDE |
| LIGNE_COMMANDE | id_produit | PRODUIT |

Ces relations garantissent la cohérence des références entre les différentes tables.

## 4. Contraintes d'intégrité et règles métier

### 4.1 Contraintes sur les clients

- Chaque client possède un identifiant unique.
- Deux clients ne peuvent pas utiliser la même adresse e-mail.
- Les mots de passe sont hachés avant leur enregistrement.
- Le rôle permet de distinguer les utilisateurs classiques des administrateurs.

### 4.2 Contraintes sur les produits

- Chaque produit appartient à une catégorie existante.
- Le prix d'un produit ne doit pas être négatif.
- Le stock d'un produit ne doit pas être négatif.
- Un produit désactivé ne doit pas apparaître dans le catalogue public.

### 4.3 Contraintes sur les commandes

- Chaque commande appartient à un client existant.
- Une commande validée doit contenir au moins un produit.
- La quantité commandée doit être strictement positive.
- La quantité commandée ne doit pas dépasser le stock disponible au moment de la validation.
- Le statut d'une commande doit respecter les transitions autorisées.

### 4.4 Conservation du prix au moment de l'achat

Le prix d'un produit peut évoluer après la validation d'une commande.

Pour cette raison, LIGNE_COMMANDE possède un attribut `prix_unitaire`, distinct du prix actuel enregistré dans PRODUIT.

Cela permet de conserver le montant historique d'une commande, même lorsque le prix du catalogue change.

Il s'agit d'une conservation volontaire des données, justifiée par les besoins métier.

## 5. Normalisation de la base de données

La normalisation consiste à organiser les données afin de limiter les redondances et d'éviter les anomalies lors des insertions, modifications et suppressions.

### 5.1 Première forme normale (1FN)

Une relation respecte la première forme normale lorsque chaque attribut contient une valeur atomique et qu'il n'existe pas de groupes de valeurs répétées.

Dans MiniShop :

- les informations des clients sont stockées dans CLIENT ;
- les catégories sont stockées dans CATEGORIE ;
- les produits sont stockés dans PRODUIT ;
- les commandes sont stockées dans COMMANDE ;
- les produits commandés sont représentés individuellement dans LIGNE_COMMANDE.

Par exemple, une commande contenant trois produits possède trois lignes dans LIGNE_COMMANDE, et non une seule colonne contenant une liste de produits.

Cette organisation respecte la première forme normale.

### 5.2 Deuxième forme normale (2FN)

Une relation respecte la deuxième forme normale lorsqu'elle est en première forme normale et que chaque attribut non-clé dépend de la totalité de chaque clé candidate.

Dans MiniShop, les tables CLIENT, CATEGORIE, PRODUIT et COMMANDE possèdent des clés primaires simples.

La table LIGNE_COMMANDE possède une clé primaire composée :

`(id_commande, id_produit)`

Ses attributs non-clés sont :

- `quantite` ;
- `prix_unitaire`.

Ces attributs dépendent du couple commande-produit et non uniquement de l'un des deux identifiants.

Par exemple, la quantité d'un même produit peut varier selon la commande.

La structure proposée respecte donc la deuxième forme normale.

### 5.3 Troisième forme normale (3FN)

Une relation respecte la troisième forme normale lorsqu'elle est en deuxième forme normale et qu'aucun attribut non-clé ne dépend transitivement d'une clé par l'intermédiaire d'un autre attribut non-clé.

Dans MiniShop :

- les informations personnelles dépendent de `id_client` ;
- les informations d'une catégorie dépendent de `id_categorie` ;
- les caractéristiques d'un produit dépendent de `id_produit` ;
- les informations d'une commande dépendent de `id_commande` ;
- les quantités et prix historiques dépendent du couple `(id_commande, id_produit)`.

Par exemple, la table PRODUIT contient `id_categorie`, mais ne répète pas le nom de la catégorie.

Le nom de la catégorie est récupéré grâce à la relation avec CATEGORIE.

Cette organisation évite qu'un changement de nom de catégorie nécessite la modification de plusieurs produits.

Sous réserve que les attributs complémentaires du schéma SQL respectent les mêmes dépendances, la structure proposée est conforme à la troisième forme normale.

### 5.4 Dépendances fonctionnelles

Les principales dépendances fonctionnelles sont les suivantes.

**CLIENT :**

`id_client → nom, prenom, email, password, role, date_creation`

L'adresse e-mail étant unique :

`email → id_client, nom, prenom, password, role, date_creation`

**CATEGORIE :**

`id_categorie → nom`

**PRODUIT :**

`id_produit → nom, description, prix, stock, actif, id_categorie`

**COMMANDE :**

`id_commande → id_client, date_commande, statut`

**LIGNE_COMMANDE :**

`(id_commande, id_produit) → quantite, prix_unitaire`

Ces dépendances fonctionnelles justifient les clés primaires et la répartition des attributs entre les tables.

### 5.5 Analyse des redondances

La séparation des données entre plusieurs tables permet de limiter les redondances.

Toutefois, certaines informations doivent être conservées volontairement pour respecter les règles métier.

C'est le cas de `prix_unitaire` dans LIGNE_COMMANDE.

Cet attribut permet de conserver le prix historique du produit au moment de la commande.

Il ne doit pas être remplacé par le prix actuel de PRODUIT, car cela modifierait rétroactivement le montant des anciennes commandes.

Cette conservation ne constitue pas, à elle seule, une violation de la troisième forme normale.

## 6. Procédures stockées et triggers

MiniShop utilise des procédures stockées et des triggers afin de compléter les traitements PHP et de renforcer la cohérence des données.

### 6.1 Procédures stockées

Cinq procédures stockées sont créées dans la base de données.

| Procédure | Rôle |
|---|---|
| `rechercher_produit` | Rechercher des produits par nom |
| `produits_par_categorie` | Filtrer les produits par catégorie |
| `commandes_client` | Récupérer les commandes d'un client |
| `lignes_commande` | Récupérer les produits d'une commande |
| `modifier_stock` | Modifier le stock d'un produit en vérifiant sa validité |

Les procédures `rechercher_produit` et `produits_par_categorie` sont déjà utilisées depuis le code PHP du catalogue.

Les autres procédures seront intégrées aux fonctionnalités de commandes et d'administration.

### 6.2 Triggers

Cinq triggers sont également présents dans la base de données.

| Trigger | Objectif |
|---|---|
| `verifier_modification_ligne` | Contrôler les modifications des lignes de commande |
| `verifier_produit_insertion` | Vérifier les données lors de l'ajout d'un produit |
| `verifier_produit_modification` | Vérifier les données lors de la modification d'un produit |
| `verifier_statut_commande` | Contrôler les changements de statut |
| `verifier_stock_commande` | Contrôler les stocks lors des opérations de commande |

Ces triggers permettent d'appliquer certaines règles métier directement au niveau de la base de données.

Leur comportement précis est défini dans le fichier `database/triggers.sql`.

## 7. Conclusion

La conception de la base de données MiniShop repose sur cinq entités principales :

- CLIENT ;
- CATEGORIE ;
- PRODUIT ;
- COMMANDE ;
- LIGNE_COMMANDE.

Le MCD permet de représenter leurs associations et leurs cardinalités.

Le MLD définit les tables, les clés primaires et les clés étrangères nécessaires au fonctionnement du système.

L'analyse des dépendances fonctionnelles justifie une organisation visant le respect de la troisième forme normale (3FN), afin de limiter les redondances et de faciliter la maintenance.

Les procédures stockées et les triggers complètent cette conception en participant au respect des règles métier.

Cette structure constitue une base cohérente pour les fonctionnalités de catalogue, de gestion des comptes clients, de commandes et d'administration de MiniShop.
