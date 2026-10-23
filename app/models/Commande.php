
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
        /**
     * Récupérer les commandes d'un client, de la plus récente
     * à la plus ancienne, avec leur montant total.
     */
    public function listerParClient(int $idClient): array
    {
        $requete = $this->pdo->prepare(
            "SELECT
                c.id_commande,
                c.date_commande,
                c.statut,
                COALESCE(SUM(l.quantite * l.prix_unitaire), 0) AS total
             FROM COMMANDE c
             LEFT JOIN LIGNE_COMMANDE l
                ON l.id_commande = c.id_commande
             WHERE c.id_client = :id_client
             GROUP BY
                c.id_commande,
                c.date_commande,
                c.statut
             ORDER BY c.date_commande DESC, c.id_commande DESC"
        );

        $requete->execute([
            'id_client' => $idClient
        ]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
 * Récupérer les détails d'une commande appartenant à un client.
 * Retourne null si la commande n'existe pas ou appartient
 * à un autre client.
 */
public function detailsParClient(int $idCommande, int $idClient): ?array
{
    $requete = $this->pdo->prepare(
        "SELECT
            c.id_commande,
            c.date_commande,
            c.statut
         FROM COMMANDE c
         WHERE c.id_commande = :id_commande
           AND c.id_client = :id_client"
    );

    $requete->execute([
        'id_commande' => $idCommande,
        'id_client' => $idClient
    ]);

    $commande = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        return null;
    }

    $requeteLignes = $this->pdo->prepare(
        "SELECT
            l.id_produit,
            p.nom,
            l.quantite,
            l.prix_unitaire,
            (l.quantite * l.prix_unitaire) AS sous_total
         FROM LIGNE_COMMANDE l
         LEFT JOIN PRODUIT p
            ON p.id_produit = l.id_produit
         WHERE l.id_commande = :id_commande
         ORDER BY l.id_produit"
    );

    $requeteLignes->execute([
        'id_commande' => $idCommande
    ]);

    $commande['lignes'] = $requeteLignes->fetchAll(PDO::FETCH_ASSOC);

    $commande['total'] = array_sum(
        array_column($commande['lignes'], 'sous_total')
    );

    return $commande;
}
}
