<?php

$titrePage = 'Gestion des produits';
$pageActive = 'admin_produits';

require __DIR__ . '/_entete.php';

$modeEdition = $produitEdition !== null;

?>

<!-- Formulaire d'ajout / de modification -->
<section class="admin-panneau">

    <h2><?= $modeEdition ? 'Modifier le produit' : 'Ajouter un produit' ?></h2>

    <form action="index.php" method="POST" enctype="multipart/form-data" class="admin-formulaire">

        <input
            type="hidden"
            name="action"
            value="<?= $modeEdition ? 'admin_modifier_produit' : 'admin_ajouter_produit' ?>"
        >

        <?= champCsrf() ?>

        <?php if ($modeEdition): ?>
            <input
                type="hidden"
                name="id_produit"
                value="<?= (int) $produitEdition['id_produit'] ?>"
            >
        <?php endif; ?>

        <label>
            Nom du produit
            <input
                type="text"
                name="nom"
                maxlength="150"
                required
                value="<?= echapper($produitEdition['nom'] ?? '') ?>"
            >
        </label>

        <label>
            Description
            <textarea name="description" rows="3" maxlength="2000"><?= echapper($produitEdition['description'] ?? '') ?></textarea>
        </label>

        <label>
            Prix (€)
            <input
                type="number"
                name="prix"
                step="0.01"
                min="0.01"
                required
                value="<?= echapper($produitEdition['prix'] ?? '') ?>"
            >
        </label>

        <?php if (!$modeEdition): ?>
            <label>
                Stock initial
                <input
                    type="number"
                    name="stock"
                    min="0"
                    step="1"
                    required
                    value="0"
                >
            </label>
        <?php endif; ?>

        <label>
            Catégorie
            <select name="id_categorie" required>
                <option value="">— Choisir —</option>
                <?php foreach ($categories as $categorie): ?>
                    <option
                        value="<?= (int) $categorie['id_categorie'] ?>"
                        <?= $modeEdition
                            && (int) $produitEdition['id_categorie'] === (int) $categorie['id_categorie']
                            ? 'selected' : '' ?>
                    >
                        <?= echapper($categorie['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        
        <!-- Photo du produit -->
        <label>
            Photo du produit
            <input
                type="file"
                name="image_produit"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >
        </label>

        <p class="admin-aide">
            Formats acceptés : JPG, PNG ou WebP.
            Taille maximale : 2 Mo.
            <?php if ($modeEdition): ?>
                Si vous ne choisissez aucune image, la photo actuelle sera conservée.
            <?php endif; ?>
        </p>


        <div class="admin-actions">
            <button type="submit" class="admin-bouton">
                <?= $modeEdition ? 'Enregistrer les modifications' : 'Ajouter le produit' ?>
            </button>

            <?php if ($modeEdition): ?>
                <a href="index.php?page=admin_produits" class="admin-bouton admin-bouton-secondaire">
                    Annuler
                </a>
            <?php endif; ?>
        </div>

        <?php if ($modeEdition): ?>
            <p class="admin-aide">
                Le stock se modifie depuis la page
                <a href="index.php?page=admin_stocks">Stocks</a>.
            </p>
        <?php endif; ?>

    </form>
</section>

<!-- Liste des produits -->
<section class="admin-panneau">

    <h2>Tous les produits (<?= count($produits) ?>)</h2>

    <?php if (empty($produits)): ?>

        <p>Aucun produit pour le moment.</p>

    <?php else: ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <?php $actif = (int) $produit['actif'] === 1; ?>
                        <tr class="<?= $actif ? '' : 'admin-ligne-inactive' ?>">
                            <td><?= (int) $produit['id_produit'] ?></td>
                            
<td>
    <?php if (!empty($produit['image'])): ?>
        <img
            src="images/<?= htmlspecialchars(basename($produit['image']), ENT_QUOTES, 'UTF-8') ?>"
            alt="Photo de <?= htmlspecialchars($produit['nom'], ENT_QUOTES, 'UTF-8') ?>"
            width="60"
            height="60"
            style="object-fit: contain; border-radius: 6px;"
        >
    <?php else: ?>
        <span>Pas de photo</span>
    <?php endif; ?>
</td>

                            <td><?= echapper($produit['nom']) ?></td>
                            <td><?= echapper($produit['categorie']) ?></td>
                            <td><?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €</td>
                            <td><?= (int) $produit['stock'] ?></td>
                            <td>
                                <span class="admin-badge <?= $actif ? 'admin-badge-ok' : 'admin-badge-off' ?>">
                                    <?= $actif ? 'Actif' : 'Désactivé' ?>
                                </span>
                            </td>
                            <td class="admin-cellule-actions">

                                <a
                                    href="index.php?page=admin_produits&amp;modifier=<?= (int) $produit['id_produit'] ?>"
                                    class="admin-bouton admin-bouton-petit"
                                >
                                    Modifier
                                </a>

                                <form action="index.php" method="POST" class="admin-formulaire">
                                    <input type="hidden" name="action" value="admin_etat_produit">
                                    <input type="hidden" name="id_produit" value="<?= (int) $produit['id_produit'] ?>">
                                    <input type="hidden" name="actif" value="<?= $actif ? '0' : '1' ?>">
                                    <?= champCsrf() ?>

                                    <button
                                        type="submit"
                                        class="admin-bouton admin-bouton-petit <?= $actif ? 'admin-bouton-danger' : '' ?>"
                                    >
                                        <?= $actif ? 'Désactiver' : 'Réactiver' ?>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="admin-aide">
            Un produit désactivé n'apparaît plus dans la boutique mais reste
            dans l'historique des commandes.
        </p>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/_pied.php'; ?>