USE minishop;

INSERT INTO CATEGORIE (nom) VALUES
('Informatique'),
('Téléphonie'),
('Accessoires');

INSERT INTO PRODUIT (nom, description, prix, stock, id_categorie) VALUES
('Ordinateur portable', 'Ordinateur portable pour les études et le travail.', 799.99, 10, 1),
('Souris sans fil', 'Souris sans fil ergonomique.', 24.99, 25, 3),
('Clavier mécanique', 'Clavier mécanique pour ordinateur.', 59.99, 15, 3),
('Smartphone', 'Smartphone dernière génération.', 499.99, 8, 2),
('Casque audio', 'Casque audio avec connexion sans fil.', 79.99, 20, 3);

INSERT INTO CLIENT (nom, prenom, email, password) VALUES
('Dupont', 'Alice', 'alice.dupont@example.com', '$2y$10$examplehash1'),
('Martin', 'Lucas', 'lucas.martin@example.com', '$2y$10$examplehash2');

INSERT INTO COMMANDE (id_client, statut) VALUES
(1, 'en_attente'),
(2, 'expediee');

INSERT INTO LIGNE_COMMANDE (id_commande, id_produit, quantite, prix_unitaire) VALUES
(1, 1, 1, 799.99),
(1, 2, 2, 24.99),
(2, 4, 1, 499.99);