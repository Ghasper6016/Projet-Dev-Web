CREATE TABLE annonce (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,

    localisation VARCHAR(50) NOT NULL,
    animal VARCHAR(50) NOT NULL,
    activite VARCHAR(50) NOT NULL,

    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,

    prix DECIMAL(8,2) NOT NULL,

    telephone VARCHAR(20),
    details VARCHAR(255),

    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_annonce_utilisateur
        FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE CASCADE
);