DELIMITER //

-- 1. Ajouter une catégorie
CREATE PROCEDURE ajouter_categorie(IN p_nom VARCHAR(100))
BEGIN
    IF EXISTS (SELECT 1 FROM CATEGORIE WHERE nom = TRIM(p_nom)) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cette categorie existe deja';
    ELSE
        INSERT INTO CATEGORIE (nom) VALUES (TRIM(p_nom));
    END IF;
END //

-- 2. Modifier une catégorie
CREATE PROCEDURE modifier_categorie(
    IN p_id_categorie INT,
    IN p_nom VARCHAR(100)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM CATEGORIE WHERE id_categorie = p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Categorie introuvable';

    ELSEIF EXISTS (
        SELECT 1 FROM CATEGORIE
        WHERE nom = TRIM(p_nom) AND id_categorie <> p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cette categorie existe deja';

    ELSE
        UPDATE CATEGORIE
        SET nom = TRIM(p_nom)
        WHERE id_categorie = p_id_categorie;
    END IF;
END //

-- 3. Supprimer une catégorie (seulement si elle est vide)
CREATE PROCEDURE supprimer_categorie(IN p_id_categorie INT)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM CATEGORIE WHERE id_categorie = p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Categorie introuvable';

    ELSEIF EXISTS (
        SELECT 1 FROM PRODUIT WHERE id_categorie = p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Impossible de supprimer une categorie contenant des produits';

    ELSE
        DELETE FROM CATEGORIE WHERE id_categorie = p_id_categorie;
    END IF;
END //

-- 4. Ajouter un produit
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
    END IF;
END //

-- 5. Modifier un produit (le stock a sa propre procédure : modifier_stock)
CREATE PROCEDURE modifier_produit(
    IN p_id_produit INT,
    IN p_nom VARCHAR(150),
    IN p_description TEXT,
    IN p_prix DECIMAL(10,2),
    IN p_id_categorie INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM PRODUIT WHERE id_produit = p_id_produit
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produit introuvable';

    ELSEIF p_nom IS NULL OR TRIM(p_nom) = '' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nom du produit est obligatoire';

    ELSEIF NOT EXISTS (
        SELECT 1 FROM CATEGORIE WHERE id_categorie = p_id_categorie
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Categorie introuvable';

    ELSE
        UPDATE PRODUIT
        SET nom = TRIM(p_nom),
            description = p_description,
            prix = p_prix,
            id_categorie = p_id_categorie
        WHERE id_produit = p_id_produit;
    END IF;
END //

-- 6. Activer ou désactiver un produit
-- (désactiver remplace la suppression : l'historique des commandes est conservé)
CREATE PROCEDURE changer_etat_produit(
    IN p_id_produit INT,
    IN p_actif BOOLEAN
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM PRODUIT WHERE id_produit = p_id_produit
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produit introuvable';
    ELSE
        UPDATE PRODUIT
        SET actif = p_actif
        WHERE id_produit = p_id_produit;
    END IF;
END //

-- 7. Modifier le statut d'une commande
-- (les transitions autorisées sont contrôlées par le trigger verifier_statut_commande)
CREATE PROCEDURE modifier_statut_commande(
    IN p_id_commande INT,
    IN p_statut VARCHAR(20)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM COMMANDE WHERE id_commande = p_id_commande
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Commande introuvable';

    ELSEIF p_statut NOT IN
        ('en_attente', 'en_preparation', 'expediee', 'livree', 'annulee') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Statut invalide';

    ELSE
        UPDATE COMMANDE
        SET statut = p_statut
        WHERE id_commande = p_id_commande;
    END IF;
END //

-- 8. Lister tous les produits (actifs et inactifs)
CREATE PROCEDURE lister_produits_admin()
BEGIN
    SELECT p.id_produit, p.nom, p.description, p.prix, p.stock,
           p.actif, p.id_categorie, c.nom AS categorie
    FROM PRODUIT p
    JOIN CATEGORIE c ON c.id_categorie = p.id_categorie
    ORDER BY p.id_produit;
END //

-- 9. Lister les catégories avec leur nombre de produits
CREATE PROCEDURE lister_categories_admin()
BEGIN
    SELECT c.id_categorie, c.nom, COUNT(p.id_produit) AS nb_produits
    FROM CATEGORIE c
    LEFT JOIN PRODUIT p ON p.id_categorie = c.id_categorie
    GROUP BY c.id_categorie, c.nom
    ORDER BY c.nom;
END //

-- 10. Lister toutes les commandes avec le client et le total
CREATE PROCEDURE lister_commandes_admin()
BEGIN
    SELECT cmd.id_commande, cmd.date_commande, cmd.statut,
           cl.nom, cl.prenom, cl.email,
           COALESCE(SUM(lc.quantite * lc.prix_unitaire), 0) AS total
    FROM COMMANDE cmd
    JOIN CLIENT cl ON cl.id_client = cmd.id_client
    LEFT JOIN LIGNE_COMMANDE lc ON lc.id_commande = cmd.id_commande
    GROUP BY cmd.id_commande, cmd.date_commande, cmd.statut,
             cl.nom, cl.prenom, cl.email
    ORDER BY cmd.date_commande DESC;
END //

-- 11. Historique des statuts d'une commande
CREATE PROCEDURE historique_statut_commande(IN p_id_commande INT)
BEGIN
    SELECT id, order_id, old_status, new_status, changed_at
    FROM ORDER_STATUS_HISTORY
    WHERE order_id = p_id_commande
    ORDER BY changed_at DESC, id DESC;
END //

DELIMITER ;