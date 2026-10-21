<?php

require_once __DIR__ . '/../models/Produit.php';

class PanierController
{
    private Produit $produitModel;

    public function __construct(PDO $pdo)
    {
        $this->produitModel = new Produit($pdo);
    }

    public function ajouter(int $idProduit): void
    {
        // Vérifier que le produit existe et est actif
        $produit = $this->produitModel->getProduitParId($idProduit);

        if ($produit === null) {
            $_SESSION['erreur'] = "Ce produit n'existe pas.";
            return;
        }

        // Vérifier le stock disponible
        $stock = (int) $produit['stock'];

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        $quantiteActuelle = (int) ($_SESSION['panier'][$idProduit] ?? 0);

        if ($stock <= 0 || $quantiteActuelle >= $stock) {
            $_SESSION['erreur'] = "Stock insuffisant pour ce produit.";
            return;
        }

        // Ajouter une unité au panier
        $_SESSION['panier'][$idProduit] = $quantiteActuelle + 1;

        $_SESSION['succes'] = "Produit ajouté au panier !";
    }

public function index(): void
{
    $panier = $_SESSION['panier'] ?? [];

    $produitsPanier = [];
    $total = 0;

    foreach ($panier as $idProduit => $quantite) {

        $produit = $this->produitModel->getProduitParId((int) $idProduit);

        if ($produit === null) {
            continue;
        }

        $quantite = (int) $quantite;

        if ($quantite <= 0) {
            continue;
        }

        $sousTotal = (float) $produit['prix'] * $quantite;

        $produitsPanier[] = [
            'id_produit' => $produit['id_produit'],
            'nom' => $produit['nom'],
            'prix' => $produit['prix'],
            'quantite' => $quantite,
            'sous_total' => $sousTotal
        ];

        $total += $sousTotal;
    }

    require __DIR__ . '/../views/panier/index.php';
}

public function supprimer(int $idProduit): void
{
    if (isset($_SESSION['panier'][$idProduit])) {
        unset($_SESSION['panier'][$idProduit]);

        $_SESSION['succes'] = "Produit supprimé du panier.";
    } else {
        $_SESSION['erreur'] = "Ce produit n'est pas dans votre panier.";
    }
}

public function modifierQuantite(int $idProduit, int $variation): void
{
    // Vérifier que le produit est déjà dans le panier
    if (!isset($_SESSION['panier'][$idProduit])) {
        $_SESSION['erreur'] = "Produit introuvable dans le panier.";
        return;
    }

    // Seules les variations +1 et -1 sont autorisées
    if (!in_array($variation, [-1, 1], true)) {
        $_SESSION['erreur'] = "Modification invalide.";
        return;
    }

    $quantiteActuelle = (int) $_SESSION['panier'][$idProduit];
    $nouvelleQuantite = $quantiteActuelle + $variation;

    // Si la quantité arrive à zéro, retirer le produit
    if ($nouvelleQuantite <= 0) {
        unset($_SESSION['panier'][$idProduit]);
        return;
    }

    // Vérifier le produit et son stock actuel
    $produit = $this->produitModel->getProduitParId($idProduit);

    if ($produit === null) {
        $_SESSION['erreur'] = "Ce produit n'est plus disponible.";
        return;
    }

    if ($nouvelleQuantite > (int) $produit['stock']) {
        $_SESSION['erreur'] = "Stock insuffisant.";
        return;
    }

    // Enregistrer la nouvelle quantité
    $_SESSION['panier'][$idProduit] = $nouvelleQuantite;
}

}