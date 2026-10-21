# MiniShop — Architecture du système

> **Objectif :** définir et documenter l'architecture de l'application Web MiniShop en présentant l'organisation des différentes couches et leurs interactions.

---

## Sommaire

- [Présentation](#présentation)
- [Architecture en trois couches](#architecture-en-trois-couches)
- [Rôle des couches](#rôle-des-couches)
- [Schéma de l'architecture](#schéma-de-larchitecture)
- [Échanges entre les composants](#échanges-entre-les-composants)
- [Technologies utilisées](#technologies-utilisées)
- [Cohérence de l'architecture](#cohérence-de-larchitecture)

---

## Présentation

L'application Web MiniShop repose sur une architecture en trois couches organisée selon le modèle MVC (*Model-View-Controller*).

Cette architecture permet de séparer la gestion des données, l'affichage de l'interface utilisateur et le traitement des requêtes. Elle facilite la maintenance, l'évolution et l'organisation du code.

## Architecture en trois couches

L'architecture de MiniShop est organisée autour des trois composants du modèle MVC :

| Couche | Composant MVC | Rôle principal |
|:--|:--|:--|
| **Présentation** | View | Afficher les pages et permettre à l'utilisateur d'interagir avec l'application. |
| **Logique applicative** | Controller | Recevoir les requêtes, appliquer les traitements nécessaires et coordonner les échanges. |
| **Accès aux données** | Model | Gérer les données et communiquer avec la base de données MySQL. |

---

## Rôle des couches

### 1. Vue (View)

La vue correspond à l'interface visible par l'utilisateur.

**Responsabilités :**
- Afficher les pages de l'application MiniShop.
- Présenter les produits, les catégories et les informations des commandes.
- Afficher les formulaires et les résultats des actions.
- Utiliser JavaScript pour gérer les interactions côté navigateur.

**Technologies :** HTML, CSS et JavaScript.

### 2. Contrôleur (Controller)

Le contrôleur constitue le point de traitement des requêtes adressées à l'application.

**Responsabilités :**
- Recevoir les requêtes HTTP.
- Vérifier et traiter les données envoyées par l'utilisateur.
- Appliquer la logique applicative et les règles métier.
- Appeler les modèles pour lire ou modifier les données.
- Transmettre les informations nécessaires à la vue.

**Technologie principale :** PHP.

### 3. Modèle (Model)

Le modèle gère les données et les interactions avec la base de données.

**Responsabilités :**
- Récupérer les données nécessaires à l'application.
- Ajouter, modifier ou supprimer des informations.
- Exécuter les opérations sur les produits, les clients et les commandes.
- Participer au respect des règles métier et de la cohérence des données.

**Technologies :** PHP, PDO et MySQL.

PDO permet à PHP de communiquer avec la base de données et d'exécuter des requêtes SQL de manière sécurisée, notamment à l'aide de requêtes préparées.

---

## Schéma de l'architecture

Le schéma suivant représente les principaux composants et les échanges entre l'utilisateur, le navigateur, l'application PHP et la base de données.

```mermaid
flowchart TD
    U["Utilisateur"]
    V["Vue — View<br/>HTML, CSS, JavaScript"]
    C["Contrôleur — Controller<br/>PHP"]
    M["Modèle — Model<br/>PHP, PDO"]
    DB[("Base de données<br/>MySQL")]

    U <-->|"Navigation et interactions"| V
    V -->|"Requête HTTP"| C
    C -->|"Appel des traitements"| M
    M -->|"Requêtes SQL via PDO"| DB
    DB -->|"Résultats des requêtes"| M
    M -->|"Données récupérées"| C
    C -->|"Sélection des données à afficher"| V
    V -->|"Réponse HTML"| U