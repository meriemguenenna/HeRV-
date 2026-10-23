<?php
// Le numéro de commande est fourni par le routeur.
$idCommande = (int) ($idCommande ?? 0);
?>

<link rel="stylesheet" href="css/style.css">

<main class="confirmation-page">
    <div class="confirmation-carte">

        <div class="confirmation-icone">🎉</div>

        <h1 class="confirmation-titre">
            Commande confirmée !
        </h1>

        <p class="confirmation-description">
            Merci pour votre achat !<br>
            Votre commande a bien été enregistrée.
        </p>

        <div class="confirmation-numero">
            Commande n°<?= $idCommande ?>
        </div>

        <p class="confirmation-paiement">
            Votre paiement a été simulé avec succès.<br>
            Aucun paiement réel n'a été effectué.
        </p>

        <a href="index.php?page=accueil" class="confirmation-bouton">
            Retourner au catalogue →
        </a>

    </div>
</main>