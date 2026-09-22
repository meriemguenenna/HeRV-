USE minishop;

DELIMITER //

-- 1. Vérifier qu'un produit ne peut pas avoir un stock négatif
CREATE TRIGGER verifier_stock_avant_insertion
BEFORE INSERT ON PRODUIT
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas être négatif';
    END IF;
END //

-- 2. Vérifier le stock avant sa modification
CREATE TRIGGER verifier_stock_avant_modification
BEFORE UPDATE ON PRODUIT
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas être négatif';
    END IF;
END //

-- 3. Vérifier la quantité d'une ligne de commande
CREATE TRIGGER verifier_quantite_ligne
BEFORE INSERT ON LIGNE_COMMANDE
FOR EACH ROW
BEGIN
    IF NEW.quantite <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La quantité doit être supérieure à zéro';
    END IF;
END //

-- 4. Vérifier le prix d'une ligne de commande
CREATE TRIGGER verifier_prix_ligne
BEFORE INSERT ON LIGNE_COMMANDE
FOR EACH ROW
BEGIN
    IF NEW.prix_unitaire <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le prix doit être supérieur à zéro';
    END IF;
END //

-- 5. Vérifier qu'un produit possède un prix positif
CREATE TRIGGER verifier_prix_produit
BEFORE INSERT ON PRODUIT
FOR EACH ROW
BEGIN
    IF NEW.prix <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le prix du produit doit être supérieur à zéro';
    END IF;
END //

DELIMITER ;