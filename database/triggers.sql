DELIMITER //

-- 1. Vérifier un produit avant son insertion
CREATE TRIGGER verifier_produit_insertion
BEFORE INSERT ON PRODUIT
FOR EACH ROW
BEGIN
    IF NEW.prix IS NULL OR NEW.prix <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le prix doit etre positif';
    END IF;

    IF NEW.stock IS NULL OR NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas etre negatif';
    END IF;
END //

-- 2. Vérifier un produit avant sa modification
CREATE TRIGGER verifier_produit_modification
BEFORE UPDATE ON PRODUIT
FOR EACH ROW
BEGIN
    IF NEW.prix IS NULL OR NEW.prix <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le prix doit etre positif';
    END IF;

    IF NEW.stock IS NULL OR NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas etre negatif';
    END IF;
END //

-- 3. Vérifier le stock et le déduire
-- lors de l'ajout d'une ligne de commande
CREATE TRIGGER verifier_stock_commande
BEFORE INSERT ON LIGNE_COMMANDE
FOR EACH ROW
BEGIN
    IF NEW.quantite IS NULL OR NEW.quantite <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La quantite doit etre positive';
    END IF;

    IF NEW.prix_unitaire IS NULL OR NEW.prix_unitaire <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le prix unitaire doit etre positif';
    END IF;

    UPDATE PRODUIT
    SET stock = stock - NEW.quantite
    WHERE id_produit = NEW.id_produit
      AND actif = TRUE
      AND stock >= NEW.quantite;

    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produit indisponible ou stock insuffisant';
    END IF;
END //

-- 4. Empêcher la modification d'une ligne
-- de commande déjà enregistrée
CREATE TRIGGER verifier_modification_ligne
BEFORE UPDATE ON LIGNE_COMMANDE
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Une ligne de commande ne peut pas etre modifiee';
END //

-- 5. Vérifier les changements de statut
-- et restituer le stock en cas d'annulation
CREATE TRIGGER verifier_statut_commande
BEFORE UPDATE ON COMMANDE
FOR EACH ROW
BEGIN
    IF NEW.statut <> OLD.statut THEN

        IF OLD.statut IN ('livree', 'annulee') THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cette commande est deja terminee';

        ELSEIF NOT (
            (OLD.statut = 'en_attente'
             AND NEW.statut IN ('en_preparation', 'annulee'))

            OR (OLD.statut = 'en_preparation'
                AND NEW.statut = 'expediee')

            OR (OLD.statut = 'expediee'
                AND NEW.statut = 'livree')
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Transition de statut non autorisee';
        END IF;

        -- Si la commande est annulée,
        -- remettre les produits en stock
        IF NEW.statut = 'annulee' THEN
            UPDATE PRODUIT p
            JOIN LIGNE_COMMANDE lc
              ON p.id_produit = lc.id_produit
            SET p.stock = p.stock + lc.quantite
            WHERE lc.id_commande = OLD.id_commande;
        END IF;

    END IF;
END //

DELIMITER ;
