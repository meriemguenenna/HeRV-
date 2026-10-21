<?php

class Produit
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTousLesProduits(): array
    {
        $sql = "SELECT id_produit, nom, description, prix, stock
                FROM PRODUIT
                WHERE actif = 1
                ORDER BY id_produit";

        $requete = $this->pdo->query($sql);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
        public function getProduitParId(int $idProduit): ?array
    {
        $sql = "SELECT id_produit, nom, prix, stock
                FROM PRODUIT
                WHERE id_produit = :id
                  AND actif = 1";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id' => $idProduit]);

        $produit = $requete->fetch(PDO::FETCH_ASSOC);

        return $produit ?: null;
    }
}