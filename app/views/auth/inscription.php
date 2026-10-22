
<?php
$erreurs = $erreurs ?? [];
$ancien = $ancien ?? [];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MiniShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="auth-container">
    <h1>Créer un compte MiniShop</h1>

    <?php if (!empty($erreurs)): ?>
        <div class="message-erreur" role="alert">
            <ul>
                <?php foreach ($erreurs as $erreur): ?>
                    <li>
                        <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?page=inscription"
          method="POST">

        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars(
                   $_SESSION['csrf_token'],
                   ENT_QUOTES,
                   'UTF-8'
               ) ?>">

        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text"
                   id="nom"
                   name="nom"
                   value="<?= htmlspecialchars(
                       $ancien['nom'] ?? '',
                       ENT_QUOTES,
                       'UTF-8'
                   ) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text"
                   id="prenom"
                   name="prenom"
                   value="<?= htmlspecialchars(
                       $ancien['prenom'] ?? '',
                       ENT_QUOTES,
                       'UTF-8'
                   ) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= htmlspecialchars(
                       $ancien['email'] ?? '',
                       ENT_QUOTES,
                       'UTF-8'
                   ) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password"
                   id="password"
                   name="password"
                   minlength="8"
                   autocomplete="new-password"
                   required>
        </div>

        <div class="form-group">
            <label for="confirmation">Confirmer le mot de passe</label>
            <input type="password"
                   id="confirmation"
                   name="confirmation"
                   minlength="8"
                   autocomplete="new-password"
                   required>
        </div>

        <button type="submit">
            Créer mon compte
        </button>
    </form>

    <p>
        Déjà un compte ?
        <a href="index.php?page=connexion">Se connecter</a>
    </p>

    <p>
        <a href="index.php">Retour à la boutique</a>
    </p>
</main>

</body>
</html>
