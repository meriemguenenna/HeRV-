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
        $produits = $this->produitModel->getTousLesProduits();


        require __DIR__ . '/../views/produits/index.php';
    }
}