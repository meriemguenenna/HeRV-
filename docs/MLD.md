# MiniShop — Modèle logique de données (MLD)

> **Objectif :** transformer le modèle conceptuel de données (MCD) de MiniShop en un modèle logique relationnel cohérent, prêt à être implémenté sous MySQL.

---

## Sommaire

- [Présentation](#présentation)
- [Structure relationnelle](#structure-relationnelle)
- [Relations entre les tables](#relations-entre-les-tables)
- [Contraintes et règles de gestion](#contraintes-et-règles-de-gestion)
- [Compatibilité MySQL](#compatibilité-mysql)
- [Livrable attendu](#livrable-attendu)

---

## Présentation

Le modèle logique de données organise les informations de MiniShop en tables relationnelles. Il définit les attributs, les clés primaires et les clés étrangères nécessaires pour assurer la cohérence de la base de données.

### Travail à réaliser

| Étape | Description |
|:--|:--|
| **01 — Entités** | Transformer les entités du MCD en tables relationnelles. |
| **02 — Attributs** | Définir les colonnes de chaque table. |
| **03 — Clés primaires** | Identifier chaque enregistrement de manière unique. |
| **04 — Clés étrangères** | Représenter les relations entre les tables. |
| **05 — Vérification** | Contrôler les cardinalités et les règles métier. |
| **06 — Implémentation** | Préparer une structure compatible avec MySQL. |

---

## Structure relationnelle

Les tables principales identifiées pour MiniShop sont présentées ci-dessous.

| Table | Description | Clé primaire |
|:--|:--|:--|
| `Client` | Informations relatives aux clients. | À définir selon le MCD |
| `Commande` | Commandes passées par les clients. | À définir selon le MCD |
| `LigneCommande` | Produits et quantités associés aux commandes. | À définir selon le MCD |
| `Produit` | Informations relatives aux produits. | À définir selon le MCD |
| `Categorie` | Catégories auxquelles appartiennent les produits. | À définir selon le MCD |

> **Remarque :** les attributs et les clés exacts doivent être repris du MCD de MiniShop afin de respecter le modèle d'origine.

---

## Relations entre les tables

Le modèle doit représenter les relations entre les clients, les commandes, les lignes de commande, les produits et les catégories.

| Relation | Cardinalité attendue | Description |
|:--|:--|:--|
| Client → Commande | 1,N | Un client peut passer plusieurs commandes. |
| Commande → LigneCommande | 1,N | Une commande contient plusieurs lignes de commande. |
| Produit → LigneCommande | 0,N | Un produit peut apparaître dans plusieurs lignes de commande. |
| Categorie → Produit | 1,N | Une catégorie peut regrouper plusieurs produits. |

Ces cardinalités sont à vérifier avec le MCD original.

### Représentation des relations

```text
CLIENT (1) -------- (N) COMMANDE
                         |
                         | (1,N)
                         |
                         v
                   LIGNECOMMANDE
                         ^
                         | (0,N)
                         |
PRODUIT (N) -------------+
    |
    | (N,1)
    |
    v
CATEGORIE