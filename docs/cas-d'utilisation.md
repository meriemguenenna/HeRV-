# MiniShop : Documentation des cas d'utilisation

Ce document décrit sous forme textuelle les principaux cas d'utilisation de **MiniShop**, tels qu'ils apparaissent dans le diagramme UML de cas d'utilisation. Il est destiné à être intégré au document de spécification du système.

## Sommaire

- [Acteurs](#acteurs)
- [Vue d'ensemble](#vue-densemble)
- [UC1 Se connecter](#uc1-se-connecter)
- [UC2 Ajouter un produit au panier](#uc2-ajouter-un-produit-au-panier)
- [UC3 Passer une commande](#uc3-passer-une-commande)
- [Conventions](#conventions)

## Acteurs

| Acteur | Rôle |
|---|---|
| Client | Utilisateur qui consulte le catalogue, remplit son panier et passe commande. |
| Administrateur | Utilisateur qui gère le catalogue et les commandes. |
| Service de paiement | Système externe qui valide les transactions bancaires. |

## Vue d'ensemble

| Identifiant | Cas d'utilisation | Acteur principal |
|---|---|---|
| UC1 | Se connecter | Client / Administrateur |
| UC2 | Ajouter un produit au panier | Client |
| UC3 | Passer une commande | Client |

---

## UC1 Se connecter

| | |
|---|---|
| **Acteur principal** | Client (ou Administrateur) |
| **Objectif** | S'authentifier pour accéder à son compte et à ses fonctionnalités. |

**Préconditions**

- L'utilisateur possède un compte.

**Scénario nominal**

1. L'utilisateur ouvre la page de connexion.
2. Il saisit son identifiant et son mot de passe.
3. Le système vérifie les informations saisies.
4. Le système ouvre la session et redirige l'utilisateur vers la page d'accueil.

**Scénarios alternatifs et exceptions**

- **3a. Identifiants incorrects** : le système affiche un message d'erreur et permet à l'utilisateur de réessayer.
- **3b. Trop de tentatives échouées** : le système bloque temporairement la connexion et en informe l'utilisateur.

**Postconditions**

- L'utilisateur est authentifié et sa session est active.

---

## UC2 Ajouter un produit au panier

| | |
|---|---|
| **Acteur principal** | Client |
| **Objectif** | Placer un produit du catalogue dans son panier afin de l'acheter. |

**Préconditions**

- Le client consulte le catalogue.
- Le produit existe et est affiché.

**Scénario nominal**

1. Le client sélectionne un produit.
2. Le système affiche la fiche du produit (description, prix, stock disponible).
3. Le client choisit la quantité et clique sur « Ajouter au panier ».
4. Le système vérifie que le stock est suffisant.
5. Le système ajoute le produit au panier et met à jour le total.
6. Le système confirme l'ajout au client.

**Scénarios alternatifs et exceptions**

- **3a. Quantité invalide** (zéro ou négative) : le système affiche une erreur et ne modifie pas le panier.
- **3b. Produit déjà présent dans le panier** : le système augmente la quantité existante au lieu de créer une nouvelle ligne.
- **4a. Stock insuffisant** : le système refuse l'ajout et indique le stock disponible.

**Postconditions**

- Le panier contient le produit avec la quantité choisie.
- Le total du panier est à jour.

---

## UC3 Passer une commande

| | |
|---|---|
| **Acteur principal** | Client |
| **Acteur secondaire** | Service de paiement |
| **Objectif** | Valider le contenu du panier et payer pour recevoir les produits. |

**Préconditions**

- Le client est connecté (voir [UC1](#uc1-se-connecter)).
- Le panier du client n'est pas vide.

**Scénario nominal**

1. Le client demande à valider son panier.
2. Le système affiche le récapitulatif (produits, quantités, total).
3. Le client renseigne ou confirme son adresse de livraison.
4. Le client choisit le mode de paiement et confirme la commande.
5. Le système transmet la demande de paiement au service de paiement.
6. Le service de paiement accepte la transaction.
7. Le système enregistre la commande, met à jour les stocks et vide le panier.
8. Le système affiche la confirmation avec un numéro de commande.

**Scénarios alternatifs et exceptions**

- **1a. Client non connecté** : le système le redirige vers la connexion (UC1), puis le ramène à son panier.
- **6a. Paiement refusé** : le système informe le client, la commande n'est pas créée et le panier est conservé.
- **7a. Produit devenu indisponible** : le système prévient le client et lui propose de modifier son panier.

**Postconditions**

- La commande est enregistrée avec le statut « payée ».
- Les stocks des produits commandés sont mis à jour.
- Le panier du client est vide.

---

## Conventions

- Chaque cas d'utilisation est identifié par un code `UCn`.
- Les scénarios alternatifs sont numérotés par rapport à l'étape du scénario nominal où ils interviennent (par exemple `4a` se produit à l'étape 4).
- Les liens entre cas d'utilisation (inclusion, extension) sont indiqués par un renvoi vers le cas concerné.