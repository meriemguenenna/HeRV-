
# MiniShop — Compte rendu des tests SQL

## 1. Objectif

Ce document présente les tests SQL réalisés ou prévus pour vérifier le fonctionnement de la base de données MiniShop.

Les tests portent principalement sur :

- La cohérence des données enregistrées.
- Le fonctionnement des procédures stockées.
- Le fonctionnement des triggers.
- La gestion des stocks.
- Les contraintes d'intégrité.
- Les restrictions sur les modifications des commandes.
- La gestion des transactions SQL.

L'objectif est de vérifier que la base de données respecte les règles métier du projet et qu'elle peut être utilisée par l'application PHP MVC.

### 1.1 Environnement de test

| Élément | Description |
|---|---|
| Projet | MiniShop |
| Système de gestion de base de données | MySQL |
| Base de données | `hm504733_minishop` |
| Outil de test SQL | phpMyAdmin |
| Application | PHP orienté objet avec architecture MVC |
| Accès aux données | PDO |
| Procédures stockées installées | 5 |
| Triggers installés | 5 |

### 1.2 Méthode de test

Chaque test est défini par :

- Un identifiant.
- L'opération réalisée.
- Le résultat attendu.
- Le résultat obtenu.
- Un statut.

Les statuts utilisés sont :

- **Réussi** : le résultat attendu a été observé.
- **Échoué** : le résultat obtenu ne correspond pas au résultat attendu.
- **À confirmer** : le test est mentionné dans le suivi initial, mais son exécution ou son résultat doit encore être vérifié.
- **À réaliser** : le test n'a pas encore été effectué.

Les tests qui modifient les données doivent être réalisés avec précaution, de préférence dans une transaction ou sur des données de test.

## 2. Vérification de la structure SQL

### 2.1 Tables principales

La base de données repose sur cinq tables principales :

1. `CLIENT`
2. `CATEGORIE`
3. `PRODUIT`
4. `COMMANDE`
5. `LIGNE_COMMANDE`

Ces tables permettent de gérer les comptes clients, le catalogue et les commandes.

### 2.2 Procédures stockées

Les cinq procédures stockées suivantes sont installées dans MySQL :

| Procédure | Fonction |
|---|---|
| `rechercher_produit` | Rechercher les produits par nom |
| `produits_par_categorie` | Récupérer les produits actifs d'une catégorie |
| `commandes_client` | Consulter les commandes d'un client |
| `lignes_commande` | Consulter les lignes d'une commande |
| `modifier_stock` | Modifier le stock d'un produit après vérification |

Les procédures `rechercher_produit` et `produits_par_categorie` sont également appelées depuis le code PHP de MiniShop.

### 2.3 Triggers

Les cinq triggers suivants sont installés :

| Trigger | Fonction principale |
|---|---|
| `verifier_produit_insertion` | Vérifier les données lors de l'insertion d'un produit |
| `verifier_produit_modification` | Vérifier les données lors de la modification d'un produit |
| `verifier_stock_commande` | Contrôler les opérations sur les lignes de commande et le stock |
| `verifier_modification_ligne` | Contrôler les modifications des lignes de commande |
| `verifier_statut_commande` | Contrôler les changements de statut des commandes |

La présence de ces triggers a été vérifiée dans la base.

Leurs comportements doivent être validés par des tests adaptés aux règles définies dans `database/triggers.sql`.

## 3. Tableau récapitulatif des tests SQL

Le tableau ci-dessous reprend les 14 tests du suivi initial.

Les résultats qui n'ont pas été revérifiés sont indiqués « À confirmer », afin de ne pas présenter comme validé un comportement non vérifié.

