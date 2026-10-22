<?php

class CommandeAdmin
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lister toutes les commandes (procédure stockée)
    public function lister(): array
    {
        $requete = $this->pdo->prepare("CALL lister_commandes_admin()");
        $requete->execute();

        $commandes = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete->closeCursor();

        return $commandes;
    }

    // Récupérer une commande avec les informations du client
    public function trouverParId(int $idCommande): ?array
    {
        $sql = "SELECT cmd.id_commande, cmd.date_commande, cmd.statut,
                       cl.nom, cl.prenom, cl.email
                FROM COMMANDE cmd
                JOIN CLIENT cl ON cl.id_client = cmd.id_client
                WHERE cmd.id_commande = :id_commande";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id_commande' => $idCommande]);

        $commande = $requete->fetch(PDO::FETCH_ASSOC);

        return $commande ?: null;
    }

    // Lignes d'une commande (procédure existante lignes_commande)
    public function getLignes(int $idCommande): array
    {
        $requete = $this->pdo->prepare("CALL lignes_commande(:id_commande)");
        $requete->execute(['id_commande' => $idCommande]);

        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete->closeCursor();

        return $lignes;
    }

    // Historique des changements de statut (procédure stockée)
    public function getHistorique(int $idCommande): array
    {
        $requete = $this->pdo->prepare(
            "CALL historique_statut_commande(:id_commande)"
        );

        $requete->execute(['id_commande' => $idCommande]);

        $historique = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete->closeCursor();

        return $historique;
    }

    // Modifier le statut (procédure + trigger de contrôle des transitions)
    public function modifierStatut(int $idCommande, string $statut): void
    {
        $requete = $this->pdo->prepare(
            "CALL modifier_statut_commande(:id_commande, :statut)"
        );

        $requete->execute([
            'id_commande' => $idCommande,
            'statut' => $statut
        ]);

        $requete->closeCursor();
    }
}