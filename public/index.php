
<?php

session_start();

// Connexion à la base de données
require_once __DIR__ . '/../config/database.php';

// Contrôleurs existants
require_once __DIR__ . '/../app/controllers/ProduitController.php';
require_once __DIR__ . '/../app/controllers/PanierController.php';

// Authentification
require_once __DIR__ . '/../app/models/Client.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

// --------------------------------------------------
// 1. Sécurité : jeton CSRF
// --------------------------------------------------

// Créer un jeton pour les formulaires s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Vérifie le jeton CSRF d'un formulaire.
 */
function verifierCsrf(): void
{
    $jetonSession = $_SESSION['csrf_token'] ?? null;
    $jetonFormulaire = $_POST['csrf_token'] ?? null;

    if (
        !is_string($jetonSession)
        || $jetonSession === ''
        || !is_string($jetonFormulaire)
        || !hash_equals($jetonSession, $jetonFormulaire)
    ) {
        http_response_code(403);
        exit('Formulaire invalide.');
    }
}


// --------------------------------------------------
// 2. Traitement des formulaires POST
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifier le jeton avant toute modification
    verifierCsrf();

    $action = $_POST['action'] ?? '';
    $page = $_GET['page'] ?? '';

    // ----------------------------------------------
    // 2.1 Ajouter un produit au panier
    // ----------------------------------------------

    if ($action === 'ajouter_panier') {

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

        // Éviter un nouvel ajout après actualisation
        header('Location: index.php', true, 303);
        exit;
    }

    // ----------------------------------------------
    // 2.2 Supprimer un produit du panier
    // ----------------------------------------------

    if ($action === 'supprimer_panier') {

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

        header('Location: index.php?page=panier', true, 303);
        exit;
    }

    // ----------------------------------------------
    // 2.3 Modifier la quantité dans le panier
    // ----------------------------------------------

    if ($action === 'modifier_quantite') {

        $idProduit = filter_var(
            $_POST['id_produit'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $variation = filter_var(
            $_POST['variation'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (
            $idProduit === false
            || $idProduit === null
            || $variation === false
            || $variation === null
            || !in_array($variation, [-1, 1], true)
        ) {
            $_SESSION['erreur'] = 'Modification invalide.';
        } else {
            $panierController = new PanierController($pdo);
            $panierController->modifierQuantite(
                $idProduit,
                $variation
            );
        }

        header('Location: index.php?page=panier', true, 303);
        exit;
    }

    // ----------------------------------------------
    // 2.4 Inscription d'un nouveau client
    // ----------------------------------------------

    if ($page === 'inscription') {

        $authController = new AuthController($pdo);
        $authController->inscrire();
        exit;
    }

    // ----------------------------------------------
    // 2.5 Connexion d'un client
    // ----------------------------------------------

    if ($page === 'connexion') {

        $authController = new AuthController($pdo);
        $authController->connecter();
        exit;
    }

    // ----------------------------------------------
    // 2.6 Déconnexion du client
    // ----------------------------------------------

    if ($action === 'deconnexion') {

        $authController = new AuthController($pdo);
        $authController->deconnecter();
        exit;
    }


    // ----------------------------------------------
    // 2.7 Modifier le profil du client
    // ----------------------------------------------

    if ($action === 'modifier_profil') {

        $authController = new AuthController($pdo);
        $authController->modifierProfil();
        exit;
    }
    
        // ----------------------------------------------
    // 2.8 Administration (réservé aux administrateurs)
    // ----------------------------------------------

    if (is_string($action) && strpos($action, 'admin_') === 0) {

        $adminController = new AdminController($pdo);
        $adminController->traiterAction($action);
        exit;
    }
    // Refuser les actions POST inconnues
    http_response_code(400);
    exit('Action inconnue.');
}


// --------------------------------------------------
// 3. Affichage des pages (GET)
// --------------------------------------------------

$page = $_GET['page'] ?? 'accueil';

switch ($page) {

    // Catalogue
    case 'accueil':
        $controller = new ProduitController($pdo);
        $controller->index();
        break;


    // Détail d'un produit
    case 'detail':
        $controller = new ProduitController($pdo);
        $controller->detail();
        break;


    // Panier
    case 'panier':
        $controller = new PanierController($pdo);
        $controller->index();
        break;

    // Inscription
    case 'inscription':
        $controller = new AuthController($pdo);
        $controller->afficherInscription();
        break;

    // Connexion
    case 'connexion':
        $controller = new AuthController($pdo);
        $controller->afficherConnexion();
        break;

    
case 'profil':
    $controller = new AuthController($pdo);
    $controller->afficherProfil();
    break;
    

        // Administration (réservé aux administrateurs)
    case 'admin':
    case 'admin_produits':
    case 'admin_categories':
    case 'admin_stocks':
    case 'admin_commandes':
        $controller = new AdminController($pdo);
        $controller->afficher($page);
        break;
    // Page inconnue
    default:
        http_response_code(404);
        echo 'Page introuvable.';
        break;
}