| N° | Test effectué | Résultat attendu | Statut |
|---|---|---|---|
| T01 | Vérification des stocks après importation | Les stocks tiennent compte des commandes de démonstration | À confirmer |
| T02 | Recherche d'un produit | La recherche « souris » retrouve « Souris sans fil » | Réussi |
| T03 | Recherche par catégorie | La catégorie Accessoires retourne les 5 produits actifs correspondants | Réussi |
| T04 | Consultation des commandes d'un client | Seules les commandes du client demandé sont retournées | À confirmer |
| T05 | Consultation des lignes d'une commande | Les produits et quantités de la commande sont retournés | À confirmer |
| T06 | Modification avec stock négatif | L'opération est refusée | À confirmer |
| T07 | Modification du stock d'un produit inexistant | L'opération est refusée | À confirmer |
| T08 | Commande supérieure au stock disponible | L'opération est refusée | À confirmer |
| T09 | Modification interdite d'une ligne de commande | La modification est refusée | À confirmer |
| T10 | Transition de statut interdite | Le changement de statut est refusé | À confirmer |
| T11 | Annulation d'une commande | Le stock est restitué si la règle métier le prévoit | À confirmer |
| T12 | Vérification du `ROLLBACK` | Les modifications de la transaction sont annulées | À confirmer |
| T13 | Modification d'un produit avec prix négatif | La modification est refusée | À confirmer |
| T14 | Insertion d'un produit avec stock négatif | L'insertion est refusée | À confirmer |

**Remarque :** les statuts T02 et T03 correspondent aux recherches déjà testées depuis l'application PHP sur le serveur. Pour attester leur exécution directement dans phpMyAdmin, il est conseillé de conserver également les résultats des appels SQL.

## 4. Tests des procédures stockées

### 4.1 Test T02 — Recherche d'un produit

**Procédure testée :** `rechercher_produit`

**Objectif :**

Vérifier que la procédure permet de retrouver les produits actifs dont le nom contient un terme donné.

**Requête SQL :**

    CALL rechercher_produit('souris');

**Résultat attendu :**

Le produit « Souris sans fil » apparaît dans les résultats.

**Résultat observé :**

La recherche du terme « souris » fonctionne depuis le catalogue PHP et retourne le produit attendu.

**Statut :** Réussi pour l'intégration PHP.

### 4.2 Test T03 — Recherche par catégorie

**Procédure testée :** `produits_par_categorie`

**Objectif :**

Vérifier que la procédure retourne les produits actifs appartenant à une catégorie.

**Requête SQL :**

    CALL produits_par_categorie(3);

**Résultat attendu :**

Les cinq produits de la catégorie Accessoires sont retournés :

1. Souris sans fil.
2. Clavier mécanique.
3. Casque audio.
4. Tapis de souris.
5. Hub USB.

**Résultat observé :**

Le filtrage par catégorie fonctionne depuis l'application PHP.

**Statut :** Réussi pour l'intégration PHP.

### 4.3 Test T04 — Consultation des commandes d'un client

**Procédure testée :** `commandes_client`

**Objectif :**

Vérifier que la procédure retourne les commandes associées à un client donné.

**Exemple de requête SQL :**

    CALL commandes_client(1);

**Résultat attendu :**

Seules les commandes appartenant au client identifié par `id_client = 1` sont affichées, triées par date décroissante.

**Résultat observé :**

À renseigner après vérification dans phpMyAdmin.

**Statut :** À confirmer.

### 4.4 Test T05 — Consultation des lignes d'une commande

**Procédure testée :** `lignes_commande`

**Objectif :**

Vérifier que la procédure retourne les produits associés à une commande.

**Exemple de requête SQL :**

    CALL lignes_commande(1);

**Résultat attendu :**

La procédure retourne les informations des lignes de la commande demandée :

- Identifiant de la commande.
- Identifiant du produit.
- Nom du produit.
- Quantité commandée.
- Prix unitaire enregistré.

**Résultat observé :**

À renseigner après vérification dans phpMyAdmin.

**Statut :** À confirmer.

### 4.5 Test T06 — Refus d'un stock négatif

**Procédure testée :** `modifier_stock`

**Objectif :**

Vérifier qu'il est impossible d'attribuer un stock négatif à un produit.

**Exemple de requête SQL :**

    CALL modifier_stock(1, -5);

