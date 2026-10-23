DELIMITER //

-- 1. Enregistrer automatiquement chaque changement de statut
CREATE TRIGGER historiser_statut_commande
AFTER UPDATE ON COMMANDE
FOR EACH ROW
BEGIN
    IF NEW.statut <> OLD.statut THEN
        INSERT INTO ORDER_STATUS_HISTORY (order_id, old_status, new_status)
        VALUES (OLD.id_commande, OLD.statut, NEW.statut);
    END IF;
END //

-- 2. Refuser une catégorie sans nom à l'insertion
CREATE TRIGGER verifier_categorie_insertion
BEFORE INSERT ON CATEGORIE
FOR EACH ROW
BEGIN
    IF NEW.nom IS NULL OR TRIM(NEW.nom) = '' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nom de la categorie est obligatoire';
    END IF;
END //

-- 3. Refuser une catégorie sans nom à la modification
CREATE TRIGGER verifier_categorie_modification
BEFORE UPDATE ON CATEGORIE
FOR EACH ROW
BEGIN
    IF NEW.nom IS NULL OR TRIM(NEW.nom) = '' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nom de la categorie est obligatoire';
    END IF;
END //

DELIMITER ;