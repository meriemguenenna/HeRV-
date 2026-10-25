-- 1. Catégories
INSERT INTO CATEGORIE (nom) VALUES
('Informatique'),
('Téléphonie'),
('Accessoires');

-- 2. Produits
-- Stocks initiaux avant les commandes de démonstration
INSERT INTO PRODUIT
(nom, description, prix, stock, actif, id_categorie)
VALUES
('Ordinateur portable',
 'Ordinateur portable pour les études et le travail.',
 799.99, 10, TRUE, 1),

('Souris sans fil',
 'Souris sans fil ergonomique.',
 24.99, 25, TRUE, 3),

('Clavier mécanique',
 'Clavier mécanique pour ordinateur.',
 59.99, 15, TRUE, 3),

('Smartphone',
 'Smartphone dernière génération.',
 499.99, 8, TRUE, 2),

('Casque audio',
 'Casque audio avec connexion sans fil.',
 79.99, 20, TRUE, 3),

('Écran 24 pouces',
 'Écran pour ordinateur de 24 pouces.',
 149.99, 12, TRUE, 1),

('Webcam HD',
 'Webcam haute définition pour les appels vidéo.',
 39.99, 18, TRUE, 1),

('Disque SSD externe',
 'Disque SSD externe pour stocker les fichiers.',
 69.99, 14, TRUE, 1),

('Ordinateur de bureau',
 'Ordinateur fixe pour le travail et les études.',
 649.99, 6, TRUE, 1),

('Coque de téléphone',
 'Coque de protection pour smartphone.',
 14.99, 30, TRUE, 2),

('Chargeur USB-C',
 'Chargeur USB-C pour appareils compatibles.',
 19.99, 25, TRUE, 2),

('Batterie externe',
 'Batterie portable pour recharger les appareils.',
 34.99, 16, TRUE, 2),

('Support téléphone',
 'Support pratique pour poser un smartphone.',
 12.99, 22, TRUE, 2),

('Tapis de souris',
 'Tapis de souris pour bureau.',
 9.99, 35, TRUE, 3),

('Hub USB',
 'Adaptateur USB pour connecter plusieurs appareils.',
 24.99, 19, TRUE, 3);


-- 2 bis. Images des produits de démonstration
-- La colonne image existe déjà dans schema.sql.

UPDATE PRODUIT SET image = 'ordinateur.jpg' WHERE id_produit = 1;
UPDATE PRODUIT SET image = 'souris.jpg' WHERE id_produit = 2;
UPDATE PRODUIT SET image = 'clavier.jpg' WHERE id_produit = 3;
UPDATE PRODUIT SET image = 'iphone.jpg' WHERE id_produit = 4;
UPDATE PRODUIT SET image = 'casque.jpg' WHERE id_produit = 5;
UPDATE PRODUIT SET image = 'ecran.jpg' WHERE id_produit = 6;
UPDATE PRODUIT SET image = 'webcam.jpg' WHERE id_produit = 7;
UPDATE PRODUIT SET image = 'ssd.jpg' WHERE id_produit = 8;
UPDATE PRODUIT SET image = 'ordinateur-bureau.jpg' WHERE id_produit = 9;
UPDATE PRODUIT SET image = 'coque.jpg' WHERE id_produit = 10;
UPDATE PRODUIT SET image = 'chargeur.jpg' WHERE id_produit = 11;
UPDATE PRODUIT SET image = 'batterie.jpg' WHERE id_produit = 12;
UPDATE PRODUIT SET image = 'support.jpg' WHERE id_produit = 13;
UPDATE PRODUIT SET image = 'tapis.jpg' WHERE id_produit = 14;
UPDATE PRODUIT SET image = 'hub-usb.jpg' WHERE id_produit = 15;


-- 3. Clients et administrateur
-- Mots de passe hachés avec password_hash() en PHP
-- Comptes réservés aux tests

INSERT INTO CLIENT
(nom, prenom, email, password, role)
VALUES
(
    'Dupont',
    'Alice',
    'alice.dupont@example.com',
    '$2y$12$j602va/5ktuCqabKaRcxkut0dPHi4ruK.7s6Pjerlj2YCXTevPtAy',
    'client'
),
(
    'Martin',
    'Lucas',
    'lucas.martin@example.com',
    '$2y$12$HMhFLfH1L3Gerepf3fh88eVJaeF5f3DTYglC2A1pBeXKD1pgYky.O',
    'client'
),
(
    'Admin',
    'MiniShop',
    'admin@minishop.example',
    '$2y$12$mXjo6KTqtZxvoRAkoYhqbeiFUq823miR6qJ33FYpP5sYSb55FVFTy',
    'admin'
);

-- 4. Commandes de démonstration
INSERT INTO COMMANDE (id_client, statut) VALUES
(1, 'en_attente'),
(2, 'expediee');

-- 5. Lignes de commande
-- Le trigger verifier_stock_commande
-- déduit automatiquement les quantités du stock.

INSERT INTO LIGNE_COMMANDE
(id_commande, id_produit, quantite, prix_unitaire)
VALUES
(1, 1, 1, 799.99),
(1, 2, 2, 24.99),
(2, 4, 1, 499.99);
