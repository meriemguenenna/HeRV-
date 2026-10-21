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

Le diagramme de cas d'utilisation présente les différentes fonctionnalités de l'application MiniShop ainsi que les interactions entre les utilisateurs et le système.

L'application comporte trois acteurs principaux :

    Visiteur : il peut consulter le catalogue, rechercher et filtrer des produits, consulter les catégories et les fiches détaillées des produits, ainsi que créer un compte.

    Client : il dispose des fonctionnalités de consultation du visiteur. Il peut également se connecter, se déconnecter, gérer son panier, passer une commande, consulter son historique de commandes, annuler une commande et modifier ses informations personnelles.

    Administrateur : il est chargé de gérer les produits, les catégories, les stocks et les commandes, ainsi que de modifier le statut des commandes.

Le diagramme représente 18 cas d'utilisation, couvrant les 14 cas minimum demandés dans le cahier des charges ainsi que des fonctionnalités complémentaires.

![Diagramme de cas d'utilisation MiniShop](uml/cas-utilisation.png)

### 5.2 Documentation des cas d'utilisation

Afin de préciser le fonctionnement de l'application MiniShop, trois cas d'utilisation ont été sélectionnés pour faire l'objet d'une description textuelle détaillée :

Créer un compte : permet à un visiteur de s'inscrire sur l'application MiniShop.

Gérer le panier : permet à un client d'ajouter des produits à son panier, de modifier les quantités et de supprimer des produits.

Gérer les produits : permet à un administrateur d'ajouter, de modifier et de supprimer des produits du catalogue.

Pour chaque cas d'utilisation, la description précisera :

l'acteur principal ;

les préconditions ;

le scénario nominal ;

les scénarios alternatifs ;

les erreurs éventuelles.

Ces descriptions permettront de détailler les interactions entre les utilisateurs et le système ainsi que les règles à respecter lors de l'exécution des fonctionnalités.

### 5.2.1 Cas d'utilisation : Créer un compte

Acteur principal : Visiteur.

Objectif : Permettre à un visiteur de créer un compte client afin d'accéder aux fonctionnalités réservées aux clients de MiniShop.

Préconditions :

    - Le visiteur peut accéder à la page d'inscription.

    - Le visiteur ne possède pas déjà un compte associé à l'adresse email renseignée.

Scénario nominal :

    1- Le visiteur accède à la page d'inscription.

    2- Le système affiche le formulaire de création de compte.

    3- Le visiteur renseigne son nom, son prénom, son adresse email et son mot de passe.

    4- Le visiteur valide le formulaire.

    5- Le système vérifie que les champs obligatoires sont renseignés et que les données sont valides.

    6- Le système vérifie que l'adresse email n'est pas déjà utilisée.

    7- Le système sécurise le mot de passe à l'aide de password_hash().

    8- Le système enregistre les informations du nouveau client dans la base de données.

    9- Le système affiche un message confirmant la création du compte.

Scénarios alternatifs :

    A1 — Adresse email déjà utilisée : le système affiche un message d'erreur et demande au visiteur de saisir une autre adresse email.

    A2 — Champs obligatoires manquants : le système signale les champs à compléter.

    A3 — Données invalides : le système demande au visiteur de corriger les informations concernées.

Erreurs possibles :

    - Une erreur de connexion à la base de données empêche l'inscription.

    - Une erreur survient pendant l'enregistrement du compte.

Dans ces situations, le système affiche un message d'erreur sans divulguer d'informations techniques sensibles.

Postconditions :

    - Un nouveau compte client est enregistré dans la base de données.

    - Le mot de passe est stocké sous forme de hachage.

    - Le client peut désormais se connecter à MiniShop.

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
```
### 8.4 Gestion des sessions

Les pages privées doivent vérifier la présence de :

```php
$_SESSION['user_id']
```
### 8.5 Autorisation

Un client ne doit pas pouvoir accéder à une commande appartenant à un autre client simplement en modifiant l'identifiant dans l'URL.

Par exemple :

```text
commande.php?id=25
```
## 9. Architecture du système

L'application MiniShop doit utiliser une architecture à trois couches basée sur le modèle MVC :

- **Modèle** : gestion des données et communication avec la base de données ;
- **Vue** : affichage de l'interface utilisateur ;
- **Contrôleur** : gestion des requêtes et coordination entre les modèles et les vues.

