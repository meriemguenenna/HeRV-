
<?php
$images = [
    1 => 'ordinateur.jpg',
    2 => 'souris.jpg',
    3 => 'clavier.jpg',
    4 => 'iphone.jpg',
    5 => 'casque.jpg',
    6 => 'ecran.jpg',
    7 => 'webcam.jpg',
    8 => 'ssd.jpg',
    9 => 'ordinateur-bureau.jpg',
    10 => 'coque.jpg',
    11 => 'chargeur.jpg',
    12 => 'batterie.jpg',
    13 => 'support.jpg',
    14 => 'tapis.jpg',
    15 => 'hub-usb.jpg'
];

$idProduit = (int) $produit['id_produit'];
$image = $images[$idProduit] ?? null;
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
            <?php if ($image !== null): ?>
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
