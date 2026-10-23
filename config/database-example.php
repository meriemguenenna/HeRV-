<?php

// Modèle de configuration : copier ce fichier en database.php
// puis remplacer les valeurs par celles de ta base MySQL.
// Ne jamais pousser database.php sur GitLab.

$host = 'xxx';
$dbname = 'xxx';
$utilisateur = 'xxx';
$motDePasse = 'xxx';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $utilisateur,
        $motDePasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Connexion à la base de données impossible.');
}