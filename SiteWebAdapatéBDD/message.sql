CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,   -- utilisateur qui envoie le message
    destinataire_id INT NOT NULL, -- utilisateur qui reçoit le message
    service VARCHAR(50) NOT NULL, -- promenade, garde, visite
    contenu TEXT NOT NULL,
    lu TINYINT(1) DEFAULT 0,     -- 0 = non lu, 1 = lu
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_message_expediteur
        FOREIGN KEY (expediteur_id) REFERENCES utilisateur(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_message_destinataire
        FOREIGN KEY (destinataire_id) REFERENCES utilisateur(id)
        ON DELETE CASCADE
);
