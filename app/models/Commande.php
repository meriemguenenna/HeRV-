
<?php

class Commande
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Enregistrer une commande et ses lignes.
     *
     * $panier = [
     *     id_produit => quantite
     * ];
     */
    public function creer(int $idClient, array $panier): int
    {
        if ($idClient <= 0 || empty($panier)) {
            throw new InvalidArgumentException(
                "Impossible de créer une commande avec un panier vide."
            );
        }

        $this->pdo->beginTransaction();

        try {
            // Créer la commande
            $requete = $this->pdo->prepare(
                "INSERT INTO COMMANDE (id_client)
                 VALUES (:id_client)"
            );

            $requete->execute([
                'id_client' => $idClient
            ]);

            $idCommande = (int) $this->pdo->lastInsertId();

            // Préparer les requêtes pour les produits
            $requeteProduit = $this->pdo->prepare(
                "SELECT prix
                 FROM PRODUIT
                 WHERE id_produit = :id_produit
                   AND actif = TRUE"
            );

            $requeteLigne = $this->pdo->prepare(
                "INSERT INTO LIGNE_COMMANDE
                    (id_commande, id_produit, quantite, prix_unitaire)
                 VALUES
                    (:id_commande, :id_produit, :quantite, :prix_unitaire)"
            );

            foreach ($panier as $idProduit => $quantite) {
                $idProduit = filter_var(
                    $idProduit,
                    FILTER_VALIDATE_INT
                );

                $quantite = filter_var(
                    $quantite,
                    FILTER_VALIDATE_INT
                );

                if (
                    $idProduit === false ||
                    $idProduit <= 0 ||
                    $quantite === false ||
                    $quantite <= 0
                ) {
                    throw new RuntimeException(
                        "Le panier contient une quantité ou un produit invalide."
                    );
                }

                // Lire le prix depuis MySQL, jamais depuis le navigateur
                $requeteProduit->execute([
                    'id_produit' => $idProduit
                ]);

                $produit = $requeteProduit->fetch(PDO::FETCH_ASSOC);
                $requeteProduit->closeCursor();

                if (!$produit) {
                    throw new RuntimeException(
                        "Un produit du panier n'est plus disponible."
                    );
                }

                // Le trigger SQL vérifie et déduit le stock
                $requeteLigne->execute([
                    'id_commande' => $idCommande,
                    'id_produit' => $idProduit,
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit['prix']
                ]);
            }

            // Tout s'est bien passé : valider la commande
            $this->pdo->commit();

            return $idCommande;

        } catch (Throwable $erreur) {
            // Si une étape échoue, annuler toutes les insertions
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $erreur;
        }
    }
}
