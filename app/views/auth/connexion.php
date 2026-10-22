
<?php
$erreur = $erreur ?? '';
$email = $email ?? '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="auth-container">
    <h1>Se connecter à MiniShop</h1>

    <?php if (!empty($_SESSION['message_succes'])): ?>
        <p class="message-succes">
            <?= htmlspecialchars(
                $_SESSION['message_succes'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
        <?php unset($_SESSION['message_succes']); ?>
    <?php endif; ?>

    <?php if ($erreur !== ''): ?>
        <p class="message-erreur" role="alert">
            <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form action="index.php?page=connexion" method="POST">

        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars(
                   $_SESSION['csrf_token'],
                   ENT_QUOTES,
                   'UTF-8'
               ) ?>">

        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= htmlspecialchars(
                       $email,
                       ENT_QUOTES,
                       'UTF-8'
                   ) ?>"
                   autocomplete="email"
                   required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password"
                   id="password"
                   name="password"
                   autocomplete="current-password"
                   required>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <p>
        Pas encore de compte ?
        <a href="index.php?page=inscription">Créer un compte</a>
    </p>

    <p>
        <a href="index.php">Retour à la boutique</a>
    </p>
</main>

</body>
</html>