**Résultat attendu :**

La procédure refuse l'opération et signale que le stock ne peut pas être négatif.

**Résultat observé :**

À renseigner après exécution.

**Statut :** À confirmer.

### 4.6 Test T07 — Refus d'un produit inexistant

**Procédure testée :** `modifier_stock`

**Objectif :**

Vérifier que la procédure refuse de modifier le stock d'un produit inexistant.

**Exemple de requête SQL :**

    CALL modifier_stock(999999, 10);

**Résultat attendu :**

La procédure refuse l'opération et signale que le produit est introuvable.

**Résultat observé :**

À renseigner après exécution.

**Statut :** À confirmer.

## 5. Tests des triggers et des contraintes

### 5.1 Test T08 — Stock insuffisant

**Objectif :**

Vérifier qu'une commande ne peut pas utiliser une quantité supérieure au stock disponible.

**Résultat attendu :**

L'opération est refusée et le stock reste cohérent.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

### 5.2 Test T09 — Modification interdite d'une ligne de commande

**Objectif :**

Vérifier que le trigger empêche les modifications de lignes qui ne sont pas autorisées par les règles métier.

**Résultat attendu :**

La modification interdite est refusée.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

### 5.3 Test T10 — Transition de statut interdite

**Objectif :**

Vérifier que le trigger de contrôle des statuts empêche une transition non autorisée.

**Résultat attendu :**

Le changement de statut est refusé.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

### 5.4 Test T11 — Annulation d'une commande

**Objectif :**

Vérifier le comportement de la base lors de l'annulation d'une commande.

**Résultat attendu :**

Le statut de la commande est mis à jour si l'annulation est autorisée.

Le stock doit être restitué si cette opération est prévue par les règles et les triggers SQL.

**Résultat observé :**

À renseigner après vérification du comportement réel.

**Statut :** À confirmer.

### 5.5 Test T12 — Vérification du ROLLBACK

**Objectif :**

Vérifier qu'une transaction SQL peut être annulée sans conserver de modifications partielles.

**Résultat attendu :**

Après un `ROLLBACK`, les modifications non validées par un `COMMIT` ne sont pas conservées.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

### 5.6 Test T13 — Refus d'un prix négatif

**Objectif :**

Vérifier qu'un produit ne peut pas être modifié avec un prix négatif.

**Résultat attendu :**

La modification est refusée par les contraintes ou les triggers de la base.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

### 5.7 Test T14 — Refus d'un stock négatif à l'insertion

**Objectif :**

Vérifier qu'un produit ne peut pas être créé avec un stock négatif.

**Résultat attendu :**

L'insertion est refusée par les contraintes ou les triggers de la base.

**Résultat observé :**

À renseigner après vérification.

**Statut :** À confirmer.

## 6. Vérification des données de démonstration

La base MiniShop contient actuellement :

- Trois catégories.
- Quinze produits.
- Des comptes de démonstration.
- Des commandes et lignes de commande de démonstration.

Les produits sont répartis de manière équilibrée entre les trois catégories :

| Catégorie | Nombre de produits |
|---|---|
| Informatique | 5 |
| Téléphonie | 5 |
| Accessoires | 5 |
| **Total** | **15** |

Les commandes de démonstration permettent notamment de vérifier le fonctionnement des relations entre CLIENT, COMMANDE, PRODUIT et LIGNE_COMMANDE.

**Attention :** le fichier `database/data.sql` ne doit pas être réexécuté sur une base déjà remplie sans préparation, car il contient des insertions de données et peut provoquer des doublons ou des erreurs de clés primaires.

## 7. Tests d'intégration PHP/MySQL

Les tests SQL sont complétés par des tests réalisés depuis l'application MiniShop sur le serveur de l'IUT.

