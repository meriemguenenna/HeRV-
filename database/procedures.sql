
/*USE minishop;
*/

DELIMITER //

-- 1. Rechercher les produits par nom
CREATE PROCEDURE rechercher_produit(IN p_nom VARCHAR(150))
BEGIN
    SELECT *
    FROM PRODUIT
    WHERE nom LIKE CONCAT('%', p_nom, '%')
      AND actif = TRUE;
END //

-- 2. Récupérer les produits d'une catégorie
CREATE PROCEDURE produits_par_categorie(IN p_id_categorie INT)
BEGIN
    SELECT *
    FROM PRODUIT
    WHERE id_categorie = p_id_categorie
      AND actif = TRUE;
END //

-- 3. Consulter les commandes d'un client
CREATE PROCEDURE commandes_client(IN p_id_client INT)
BEGIN
    SELECT *
    FROM COMMANDE
    WHERE id_client = p_id_client
    ORDER BY date_commande DESC;
END //

-- 4. Consulter les lignes d'une commande
CREATE PROCEDURE lignes_commande(IN p_id_commande INT)
BEGIN
    SELECT
        lc.id_commande,
        lc.id_produit,
        p.nom,
        lc.quantite,
        lc.prix_unitaire
    FROM LIGNE_COMMANDE lc
    JOIN PRODUIT p ON p.id_produit = lc.id_produit
    WHERE lc.id_commande = p_id_commande;
END //

-- 5. Modifier le stock d'un produit
CREATE PROCEDURE modifier_stock(
    IN p_id_produit INT,
    IN p_nouveau_stock INT
)
BEGIN
    IF p_nouveau_stock IS NULL OR p_nouveau_stock < 0 THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas etre negatif';

    ELSEIF NOT EXISTS (
        SELECT 1
        FROM PRODUIT
        WHERE id_produit = p_id_produit
    ) THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produit introuvable';

    ELSE

        UPDATE PRODUIT
        SET stock = p_nouveau_stock
        WHERE id_produit = p_id_produit;

    END IF;
END //

DELIMITER ;
