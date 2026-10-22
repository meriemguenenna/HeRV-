
# MiniShop — Spécification du système

## 1. Présentation du projet

MiniShop est une application Web de commerce électronique développée dans le cadre d'une SAE de BUT Informatique.

Elle permet aux visiteurs de consulter un catalogue de produits technologiques, de rechercher des articles, de filtrer les produits par catégorie et de consulter leurs fiches détaillées.

Les utilisateurs peuvent créer un compte client afin d'accéder à des fonctionnalités supplémentaires, notamment la gestion de leur profil, du panier et des commandes.

L'application prévoit également un espace d'administration permettant de gérer les produits, les catégories, les stocks et le suivi des commandes.

Le projet repose sur une architecture MVC (Modèle-Vue-Contrôleur) utilisant PHP, MySQL, PDO, HTML, CSS et JavaScript.

## 2. Objectifs du projet

### 2.1 Objectif général

L'objectif principal est de concevoir et développer une application Web de commerce électronique fonctionnelle, sécurisée et organisée selon les principes de la programmation orientée objet.

### 2.2 Objectifs techniques et pédagogiques

Le projet permet de mettre en pratique les compétences suivantes :

- Concevoir et modéliser une base de données relationnelle.
- Réaliser un modèle conceptuel de données (MCD).
- Réaliser un modèle logique de données (MLD).
- Appliquer les principes de normalisation.
- Utiliser SQL et MySQL.
- Développer une application en PHP orienté objet.
- Accéder à la base de données avec PDO.
- Utiliser des requêtes préparées.
- Organiser le code selon une architecture MVC.
- Développer des interactions côté client avec JavaScript.
- Gérer l'authentification et les sessions.
- Mettre en place des protections de sécurité Web.
- Créer et utiliser des procédures stockées.
- Créer et utiliser des déclencheurs SQL (triggers).
- Réaliser des diagrammes UML.
- Effectuer des tests fonctionnels et de sécurité.
- Utiliser Git et GitLab pour le suivi du développement.

## 3. Acteurs du système

MiniShop comporte trois acteurs principaux : le visiteur, le client et l'administrateur.

### 3.1 Visiteur

Le visiteur est un utilisateur qui consulte MiniShop sans être connecté à un compte.

Il peut :

- Consulter le catalogue de produits.
- Consulter les catégories.
- Rechercher un produit par son nom.
- Filtrer les produits par catégorie.
- Consulter la fiche détaillée d'un produit.
- Créer un compte.
- Accéder à la page de connexion.

### 3.2 Client

Le client est un utilisateur possédant un compte MiniShop.

Il bénéficie des fonctionnalités de consultation du visiteur.

Il peut également :

- Se connecter.
- Se déconnecter.
- Consulter son profil.
- Modifier son nom, son prénom et son adresse e-mail.
- Ajouter des produits à son panier.
- Consulter son panier.
- Augmenter ou diminuer les quantités.
- Supprimer des produits du panier.
- Saisir une adresse de livraison.
- Valider une commande.
- Consulter l'historique de ses commandes.
- Consulter le détail de ses commandes.
- Annuler une commande lorsque son statut le permet.

### 3.3 Administrateur

L'administrateur est un utilisateur disposant de droits supplémentaires.

Il peut :

- Accéder à l'espace d'administration.
- Ajouter une catégorie.
- Modifier une catégorie.
- Supprimer une catégorie lorsque les contraintes le permettent.
- Ajouter un produit.
- Modifier un produit.
- Désactiver ou supprimer un produit selon les règles métier.
- Modifier le stock d'un produit.
- Consulter les commandes des clients.
- Consulter le détail d'une commande.
- Modifier le statut d'une commande.

L'accès aux fonctionnalités administrateur doit être limité aux utilisateurs possédant le rôle approprié.

## 4. Spécifications fonctionnelles

### 4.1 Consultation du catalogue

Le système doit permettre à un visiteur ou à un client de :

- Afficher les produits actifs.
- Consulter leur nom, leur description et leur prix.
- Afficher les images des produits.
- Consulter la quantité disponible en stock.
- Rechercher des produits par leur nom.
- Filtrer les produits par catégorie.
- Consulter la fiche détaillée d'un produit.

Les produits désactivés ne doivent pas apparaître dans le catalogue public.

### 4.2 Inscription et authentification

