-- ENUM
CREATE TYPE type_compte_enum AS ENUM ('principal', 'secondaire');
CREATE TYPE type_transaction_enum AS ENUM ('PAIEMENT', 'DEPOT', 'RETRAIT');

CREATE TABLE CLIENT (
    id_client SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    numero_telephone VARCHAR(20) UNIQUE NOT NULL,
    numero_cni VARCHAR(20) UNIQUE NOT NULL,
    adresse TEXT NOT NULL,
    photo_recto VARCHAR(255),
    photo_verso VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TYPE_COMPTE (
    id_type SERIAL PRIMARY KEY,
    libelle type_compte_enum NOT NULL UNIQUE
);

CREATE TABLE TYPE_TRANSACTION (
    id_type SERIAL PRIMARY KEY,
    libelle type_transaction_enum NOT NULL UNIQUE
);

CREATE TABLE COMPTE (
    id_compte SERIAL PRIMARY KEY,
    numero_telephone VARCHAR(20) NOT NULL,
    solde DECIMAL(15,2) DEFAULT 0.00,
    code_secret CHAR(4) NOT NULL CHECK (code_secret ~ '^[0-9]{4}$'),
    est_principal BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_client INTEGER NOT NULL,
    id_type_compte INTEGER NOT NULL,
    FOREIGN KEY (id_client) REFERENCES CLIENT(id_client) ON DELETE CASCADE,
    FOREIGN KEY (id_type_compte) REFERENCES TYPE_COMPTE(id_type)
);

CREATE TABLE TRANSACTION (
    id_transaction SERIAL PRIMARY KEY,
    montant DECIMAL(15,2) NOT NULL,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_compte INTEGER NOT NULL,
    id_type_transaction INTEGER NOT NULL,
    FOREIGN KEY (id_compte) REFERENCES COMPTE(id_compte) ON DELETE CASCADE,
    FOREIGN KEY (id_type_transaction) REFERENCES TYPE_TRANSACTION(id_type)
);

CREATE TABLE SERVICE_COMMERCIAL (
    id_service SERIAL PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password CHAR(4) NOT NULL CHECK (password ~ '^[0-9]{4}$'),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO TYPE_COMPTE (libelle) VALUES ('principal'), ('secondaire');
INSERT INTO TYPE_TRANSACTION (libelle) VALUES ('PAIEMENT'), ('DEPOT'), ('RETRAIT');

INSERT INTO CLIENT (nom, prenom, numero_telephone, numero_cni, adresse, photo_recto, photo_verso)
VALUES 
('Ndoye', 'Mamadou', '771112233', '2211999000111', 'Dakar, Liberté 6', 'ndoye_recto.jpg', 'ndoye_verso.jpg'),
('Ba', 'Aminata', '781122334', '2211888000222', 'Thiès, Grand Standing', 'ba_recto.jpg', 'ba_verso.jpg');

--
INSERT INTO COMPTE (numero_telephone, solde, code_secret, est_principal, id_client, id_type_compte)
VALUES 
('771112233', 80000.00, '1234', TRUE, 1, 1),
('781122334', 30000.00, '5678', TRUE, 2, 1);

-- Transactions
INSERT INTO TRANSACTION (montant, id_compte, id_type_transaction)
VALUES 
(15000.00, 1, 2),  -- DEPOT
(10000.00, 2, 3);  -- RETRAIT

-- Agents service commercial
INSERT INTO SERVICE_COMMERCIAL (login, password, nom, prenom)
VALUES 
('sc_admin', '0000', 'Sow', 'Moussa'),
('sc_agent', '1111', 'Diagne', 'Seynabou');
