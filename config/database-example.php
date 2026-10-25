
<?php

// Exemple de configuration de la base de données.
//
// Copier ce fichier sous le nom database.php,
// puis adapter les paramètres à son environnement.
//
// Ne jamais envoyer database.php sur GitLab.

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=nom_de_la_base;charset=utf8mb4',
        'nom_utilisateur',
        'mot_de_passe'
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Connexion à la base de données impossible.');
}
