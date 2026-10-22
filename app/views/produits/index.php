
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MiniShop — Boutique Tech</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Barre de navigation -->
    <header class="site-header">
    <a href="index.php" class="logo">
        MiniShop <span>✦</span>
    </a>

    <nav class="navigation" aria-label="Navigation principale">
        <a href="index.php" class="nav-active">Accueil</a>
        <a href="#produits">Boutique</a>
    </nav>

    
<div class="header-actions">

    <?php if (!empty($_SESSION['client_id'])): ?>

        <!-- Client connecté -->
        <span class="connexion-apercu">
            👤 Bonjour,
            <?= htmlspecialchars(
                $_SESSION['client_prenom'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </span>

        <form action="index.php" method="POST" class="form-deconnexion">
            <input type="hidden" name="action" value="deconnexion">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION['csrf_token'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <button type="submit" class="connexion-apercu bouton-deconnexion">
                Déconnexion
            </button>
        </form>

    <?php else: ?>

        <!-- Visiteur non connecté -->
        <a href="index.php?page=connexion" class="connexion-apercu">
            👤 Connexion
        </a>

        <a href="index.php?page=inscription" class="connexion-apercu">
            Inscription
        </a>

    <?php endif; ?>


        
<a href="index.php?page=panier" class="panier-apercu">
    <span aria-hidden="true">🛒</span>
    Panier
    <span class="panier-compteur">
        <?= array_sum($_SESSION['panier'] ?? []) ?>
    </span>
</a>

    </div>
</header>

    <main>

        <!-- Présentation -->
        <section class="hero">
            <span class="hero-badge">✦ LA TECH À PORTÉE DE MAIN</span>

            <h1>
                La tech qui donne
                <span>envie.</span>
            </h1>

            <p class="sous-titre">
                Découvrez notre sélection d'accessoires et de produits
                technologiques pour votre quotidien.
            </p>

            <a href="#produits" class="bouton-decouvrir">
                Découvrir la collection ↓
            </a>
        </section>

        <!-- Catalogue -->
        <section id="produits" class="catalogue">

            <div class="section-titre">
                <div>
                    <span class="section-label">NOTRE SÉLECTION</span>
                    <h2>Nos produits <span>✦</span></h2>
                </div>

                <p>
                    <?= count($produits ?? []) ?> produits à découvrir
                </p>
            </div>

            <?php if (empty($produits)): ?>

                <p>Aucun produit disponible pour le moment.</p>

            <?php else: ?>

                <div class="produits-grid">

                    <?php
                    $images = [
                        1 => 'ordinateur.jpg',
                        2 => 'souris.jpg',
                        3 => 'clavier.jpg',
                        4 => 'iphone.jpg',
                        5 => 'casque.jpg'
                    ];
                    ?>

                    <?php foreach ($produits as $produit): ?>

                        <?php
                        $id = (int) $produit['id_produit'];
                        $image = $images[$id] ?? null;
                        $stock = (int) $produit['stock'];
                        ?>

                        <article class="produit-card">

                            <div class="produit-image">

                                <span class="produit-badge">
                                    <?= $stock > 0 ? 'Disponible' : 'Épuisé' ?>
                                </span>

                                <?php if ($image !== null): ?>
                                    <img
                                        src="images/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                        loading="lazy"
                                    >
                                <?php endif; ?>

                            </div>

                            <div class="produit-infos">

                                <span class="produit-categorie">
                                    COLLECTION TECH
                                </span>

                                <h3>
                                    <?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?>
                                </h3>

                                <p class="produit-description">
                                    <?= htmlspecialchars($produit['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </p>

                                <div class="produit-bas">

    <p class="prix">
        <?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €
    </p>

    <span class="stock <?= $stock > 0 ? '' : 'stock-vide' ?>">
        <?= $stock > 0 ? '● En stock : ' . $stock : '● Rupture de stock' ?>
    </span>

    <form action="index.php" method="POST" class="form-panier">
        <input type="hidden" name="action" value="ajouter_panier">

        <input
            type="hidden"
            name="id_produit"
            value="<?= (int) $produit['id_produit'] ?>"
        >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
        >

        <button
            type="submit"
            class="bouton-panier"
            <?= $stock <= 0 ? 'disabled' : '' ?>
        >
            🛒 <?= $stock > 0 ? 'Ajouter au panier' : 'Indisponible' ?>
        </button>
    </form>

</div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </main>

    <!-- Pied de page -->
    <footer>
        <div class="footer-logo">MiniShop ✦</div>
        <p>La tech, avec une touche de style.</p>
        <p>© <?= date('Y') ?> MiniShop — Tous droits réservés.</p>
    </footer>

</body>
</html>
