-- Historique des changements de statut des commandes
CREATE TABLE ORDER_STATUS_HISTORY (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    old_status VARCHAR(20) NOT NULL,
    new_status VARCHAR(20) NOT NULL,
    changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_history_commande
        FOREIGN KEY (order_id)
        REFERENCES COMMANDE(id_commande)
);