Le système doit permettre à un visiteur de créer un compte en renseignant :

- Son nom.
- Son prénom.
- Son adresse e-mail.
- Son mot de passe.
- La confirmation de son mot de passe.

Le système doit :

- Vérifier les champs obligatoires.
- Vérifier le format de l'adresse e-mail.
- Refuser une adresse e-mail déjà utilisée.
- Vérifier la confirmation du mot de passe.
- Exiger un mot de passe d'au moins huit caractères.
- Hacher le mot de passe avant son enregistrement.

Lors de la connexion, le système doit vérifier les identifiants et créer une session authentifiée.

Le client doit également pouvoir se déconnecter.

### 4.3 Gestion du profil client

Le système doit permettre au client connecté de consulter :

- Son prénom.
- Son nom.
- Son adresse e-mail.

Le client doit pouvoir modifier ces informations.

Lors de la modification, le système doit :

- Vérifier que le client est connecté.
- Utiliser l'identifiant du client enregistré dans la session.
- Vérifier que les champs sont renseignés.
- Contrôler la validité de l'adresse e-mail.
- Refuser une adresse e-mail appartenant à un autre compte.
- Enregistrer les modifications dans MySQL.
- Actualiser les informations de session concernées.
- Afficher un message de confirmation ou d'erreur.

Un client ne doit pas pouvoir modifier le profil d'un autre utilisateur.

### 4.4 Gestion du panier

Le système doit permettre de :

- Ajouter un produit au panier.
- Afficher le contenu du panier.
- Augmenter la quantité d'un produit.
- Diminuer la quantité d'un produit.
- Supprimer un produit du panier.
- Afficher les prix et les quantités.
- Calculer le montant total du panier.

Le système doit vérifier les stocks disponibles.

La quantité d'un produit dans le panier ne doit pas dépasser la quantité disponible en stock.

L'ajout d'un produit au panier ne doit pas entraîner immédiatement une diminution du stock dans la base de données.

### 4.5 Validation d'une commande

Le système doit permettre à un client connecté de valider son panier.

Le processus de commande doit comprendre :

1. La vérification de l'authentification du client.
2. La vérification que le panier n'est pas vide.
3. La vérification des produits et des quantités.
4. La vérification des stocks disponibles.
5. La saisie ou la confirmation d'une adresse de livraison.
6. L'enregistrement de la commande.
7. L'enregistrement des lignes de commande.
8. La mise à jour des stocks selon les règles définies.
9. L'affichage d'une confirmation.

Les opérations d'enregistrement d'une commande doivent être réalisées dans une transaction afin d'éviter les enregistrements incomplets.

### 4.6 Historique des commandes

Le client doit pouvoir consulter ses commandes précédentes.

Pour chaque commande, le système doit permettre de consulter :

- Son identifiant.
- Sa date.
- Son statut.
- Les produits commandés.
- Les quantités.
- Les prix unitaires enregistrés.
- Le montant total.

Un client ne doit pouvoir consulter que les commandes associées à son propre compte.

### 4.7 Annulation d'une commande

Le client doit pouvoir demander l'annulation d'une commande lorsque les règles métier l'autorisent.

Le système doit :

- Vérifier que le client est connecté.
- Vérifier que la commande lui appartient.
- Vérifier que son statut permet l'annulation.
- Modifier son statut.
- Appliquer les éventuelles règles de rétablissement du stock.
- Afficher un message de confirmation ou d'erreur.

Une commande ne doit pas pouvoir être annulée lorsque son état ne le permet plus.

### 4.8 Gestion administrative

L'espace d'administration doit permettre de :

- Gérer les catégories.
- Ajouter et modifier les produits.
- Gérer l'activation des produits.
- Consulter et modifier les stocks.
- Consulter les commandes.
- Consulter les détails des commandes.
- Modifier les statuts des commandes.

Toutes ces opérations doivent être réservées aux administrateurs.

## 5. Modélisation UML

### 5.1 Diagramme de cas d'utilisation

Le diagramme de cas d'utilisation représente les interactions entre les acteurs et le système MiniShop.

Les trois acteurs principaux sont :

- Visiteur.
- Client.
- Administrateur.

Le diagramme doit représenter au minimum 14 cas d'utilisation, conformément aux exigences du projet.

Les cas d'utilisation prévus comprennent notamment :

