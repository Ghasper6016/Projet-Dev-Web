
-- ===========================
--      TABLE CLIENT
-- ===========================
CREATE TABLE Client (
    id_client INT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    mail VARCHAR(100) UNIQUE,
    numero_telephone VARCHAR(20),
    mot_de_passe VARCHAR(255),
    adresse VARCHAR(255),
    code_postal VARCHAR(10),
    detail_adresse VARCHAR(255),
    ville VARCHAR(50),
    id_annonce INT,     -- (optionnel selon ton mapping)
    id_note INT         -- (optionnel selon ton mapping)
);

-- ===========================
--      TABLE PETSITTER
-- ===========================
CREATE TABLE Petsitter (
    id_petsitter INT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    mail VARCHAR(100) UNIQUE,
    numero_telephone VARCHAR(20),
    mot_de_passe VARCHAR(255),
    adresse VARCHAR(255),
    code_postal VARCHAR(10),
    detail_adresse VARCHAR(255),
    ville VARCHAR(50),
    id_annonce INT,
    id_note INT
);

-- ===========================
--      TABLE ANIMAUX
-- ===========================
CREATE TABLE Animaux (
    id_animaux INT PRIMARY KEY,
    prenom VARCHAR(50),
    type VARCHAR(50),
    age INT,
    info_supp TEXT
);

-- ===========================
--      TABLE ANIMAUX_CLIENT (1,N entre client et animaux)
-- ===========================
CREATE TABLE Animaux_Client (
    id_client INT,
    id_animaux INT,
    PRIMARY KEY (id_client, id_animaux),
    FOREIGN KEY (id_client) REFERENCES Client(id_client),
    FOREIGN KEY (id_animaux) REFERENCES Animaux(id_animaux)
);

-- ===========================
--  TABLE ANIMAUX_GARDE (N,N petsitter <-> animaux)
-- ===========================
CREATE TABLE Animaux_Garde (
    id_garde INT PRIMARY KEY,
    id_petsitter INT,
    id_animaux INT,
    FOREIGN KEY (id_petsitter) REFERENCES Petsitter(id_petsitter),
    FOREIGN KEY (id_animaux) REFERENCES Animaux(id_animaux)
);

-- ===========================
--  TABLE ANNONCE
-- ===========================
CREATE TABLE Annonce (
    id_annonce INT PRIMARY KEY,
    date_debut DATE,
    date_fin DATE,
    description TEXT,
    numero_service INT,
    id_client INT,
    FOREIGN KEY (id_client) REFERENCES Client(id_client)
);

-- ===========================
--  TABLE SERVICE
-- ===========================
CREATE TABLE Service (
    numero_service INT PRIMARY KEY,
    intitule VARCHAR(100),
    type_animaux VARCHAR(50)
);

-- ===========================
--  TABLE SERVICE_CLIENT
-- ===========================
CREATE TABLE Service_Client (
    id_service INT,
    id_client INT,
    PRIMARY KEY (id_service, id_client),
    FOREIGN KEY (id_service) REFERENCES Service(numero_service),
    FOREIGN KEY (id_client) REFERENCES Client(id_client)
);

-- ===========================
--  TABLE TARIF (1,1 service → tarif)
-- ===========================
CREATE TABLE Tarif (
    type_animaux VARCHAR(50) PRIMARY KEY,
    horaire DECIMAL(5,2),
    journalier DECIMAL(5,2),
    hebdomadaire DECIMAL(5,2),
    mensuel DECIMAL(5,2),
    annuel DECIMAL(5,2)
);

-- ===========================
--  TABLE NOTE
-- ===========================
CREATE TABLE Note (
    id_note INT PRIMARY KEY,
    date DATE,
    note INT CHECK(note BETWEEN 1 AND 5),
    commentaire TEXT
);
