
<?php

// En-tête commun à toutes les pages d'administration.
// Variables attendues : $titrePage, $pageActive,
// $messageSucces, $messageErreur (fournies par AdminController).

if (!function_exists('echapper')) {
    // Échapper une valeur avant affichage (protection XSS)
    function echapper($valeur): string
    {
        return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('champCsrf')) {
    // Champ caché contenant le jeton CSRF des formulaires
    function champCsrf(): string
    {
        return '<input type="hidden" name="csrf_token" value="'
            . echapper($_SESSION['csrf_token'] ?? '')
            . '">';
    }
}

$titrePage = $titrePage ?? 'Administration';
$pageActive = $pageActive ?? '';

$menuAdmin = [
    'admin'            => 'Tableau de bord',
    'admin_produits'   => 'Produits',
    'admin_categories' => 'Catégories',
    'admin_stocks'     => 'Stocks',
    'admin_commandes'  => 'Commandes'
];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= echapper($titrePage) ?> — Administration MiniShop</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Barre de navigation administration -->
    <header class="site-header">

        <!-- Retour au tableau de bord -->
        <a href="index.php?page=admin" class="logo">
            MiniShop <span>✦</span> Admin
        </a>

        <nav class="navigation" aria-label="Navigation administration">
            <?php foreach ($menuAdmin as $cle => $libelle): ?>
                <a
                    href="index.php?page=<?= echapper($cle) ?>"
                    class="<?= $pageActive === $cle ? 'nav-active' : '' ?>"
                >
                    <?= echapper($libelle) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions">

            <!-- Accès à la boutique -->
            <a href="index.php" class="connexion-apercu">
                🛍️ Voir la boutique
            </a>

            <!-- Accès au profil administrateur -->
            <a href="index.php?page=profil" class="connexion-apercu">
                👤 Mon profil
            </a>

            <!-- Prénom de l'administrateur connecté -->
            <span class="connexion-apercu">
                👤 <?= echapper($_SESSION['client_prenom'] ?? '') ?>
            </span>

            <!-- Déconnexion sécurisée -->
            <form action="index.php" method="POST" class="form-deconnexion">
                <input type="hidden" name="action" value="deconnexion">

                <?= champCsrf() ?>

                <button
                    type="submit"
                    class="connexion-apercu bouton-deconnexion"
                >
                    Déconnexion
                </button>
            </form>

        </div>
    </header>

    <main class="admin-main">

        <h1 class="admin-titre"><?= echapper($titrePage) ?></h1>

        <?php if (!empty($messageSucces)): ?>
            <div class="admin-message admin-message-succes" role="status">
                <?= echapper($messageSucces) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($messageErreur)): ?>
            <div class="admin-message admin-message-erreur" role="alert">
                <?= echapper($messageErreur) ?>
            </div>
        <?php endif; ?>
