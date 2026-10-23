
-- Évolution MiniShop : gestion des photos des produits.
-- À exécuter une seule fois sur une base existante.

-- 1. Ajouter une colonne pour le nom de l'image.
ALTER TABLE PRODUIT
ADD COLUMN image VARCHAR(255) NULL;

-- 2. Associer les images existantes aux 15 produits.
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

-- 3. Procédure pour enregistrer ou remplacer une image.
DELIMITER //

CREATE PROCEDURE modifier_image_produit(
    IN p_id_produit INT,
    IN p_image VARCHAR(255)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM PRODUIT
        WHERE id_produit = p_id_produit
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produit introuvable';

    ELSE
        UPDATE PRODUIT
        SET image = p_image
        WHERE id_produit = p_id_produit;
    END IF;
END //

DELIMITER ;


-- Mettre à jour la liste des produits pour inclure les photos.

DROP PROCEDURE IF EXISTS lister_produits_admin;

DELIMITER //

CREATE PROCEDURE lister_produits_admin()
BEGIN
    SELECT
        p.id_produit,
        p.nom,
        p.description,
        p.prix,
        p.stock,
        p.actif,
        p.id_categorie,
        p.image,
        c.nom AS categorie
    FROM PRODUIT p
    INNER JOIN CATEGORIE c
        ON c.id_categorie = p.id_categorie
    ORDER BY p.id_produit;
END //

DELIMITER ;

-- Mettre à jour la procédure d'ajout pour retourner
-- l'identifiant du produit nouvellement créé.

DROP PROCEDURE IF EXISTS ajouter_produit;

DELIMITER //

CREATE PROCEDURE ajouter_produit(
    IN p_nom VARCHAR(150),
    IN p_description TEXT,
    IN p_prix DECIMAL(10,2),
    IN p_stock INT,
    IN p_id_categorie INT
)
BEGIN
    IF p_nom IS NULL OR TRIM(p_nom) = '' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nom du produit est obligatoire';

    ELSEIF NOT EXISTS (
        SELECT 1 FROM CATEGORIE WHERE id_categorie = p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Categorie introuvable';

    ELSE
        INSERT INTO PRODUIT
            (nom, description, prix, stock, actif, id_categorie)
        VALUES
            (TRIM(p_nom), p_description, p_prix, p_stock, TRUE, p_id_categorie);

        SELECT LAST_INSERT_ID() AS id_produit;
    END IF;
END //

DELIMITER ;