| Fonctionnalité | Vérification | Statut |
|---|---|---|
| Catalogue | Les 15 produits sont affichés | Réussi |
| Recherche | La recherche « souris » retourne le produit correspondant | Réussi |
| Filtrage | Les produits sont filtrés par catégorie | Réussi |
| Fiche produit | Les informations du produit sont affichées | Réussi |
| Inscription | Un nouveau compte peut être enregistré | Réussi |
| Connexion | Un client peut s'authentifier | Réussi |
| Profil | Les informations du client sont récupérées depuis MySQL | Réussi |
| Modification du profil | Les informations modifiées sont enregistrées | Réussi |
| E-mail déjà utilisé | La modification est refusée | Réussi |
| Panier | Les produits et quantités sont gérés | Réussi |
| Contrôle du stock du panier | La quantité disponible ne peut pas être dépassée | Réussi |
| Validation de commande | Commande enregistrée depuis PHP | À réaliser |
| Historique des commandes | Consultation des commandes du client | À réaliser |
| Annulation de commande | Annulation depuis l'espace client | À réaliser |
| Administration | Gestion des produits, stocks et commandes | À réaliser |

Ces tests d'intégration ne remplacent pas les tests SQL directs, mais ils permettent de vérifier la communication entre PHP, PDO et MySQL.

## 8. Précautions pour les tests SQL

Certains tests nécessitent des opérations `INSERT`, `UPDATE` ou `DELETE`.

Afin de préserver les données utilisées par l'application, il est recommandé de :

- Réaliser une sauvegarde avant les tests modifiant les données.
- Utiliser des enregistrements de test clairement identifiés.
- Vérifier les effets des triggers avant toute modification.
- Éviter les opérations sur les comptes et commandes réels.
- Utiliser des transactions lorsque les opérations le permettent.
- Vérifier les données après chaque test.
- Conserver les messages d'erreur et les résultats obtenus.

Un `ROLLBACK` ne doit pas être considéré comme une protection universelle : certaines opérations SQL peuvent entraîner une validation implicite, et certaines modifications ne sont pas transactionnelles.

## 9. Bilan des tests

### 9.1 Éléments déjà confirmés

Les éléments suivants sont confirmés :

- Les cinq procédures stockées sont installées dans MySQL.
- Les cinq triggers sont installés dans MySQL.
- La recherche de produits fonctionne depuis PHP.
- Le filtrage par catégorie fonctionne depuis PHP.
- Les données du catalogue sont accessibles depuis l'application.
- La modification du profil client est enregistrée dans MySQL.
- Le contrôle des quantités du panier fonctionne.

### 9.2 Éléments à confirmer

Les résultats détaillés des tests SQL suivants restent à confirmer à partir des exécutions dans phpMyAdmin :

- Vérification précise des stocks après importation.
- Consultation des commandes par procédure stockée.
- Consultation des lignes de commande par procédure stockée.
- Refus d'un stock négatif.
- Refus d'un identifiant de produit inexistant.
- Refus d'une commande dépassant le stock.
- Refus d'une modification interdite de ligne.
- Refus d'une transition de statut interdite.
- Comportement de l'annulation et de la restitution du stock.
- Fonctionnement du `ROLLBACK`.
- Refus d'un prix négatif.
- Refus d'un stock négatif lors de l'insertion.

Ces vérifications permettront de compléter le compte rendu avec des résultats observés et, si nécessaire, des captures d'écran.

## 10. Conclusion

Les premiers tests confirment que la base de données MiniShop est correctement reliée à l'application PHP MVC pour les fonctionnalités déjà développées.

Les procédures stockées de recherche et de filtrage sont utilisées avec succès depuis PHP.

La base contient également les cinq procédures stockées et les cinq triggers demandés dans le cadre du projet.

Le compte rendu initial recense 14 tests SQL. Toutefois, les résultats détaillés de plusieurs tests doivent encore être confirmés pour disposer d'un bilan entièrement vérifiable.

La prochaine étape consiste à compléter les tests directs dans phpMyAdmin, puis à vérifier l'utilisation des procédures et des triggers lors du développement des commandes et de l'administration.

Une fois ces vérifications réalisées, le document pourra présenter un bilan final des tests réussis, échoués et corrigés.
