<?php

require_once __DIR__ . '/../models/Produit.php';

class ProduitController
{
    private Produit $produitModel;

    public function __construct(PDO $pdo)
    {
        $this->produitModel = new Produit($pdo);
    }

   

public function index(): void
{
    // Récupérer la recherche
    $recherche = trim(
        is_string($_GET['recherche'] ?? null)
            ? $_GET['recherche']
            : ''
    );

    // Récupérer la catégorie sélectionnée
    $idCategorie = filter_var(
        $_GET['categorie'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($recherche !== '') {
        // Priorité à la recherche par nom
        $produits = $this->produitModel->rechercherProduits($recherche);

    } elseif ($idCategorie !== false && $idCategorie !== null) {
        // Filtrer par catégorie
        $produits = $this->produitModel->getProduitsParCategorie($idCategorie);

    } else {
        // Afficher tous les produits
        $produits = $this->produitModel->getTousLesProduits();
    }

    require __DIR__ . '/../views/produits/index.php';
}


}