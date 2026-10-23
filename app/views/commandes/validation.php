
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de commande - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">MiniShop <span>✦</span></a>

    <nav class="navigation">
        <a href="index.php">Accueil</a>
        <a href="index.php?page=panier">🛒 Mon panier</a>
    </nav>
</header>

<main class="conteneur-panier">

    <h1>💜 Validation de votre commande</h1>
    <p>Vérifiez votre panier avant de confirmer votre achat.</p>

    <div class="contenu-panier">

        <?php foreach ($produitsPanier as $produit): ?>
            <div class="ligne-panier">
                <div class="infos-panier">
                    <h2>
                        <?= htmlspecialchars(
                            $produit['nom'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h2>

                    <p>
                        Quantité :
                        <?= (int) $produit['quantite'] ?>
                    </p>

                    <p>
                        Prix unitaire :
                        <?= number_format(
                            (float) $produit['prix'],
                            2,
                            ',',
                            ' '
                        ) ?> €
                    </p>
                </div>

                <p class="sous-total-panier">
                    <?= number_format(
                        (float) $produit['sous_total'],
                        2,
                        ',',
                        ' '
                    ) ?> €
                </p>
            </div>
        <?php endforeach; ?>

        <div class="total-panier">
            <strong>Total à payer :</strong>
            <strong>
                <?= number_format($total, 2, ',', ' ') ?> €
            </strong>
        </div>

        <p>
            💳 <strong>Paiement simulé</strong> :
            aucun paiement réel ne sera effectué
            et aucune donnée bancaire n'est nécessaire.
        </p>

        <form action="index.php" method="POST">
            <input type="hidden"
                   name="action"
                   value="confirmer_commande">

            <input type="hidden"
                   name="csrf_token"
                   value="<?= htmlspecialchars(
                       $_SESSION['csrf_token'],
                       ENT_QUOTES,
                       'UTF-8'
                   ) ?>">

            <button type="submit" class="bouton-commander">
                💜 Confirmer le paiement simulé
            </button>
        </form>

    </div>

    <a href="index.php?page=panier" class="retour-boutique">
        ← Retour au panier
    </a>

</main>

</body>
</html>
