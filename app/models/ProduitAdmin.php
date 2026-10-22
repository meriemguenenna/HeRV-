<?php

class ProduitAdmin
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lister tous les produits, actifs ou non (procédure stockée)
    public function lister(): array
    {
        $requete = $this->pdo->prepare("CALL lister_produits_admin()");
        $requete->execute();

        $produits = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete->closeCursor();

        return $produits;
    }

    // Récupérer un produit, même s'il est désactivé
    public function trouverParId(int $idProduit): ?array
    {
        $sql = "SELECT id_produit, nom, description, prix,
                       stock, actif, id_categorie
                FROM PRODUIT
                WHERE id_produit = :id_produit";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id_produit' => $idProduit]);

        $produit = $requete->fetch(PDO::FETCH_ASSOC);

        return $produit ?: null;
    }

    // Ajouter un produit (procédure stockée)
    public function ajouter(
        string $nom,
        string $description,
        string $prix,
        int $stock,
        int $idCategorie
    ): void {
        $requete = $this->pdo->prepare(
            "CALL ajouter_produit(
                :nom, :description, :prix, :stock, :id_categorie
            )"
        );

        $requete->execute([
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix,
            'stock' => $stock,
            'id_categorie' => $idCategorie
        ]);

        $requete->closeCursor();
    }

    // Modifier un produit (procédure stockée)
    public function modifier(
        int $idProduit,
        string $nom,
        string $description,
        string $prix,
        int $idCategorie
    ): void {
        $requete = $this->pdo->prepare(
            "CALL modifier_produit(
                :id_produit, :nom, :description, :prix, :id_categorie
            )"
        );

        $requete->execute([
            'id_produit' => $idProduit,
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix,
            'id_categorie' => $idCategorie
        ]);

        $requete->closeCursor();
    }

    // Activer ou désactiver un produit (procédure stockée)
    public function changerEtat(int $idProduit, bool $actif): void
    {
        $requete = $this->pdo->prepare(
            "CALL changer_etat_produit(:id_produit, :actif)"
        );

        $requete->execute([
            'id_produit' => $idProduit,
            'actif' => $actif ? 1 : 0
        ]);

        $requete->closeCursor();
    }

    // Modifier le stock (procédure existante modifier_stock)
    public function modifierStock(int $idProduit, int $nouveauStock): void
    {
        $requete = $this->pdo->prepare(
            "CALL modifier_stock(:id_produit, :nouveau_stock)"
        );

        $requete->execute([
            'id_produit' => $idProduit,
            'nouveau_stock' => $nouveauStock
        ]);

        $requete->closeCursor();
    }
}