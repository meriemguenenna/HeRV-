
<?php
$nom = htmlspecialchars($client['nom'], ENT_QUOTES, 'UTF-8');
$prenom = htmlspecialchars($client['prenom'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($client['email'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profil-container {
            max-width: 650px;
            margin: 60px auto;
            padding: 35px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .profil-container h1 {
            color: #6d28d9;
            margin-bottom: 10px;
        }

        .profil-sous-titre {
            color: #666;
            margin-bottom: 30px;
        }

        .profil-champ {
            margin-bottom: 22px;
        }

        .profil-champ strong {
            display: block;
            color: #555;
            margin-bottom: 8px;
        }

        .profil-valeur {
            padding: 14px;
            background: #f5f3ff;
            border-radius: 8px;
            overflow-wrap: anywhere;
        }

        .profil-retour {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            background: #6d28d9;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .profil-retour:hover {
            background: #5b21b6;
        }
        
/* Formulaire de modification du profil */

.profil-container h2 {
    margin-top: 45px;
    margin-bottom: 24px;
    color: #21183c;
    font-size: 26px;
}

.form-profil {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 25px;
}

.form-profil label {
    display: block;
    margin-top: 8px;
    color: #444;
    font-size: 15px;
    font-weight: 600;
}

.form-profil input[type="text"],
.form-profil input[type="email"] {
    display: block;
    width: 100%;
    box-sizing: border-box;
    padding: 14px 16px;
    border: 1px solid #ddd6fe;
    border-radius: 9px;
    background: #f5f3ff;
    color: #21183c;
    font: inherit;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-profil input:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}

.form-profil button[type="submit"] {
    align-self: flex-start;
    margin-top: 18px;
    padding: 14px 22px;
    border: none;
    border-radius: 9px;
    background: #6d28d9;
    color: white;
    font: inherit;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.form-profil button[type="submit"]:hover {
    background: #5b21b6;
}

@media (max-width: 600px) {
    .profil-container {
        margin: 20px 12px;
        padding: 24px 18px;
    }

    .form-profil button[type="submit"] {
        width: 100%;
    }
}

    </style>
</head>
<body>

    <main class="profil-container">
        <h1>Mon profil 💜</h1>
        <p class="profil-sous-titre">
            Retrouvez les informations de votre compte MiniShop.
        </p>

        <div class="profil-champ">
            <strong>Prénom</strong>
            <div class="profil-valeur"><?= $prenom ?></div>
        </div>

        <div class="profil-champ">
            <strong>Nom</strong>
            <div class="profil-valeur"><?= $nom ?></div>
        </div>

        <div class="profil-champ">
            <strong>Adresse e-mail</strong>
            <div class="profil-valeur"><?= $email ?></div>
        </div>


<h2>Modifier mes informations</h2>

<?php if (!empty($erreurs)): ?>
    <?php foreach ($erreurs as $erreur): ?>
        <p style="color: #b91c1c;">
            <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($_SESSION['message_succes'])): ?>
    <p style="color: #15803d;">
        <?= htmlspecialchars(
            $_SESSION['message_succes'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
    <?php unset($_SESSION['message_succes']); ?>
<?php endif; ?>

<form action="index.php" method="POST" class="form-profil">

    <input
        type="hidden"
        name="action"
        value="modifier_profil"
    >

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $_SESSION['csrf_token'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <label for="profil-prenom">Prénom</label>
    <input
        type="text"
        id="profil-prenom"
        name="prenom"
        value="<?= $prenom ?>"
        required
        maxlength="100"
    >

    <label for="profil-nom">Nom</label>
    <input
        type="text"
        id="profil-nom"
        name="nom"
        value="<?= $nom ?>"
        required
        maxlength="100"
    >

    <label for="profil-email">Adresse e-mail</label>
    <input
        type="email"
        id="profil-email"
        name="email"
        value="<?= $email ?>"
        required
        maxlength="255"
    >

    <button type="submit">
        Enregistrer les modifications
    </button>
</form>
<?php if (($_SESSION['client_role'] ?? '') === 'client'): ?>
    <p style="margin-top: 20px;">
        <a href="index.php?page=mes_commandes">
             Mes commandes
        </a>
    </p>
<?php endif; ?>


        <a href="index.php" class="profil-retour">
            ← Retour au catalogue
        </a>
    </main>

</body>
</html>
