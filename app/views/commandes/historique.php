<?php
$commandes = $commandes ?? [];
?>

<main class="commandes-page">

    <h1 class="commandes-titre">💜 Mes commandes</h1>

    <p class="commandes-description">
        Retrouvez ici l'historique de vos achats sur MiniShop.
    </p>

    <link rel="stylesheet" href="css/style.css">

    <?php if (empty($commandes)): ?>

        <div class="commandes-vide">
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="index.php?page=accueil">Découvrir les produits</a>
        </div>

    <?php else: ?>

        <div class="commandes-carte">
            <table class="commandes-tableau">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Détails</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <?php
                        $statut = (string) $commande['statut'];

                        $classeStatut = 'commandes-statut';

                        if (in_array($statut, ['annulee', 'livree'], true)) {
                            $classeStatut .= ' commandes-statut--' . $statut;
                        }
                        ?>

                        <tr>
                            <td>
                                <span class="commandes-numero">
                                    N°<?= (int) $commande['id_commande'] ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    (string) $commande['date_commande'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <span class="<?= htmlspecialchars(
                                    $classeStatut,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>">
                                    <?= htmlspecialchars(
                                        str_replace('_', ' ', $statut),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <span class="commandes-total">
                                    <?= number_format(
                                        (float) $commande['total'],
                                        2,
                                        ',',
                                        ' '
                                    ) ?> €
                                </span>
                            </td>

                            <td>
                                <a
                                    href="index.php?page=detail_commande&id=<?= (int) $commande['id_commande'] ?>"
                                    class="commandes-numero"
                                >
                                    Voir les détails →
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <a href="index.php?page=accueil" class="commandes-retour">
        ← Retour au catalogue
    </a>

</main>