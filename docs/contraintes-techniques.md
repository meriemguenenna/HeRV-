## Contraintes techniques

Cette section décrit les technologies, les méthodes de développement et les contraintes que le projet MiniShop doit respecter pour sa réalisation et son déploiement.

### Sommaire

- [Nature de l'application](#nature-de-lapplication)
- [Technologies utilisées](#technologies-utilisées)
- [Architecture MVC](#architecture-mvc)
- [Programmation orientée objet](#programmation-orientée-objet)
- [Accès à la base de données](#accès-à-la-base-de-données)
- [Procédures stockées et triggers](#procédures-stockées-et-triggers)
- [Sécurité](#sécurité)
- [Gestion de projet sur GitLab](#gestion-de-projet-sur-gitlab)
- [Contraintes de déploiement](#contraintes-de-déploiement)
- [Cohérence avec le reste du cahier des charges](#cohérence-avec-le-reste-du-cahier-des-charges)

### Nature de l'application

MiniShop est une **application Web** : elle est accessible depuis un navigateur, sans installation côté utilisateur. Le serveur traite les requêtes HTTP, interroge la base de données et renvoie des pages HTML.

### Technologies utilisées

| Technologie | Rôle dans le projet | Statut |
|---|---|---|
| PHP | Langage côté serveur : logique métier, contrôleurs, génération des pages | Imposé |
| MySQL | Système de gestion de base de données relationnelle | Imposé |
| PDO | Interface PHP d'accès à la base de données | Imposé |
| JavaScript | Interactions côté navigateur : validation des formulaires, mises à jour dynamiques de l'interface | Utilisé |
| HTML / CSS | Structure et mise en forme des pages | Utilisé |
| Git / GitLab | Gestion de versions et suivi du projet | Imposé |

### Architecture MVC

L'application respecte l'architecture **Modèle - Vue - Contrôleur** :

- **Modèle** : classes qui représentent les données (client, produit, commande…) et qui effectuent les accès à la base. Elles contiennent les règles métier.
- **Vue** : fichiers de présentation (HTML avec un minimum de PHP). Elles n'effectuent aucun accès à la base.
- **Contrôleur** : reçoit la requête de l'utilisateur, appelle les modèles, puis choisit la vue à afficher.

Un point d'entrée unique (`public/index.php`) reçoit toutes les requêtes et les dirige vers le bon contrôleur. Arborescence proposée :

```text
minishop/
├── public/            # Point d'entrée (index.php), CSS, JavaScript, images
├── app/
│   ├── controllers/   # Contrôleurs
│   ├── models/        # Modèles (classes métier et accès aux données)
│   └── views/         # Vues
├── config/            # Configuration (accès base de données, paramètres)
├── sql/               # Scripts SQL (schéma, procédures stockées, triggers, jeu d'essai)
├── docs/              # Documentation
└── README.md
```

### Programmation orientée objet

Le code PHP est écrit en **programmation orientée objet** :

- chaque entité du domaine (`Client`, `Produit`, `Categorie`, `Commande`, `LigneCommande`) est représentée par une classe ;
- les attributs sont privés et accessibles par des accesseurs (getters et setters) ;
- les contrôleurs et les modèles sont des classes distinctes, chacune avec une responsabilité unique ;
- la connexion à la base est encapsulée dans une classe dédiée (par exemple `Database`), réutilisée par tous les modèles ;
- un chargement automatique des classes (autoload) évite les `require` multiples.

### Accès à la base de données

- Tous les échanges avec la base de données passent par **PDO**. Les anciennes extensions (`mysql_*`, et `mysqli` pour ce projet) ne sont pas utilisées.
- La connexion est configurée avec le mode d'erreur par exceptions (`PDO::ERRMODE_EXCEPTION`) et l'encodage `utf8mb4`.
- Toutes les requêtes SQL qui utilisent des données variables sont des **requêtes préparées** avec paramètres liés (`prepare()` puis `execute()`). Aucune donnée venant de l'utilisateur n'est concaténée dans une requête SQL, afin d'empêcher les injections SQL.
- Les opérations qui modifient plusieurs tables à la fois (par exemple la validation d'une commande) s'exécutent dans une **transaction** (`beginTransaction`, `commit`, `rollBack`).
- Le moteur de tables est **InnoDB**, nécessaire pour les clés étrangères et les transactions.

### Procédures stockées et triggers

Les procédures stockées et les triggers demandés font partie intégrante du projet :

- ils sont écrits en SQL dans le dossier `sql/` du dépôt, un fichier par objet ou regroupés par thème ;
- ils sont versionnés avec le reste du code, afin qu'une base complète puisse être recréée à partir du dépôt ;
- un script d'installation exécute dans l'ordre : le schéma, les procédures stockées, les triggers, puis le jeu d'essai ;
- les procédures stockées sont appelées depuis PHP avec PDO (`CALL nom_procedure(?, ?)`) en utilisant des paramètres préparés.

### Sécurité

Les contraintes de sécurité suivantes s'appliquent à l'ensemble de l'application :

- **Injections SQL** : requêtes préparées uniquement (voir ci-dessus).
- **Mots de passe** : jamais stockés en clair ; hachés avec `password_hash()` et vérifiés avec `password_verify()`.
- **Failles XSS** : toute donnée affichée dans une page est échappée avec `htmlspecialchars()`.
- **Sessions** : l'identifiant de session est régénéré à la connexion ; l'accès aux pages réservées (compte client, administration) est contrôlé côté serveur.
- **Validation des données** : le JavaScript valide les formulaires pour aider l'utilisateur, mais la **validation côté serveur est obligatoire** car le JavaScript peut être contourné.
- **Configuration sensible** : les identifiants de la base de données ne sont pas écrits dans le code versionné ni dans les pages accessibles depuis le navigateur.

### Gestion de projet sur GitLab

- Le projet est **développé et suivi sur GitLab** (`iut-git.unice.fr`) : code, issues et historique y sont centralisés.
- Chaque membre de l'équipe **contribue régulièrement au dépôt**, avec ses propres commits, afin que les contributions individuelles soient visibles dans l'historique.
- Les commits sont fréquents, de taille raisonnable, et accompagnés d'un message clair (par exemple `feat: ajout du panier`, `fix: correction du calcul du total`).
- Chaque membre configure son identité Git (`user.name` et `user.email`) avec son compte, pour que ses commits lui soient attribués.
- Les tâches sont suivies avec les issues GitLab ; un commit peut y faire référence (`Refs #12`).
- Le travail s'effectue sur des branches, intégrées à la branche principale par merge requests.
- Le fichier `README.md` explique comment installer et lancer le projet.

### Contraintes de déploiement

| Élément | Contrainte |
|---|---|
| Serveur Web | Apache (ou équivalent) avec PHP activé ; la racine web pointe sur le dossier `public/` |
| Version de PHP | PHP 8.x avec l'extension `pdo_mysql` activée |
| Version de MySQL | MySQL 8.0.16 ou plus (nécessaire pour que les contraintes `CHECK` des règles métier soient appliquées) |
| Encodage | `utf8mb4` pour la base, les pages et les échanges |
| Configuration | Fichier de configuration séparé du code, non versionné (exemple fourni sous forme de fichier modèle) |
| Installation de la base | Scripts du dossier `sql/` exécutés dans l'ordre indiqué |
| Navigateurs | Navigateurs récents (Firefox, Chrome, Edge) avec JavaScript activé |
| Sécurité du transport | Accès en HTTPS en production |

### Cohérence avec le reste du cahier des charges

- **Règles métier** : les contraintes (`UNIQUE`, `CHECK`, clés étrangères) sont réalisées en MySQL et doublées par une validation PHP. La section « Règles métier » doit utiliser la syntaxe MySQL.
- **Architecture** : la séparation Modèle, Vue et Contrôleur est celle décrite dans la partie architecture.
- **Sécurité et base de données** : les requêtes préparées, le hachage des mots de passe et les transactions répondent aux exigences de sécurité et d'intégrité des données.
- **Développement Web** : PHP gère le serveur et JavaScript le comportement dans le navigateur, avec une validation côté serveur systématique.