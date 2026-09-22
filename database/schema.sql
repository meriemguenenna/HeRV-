CREATE DATABASE IF NOT EXISTS minishop;

USE minishop;

CREATE TABLE CLIENT (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE CATEGORIE (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE PRODUIT (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    id_categorie INT NOT NULL,

    CONSTRAINT fk_produit_categorie
        FOREIGN KEY (id_categorie)
        REFERENCES CATEGORIE(id_categorie),

    CONSTRAINT chk_produit_prix
        CHECK (prix > 0),

    CONSTRAINT chk_produit_stock
        CHECK (stock >= 0)
);

CREATE TABLE COMMANDE (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(30) NOT NULL DEFAULT 'en_attente',

    CONSTRAINT fk_commande_client
        FOREIGN KEY (id_client)
        REFERENCES CLIENT(id_client)
);

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