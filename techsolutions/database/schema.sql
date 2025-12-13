-- Création de la base de données TechSolutions
CREATE DATABASE IF NOT EXISTS techsolutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techsolutions;

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS administrateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nom_complet VARCHAR(100) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    entreprise VARCHAR(100),
    adresse TEXT,
    ville VARCHAR(100),
    code_postal VARCHAR(10),
    pays VARCHAR(50) DEFAULT 'France',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion DATETIME,
    actif BOOLEAN DEFAULT TRUE
);

-- Table des actualités
CREATE TABLE IF NOT EXISTS actualites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200) NOT NULL,
    contenu TEXT NOT NULL,
    auteur VARCHAR(100),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME,
    publie BOOLEAN DEFAULT TRUE
);

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS messages_contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    sujet VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE,
    traite BOOLEAN DEFAULT FALSE
);

-- Table des demandes de devis
CREATE TABLE IF NOT EXISTS demandes_devis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    entreprise VARCHAR(100),
    type_service VARCHAR(100),
    description TEXT NOT NULL,
    budget_estime VARCHAR(50),
    delai VARCHAR(50),
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'en_cours', 'envoye', 'accepte', 'refuse') DEFAULT 'en_attente',
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
);

-- Table des paramètres du site
CREATE TABLE IF NOT EXISTS parametres_site (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT,
    type VARCHAR(50),
    description TEXT
);

-- Table des logs d'activité
CREATE TABLE IF NOT EXISTS logs_activite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_type ENUM('admin', 'client') NOT NULL,
    utilisateur_id INT NOT NULL,
    action VARCHAR(200) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertion d'un administrateur par défaut
-- Mot de passe: admin123 (à changer en production!)
INSERT INTO administrateurs (username, password, email, nom_complet) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@techsolutions.fr', 'Administrateur Principal');

-- Insertion de quelques actualités par défaut
INSERT INTO actualites (titre, contenu, auteur, publie) VALUES
('Nouveau Partenariat Stratégique', 'TechSolutions annonce un partenariat avec un leader européen du cloud computing pour offrir des solutions encore plus performantes à nos clients. Cette collaboration nous permettra d''étendre notre offre de services cloud et d''accompagner nos clients dans leur transformation digitale avec des outils de pointe.', 'TechSolutions', TRUE),
('Innovation en Cybersécurité', 'Découvrez notre nouvelle solution de protection avancée contre les cybermenaces, développée par notre équipe R&D. Cette solution intègre l''intelligence artificielle pour une détection proactive des menaces et une réponse automatisée aux incidents de sécurité.', 'TechSolutions', TRUE),
('Prix de l''Innovation 2025', 'TechSolutions récompensée pour son engagement dans l''accessibilité numérique et l''innovation sociale. Ce prix reconnaît nos efforts pour rendre la technologie accessible à tous, notamment notre programme d''adaptation des postes de travail pour les personnes en situation de handicap.', 'TechSolutions', TRUE);

-- Insertion de paramètres par défaut
INSERT INTO parametres_site (cle, valeur, type, description) VALUES
('nom_entreprise', 'TechSolutions', 'text', 'Nom de l''entreprise'),
('email_contact', 'contact@techsolutions.fr', 'email', 'Email de contact principal'),
('telephone', '05 55 17 38 00', 'tel', 'Numéro de téléphone'),
('adresse', '12 rue des Innovateurs', 'text', 'Adresse'),
('ville', 'Brive-la-Gaillarde', 'text', 'Ville'),
('code_postal', '19100', 'text', 'Code postal'),
('horaires', 'Lun-Ven : 9h00 - 18h00', 'text', 'Horaires d''ouverture');
