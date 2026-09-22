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