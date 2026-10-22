
<?php

class Client
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Vérifier si une adresse e-mail est déjà utilisée
    public function emailExiste(string $email): bool
    {
        $sql = "SELECT id_client
                FROM CLIENT
                WHERE email = :email";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['email' => $email]);

        return $requete->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Inscrire un nouveau client
    public function creerClient(
        string $nom,
        string $prenom,
        string $email,
        string $motDePasse
    ): bool {
        $motDePasseHash = password_hash(
            $motDePasse,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO CLIENT
                    (nom, prenom, email, password, role)
                VALUES
                    (:nom, :prenom, :email, :password, 'client')";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $motDePasseHash
        ]);
    }

    // Retrouver un client grâce à son e-mail
    public function trouverParEmail(string $email): ?array
    {
        $sql = "SELECT id_client, nom, prenom,
                       email, password, role
                FROM CLIENT
                WHERE email = :email";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['email' => $email]);

        $client = $requete->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }

    // Récupérer les informations du client connecté
    public function trouverParId(int $idClient): ?array
    {
        $sql = "SELECT id_client, nom, prenom, email
                FROM CLIENT
                WHERE id_client = :id_client";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            'id_client' => $idClient
        ]);

        $client = $requete->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }

    // Modifier les informations du client connecté
    public function modifierProfil(
        int $idClient,
        string $nom,
        string $prenom,
        string $email
    ): bool {
        $sql = "UPDATE CLIENT
                SET nom = :nom,
                    prenom = :prenom,
                    email = :email
                WHERE id_client = :id_client";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'id_client' => $idClient
        ]);
    }


}
