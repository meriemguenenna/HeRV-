<?php

class Produit
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    p.id_produit,
                    p.nom,
                    p.description,
                    p.prix,
                    p.stock,
                    c.nom AS categorie
                FROM PRODUIT p
                JOIN CATEGORIE c 
                    ON p.id_categorie = c.id_categorie
                ORDER BY p.nom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT 
                    p.id_produit,
                    p.nom,
                    p.description,
                    p.prix,
                    p.stock,
                    c.nom AS categorie
                FROM PRODUIT p
                JOIN CATEGORIE c 
                    ON p.id_categorie = c.id_categorie
                WHERE p.id_produit = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $produit = $stmt->fetch();

        return $produit ?: null;
    }

    public function search(string $nom): array
    {
        $sql = "SELECT 
                    p.id_produit,
                    p.nom,
                    p.description,
                    p.prix,
                    p.stock,
                    c.nom AS categorie
                FROM PRODUIT p
                JOIN CATEGORIE c 
                    ON p.id_categorie = c.id_categorie
                WHERE p.nom LIKE :nom
                ORDER BY p.nom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nom' => '%' . $nom . '%'
        ]);

        return $stmt->fetchAll();
    }
}