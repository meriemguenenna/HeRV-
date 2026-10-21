**# MiniShop : Modèle logique de données (MLD)**

Ce document décrit la transformation du modèle conceptuel de données (MCD) de **MiniShop** en modèle logique de données (MLD). Il présente les tables relationnelles, leurs attributs, les clés primaires et les clés étrangères nécessaires à la structuration de la base de données.

**## Sommaire**

**-&#x20;**[Objectif](#objectif)  
**-&#x20;**[Transformation du MCD en MLD](#transformation-du-mcd-en-mld)  
**-&#x20;**[Tables relationnelles](#tables-relationnelles)  
**-&#x20;**[Relations entre les tables](#relations-entre-les-tables)  
**-&#x20;**[Règles de gestion](#règles-de-gestion)  
**-&#x20;**[Compatibilité avec MySQL](#compatibilité-avec-mysql)  
**-&#x20;**[Livrable attendu](#livrable-attendu)

**---**

**## Objectif**

L'objectif est de transformer le MCD de MiniShop en un modèle logique de données permettant de représenter les informations sous forme de tables relationnelles.

Ce travail consiste à :
**-&#x20;**Transformer les entités du MCD en tables.
**-&#x20;**Définir les attributs de chaque table.
**-&#x20;**Identifier les clés primaires (PK).
**-&#x20;**Définir les clés étrangères (FK).
**-&#x20;**Respecter les relations et les cardinalités du MCD.
**-&#x20;**Préparer la structure de la base de données pour une implémentation sous MySQL.

**## Transformation du MCD en MLD**

La transformation du MCD en MLD repose sur les règles suivantes :

**-&#x20;**Chaque entité du MCD devient une table relationnelle.
**-&#x20;**Chaque attribut d'une entité devient une colonne de la table correspondante.
**-&#x20;**Chaque table possède une clé primaire permettant d'identifier chaque enregistrement de manière unique.
**-&#x20;**Les relations entre les tables sont représentées par des clés étrangères.
**-&#x20;**Les associations de type plusieurs-à-plusieurs sont représentées par des tables intermédiaires contenant les clés étrangères des tables concernées.

**## Tables relationnelles**

Le MLD doit notamment représenter les entités suivantes :

**| Table | Rôle |**
**|---|---|**
**| Client | Contient les informations relatives aux clients. |**
**| Commande | Enregistre les commandes passées par les clients. |**
**| LigneCommande | Détaille les produits et les quantités associés à chaque commande. |**
**| Produit | Contient les informations relatives aux produits disponibles dans la boutique. |**
**| Categorie | Regroupe les produits par catégorie. |**

Les attributs exacts de chaque table doivent être définis à partir du MCD de MiniShop et des règles métier du cahier des charges.

**## Relations entre les tables**

Les relations suivantes doivent être vérifiées lors de la construction du MLD :

**-&#x20;**Un client peut passer plusieurs commandes.
**-&#x20;**Une commande appartient à un seul client.
**-&#x20;**Une commande peut contenir plusieurs lignes de commande.
**-&#x20;**Chaque ligne de commande concerne un produit.
**-&#x20;**Un produit peut apparaître dans plusieurs lignes de commande.
**-&#x20;**Une catégorie peut regrouper plusieurs produits.
**-&#x20;**Chaque produit est associé à une catégorie, si les cardinalités du MCD le prévoient.

Les clés étrangères doivent assurer la cohérence entre les tables et respecter les cardinalités définies dans le MCD.

**## Règles de gestion**

Le modèle logique de données doit respecter les règles métier définies dans le cahier des charges de MiniShop.

**-&#x20;**Chaque enregistrement doit être identifié de manière unique par sa clé primaire.
**-&#x20;**Les clés étrangères doivent référencer des enregistrements existants.
**-&#x20;**Les relations entre clients, commandes, lignes de commande, produits et catégories doivent être cohérentes.
**-&#x20;**Les contraintes d'intégrité doivent empêcher les incohérences dans les données.
**-&#x20;**Les attributs et les contraintes doivent correspondre aux besoins fonctionnels de l'application.

**## Compatibilité avec MySQL**

Le MLD doit permettre la création du schéma relationnel sous MySQL.

Il faut notamment :
**-&#x20;**Choisir des types de données adaptés aux attributs.
**-&#x20;**Définir les clés primaires et les clés étrangères.
**-&#x20;**Ajouter les contraintes d'intégrité nécessaires.
**-&#x20;**Vérifier la cohérence des relations entre les tables.
**-&#x20;**S'assurer que la structure obtenue peut être traduite en instructions SQL de création de tables.

**## Livrable attendu**

Le livrable final comprend un MLD complet et cohérent de la base de données MiniShop, avec :

**-&#x20;**La liste des tables relationnelles.
**-&#x20;**Les attributs de chaque table.
**-&#x20;**Les clés primaires et les clés étrangères.
**-&#x20;**Une représentation claire des relations entre les tables.
**-&#x20;**Une structure compatible avec une future implémentation sous MySQL.

**## Résultat attendu**

À l'issue de ce travail, le MLD doit fournir une représentation logique et structurée de la base de données MiniShop, servant de base à la création du schéma relationnel et à son implémentation sous MySQL.