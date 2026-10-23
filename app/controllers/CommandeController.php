
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

            // 5. Rediriger vers le panier pour le moment
            header('Location: index.php?page=panier', true, 303);
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
}
