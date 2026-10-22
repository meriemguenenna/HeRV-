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
    $recherche = trim(
        is_string($_GET['recherche'] ?? null)
            ? $_GET['recherche']
            : ''
    );

    if ($recherche !== '') {
        $produits = $this->produitModel->rechercherProduits($recherche);
    } else {
        $produits = $this->produitModel->getTousLesProduits();
    }

    require __DIR__ . '/../views/produits/index.php';
}

}