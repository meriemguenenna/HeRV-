<?php

$titrePage = 'Tableau de bord';
$pageActive = 'admin';

require __DIR__ . '/_entete.php';

?>

<section class="admin-cartes">

    <a href="index.php?page=admin_produits" class="admin-carte">
        <span class="admin-carte-nombre"><?= (int) $nbProduits ?></span>
        <span class="admin-carte-titre">Produits</span>
        <span class="admin-carte-lien">Gérer les produits →</span>
    </a>

    <a href="index.php?page=admin_categories" class="admin-carte">
        <span class="admin-carte-nombre"><?= (int) $nbCategories ?></span>
        <span class="admin-carte-titre">Catégories</span>
        <span class="admin-carte-lien">Gérer les catégories →</span>
    </a>

    <a href="index.php?page=admin_stocks" class="admin-carte">
        <span class="admin-carte-nombre">📦</span>
        <span class="admin-carte-titre">Stocks</span>
        <span class="admin-carte-lien">Mettre à jour les stocks →</span>
    </a>

    <a href="index.php?page=admin_commandes" class="admin-carte">
        <span class="admin-carte-nombre"><?= (int) $nbCommandes ?></span>
        <span class="admin-carte-titre">
            Commandes (<?= (int) $nbEnAttente ?> en attente)
        </span>
        <span class="admin-carte-lien">Suivre les commandes →</span>
    </a>

</section>

<?php require __DIR__ . '/_pied.php'; ?>