1. Consulter le catalogue.
2. Rechercher un produit.
3. Filtrer les produits.
4. Consulter une fiche produit.
5. Créer un compte.
6. Se connecter.
7. Se déconnecter.
8. Consulter son profil.
9. Modifier son profil.
10. Ajouter un produit au panier.
11. Modifier les quantités du panier.
12. Supprimer un produit du panier.
13. Valider une commande.
14. Consulter l'historique des commandes.
15. Annuler une commande.
16. Gérer les catégories.
17. Gérer les produits.
18. Modifier les stocks.
19. Consulter les commandes des clients.
20. Modifier le statut d'une commande.

Le diagramme UML doit représenter les relations entre les acteurs et les cas d'utilisation.

Le client bénéficie des fonctionnalités de consultation du visiteur.

**Diagramme :**

![Diagramme de cas d'utilisation MiniShop](uml/cas-utilisation.png)

Le fichier image doit être présent à cet emplacement pour que le diagramme s'affiche correctement.

### 5.2 Descriptions textuelles des cas d'utilisation

Trois cas d'utilisation sont détaillés afin de préciser leurs acteurs, leurs préconditions, leurs scénarios et leurs résultats.

### 5.2.1 Cas d'utilisation : Créer un compte

**Acteur principal :** Visiteur.

**Objectif :** Permettre à un visiteur de créer un compte client.

**Préconditions :**

- Le visiteur peut accéder à la page d'inscription.
- Le système et la base de données sont disponibles.

**Scénario nominal :**

1. Le visiteur accède à la page d'inscription.
2. Le système affiche le formulaire.
3. Le visiteur renseigne son nom, son prénom, son adresse e-mail, son mot de passe et sa confirmation.
4. Le visiteur valide le formulaire.
5. Le système vérifie les champs obligatoires.
6. Le système vérifie le format de l'adresse e-mail.
7. Le système vérifie la longueur et la confirmation du mot de passe.
8. Le système vérifie que l'adresse e-mail n'est pas déjà utilisée.
9. Le système hache le mot de passe avec `password_hash()`.
10. Le système enregistre le nouveau client dans la base de données.
11. Le système redirige le visiteur vers la page de connexion.
12. Un message confirme la création du compte.

**Scénarios alternatifs :**

- **A1 — Adresse e-mail déjà utilisée :** le système refuse l'inscription et affiche un message d'erreur.
- **A2 — Champs obligatoires manquants :** le système demande de compléter les informations.
- **A3 — Adresse e-mail invalide :** le système demande une adresse valide.
- **A4 — Mot de passe trop court :** le système indique que le mot de passe doit contenir au moins huit caractères.
- **A5 — Confirmation incorrecte :** le système signale que les mots de passe ne correspondent pas.

**Erreurs possibles :**

- La connexion à la base de données échoue.
- L'enregistrement du compte échoue.

Dans ces situations, le système affiche un message général sans divulguer les détails techniques.

**Postconditions :**

- Un compte client est créé dans la base de données.
- Le mot de passe est enregistré sous forme hachée.
- Le client peut se connecter.

### 5.2.2 Cas d'utilisation : Gérer le panier

**Acteur principal :** Client.

**Objectif :** Permettre au client de sélectionner des produits et de préparer une commande.

**Préconditions :**

- Le catalogue est accessible.
- Les produits concernés existent dans la base de données.

**Scénario nominal :**

1. Le client consulte le catalogue.
2. Il sélectionne un produit.
3. Il clique sur « Ajouter au panier ».
4. Le système vérifie l'existence du produit et son stock.
5. Le système ajoute le produit au panier.
6. Le client accède à la page du panier.
7. Le système affiche les produits, les quantités et les prix.
8. Le client augmente ou diminue une quantité.
9. Le système vérifie la disponibilité du stock.
10. Le système actualise le panier et son montant total.

**Scénarios alternatifs :**

- **A1 — Produit indisponible :** le système refuse l'ajout.
- **A2 — Stock insuffisant :** le système empêche de dépasser la quantité disponible.
- **A3 — Suppression d'un produit :** le client supprime un article du panier.
- **A4 — Panier vide :** le système indique qu'aucun produit n'est présent.

**Erreurs possibles :**

- Le produit demandé n'existe plus.
- Une quantité transmise est invalide.
- Une opération sur le panier échoue.

**Postconditions :**

- Le panier contient les produits et quantités sélectionnés.
- Le montant total est actualisé.
- Le stock de la base de données n'est pas diminué par le simple ajout au panier.

### 5.2.3 Cas d'utilisation : Gérer les produits

**Acteur principal :** Administrateur.

**Objectif :** Permettre à un administrateur de gérer les produits du catalogue.

**Préconditions :**

- L'administrateur est authentifié.
- Son compte possède le rôle `admin`.
- La base de données est accessible.

**Scénario nominal :**

1. L'administrateur accède à l'espace d'administration.
2. Le système vérifie ses droits.
3. Le système affiche la liste des produits.
4. L'administrateur choisit d'ajouter un produit.
5. Le système affiche le formulaire.
6. L'administrateur renseigne le nom, la description, le prix, le stock et la catégorie.
7. L'administrateur valide le formulaire.
8. Le système vérifie les données.
9. Le système enregistre le produit dans la base de données.
10. Le système affiche un message de confirmation.

**Scénarios alternatifs :**

- **A1 — Modifier un produit :** l'administrateur sélectionne un produit existant, modifie ses informations et enregistre les changements.
- **A2 — Désactiver un produit :** l'administrateur retire un produit du catalogue public sans nécessairement supprimer son historique.
- **A3 — Modifier le stock :** l'administrateur change la quantité disponible.
- **A4 — Données invalides :** le système refuse l'enregistrement et affiche les erreurs.

**Erreurs possibles :**

- L'utilisateur ne possède pas les droits d'administration.
- La catégorie sélectionnée n'existe pas.
- Le prix ou le stock est invalide.
- L'enregistrement dans MySQL échoue.

**Postconditions :**

- Les informations du produit sont enregistrées ou mises à jour.
- Le catalogue reflète les modifications autorisées.
- Les contraintes de la base de données sont respectées.

### 5.3 Diagramme de classes

Le diagramme de classes doit représenter les principales classes de l'application et leurs relations.

Il doit notamment présenter les classes utilisées pour :

- L'authentification.
- La gestion des clients.
- La gestion des produits.
- La gestion du panier.
- La gestion des commandes.
- L'administration.

Les classes doivent être représentées avec :

- Leur nom.
- Leurs attributs principaux.
- Leurs méthodes principales.
- Les relations entre les classes.
- Les dépendances utiles à la compréhension de l'architecture.

Les noms et les méthodes du diagramme doivent correspondre autant que possible aux classes réellement présentes dans le projet PHP.

### 5.4 Diagrammes de séquence

Au moins quatre diagrammes de séquence doivent être réalisés.

**Diagramme 1 — Authentification**

Il représente :

1. La saisie des identifiants.
2. L'envoi du formulaire.
3. La vérification des données.
4. La recherche du client dans MySQL.
5. La vérification du mot de passe.
6. La création de la session.
7. La redirection après connexion.

**Diagramme 2 — Recherche d'un produit**

Il représente :

1. La saisie d'un terme de recherche.
2. L'envoi de la requête.
3. L'appel du contrôleur.
4. L'appel du modèle Produit.
5. L'exécution de la procédure stockée `rechercher_produit`.
6. La récupération des résultats.
7. L'affichage des produits correspondants.

**Diagramme 3 — Ajout au panier**

Il représente :

1. La sélection d'un produit.
2. L'envoi de la demande d'ajout.
3. La vérification du jeton CSRF.
4. La vérification du produit.
5. La vérification du stock.
6. La mise à jour du panier en session.
7. L'affichage du panier actualisé.

**Diagramme 4 — Passage d'une commande**

Il doit représenter :

1. La demande de validation du panier.
2. La vérification de l'authentification.
3. La vérification du panier.
4. La vérification des stocks.
5. La saisie de l'adresse de livraison.
6. Le début d'une transaction SQL.
7. L'enregistrement de la commande.
8. L'enregistrement des lignes de commande.
9. L'application des règles de stock.
10. La validation de la transaction.
11. L'affichage de la confirmation.

Les procédures stockées et les triggers peuvent être représentés lorsque leur intervention est pertinente.

## 6. Règles métier

Les principales règles métier de MiniShop sont les suivantes :

**RM01 — Unicité des comptes**

L'adresse e-mail d'un client doit être unique.

**RM02 — Sécurité des mots de passe**

Les mots de passe doivent être hachés avant leur enregistrement.

**RM03 — Prix des produits**

Le prix d'un produit doit être strictement positif.

**RM04 — Gestion des stocks**

Le stock d'un produit ne peut pas être négatif.

**RM05 — Appartenance à une catégorie**

Chaque produit appartient à une seule catégorie.

**RM06 — Contenu d'une catégorie**

Une catégorie peut contenir plusieurs produits ou aucun produit.

**RM07 — Commandes d'un client**

Un client peut passer plusieurs commandes ou aucune.

**RM08 — Propriétaire d'une commande**

Chaque commande appartient à un seul client.

**RM09 — Contenu d'une commande**

Une commande validée doit contenir au moins une ligne de commande.

**RM10 — Quantités commandées**

La quantité d'une ligne de commande doit être strictement positive.

**RM11 — Vérification du stock**

La quantité commandée ne doit pas dépasser le stock disponible au moment de la validation.

**RM12 — Conservation des prix**

Le prix unitaire d'une ligne de commande correspond au prix du produit au moment de l'achat.

**RM13 — Gestion des statuts**

Une commande possède un statut dont les modifications doivent respecter les transitions autorisées.

**RM14 — Confidentialité des commandes**

Un client ne peut consulter et modifier que les commandes qui lui appartiennent, dans la limite des actions autorisées.

**RM15 — Accès à l'administration**

Les fonctionnalités administrateur sont réservées aux utilisateurs disposant du rôle approprié.

**RM16 — Gestion du panier**

L'ajout d'un produit au panier ne diminue pas immédiatement le stock enregistré dans MySQL.

**RM17 — Produits actifs**

Seuls les produits actifs doivent être affichés dans le catalogue public.

**RM18 — Modification du profil**

Un client ne peut modifier que les informations de son propre compte.

## 7. Contraintes techniques

### 7.1 Technologies utilisées

L'application MiniShop repose sur les technologies suivantes :

| Technologie | Utilisation |
|---|---|
| PHP | Développement côté serveur |
| MySQL | Gestion de la base de données |
| PDO | Communication sécurisée avec MySQL |
| HTML | Structure des pages |
| CSS | Présentation de l'interface |
| JavaScript | Interactions côté client |
| Git | Gestion des versions |
| GitLab | Hébergement du dépôt et suivi du projet |

### 7.2 Architecture MVC

L'application utilise une architecture MVC.

**Modèle :**

- Exécute les requêtes SQL.
- Accède aux données.
- Utilise les procédures stockées lorsque nécessaire.
- Fournit les données aux contrôleurs.

**Vue :**

- Affiche les pages HTML.
- Présente les produits et les informations des utilisateurs.
- Contient les formulaires.
- Échappe les données affichées.

**Contrôleur :**

- Reçoit les requêtes HTTP.
- Vérifie les données transmises.
- Vérifie les autorisations.
- Appelle les méthodes des modèles.
- Sélectionne les vues ou effectue les redirections.

### 7.3 Base de données

La base de données doit comporter :

- Les tables nécessaires au fonctionnement du projet.
- Des clés primaires et étrangères.
- Des contraintes d'intégrité.
- Au moins cinq procédures stockées.
- Au moins cinq triggers.

Les procédures stockées et les triggers doivent être utilisés dans des fonctionnalités pertinentes du projet.

### 7.4 Compatibilité et déploiement

L'application doit pouvoir fonctionner sur l'environnement PHP/MySQL fourni par l'IUT.

Les fichiers sont développés localement puis transférés sur le serveur de test.

Le fonctionnement doit être vérifié sur le serveur après les modifications importantes.

## 8. Sécurité de l'application

### 8.1 Protection contre les injections SQL

Les données provenant des utilisateurs ne doivent pas être directement concaténées dans les requêtes SQL.

L'application doit utiliser des requêtes préparées PDO :

- `PDO::prepare()`
- `PDOStatement::execute()`

Les paramètres doivent être transmis séparément de la requête SQL.

### 8.2 Protection des mots de passe

Les mots de passe ne doivent jamais être enregistrés en clair.

L'application utilise :

- `password_hash()` lors de la création du compte.
- `password_verify()` lors de la connexion.

Les mots de passe ne doivent pas être affichés dans les pages de profil.

### 8.3 Protection contre les attaques XSS

Les données affichées dans les pages HTML doivent être échappées selon leur contexte.

Pour les valeurs textuelles et les attributs HTML, l'application utilise notamment :

`htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8')`

Cette protection empêche le navigateur d'interpréter comme du HTML les caractères spéciaux présents dans les données affichées.

### 8.4 Gestion des sessions

Les informations d'authentification sont enregistrées dans la session PHP.

L'application utilise notamment :

- `$_SESSION['client_id']`
- `$_SESSION['client_nom']`
- `$_SESSION['client_prenom']`
- `$_SESSION['client_role']`

Les pages privées doivent vérifier la présence d'un identifiant de client valide.

Lors de la connexion, l'identifiant de session est renouvelé avec :

`session_regenerate_id(true)`

La déconnexion supprime les informations d'authentification de la session.

### 8.5 Protection CSRF

Les formulaires réalisant des modifications doivent être protégés contre les attaques CSRF.

L'application utilise un jeton enregistré dans la session :

`$_SESSION['csrf_token']`

Le jeton est transmis dans les formulaires POST puis vérifié côté serveur.

Une requête présentant un jeton invalide doit être refusée.

### 8.6 Contrôle des autorisations

L'authentification ne suffit pas à autoriser toutes les opérations.

Le système doit vérifier :

- Qu'un utilisateur est connecté avant d'accéder aux fonctionnalités privées.
- Qu'un client accède uniquement à ses propres données.
- Qu'un administrateur possède le rôle approprié.
- Qu'une commande appartient au client qui souhaite la consulter ou l'annuler.

Par exemple, modifier un identifiant dans une URL ne doit pas permettre de consulter la commande d'un autre client.

Les vérifications doivent être réalisées côté serveur.

### 8.7 Gestion des erreurs

Les erreurs techniques détaillées ne doivent pas être affichées aux utilisateurs.

Les erreurs SQL doivent être enregistrées dans les journaux du serveur lorsque cela est nécessaire.

L'utilisateur doit recevoir un message compréhensible sans divulgation d'informations sensibles.

## 9. Architecture du système

### 9.1 Organisation générale

MiniShop suit une architecture MVC reposant sur trois responsabilités principales :

1. La présentation des informations.
2. Le traitement des requêtes et des règles applicatives.
3. L'accès aux données.

Cette séparation facilite la maintenance et l'évolution du projet.

### 9.2 Organisation des dossiers

L'organisation principale du projet est la suivante :

    minishop/
    ├── app/
    │   ├── controllers/
    │   ├── models/
    │   └── views/
    ├── config/
    ├── database/
    ├── docs/
    │   └── uml/
    ├── public/
    │   ├── css/
    │   ├── images/
    │   ├── js/
    │   └── index.php
    └── tests/

Les contrôleurs sont placés dans `app/controllers/`.

Les modèles sont placés dans `app/models/`.

Les vues sont placées dans `app/views/`.

Le dossier `database/` contient les scripts SQL du projet.

Le dossier `public/` contient le point d'entrée de l'application et les ressources accessibles depuis le navigateur.

### 9.3 Fonctionnement d'une requête

Le traitement d'une requête suit généralement les étapes suivantes :

1. L'utilisateur effectue une action depuis son navigateur.
2. La requête arrive dans `public/index.php`.
3. Le routeur identifie l'action demandée.
4. Le contrôleur concerné est appelé.
5. Le contrôleur effectue les vérifications nécessaires.
6. Le modèle interagit avec la base de données si nécessaire.
7. Le contrôleur transmet les données à une vue.
8. La vue affiche le résultat dans le navigateur.

### 9.4 Exemple : consultation du profil

1. Le client clique sur « Mon profil ».
2. Le navigateur demande `index.php?page=profil`.
3. Le routeur appelle `AuthController`.
4. Le contrôleur vérifie la session du client.
5. Le modèle `Client` récupère les informations depuis MySQL.
6. Le contrôleur charge la vue `app/views/compte/profil.php`.
7. La vue affiche le nom, le prénom et l'adresse e-mail.

### 9.5 Exemple : modification du profil

1. Le client modifie ses informations.
2. Il envoie le formulaire.
3. Le routeur vérifie le jeton CSRF.
4. Le contrôleur vérifie l'authentification.
5. Le contrôleur valide les informations saisies.
6. Le modèle met à jour les données dans MySQL.
7. La session est actualisée.
8. Le client est redirigé vers son profil.
9. Un message confirme la modification.

## 10. Tests et validation

### 10.1 Tests fonctionnels

Les tests fonctionnels doivent vérifier les principales fonctionnalités de l'application :

- Consultation du catalogue.
- Recherche de produits.
- Filtrage par catégorie.
- Consultation des fiches détaillées.
- Inscription.
- Connexion et déconnexion.
- Affichage du profil.
- Modification du profil.
- Ajout au panier.
- Modification des quantités.
- Suppression d'un produit du panier.
- Validation d'une commande.
- Consultation de l'historique.
- Annulation d'une commande.
- Gestion administrative.

### 10.2 Tests de sécurité

Les tests de sécurité doivent notamment vérifier :

- Le refus des adresses e-mail déjà utilisées.
- Le refus des champs obligatoires vides.
- La vérification des mots de passe.
- La protection des pages privées.
- La vérification des rôles.
- La protection contre les injections SQL.
- L'échappement des données affichées.
- Le refus des requêtes POST sans jeton CSRF valide.
- L'impossibilité de consulter les données d'un autre client.
- Le contrôle des stocks.

### 10.3 Tests de la base de données

Les tests SQL doivent vérifier :

- L'intégrité des clés primaires et étrangères.
- L'unicité des adresses e-mail.
- Les contraintes sur les prix.
- Les contraintes sur les stocks.
- Le fonctionnement des procédures stockées.
- Le fonctionnement des triggers.
- Le comportement des transactions lors de la validation d'une commande.

### 10.4 Suivi des tests

Les tests doivent être documentés avec :

- Un identifiant de test.
- La fonctionnalité concernée.
- Les conditions initiales.
- Les étapes réalisées.
- Le résultat attendu.
- Le résultat obtenu.
- Le statut du test.

Les fonctionnalités non encore développées doivent rester identifiées comme « À tester » et ne doivent pas être considérées comme validées.

## 11. État d'avancement du projet

Cette section distingue les fonctionnalités déjà développées des fonctionnalités prévues.

### 11.1 Fonctionnalités développées et testées

Les fonctionnalités suivantes sont déjà opérationnelles :

- Consultation du catalogue.
- Affichage des 15 produits.
- Recherche par nom.
- Filtrage par catégorie.
- Consultation des fiches détaillées.
- Inscription.
- Connexion.
- Déconnexion.
- Affichage du profil.
- Modification du nom, du prénom et de l'adresse e-mail.
- Gestion du panier.
- Modification des quantités.
- Suppression des produits du panier.
- Vérification des limites de stock du panier.

Les cinq procédures stockées et les cinq triggers ont été créés dans la base de données.

Les procédures `rechercher_produit` et `produits_par_categorie` sont déjà utilisées depuis PHP.

### 11.2 Fonctionnalités restant à développer

Les principales fonctionnalités restantes sont :

- Validation du panier et passage de commande.
- Saisie de l'adresse de livraison.
- Enregistrement des commandes dans MySQL.
- Historique des commandes.
- Annulation des commandes.
- Espace d'administration.
- Gestion administrative des produits et catégories.
- Gestion administrative des stocks.
- Suivi et modification des statuts de commande.
- Fonctionnalités JavaScript obligatoires.
- Finalisation des diagrammes UML.
- Finalisation des tests et de la documentation.

## 12. Conclusion

MiniShop est une application de commerce électronique organisée selon une architecture MVC et utilisant PHP, MySQL et PDO.

Les spécifications fonctionnelles définissent les possibilités offertes aux visiteurs, aux clients et aux administrateurs.

Les règles métier précisent les contraintes nécessaires à la gestion des produits, des stocks et des commandes.

La sécurité repose notamment sur les requêtes préparées, le hachage des mots de passe, les sessions PHP, la protection CSRF et les contrôles d'autorisation.

La modélisation UML et les tests permettent de documenter, de vérifier et de justifier les choix de conception.

Les fonctionnalités de consultation du catalogue, d'authentification, de gestion du profil et du panier sont déjà développées.

Les prochaines étapes concernent principalement la validation des commandes, l'administration et la finalisation des livrables du projet.
