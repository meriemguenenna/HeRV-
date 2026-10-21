### MiniShop

## Présentation du projet

MiniShop est une application web de commerce électronique développée dans le cadre d'une SAE du BUT Informatique.

L'objectif est de concevoir une boutique en ligne permettant de consulter un catalogue de produits, de gérer un panier et, à terme, de passer des commandes.

Le projet est développé en binôme et suit une architecture MVC (Modèle – Vue – Contrôleur).

## Technologies utilisées

- PHP : développement côté serveur

- MySQL : gestion de la base de données

- PDO : communication sécurisée entre PHP et MySQL

- HTML5 / CSS3 : structure et mise en forme des pages

- JavaScript : évolutions possibles de l'interface

- Git / GitLab : versionnement et travail collaboratif

- PlantUML : modélisation UML

## Fonctionnalités réalisées

# Catalogue de produits

- Affichage des produits actifs depuis la base MySQL
- Présentation du nom, de la description, du prix et du stock
- Affichage des images des produits
- Interface responsive avec un design violet

# Gestion du panier

- Ajout de produits au panier

- Conservation du panier avec les sessions PHP

- Affichage des produits sélectionnés

- Calcul automatique des sous-totaux et du total

- Augmentation et diminution des quantités

- Suppression d'un produit

- Vérification du stock disponible

- Protection des formulaires avec un jeton CSRF

# Base de données

- Gestion des clients, catégories, produits, commandes et lignes de commande

- Contraintes d'intégrité

- Procédures stockées

- Triggers pour certaines règles métier

- Jeux de données de test

## Fonctionnalités prévues

- Inscription et connexion des clients

- Gestion des comptes utilisateurs

- Validation des commandes

- Consultation de l'historique des commandes

- Interface d'administration

- Gestion des produits et des catégories par un administrateur

Le paiement bancaire réel n'est pas encore implémenté.

## Architecture du projet

minishop/
├── app/
│   ├── controllers/
│   │   ├── ProduitController.php
│   │   └── PanierController.php
│   ├── models/
│   │   └── Produit.php
│   └── views/
│       ├── produits/
│       │   └── index.php
│       └── panier/
│           └── index.php
├── config/
│   └── database.php
├── database/
│   ├── schema.sql
│   ├── data.sql
│   ├── procedures.sql
│   └── triggers.sql
├── docs/
│   ├── specification.md
│   └── tests-validation.md
├── public/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   └── index.php
├── .gitignore
└── README.md

## Installation et configuration

# Prérequis

- PHP avec l'extension PDO MySQL

- Serveur web compatible PHP

- Serveur MySQL

- Git

# Mise en place

1. Récupérer le dépôt GitLab.

2. Créer une base de données MySQL.

3. Exécuter les scripts SQL du dossier database/ dans l'ordre : schema.sql, procedures.sql, triggers.sql, puis data.sql.

4. Configurer le fichier local config/database.php avec les paramètres de connexion MySQL.

5. Configurer le serveur web pour accéder à public/index.php.

6. Ouvrir l'application dans un navigateur.

# Sécurité : le fichier config/database.php contient des informations locales sensibles. Il est exclu du suivi Git grâce au fichier .gitignore.

## Tests

Les tests réalisés concernent notamment :

- La recherche de produits

- La récupération des produits par catégorie

- La consultation des commandes

- Les contraintes de prix et de stock

- La vérification des transitions de statut

- Les procédures stockées et les triggers

Le détail des tests SQL est disponible dans docs/tests-validation.md.

## Organisation du travail

Le projet est développé en binôme avec GitLab.

Les fonctionnalités sont développées sur des branches dédiées, puis peuvent être intégrées après vérification.

Exemple de branche :

feature/minishop-mvc-panier

Les commits sont organisés par fonctionnalité afin de conserver un historique clair.

## État d'avancement

Le catalogue et le panier sont fonctionnels.

Les prochaines étapes concernent l'authentification, la validation des commandes et les fonctionnalités d'administration.

## Contexte

Projet universitaire — SAE, BUT Informatique.