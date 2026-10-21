
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon panier - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">MiniShop <span>✦</span></a>

    <nav class="navigation">
        <a href="index.php">Accueil</a>
        <a href="index.php#produits">Boutique</a>
    </nav>

    <div class="header-actions">
        <span class="panier-apercu">
            🛒 Mon panier
        </span>
    </div>
</header>

<main class="conteneur-panier">

    <h1>🛒 Mon panier</h1>
    <p>Retrouvez ici les produits que vous avez sélectionnés.</p>

    
<div class="contenu-panier">

    <?php if (empty($produitsPanier)): ?>

        <p>Votre panier est vide pour le moment. 💜</p>

    <?php else: ?>

        <?php foreach ($produitsPanier as $produit): ?>

            <div class="ligne-panier">

                <div class="infos-panier">
                    <h2>
                        <?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <p>
                        Prix unitaire :
                        <?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €
                    </p>

                    
<div class="quantite-panier">
    <span>Quantité :</span>

    <div class="controle-quantite">

        <!-- Bouton moins -->
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="modifier_quantite">
            <input type="hidden" name="id_produit"
                   value="<?= (int) $produit['id_produit'] ?>">
            <input type="hidden" name="variation" value="-1">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

            <button type="submit" class="bouton-quantite"
                    aria-label="Diminuer la quantité">
                −
            </button>
        </form>

        <strong><?= (int) $produit['quantite'] ?></strong>

        <!-- Bouton plus -->
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="modifier_quantite">
            <input type="hidden" name="id_produit"
                   value="<?= (int) $produit['id_produit'] ?>">
            <input type="hidden" name="variation" value="1">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

            <button type="submit" class="bouton-quantite"
                    aria-label="Augmenter la quantité">
                +
            </button>
        </form>

    </div>
</div>


<form action="index.php" method="POST" class="form-supprimer">
    <input type="hidden" name="action" value="supprimer_panier">

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

    <button type="submit" class="bouton-supprimer">
        🗑️ Supprimer
    </button>
</form>

                </div>

                <p class="sous-total-panier">
                    <?= number_format((float) $produit['sous_total'], 2, ',', ' ') ?> €
                </p>

            </div>

        <?php endforeach; ?>

        <div class="total-panier">
            <strong>Total du panier :</strong>
            <strong>
                <?= number_format((float) $total, 2, ',', ' ') ?> €
            </strong>
        </div>

    <?php endif; ?>

</div>


    <a href="index.php#produits" class="retour-boutique">
        ← Continuer mes achats
    </a>

</main>

</body>
</html>
