# MiniShop — Compte rendu des tests SQL

## 1. Objectif

Vérifier le bon fonctionnement de la base de données MiniShop, notamment les procédures stockées, les triggers et les contraintes d'intégrité.

**Base de données :** `hm504733_minishop`  
**Outil utilisé :** phpMyAdmin  
**Résultat global :** 14 tests réussis sur 14.

## 2. Résultats des tests

| N° | Test effectué | Résultat attendu | Statut |
|---|---|---|---|
| 1 | Vérification des stocks après importation | Stocks correctement déduits | Réussi |
| 2 | Recherche d'un produit | La souris sans fil est trouvée | Réussi |
| 3 | Recherche par catégorie | Les trois accessoires sont affichés | Réussi |
| 4 | Consultation des commandes d'un client | La commande du client est affichée | Réussi |
| 5 | Consultation des lignes d'une commande | Les deux produits de la commande sont affichés | Réussi |
| 6 | Modification avec stock négatif | Opération refusée | Réussi |
| 7 | Modification d'un produit inexistant | Opération refusée | Réussi |
| 8 | Commande supérieure au stock disponible | Insertion refusée | Réussi |
| 9 | Modification d'une ligne de commande | Modification refusée | Réussi |
| 10 | Transition de statut interdite | Changement refusé | Réussi |
| 11 | Annulation d'une commande | Stock restitué | Réussi |
| 12 | Vérification du ROLLBACK | Données initiales restaurées | Réussi |
| 13 | Modification avec prix négatif | Modification refusée | Réussi |
| 14 | Insertion avec stock négatif | Insertion refusée | Réussi |

## 3. Conclusion

Les 14 tests exécutés avec phpMyAdmin ont produit les résultats attendus.

Ils confirment le fonctionnement des procédures stockées testées, des mécanismes de contrôle des stocks, des restrictions sur les modifications de commandes et des contraintes de validation des produits.

La base de données est prête pour la poursuite du développement de l'application PHP MVC MiniShop.

**Remarque :** ces tests portent sur la base de données. Les fonctionnalités PHP, l'authentification et les contrôles d'accès devront être testés séparément.