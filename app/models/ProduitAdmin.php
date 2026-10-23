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
                       stock, actif, id_categorie, image
                FROM PRODUIT
                WHERE id_produit = :id_produit";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id_produit' => $idProduit]);

        $produit = $requete->fetch(PDO::FETCH_ASSOC);

        return $produit ?: null;
    }

    
    // Ajouter un produit et récupérer son identifiant
    public function ajouter(
        string $nom,
        string $description,
        string $prix,
        int $stock,
        int $idCategorie
    ): int {
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

        
$resultat = $requete->fetch(PDO::FETCH_ASSOC);
$requete->closeCursor();

$idProduit = (int) ($resultat['id_produit'] ?? 0);


        if ($idProduit <= 0) {
            throw new RuntimeException(
                "Impossible de récupérer l'identifiant du nouveau produit."
            );
        }

        return $idProduit;
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

    
    // Associer ou remplacer la photo d'un produit
    // (procédure stockée modifier_image_produit)
    public function modifierImage(int $idProduit, string $nomImage): void
    {
        $requete = $this->pdo->prepare(
            "CALL modifier_image_produit(:id_produit, :image)"
        );

        $requete->execute([
            'id_produit' => $idProduit,
            'image' => $nomImage
        ]);

        $requete->closeCursor();
    }
    
public function commencerTransaction(): void
{
    $this->pdo->beginTransaction();
}

public function validerTransaction(): void
{
    $this->pdo->commit();
}

public function annulerTransaction(): void
{
    if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
    }
}


}