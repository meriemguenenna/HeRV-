# MiniShop — Spécification du système

## 1. Présentation du projet

MiniShop est une application Web de commerce électronique permettant aux clients de consulter un catalogue de produits, de gérer leur panier et de passer des commandes.

L'application dispose également d'un espace d'administration permettant aux gestionnaires de gérer les produits, les catégories, les stocks et les commandes.

## 2. Objectifs

Le projet MiniShop a pour objectifs de mettre en pratique les connaissances acquises en :

- conception et modélisation de bases de données ;
- SQL et MySQL ;
- développement PHP ;
- programmation orientée objet ;
- accès aux bases de données avec PDO ;
- programmation côté client avec JavaScript ;
- authentification et gestion des sessions ;
- sécurité des applications Web ;
- procédures stockées ;
- déclencheurs (triggers) ;
- tests et validation d'une application Web.

## 3. Acteurs

### 3.1 Visiteur

Le visiteur peut :

- consulter les catégories ;
- rechercher un produit ;
- filtrer les produits ;
- consulter la fiche détaillée d'un produit ;
- créer un compte.

### 3.2 Client

Le client peut :

- se connecter et se déconnecter ;
- modifier ses informations ;
- ajouter des produits au panier ;
- modifier les quantités ;
- supprimer des produits du panier ;
- valider une commande ;
- consulter son historique de commandes.

### 3.3 Administrateur

L'administrateur peut :

- ajouter, modifier et supprimer une catégorie ;
- ajouter, modifier et supprimer un produit ;
- modifier le stock ;
- consulter les commandes ;
- modifier le statut d'une commande.

## 4. Fonctionnalités

### 4.1 Fonctionnalités du visiteur

- Consulter les catégories.
- Rechercher un produit.
- Filtrer les produits.
- Consulter la fiche détaillée d'un produit.
- Créer un compte.

### 4.2 Fonctionnalités du client

- Se connecter / se déconnecter.
- Modifier ses informations.
- Ajouter des produits au panier.
- Modifier les quantités.
- Supprimer des produits du panier.
- Valider une commande.
- Consulter l'historique de ses commandes.

### 4.3 Fonctionnalités de l'administrateur

- Ajouter, modifier et supprimer une catégorie.
- Ajouter, modifier et supprimer un produit.
- Modifier le stock.
- Consulter les commandes.
- Modifier le statut d'une commande.

## 5. Diagrammes UML

### 5.1 Diagramme de cas d'utilisation

Le diagramme de cas d'utilisation doit représenter les trois acteurs principaux :

- Visiteur ;
- Client ;
- Administrateur.

Les cas d'utilisation minimum à identifier sont :

1. Consulter le catalogue
2. Rechercher un produit
3. Consulter un produit
4. Créer un compte
5. Se connecter
6. Gérer le panier
7. Passer une commande
8. Consulter l'historique des commandes
9. Annuler une commande
10. Gérer les produits
11. Gérer les catégories
12. Gérer les stocks
13. Gérer les commandes
14. Modifier le statut d'une commande

### 5.2 Documentation des cas d'utilisation

Les cas d'utilisation doivent être documentés avec une description textuelle.

Trois cas d'utilisation doivent être détaillés avec :

- l'acteur principal ;
- les préconditions ;
- le scénario nominal ;
- les scénarios alternatifs ;
- les erreurs éventuelles.

### 5.3 Diagramme de classes

Le diagramme de classes doit :

- identifier les classes ;
- définir leurs attributs ;
- définir les méthodes principales ;
- définir les associations ;
- préciser les cardinalités ;
- justifier les choix de conception.

### 5.4 Diagrammes de séquence

Au moins quatre diagrammes de séquence doivent être réalisés :

- authentification ;
- recherche d'un produit ;
- ajout au panier ;
- passage d'une commande.

Les procédures stockées et les déclencheurs peuvent être intégrés aux diagrammes lorsque cela est pertinent.

## 6. Règles métier

Les règles métier du système sont les suivantes :

1. L'adresse email d'un client doit être unique.
2. Le prix d'un produit doit être strictement positif.
3. Le stock ne peut jamais être négatif.
4. Une commande doit contenir au moins une ligne.
5. La quantité commandée doit être supérieure à zéro.
6. Le prix enregistré dans une ligne de commande correspond au prix au moment de l'achat.
7. Un produit appartient à une seule catégorie.
8. Une catégorie peut contenir plusieurs produits.
9. Un client peut passer plusieurs commandes.
10. Une commande appartient à un seul client.
11. Une commande peut avoir différents états.

## 7. Contraintes techniques

L'application doit utiliser :

- PHP pour le développement côté serveur ;
- MySQL pour la base de données ;
- PDO pour l'accès à la base de données ;
- JavaScript pour la programmation côté client ;
- une architecture MVC en trois couches ;
- des procédures stockées ;
- des déclencheurs SQL ;
- GitLab pour le développement et le suivi du projet.

## 8. Sécurité

### 8.1 Protection contre les injections SQL

Toutes les données provenant des utilisateurs doivent être traitées avec :

- `PDO::prepare()`
- `PDO::execute()`

La concaténation directe des paramètres utilisateur dans les requêtes SQL doit être interdite.

### 8.2 Protection des mots de passe

Les mots de passe ne doivent pas être enregistrés en clair.

L'application doit utiliser :

- `password_hash()`
- `password_verify()`

### 8.3 Protection contre les attaques XSS

Les données affichées doivent être échappées avec :

```php
htmlspecialchars()