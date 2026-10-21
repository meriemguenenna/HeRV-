
/*USE minishop;
*/

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
 79.99, 20, TRUE, 3);

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
