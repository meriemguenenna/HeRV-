
<?php

require_once __DIR__ . '/../models/Commande.php';

class CommandeController
{
    private Commande $commandeModel;

    public function __construct(PDO $pdo)
    {
        $this->commandeModel = new Commande($pdo);
    }

    /**
     * Confirmer une commande à partir du panier.
     */
    public function confirmer(): void
    {
        // 1. Vérifier que le client est connecté
        $idClient = (int) ($_SESSION['client_id'] ?? 0);
        $role = $_SESSION['client_role'] ?? '';

        if ($idClient <= 0 || $role !== 'client') {
            $_SESSION['erreur'] =
                'Connectez-vous avec un compte client pour commander.';

            header('Location: index.php?page=connexion', true, 303);
            exit;
        }

        // 2. Vérifier que le panier n'est pas vide
        $panier = $_SESSION['panier'] ?? [];

        if (!is_array($panier) || empty($panier)) {
            $_SESSION['erreur'] = 'Votre panier est vide.';

            header('Location: index.php?page=panier', true, 303);
            exit;
        }

        // 3. Enregistrer la commande
        try {
            $idCommande = $this->commandeModel->creer(
                $idClient,
                $panier
            );

            // 4. Vider le panier uniquement après réussite
            unset($_SESSION['panier']);

            $_SESSION['succes'] =
                'Votre commande n°' . $idCommande
                . ' a été enregistrée avec succès !';

            // 5. Mémoriser la commande confirmée
$_SESSION['commande_confirmee'] = $idCommande;

// Rediriger vers la page de confirmation
header('Location: index.php?page=confirmation_commande', true, 303);
exit;

        } catch (Throwable $erreur) {
            // Journaliser l'erreur sans exposer les détails SQL
            error_log($erreur->getMessage());

            $_SESSION['erreur'] =
                'Impossible de valider votre commande. '
                . 'Vérifiez la disponibilité des produits.';

            header('Location: index.php?page=panier', true, 303);
            exit;
        }
    }
        /**
     * Afficher l'historique des commandes du client connecté.
     */
    public function historique(): void
    {
        // Vérifier que le client est connecté
        $idClient = (int) ($_SESSION['client_id'] ?? 0);
        $role = $_SESSION['client_role'] ?? '';

        if ($idClient <= 0 || $role !== 'client') {
            $_SESSION['erreur'] =
                'Connectez-vous pour consulter vos commandes.';

            header('Location: index.php?page=connexion', true, 303);
            exit;
        }

        // Récupérer uniquement les commandes de ce client
        $commandes = $this->commandeModel->listerParClient($idClient);

        // Afficher la page d'historique
        require __DIR__ . '/../views/commandes/historique.php';
    }
    /**
 * Afficher les détails d'une commande du client connecté.
 */
public function details(): void
{
    // Vérifier que le client est connecté
    $idClient = (int) ($_SESSION['client_id'] ?? 0);
    $role = $_SESSION['client_role'] ?? '';

    if ($idClient <= 0 || $role !== 'client') {
        $_SESSION['erreur'] =
            'Connectez-vous pour consulter vos commandes.';

        header('Location: index.php?page=connexion', true, 303);
        exit;
    }

    // Vérifier le numéro de commande reçu dans l'URL
    $idCommande = filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT
    );

    if ($idCommande === false || $idCommande === null || $idCommande <= 0) {
        http_response_code(404);
        echo 'Commande introuvable.';
        return;
    }

    // La méthode vérifie aussi que la commande appartient au client
    $commande = $this->commandeModel->detailsParClient(
        $idCommande,
        $idClient
    );

    if ($commande === null) {
        http_response_code(404);
        echo 'Commande introuvable.';
        return;
    }

    // Afficher les détails
    require __DIR__ . '/../views/commandes/details.php';
}
}
