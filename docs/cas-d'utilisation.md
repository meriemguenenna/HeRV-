MiniShop : Documentation des cas d'utilisation

Ce document décrit sous forme textuelle les principaux cas d'utilisation de MiniShop , tels qu'ils apparaissent dans le diagramme UML de cas d'utilisation. Il est destiné à être intégré au document de spécification du système.

Sommaire
Acteurs
Vue d'ensemble
Connecteur UC1 Se
UC2 Ajouter un produit au panier
UC3 Passer une commande
Conventions
Acteurs
Acteur	Rôle
Client	Utilisateur qui consulte le catalogue, remplit son panier et passe commande.
Administrateur	Utilisateur qui gère le catalogue et les commandes.
Service de paiement	Système externe qui valide les transactions bancaires.
Vue d'ensemble
Identifiant	Cas d'utilisation	Acteur principal
UC1	Se connecter	Client / Administrateur
UC2	Ajouter un produit au panier	Client
UC3	Passer une commande	Client
Connecteur UC1 Se
	
Acteur principal	Client (ou Administrateur)
Objectif	S'authentifier pour accéder à son compte et à ses fonctionnalités.

Préconditions

L'utilisateur possède un compte.

Scénario nominal

L'utilisateur ouvre la page de connexion.
Il saisit son identifiant et son mot de passe.
Le système vérifie les informations saisies.
Le système ouvre la session et redirige l'utilisateur vers la page d'accueil.

Scénarios alternatifs et exceptions

3a. Identifiants incorrects : le système affiche un message d'erreur et permet à l'utilisateur de réessayer.
3b. Trop de tentatives échouées : le système bloque temporairement la connexion et en informe l'utilisateur.

Conditions de publication

L'utilisateur est authentifié et sa session est active.
UC2 Ajouter un produit au panier
	
Acteur principal	Client
Objectif	Placer un produit du catalogue dans son panier afin de l'acheter.

Préconditions

Le client consulte le catalogue.
Le produit existe et est affiché.

Scénario nominal

Le client sélectionne un produit.
Le système affiche la fiche du produit (description, prix, stock disponible).
Le client choisit la quantité et clique sur « Ajouter au panier ».
Le système vérifie que le stock est suffisant.
Le système ajoute le produit au panier et met à jour le total.
Le système confirme l'ajout au client.

Scénarios alternatifs et exceptions

3a. Quantité invalide (zéro ou négative) : le système affiche une erreur et ne modifie pas le panier.
3b. Produit déjà présent dans le panier : le système augmente la quantité existante au lieu de créer une nouvelle ligne.
4a. Stock insuffisant : le système refuse l'ajout et indique le stock disponible.

Conditions de publication

Le panier contient le produit avec la quantité choisie.
Le total du panier est à jour.
UC3 Passer une commande
	
Acteur principal	Client
Acteur secondaire	Service de paiement
Objectif	Valider le contenu du panier et payer pour recevoir les produits.

Préconditions

Le client est connecté (voir UC1 ).
Le panier du client n'est pas vide.

Scénario nominal

Le client demande à valider son panier.
Le système affiche le récapitulatif (produits, quantités, total).
Le client renseigne ou confirme son adresse de livraison.
Le client choisit le mode de paiement et confirme la commande.
Le système transmet la demande de paiement au service de paiement.
Le service de paiement accepte la transaction.
Le système enregistre la commande, met à jour les stocks et vide le panier.
Le système affiche la confirmation avec un numéro de commande.

Scénarios alternatifs et exceptions

1a. Client non connecté : le système le redirige vers la connexion (UC1), puis le ramène à son panier.
6a. Paiement refusé : le système informe le client, la commande n'est pas créée et le panier est conservé.
7a. Produit devenu indisponible : le système prévient le client et lui propose de modifier son panier.

Conditions de publication

La commande est enregistrée avec le statut « payée ».
Les stocks des produits commandés sont mis à jour.
Le panier du client est vide.
Conventions
Chaque cas d'utilisation est identifié par un code UCn.
Les scénarios alternatifs sont numérotés par rapport à l'étape du scénario nominal où ils interviennent (par exemple 4ase produit à l'étape 4).
Les liens entre cas d'utilisation (inclusion, extension) sont indiqués par un renvoi vers le cas concerné.