<?php

$titrePage = 'Gestion des catégories';
$pageActive = 'admin_categories';

require __DIR__ . '/_entete.php';

$modeEdition = $categorieEdition !== null;

?>

<!-- Formulaire d'ajout / de modification -->
<section class="admin-panneau">

    <h2><?= $modeEdition ? 'Modifier la catégorie' : 'Ajouter une catégorie' ?></h2>

    <form action="index.php" method="POST" class="admin-formulaire">

        <input
            type="hidden"
            name="action"
            value="<?= $modeEdition ? 'admin_modifier_categorie' : 'admin_ajouter_categorie' ?>"
        >

        <?= champCsrf() ?>

        <?php if ($modeEdition): ?>
            <input
                type="hidden"
                name="id_categorie"
                value="<?= (int) $categorieEdition['id_categorie'] ?>"
            >
        <?php endif; ?>

        <label>
            Nom de la catégorie
            <input
                type="text"
                name="nom"
                maxlength="100"
                required
                value="<?= echapper($categorieEdition['nom'] ?? '') ?>"
            >
        </label>

        <div class="admin-actions">
            <button type="submit" class="admin-bouton">
                <?= $modeEdition ? 'Enregistrer' : 'Ajouter la catégorie' ?>
            </button>

            <?php if ($modeEdition): ?>
                <a href="index.php?page=admin_categories" class="admin-bouton admin-bouton-secondaire">
                    Annuler
                </a>
            <?php endif; ?>
        </div>

    </form>
</section>

<!-- Liste des catégories -->
<section class="admin-panneau">

    <h2>Toutes les catégories (<?= count($categories) ?>)</h2>

    <?php if (empty($categories)): ?>

        <p>Aucune catégorie pour le moment.</p>

    <?php else: ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Produits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $categorie): ?>
                        <?php $nbProduits = (int) $categorie['nb_produits']; ?>
                        <tr>
                            <td><?= (int) $categorie['id_categorie'] ?></td>
                            <td><?= echapper($categorie['nom']) ?></td>
                            <td><?= $nbProduits ?></td>
                            <td class="admin-cellule-actions">

                                <a
                                    href="index.php?page=admin_categories&amp;modifier=<?= (int) $categorie['id_categorie'] ?>"
                                    class="admin-bouton admin-bouton-petit"
                                >
                                    Modifier
                                </a>

                                <form
                                    action="index.php"
                                    method="POST"
                                    class="admin-formulaire-inline"
                                    onsubmit="return confirm('Supprimer cette catégorie ?');"
                                >
                                    <input type="hidden" name="action" value="admin_supprimer_categorie">
                                    <input type="hidden" name="id_categorie" value="<?= (int) $categorie['id_categorie'] ?>">
                                    <?= champCsrf() ?>

                                    <button
                                        type="submit"
                                        class="admin-bouton admin-bouton-petit admin-bouton-danger"
                                        <?= $nbProduits > 0 ? 'disabled title="Une catégorie contenant des produits ne peut pas être supprimée"' : '' ?>
                                    >
                                        Supprimer
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="admin-aide">
            Une catégorie ne peut être supprimée que si elle ne contient
            aucun produit.
        </p>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/_pied.php'; ?>