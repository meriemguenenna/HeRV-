<?php

$titrePage = 'Gestion des commandes';
$pageActive = 'admin_commandes';

require __DIR__ . '/_entete.php';

// Classe de couleur selon le statut
function classeStatut(string $statut): string
{
    switch ($statut) {
        case 'livree':
            return 'admin-badge-ok';
        case 'annulee':
            return 'admin-badge-off';
        case 'en_attente':
            return 'admin-badge-alerte';
        default:
            return 'admin-badge-info';
    }
}

?>

<?php if ($commandeDetail !== null): ?>

    <!-- Détail d'une commande -->
    <section class="admin-panneau">

        <h2>Commande n° <?= (int) $commandeDetail['id_commande'] ?></h2>

        <p>
            <strong>Client :</strong>
            <?= echapper($commandeDetail['prenom'] . ' ' . $commandeDetail['nom']) ?>
            (<?= echapper($commandeDetail['email']) ?>)
        </p>

        <p>
            <strong>Date :</strong>
            <?= echapper(date('d/m/Y H:i', strtotime($commandeDetail['date_commande']))) ?>
        </p>

        <p>
            <strong>Statut :</strong>
            <span class="admin-badge <?= classeStatut($commandeDetail['statut']) ?>">
                <?= echapper($statuts[$commandeDetail['statut']] ?? $commandeDetail['statut']) ?>
            </span>
        </p>

        <h3>Produits commandés</h3>

        <?php $totalCommande = 0; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $ligne): ?>
                        <?php
                        $sousTotal = (int) $ligne['quantite'] * (float) $ligne['prix_unitaire'];
                        $totalCommande += $sousTotal;
                        ?>
                        <tr>
                            <td><?= echapper($ligne['nom']) ?></td>
                            <td><?= (int) $ligne['quantite'] ?></td>
                            <td><?= number_format((float) $ligne['prix_unitaire'], 2, ',', ' ') ?> €</td>
                            <td><?= number_format($sousTotal, 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?= number_format($totalCommande, 2, ',', ' ') ?> €</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <h3>Modifier le statut</h3>

        <?php if (empty($transitionsPossibles)): ?>

            <p class="admin-aide">
                Cette commande est terminée : son statut ne peut plus changer.
            </p>

        <?php else: ?>

            <form action="index.php" method="POST" class="admin-formulaire-inline">
                <input type="hidden" name="action" value="admin_modifier_statut">
                <input type="hidden" name="id_commande" value="<?= (int) $commandeDetail['id_commande'] ?>">
                <?= champCsrf() ?>

                <select name="statut" required aria-label="Nouveau statut">
                    <?php foreach ($transitionsPossibles as $statut): ?>
                        <option value="<?= echapper($statut) ?>">
                            <?= echapper($statuts[$statut] ?? $statut) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="admin-bouton">
                    Mettre à jour
                </button>
            </form>

            <p class="admin-aide">
                Seuls les changements autorisés sont proposés. Une annulation
                remet automatiquement les produits en stock.
            </p>

        <?php endif; ?>

        <h3>Historique des statuts</h3>

        <?php if (empty($historique)): ?>

            <p class="admin-aide">Aucun changement de statut enregistré.</p>

        <?php else: ?>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ancien statut</th>
                            <th>Nouveau statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $changement): ?>
                            <tr>
                                <td><?= echapper(date('d/m/Y H:i', strtotime($changement['changed_at']))) ?></td>
                                <td><?= echapper($statuts[$changement['old_status']] ?? $changement['old_status']) ?></td>
                                <td><?= echapper($statuts[$changement['new_status']] ?? $changement['new_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

        <p>
            <a href="index.php?page=admin_commandes" class="admin-bouton admin-bouton-secondaire">
                ← Retour à la liste
            </a>
        </p>

    </section>

<?php endif; ?>

<!-- Liste des commandes -->
<section class="admin-panneau">

    <h2>Toutes les commandes (<?= count($commandes) ?>)</h2>

    <?php if (empty($commandes)): ?>

        <p>Aucune commande pour le moment.</p>

    <?php else: ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Détail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= (int) $commande['id_commande'] ?></td>
                            <td><?= echapper(date('d/m/Y H:i', strtotime($commande['date_commande']))) ?></td>
                            <td>
                                <?= echapper($commande['prenom'] . ' ' . $commande['nom']) ?><br>
                                <small><?= echapper($commande['email']) ?></small>
                            </td>
                            <td><?= number_format((float) $commande['total'], 2, ',', ' ') ?> €</td>
                            <td>
                                <span class="admin-badge <?= classeStatut($commande['statut']) ?>">
                                    <?= echapper($statuts[$commande['statut']] ?? $commande['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <a
                                    href="index.php?page=admin_commandes&amp;id=<?= (int) $commande['id_commande'] ?>"
                                    class="admin-bouton admin-bouton-petit"
                                >
                                    Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/_pied.php'; ?>