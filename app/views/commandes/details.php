<link rel="stylesheet" href="css/style.css">

<?php
$statut = (string) $commande['statut'];
$classeStatut = 'commandes-statut';

if (in_array($statut, ['annulee', 'livree'], true)) {
    $classeStatut .= ' commandes-statut--' . $statut;
}
?>

<main class="commandes-page">

    <h1 class="commandes-titre">
        💜 Commande n°<?= (int) $commande['id_commande'] ?>
    </h1>

    <p class="commandes-description">
        Passée le
        <?= htmlspecialchars(
            (string) $commande['date_commande'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>

    <p style="margin-bottom: 25px;">
        Statut :
        <span class="<?= htmlspecialchars($classeStatut, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars(
                str_replace('_', ' ', $statut),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </span>
    </p>

    <h2 style="margin-bottom: 20px;">Produits commandés</h2>

    <div class="commandes-carte">
        <table class="commandes-tableau">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($commande['lignes'] as $ligne): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                (string) ($ligne['nom'] ?? 'Produit indisponible'),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td><?= (int) $ligne['quantite'] ?></td>

                        <td>
                            <?= number_format(
                                (float) $ligne['prix_unitaire'],
                                2,
                                ',',
                                ' '
                            ) ?> €
                        </td>

                        <td class="commandes-total">
                            <?= number_format(
                                (float) $ligne['sous_total'],
                                2,
                                ',',
                                ' '
                            ) ?> €
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p style="text-align: right; margin-top: 25px; font-size: 20px;">
        <strong>
            Total :
            <span style="color: #8054eb;">
                <?= number_format(
                    (float) $commande['total'],
                    2,
                    ',',
                    ' '
                ) ?> €
            </span>
        </strong>
    </p>

    <a href="index.php?page=mes_commandes" class="commandes-retour">
        ← Retour à mes commandes
    </a>

</main>