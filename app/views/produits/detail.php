<?php
$idProduit = (int) $produit['id_produit'];
$image = $produit['image'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?> - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="detail-produit">

    <a href="index.php?page=accueil#produits" class="retour-catalogue">
        ← Retour au catalogue
    </a>

    <div class="detail-contenu">

        <div class="detail-image">
            <?php if (!empty($image)): ?>
                <img
                    src="images/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?>"
                >
            <?php endif; ?>
        </div>

        <div class="detail-informations">

            <h1><?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?></h1>

            <p class="detail-description">
                <?= htmlspecialchars($produit['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p class="detail-prix">
                <?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €
            </p>

            <p class="detail-stock">
                <?php if ((int) $produit['stock'] > 0): ?>
                    En stock : <?= (int) $produit['stock'] ?> disponible(s)
                <?php else: ?>
                    Rupture de stock
                <?php endif; ?>
            </p>

            <?php if ((int) $produit['stock'] > 0): ?>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="ajouter_panier">
                    <input type="hidden" name="id_produit" value="<?= $idProduit ?>">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                    <button type="submit" class="btn-ajouter-detail">
                        🛒 Ajouter au panier
                    </button>
                </form>
            <?php endif; ?>

        </div>
    </div>

</main>

</body>
</html>