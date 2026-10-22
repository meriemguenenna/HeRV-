
<?php

class AuthController
{
    private Client $client;

    public function __construct(PDO $pdo)
    {
        $this->client = new Client($pdo);
    }

    public function afficherInscription(): void
    {
        $erreurs = [];
        $ancien = [];

        require __DIR__ . '/../views/auth/inscription.php';
    }

    public function inscrire(): void
    {
        
        // Récupérer uniquement des chaînes de caractères
        $nom = is_string($_POST['nom'] ?? null)
            ? trim($_POST['nom'])
            : '';

        $prenom = is_string($_POST['prenom'] ?? null)
            ? trim($_POST['prenom'])
            : '';

        $email = is_string($_POST['email'] ?? null)
            ? trim($_POST['email'])
            : '';

        $motDePasse = is_string($_POST['password'] ?? null)
            ? $_POST['password']
            : '';

        $confirmation = is_string($_POST['confirmation'] ?? null)
            ? $_POST['confirmation']
            : '';


        $erreurs = [];

        if ($nom === '' || $prenom === '' || $email === ''
            || $motDePasse === '' || $confirmation === '') {
            $erreurs[] = 'Tous les champs sont obligatoires.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Adresse e-mail invalide.';
        }

        if ($motDePasse !== '' && strlen($motDePasse) < 8) {
            $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($motDePasse !== $confirmation) {
            $erreurs[] = 'Les mots de passe ne correspondent pas.';
        }

        if (empty($erreurs) && $this->client->emailExiste($email)) {
            $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
        }

        $ancien = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];

        if (!empty($erreurs)) {
            require __DIR__ . '/../views/auth/inscription.php';
            return;
        }

        try {
            $inscriptionReussie = $this->client->creerClient(
                $nom,
                $prenom,
                $email,
                $motDePasse
            );

            if (!$inscriptionReussie) {
                throw new RuntimeException('Inscription non enregistrée.');
            }

            $_SESSION['message_succes'] =
                'Compte créé avec succès. Vous pouvez vous connecter.';

            header('Location: index.php?page=connexion');
            exit;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erreurs[] = 'Impossible de créer le compte pour le moment.';
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            $erreurs[] = 'Impossible de créer le compte pour le moment.';
        }

        require __DIR__ . '/../views/auth/inscription.php';
    }
    
    // Afficher le formulaire de connexion
    public function afficherConnexion(): void
    {
        $erreur = '';
        $email = '';

        require __DIR__ . '/../views/auth/connexion.php';
    }

    // Vérifier les identifiants du client
    
    // Vérifier les identifiants du client
    public function connecter(): void
    {
        
        // Accepter uniquement des chaînes de caractères
        $email = is_string($_POST['email'] ?? null)
            ? trim($_POST['email'])
            : '';

        $motDePasse = is_string($_POST['password'] ?? null)
            ? $_POST['password']
            : '';

        $erreur = '';

        if (
            $email === ''
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || $motDePasse === ''
        ) {
            $erreur = 'Veuillez saisir des identifiants valides.';
        } else {
            try {
                $client = $this->client->trouverParEmail($email);

                if (
                    $client === null
                    || !password_verify(
                        $motDePasse,
                        $client['password']
                    )
                ) {
                    $erreur = 'Adresse e-mail ou mot de passe incorrect.';
                } else {
                    // Renouveler l'identifiant de session
                    session_regenerate_id(true);

                    // Enregistrer les informations du client
                    $_SESSION['client_id'] = (int) $client['id_client'];
                    $_SESSION['client_nom'] = $client['nom'];
                    $_SESSION['client_prenom'] = $client['prenom'];
                    $_SESSION['client_role'] = $client['role'];

                    header('Location: index.php', true, 303);
                    exit;
                }
            } catch (PDOException $e) {
                // Journaliser l'erreur sans afficher les détails SQL
                error_log($e->getMessage());
                $erreur = 'Connexion momentanément indisponible.';
            }
        }

        require __DIR__ . '/../views/auth/connexion.php';
    }

    
    // Déconnecter le client
    public function deconnecter(): void
    {
        // Supprimer les informations d'authentification
        unset(
            $_SESSION['client_id'],
            $_SESSION['client_nom'],
            $_SESSION['client_prenom'],
            $_SESSION['client_role']
        );

        // Renouveler l'identifiant de session
        session_regenerate_id(true);

        // Rediriger vers le catalogue
        header('Location: index.php', true, 303);
        exit;
    }

    // Afficher le profil du client connecté
    public function afficherProfil(): void
    {
        // Vérifier que le client est connecté
        if (empty($_SESSION['client_id'])) {
            header('Location: index.php?page=connexion', true, 303);
            exit;
        }

        // Récupérer uniquement le profil du client connecté
        $idClient = (int) $_SESSION['client_id'];
        $client = $this->client->trouverParId($idClient);

        if ($client === null) {
            http_response_code(404);
            echo 'Profil introuvable.';
            return;
        }

        // Afficher la page profil
        require __DIR__ . '/../views/compte/profil.php';
    }

    // Enregistrer les modifications du profil
    public function modifierProfil(): void
    {
        // Vérifier que le client est connecté
        if (empty($_SESSION['client_id'])) {
            header('Location: index.php?page=connexion', true, 303);
            exit;
        }

        // Vérifier le jeton CSRF
        $jeton = is_string($_POST['csrf_token'] ?? null)
            ? $_POST['csrf_token']
            : '';

        if (
            empty($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $jeton)
        ) {
            http_response_code(403);
            echo 'Requête non autorisée.';
            return;
        }

        // Récupérer les informations saisies
        $nom = is_string($_POST['nom'] ?? null)
            ? trim($_POST['nom'])
            : '';

        $prenom = is_string($_POST['prenom'] ?? null)
            ? trim($_POST['prenom'])
            : '';

        $email = is_string($_POST['email'] ?? null)
            ? trim($_POST['email'])
            : '';

        $idClient = (int) $_SESSION['client_id'];
        $erreurs = [];

        // Valider les champs
        if ($nom === '' || $prenom === '' || $email === '') {
            $erreurs[] = 'Tous les champs sont obligatoires.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Adresse e-mail invalide.';
        }

        try {
            // Vérifier que l'adresse n'appartient pas à un autre client
            $autreClient = $this->client->trouverParEmail($email);

            if (
                $autreClient !== null
                && (int) $autreClient['id_client'] !== $idClient
            ) {
                $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
            }

            if (empty($erreurs)) {
                $this->client->modifierProfil(
                    $idClient,
                    $nom,
                    $prenom,
                    $email
                );

                // Actualiser les informations de session
                $_SESSION['client_nom'] = $nom;
                $_SESSION['client_prenom'] = $prenom;

                $_SESSION['message_succes'] =
                    'Votre profil a été modifié avec succès.';

                header('Location: index.php?page=profil', true, 303);
                exit;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erreurs[] = 'Impossible de modifier le profil pour le moment.';
        }

        // Réafficher le profil avec les erreurs
        $client = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];

        require __DIR__ . '/../views/compte/profil.php';
    }




}
