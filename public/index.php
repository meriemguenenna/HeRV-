
<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/ProduitController.php';
require_once __DIR__ . '/../app/controllers/PanierController.php';

// Traiter l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'ajouter_panier') {

    // Vérifier le jeton de sécurité du formulaire
    if (!hash_equals(
        $_SESSION['csrf_token'] ?? '',
        $_POST['csrf_token'] ?? ''
    ) || empty($_SESSION['csrf_token'])) {
        http_response_code(403);
        exit('Formulaire invalide.');
    }

    $idProduit = filter_var(
        $_POST['id_produit'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($idProduit === false || $idProduit === null) {
        $_SESSION['erreur'] = 'Produit invalide.';
    } else {
        $panierController = new PanierController($pdo);
        $panierController->ajouter($idProduit);
    }

    // Éviter un nouvel ajout si on actualise la page
    header('Location: index.php', true, 303);
    exit;
}

// Traiter la suppression d'un produit du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'supprimer_panier') {

    // Vérifier le jeton de sécurité
    if (
        empty($_SESSION['csrf_token'])
        || !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token'] ?? ''
        )
    ) {
        http_response_code(403);
        exit('Formulaire invalide.');
    }

    // Vérifier l'identifiant du produit
    $idProduit = filter_var(
        $_POST['id_produit'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($idProduit === false || $idProduit === null) {
        $_SESSION['erreur'] = 'Produit invalide.';
    } else {
        $panierController = new PanierController($pdo);
        $panierController->supprimer($idProduit);
    }

    // Revenir à la page panier
    header('Location: index.php?page=panier', true, 303);
    exit;
}


// Traiter la modification de quantité du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'modifier_quantite') {

    // Vérifier le jeton de sécurité
    if (
        empty($_SESSION['csrf_token'])
        || !is_string($_POST['csrf_token'] ?? null)
        || !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        )
    ) {
        http_response_code(403);
        exit('Formulaire invalide.');
    }

    // Vérifier l'identifiant du produit
    $idProduit = filter_var(
        $_POST['id_produit'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    // Accepter uniquement +1 ou -1
    $variation = filter_var(
        $_POST['variation'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        $idProduit === false || $idProduit === null
        || $variation === false || $variation === null
        || !in_array($variation, [-1, 1], true)
    ) {
        $_SESSION['erreur'] = 'Modification invalide.';
    } else {
        $panierController = new PanierController($pdo);
        $panierController->modifierQuantite($idProduit, $variation);
    }

    // Revenir au panier après modification
    header('Location: index.php?page=panier', true, 303);
    exit;
}


// Créer le jeton de sécurité pour les formulaires
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Afficher la page demandée
$page = $_GET['page'] ?? 'accueil';

if ($page === 'panier') {
    $controller = new PanierController($pdo);
    $controller->index();
} else {
    $controller = new ProduitController($pdo);
    $controller->index();
}
