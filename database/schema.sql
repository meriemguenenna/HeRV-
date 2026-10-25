-- Base de données déjà créée sur le serveur de l'IUT
-- La base est sélectionnée dans phpMyAdmin.

-- 1. Clients et administrateurs
CREATE TABLE CLIENT (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. Catégories
CREATE TABLE CATEGORIE (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- 3. Produits
CREATE TABLE PRODUIT (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    actif BOOLEAN NOT NULL DEFAULT TRUE,
    id_categorie INT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,

    CONSTRAINT fk_produit_categorie
        FOREIGN KEY (id_categorie)
        REFERENCES CATEGORIE(id_categorie),

    CONSTRAINT chk_produit_prix
        CHECK (prix > 0),

    CONSTRAINT chk_produit_stock
        CHECK (stock >= 0)
);

-- 4. Commandes
CREATE TABLE COMMANDE (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut ENUM(
        'en_attente',
        'en_preparation',
        'expediee',
        'livree',
        'annulee'
    ) NOT NULL DEFAULT 'en_attente',

    CONSTRAINT fk_commande_client
        FOREIGN KEY (id_client)
        REFERENCES CLIENT(id_client)
);

-- 5. Lignes de commande
CREATE TABLE LIGNE_COMMANDE (
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (id_commande, id_produit),

    CONSTRAINT fk_ligne_commande
        FOREIGN KEY (id_commande)
        REFERENCES COMMANDE(id_commande),

    CONSTRAINT fk_ligne_produit
        FOREIGN KEY (id_produit)
        REFERENCES PRODUIT(id_produit),

    CONSTRAINT chk_ligne_quantite
        CHECK (quantite > 0),

    CONSTRAINT chk_ligne_prix
        CHECK (prix_unitaire > 0)
);
