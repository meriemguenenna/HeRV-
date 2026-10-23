<?php

$titrePage = 'Gestion des stocks';
$pageActive = 'admin_stocks';

require __DIR__ . '/_entete.php';

?>

<section class="admin-panneau">

    <h2>Stock des produits (<?= count($produits) ?>)</h2>

    <?php if (empty($produits)): ?>

        <p>Aucun produit pour le moment.</p>

    <?php else: ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Stock actuel</th>
                        <th>Niveau</th>
                        <th>Nouveau stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <?php
                        $stock = (int) $produit['stock'];

                        if ($stock === 0) {
                            $niveau = 'Rupture';
                            $classeNiveau = 'admin-badge-off';
                        } elseif ($stock <= 5) {
                            $niveau = 'Stock faible';
                            $classeNiveau = 'admin-badge-alerte';
                        } else {
                            $niveau = 'Correct';
                            $classeNiveau = 'admin-badge-ok';
                        }
                        ?>
                        <tr class="<?= (int) $produit['actif'] === 1 ? '' : 'admin-ligne-inactive' ?>">
                            <td><?= (int) $produit['id_produit'] ?></td>
                            <td><?= echapper($produit['nom']) ?></td>
                            <td><?= echapper($produit['categorie']) ?></td>
                            <td><strong><?= $stock ?></strong></td>
                            <td>
                                <span class="admin-badge <?= $classeNiveau ?>">
                                    <?= $niveau ?>
                                </span>
                            </td>
                            <td>
                                <form action="index.php" method="POST" class="admin-formulaire-inline">
                                    <input type="hidden" name="action" value="admin_modifier_stock">
                                    <input type="hidden" name="id_produit" value="<?= (int) $produit['id_produit'] ?>">
                                    <?= champCsrf() ?>

                                    <input
                                        type="number"
                                        name="stock"
                                        min="0"
                                        step="1"
                                        required
                                        value="<?= $stock ?>"
                                        aria-label="Nouveau stock pour <?= echapper($produit['nom']) ?>"
                                        class="admin-champ-stock"
                                    >

                                    <button type="submit" class="admin-bouton admin-bouton-petit">
                                        Enregistrer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="admin-aide">
            Le stock ne peut jamais être négatif : la base de données
            refuse toute valeur inférieure à zéro.
        </p>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/_pied.php'; ?>