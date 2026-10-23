<?php

class Categorie
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lister les catégories avec leur nombre de produits
    public function listerAdmin(): array
    {
        $requete = $this->pdo->prepare("CALL lister_categories_admin()");
        $requete->execute();

        $categories = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete->closeCursor();

        return $categories;
    }

    // Récupérer une catégorie grâce à son identifiant
    public function trouverParId(int $idCategorie): ?array
    {
        $sql = "SELECT id_categorie, nom
                FROM CATEGORIE
                WHERE id_categorie = :id_categorie";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id_categorie' => $idCategorie]);

        $categorie = $requete->fetch(PDO::FETCH_ASSOC);

        return $categorie ?: null;
    }

    // Ajouter une catégorie (procédure stockée)
    public function ajouter(string $nom): void
    {
        $requete = $this->pdo->prepare("CALL ajouter_categorie(:nom)");
        $requete->execute(['nom' => $nom]);
        $requete->closeCursor();
    }

    // Modifier une catégorie (procédure stockée)
    public function modifier(int $idCategorie, string $nom): void
    {
        $requete = $this->pdo->prepare(
            "CALL modifier_categorie(:id_categorie, :nom)"
        );

        $requete->execute([
            'id_categorie' => $idCategorie,
            'nom' => $nom
        ]);

        $requete->closeCursor();
    }

    // Supprimer une catégorie vide (procédure stockée)
    public function supprimer(int $idCategorie): void
    {
        $requete = $this->pdo->prepare(
            "CALL supprimer_categorie(:id_categorie)"
        );

        $requete->execute(['id_categorie' => $idCategorie]);
        $requete->closeCursor();
    }
}