-- =====================================================
-- BASE DE DONNÉES TECHSOLUTIONS - COMPLÈTE ET UNIFIÉE
-- Gestion du site web + Configurations PC par métier
-- =====================================================

CREATE DATABASE IF NOT EXISTS techsolutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techsolutions;

-- =====================================================
-- PARTIE 1: GESTION DU SITE WEB
-- =====================================================

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS administrateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nom_complet VARCHAR(100) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    actif BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des actualités
CREATE TABLE IF NOT EXISTS actualites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200) NOT NULL,
    contenu TEXT NOT NULL,
    auteur VARCHAR(100),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME,
    publie BOOLEAN DEFAULT TRUE,
    INDEX idx_publie (publie),
    INDEX idx_date (date_publication)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    traite BOOLEAN DEFAULT FALSE,
    INDEX idx_lu (lu),
    INDEX idx_date (date_envoi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    INDEX idx_statut (statut),
    INDEX idx_date (date_demande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des paramètres du site
CREATE TABLE IF NOT EXISTS parametres_site (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT,
    type VARCHAR(50),
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des logs d'activité
CREATE TABLE IF NOT EXISTS logs_activite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_type ENUM('admin', 'client') NOT NULL,
    utilisateur_id INT NOT NULL,
    action VARCHAR(200) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type (utilisateur_type),
    INDEX idx_user (utilisateur_id),
    INDEX idx_date (date_action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PARTIE 2: GESTION DES CONFIGURATIONS PC
-- =====================================================

-- Table des métiers/départements
CREATE TABLE IF NOT EXISTS metiers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_metier VARCHAR(100) NOT NULL,
    description TEXT,
    nombre_postes INT DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nom (nom_metier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des catégories de composants
CREATE TABLE IF NOT EXISTS categories_composants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des composants PC
CREATE TABLE IF NOT EXISTS composants_pc (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie INT NOT NULL,
    type_composant VARCHAR(50) NOT NULL,
    nom_composant VARCHAR(200) NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(100),
    specifications TEXT,
    prix_unitaire DECIMAL(10,2),
    justification TEXT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories_composants(id) ON DELETE CASCADE,
    INDEX idx_type (type_composant),
    INDEX idx_categorie (id_categorie),
    INDEX idx_marque (marque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des catégories de périphériques
CREATE TABLE IF NOT EXISTS categories_peripheriques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des périphériques
CREATE TABLE IF NOT EXISTS peripheriques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie INT NOT NULL,
    type_peripherique VARCHAR(50) NOT NULL,
    nom_peripherique VARCHAR(200) NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(100),
    specifications TEXT,
    prix_unitaire DECIMAL(10,2),
    justification TEXT NOT NULL,
    accessibilite BOOLEAN DEFAULT FALSE,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories_peripheriques(id) ON DELETE CASCADE,
    INDEX idx_type (type_peripherique),
    INDEX idx_accessibilite (accessibilite),
    INDEX idx_marque (marque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des logiciels
CREATE TABLE IF NOT EXISTS logiciels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_logiciel VARCHAR(50) NOT NULL,
    nom_logiciel VARCHAR(100) NOT NULL,
    editeur VARCHAR(100),
    version VARCHAR(50),
    prix_unitaire DECIMAL(10,2),
    type_licence VARCHAR(50),
    justification TEXT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type (type_logiciel),
    INDEX idx_editeur (editeur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des configurations PC (association métier-composants)
CREATE TABLE IF NOT EXISTS configurations_pc (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_composant INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id) ON DELETE CASCADE,
    FOREIGN KEY (id_composant) REFERENCES composants_pc(id) ON DELETE CASCADE,
    UNIQUE KEY unique_config (id_metier, id_composant),
    INDEX idx_metier (id_metier),
    INDEX idx_composant (id_composant),
    INDEX idx_priorite (priorite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des configurations périphériques (association métier-périphériques)
CREATE TABLE IF NOT EXISTS configurations_peripheriques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_peripherique INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id) ON DELETE CASCADE,
    FOREIGN KEY (id_peripherique) REFERENCES peripheriques(id) ON DELETE CASCADE,
    UNIQUE KEY unique_config_periph (id_metier, id_peripherique),
    INDEX idx_metier (id_metier),
    INDEX idx_peripherique (id_peripherique)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des configurations logiciels (association métier-logiciels)
CREATE TABLE IF NOT EXISTS configurations_logiciels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_logiciel INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id) ON DELETE CASCADE,
    FOREIGN KEY (id_logiciel) REFERENCES logiciels(id) ON DELETE CASCADE,
    UNIQUE KEY unique_config_log (id_metier, id_logiciel),
    INDEX idx_metier (id_metier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTION DES DONNÉES - PARTIE SITE WEB
-- =====================================================

-- Administrateur par défaut (mot de passe: admin123)
INSERT INTO administrateurs (username, password, email, nom_complet) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@techsolutions.fr', 'Administrateur Principal');

-- Actualités par défaut
INSERT INTO actualites (titre, contenu, auteur, publie) VALUES
('Nouveau Partenariat Stratégique', 'TechSolutions annonce un partenariat avec un leader européen du cloud computing pour offrir des solutions encore plus performantes à nos clients. Cette collaboration nous permettra d''étendre notre offre de services cloud et d''accompagner nos clients dans leur transformation digitale avec des outils de pointe.', 'TechSolutions', TRUE),
('Innovation en Cybersécurité', 'Découvrez notre nouvelle solution de protection avancée contre les cybermenaces, développée par notre équipe R&D. Cette solution intègre l''intelligence artificielle pour une détection proactive des menaces et une réponse automatisée aux incidents de sécurité.', 'TechSolutions', TRUE),
('Prix de l''Innovation 2025', 'TechSolutions récompensée pour son engagement dans l''accessibilité numérique et l''innovation sociale. Ce prix reconnaît nos efforts pour rendre la technologie accessible à tous, notamment notre programme d''adaptation des postes de travail pour les personnes en situation de handicap.', 'TechSolutions', TRUE);

-- Paramètres du site
INSERT INTO parametres_site (cle, valeur, type, description) VALUES
('nom_entreprise', 'TechSolutions', 'text', 'Nom de l''entreprise'),
('email_contact', 'contact@techsolutions.fr', 'email', 'Email de contact principal'),
('telephone', '05 55 17 38 00', 'tel', 'Numéro de téléphone'),
('adresse', '12 rue des Innovateurs', 'text', 'Adresse'),
('ville', 'Brive-la-Gaillarde', 'text', 'Ville'),
('code_postal', '19100', 'text', 'Code postal'),
('horaires', 'Lun-Ven : 9h00 - 18h00', 'text', 'Horaires d''ouverture');

-- =====================================================
-- INSERTION DES DONNÉES - MÉTIERS
-- =====================================================

INSERT INTO metiers (nom_metier, description, nombre_postes) VALUES
('Développement logiciel', 'Création et maintenance des logiciels sur mesure pour les clients', 15),
('Gestion des infrastructures systèmes et réseau', 'Mise en place et entretien des infrastructures informatiques', 5),
('Design UX/UI', 'Conception d''interfaces utilisateur attrayantes et fonctionnelles', 5),
('Marketing et vente', 'Stratégie commerciale, promotion des services et relation client', 10),
('Support client', 'Assistance technique et support nécessaire aux clients', 5),
('Ressources humaines et administration', 'Gestion du personnel, recrutement et aspects administratifs', 5),
('Direction', 'Gestion globale et stratégie de l''entreprise', 5);

-- =====================================================
-- INSERTION DES DONNÉES - CATÉGORIES
-- =====================================================

INSERT INTO categories_composants (nom_categorie, description) VALUES
('Processeur', 'Unités centrales de traitement (CPU)'),
('Carte Mère', 'Cartes mères et chipsets'),
('Mémoire', 'Modules de mémoire RAM'),
('Stockage', 'Disques durs, SSD et autres supports'),
('Carte Graphique', 'Cartes graphiques dédiées (GPU)'),
('Alimentation', 'Blocs d''alimentation (PSU)'),
('Boîtier', 'Boîtiers et châssis PC'),
('Refroidissement', 'Solutions de refroidissement');

INSERT INTO categories_peripheriques (nom_categorie, description) VALUES
('Affichage', 'Écrans et moniteurs'),
('Saisie', 'Claviers et souris'),
('Audio/Vidéo', 'Casques, webcams et microphones'),
('Impression', 'Imprimantes et scanners'),
('Accessibilité', 'Équipements adaptés pour handicap');

-- =====================================================
-- INSERTION DES DONNÉES - COMPOSANTS PC
-- =====================================================

-- PROCESSEURS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(1, 'Processeur', 'AMD Ryzen 9 7950X3D', 'AMD', '7950X3D', '16 cœurs, 32 threads, 4.2-5.7 GHz', 699.99, 'Processeur haut de gamme pour développement intensif, compilation rapide et virtualisation multiple.'),
(1, 'Processeur', 'Intel Core i7-14700K', 'Intel', '14700K', '20 cœurs, 28 threads, jusqu''à 5.6 GHz', 449.99, 'Excellent équilibre performance/prix pour développement et multitâche.'),
(1, 'Processeur', 'AMD Ryzen 7 7800X3D', 'AMD', '7800X3D', '8 cœurs, 16 threads, 4.2-5.0 GHz', 449.99, 'Performant pour design et développement avec cache 3D.'),
(1, 'Processeur', 'Intel Core i5-14600K', 'Intel', '14600K', '14 cœurs, 20 threads, jusqu''à 5.3 GHz', 319.99, 'Solution équilibrée pour postes bureautiques renforcés.'),
(1, 'Processeur', 'AMD Ryzen 5 7600X', 'AMD', '7600X', '6 cœurs, 12 threads, 4.7-5.3 GHz', 249.99, 'Efficace pour tâches bureautiques standards.');

-- CARTES MÈRES
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(2, 'Carte Mère', 'ASUS ROG Crosshair X670E Hero', 'ASUS', 'X670E Hero', 'AM5, DDR5, PCIe 5.0, WiFi 6E', 699.99, 'Carte mère premium pour stations de travail haut de gamme.'),
(2, 'Carte Mère', 'MSI MPG Z790 Carbon WiFi', 'MSI', 'Z790 Carbon', 'LGA1700, DDR5, PCIe 5.0, WiFi 6E', 449.99, 'Excellente pour processeurs Intel 13e/14e gen.'),
(2, 'Carte Mère', 'GIGABYTE B650 AORUS Elite AX', 'GIGABYTE', 'B650 Elite', 'AM5, DDR5, PCIe 4.0, WiFi 6', 249.99, 'Rapport qualité/prix optimal milieu de gamme.'),
(2, 'Carte Mère', 'ASUS TUF Gaming B760-Plus', 'ASUS', 'B760-Plus', 'LGA1700, DDR4, PCIe 4.0, WiFi 6', 189.99, 'Solution économique compatible DDR4.');

-- MÉMOIRE RAM
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(3, 'Mémoire RAM', 'G.Skill Trident Z5 RGB 64GB', 'G.Skill', 'Trident Z5', 'DDR5-6000, 64GB (2x32GB)', 299.99, '64GB pour développement avec VMs et conteneurs.'),
(3, 'Mémoire RAM', 'Corsair Vengeance 64GB DDR5', 'Corsair', 'Vengeance DDR5', 'DDR5-5600, 64GB (2x32GB)', 249.99, 'Capacité importante pour workloads intensifs.'),
(3, 'Mémoire RAM', 'Kingston FURY Beast 32GB DDR5', 'Kingston', 'FURY Beast', 'DDR5-5200, 32GB (2x16GB)', 149.99, '32GB adapté au design graphique.'),
(3, 'Mémoire RAM', 'Corsair Vengeance 32GB DDR4', 'Corsair', 'Vengeance LPX', 'DDR4-3200, 32GB (2x16GB)', 89.99, 'Solution DDR4 économique pour bureautique.'),
(3, 'Mémoire RAM', 'Crucial 16GB DDR4', 'Crucial', 'Standard DDR4', 'DDR4-3200, 16GB (2x8GB)', 44.99, '16GB suffisant pour bureautique légère.');

-- STOCKAGE SSD
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(4, 'Stockage SSD', 'Samsung 990 PRO 2TB', 'Samsung', '990 PRO', 'M.2 NVMe PCIe 4.0, 2TB', 189.99, 'SSD ultra-rapide pour développeurs.'),
(4, 'Stockage SSD', 'WD Black SN850X 2TB', 'Western Digital', 'SN850X', 'M.2 NVMe PCIe 4.0, 2TB', 179.99, 'Haute performance pour virtualisation.'),
(4, 'Stockage SSD', 'Samsung 980 PRO 1TB', 'Samsung', '980 PRO', 'M.2 NVMe PCIe 4.0, 1TB', 119.99, 'Vitesses élevées pour fichiers volumineux.'),
(4, 'Stockage SSD', 'Crucial P3 Plus 1TB', 'Crucial', 'P3 Plus', 'M.2 NVMe PCIe 4.0, 1TB', 79.99, 'Performant et accessible pour bureautique.'),
(4, 'Stockage SSD', 'Kingston NV2 500GB', 'Kingston', 'NV2', 'M.2 NVMe PCIe 4.0, 500GB', 44.99, 'Économique pour postes d''entrée de gamme.');

-- STOCKAGE HDD
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(4, 'Stockage HDD', 'Seagate IronWolf 4TB', 'Seagate', 'IronWolf', 'SATA 3.5", 4TB, 5900 RPM', 119.99, 'Sauvegarde locale et archives projets.'),
(4, 'Stockage HDD', 'WD Blue 2TB', 'Western Digital', 'Blue', 'SATA 3.5", 2TB, 5400 RPM', 59.99, 'Stockage secondaire économique.');

-- CARTES GRAPHIQUES
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(5, 'Carte Graphique', 'NVIDIA RTX 4070 12GB', 'NVIDIA', 'RTX 4070', '12GB GDDR6X, 5888 CUDA cores', 629.99, 'Puissante pour design graphique et rendu.'),
(5, 'Carte Graphique', 'AMD Radeon RX 7700 XT', 'AMD', 'RX 7700 XT', '12GB GDDR6, 3456 SP', 449.99, 'Alternative AMD pour applications créatives.'),
(5, 'Carte Graphique', 'NVIDIA RTX 4060 8GB', 'NVIDIA', 'RTX 4060', '8GB GDDR6, 3072 CUDA cores', 329.99, 'Polyvalente pour développement et double écran.'),
(5, 'Carte Graphique', 'Intel UHD Graphics 770', 'Intel', 'UHD 770', 'Graphiques intégrés', 0.00, 'Suffisant pour bureautique sans besoin graphique.');

-- ALIMENTATIONS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(6, 'Alimentation', 'Corsair RM850x', 'Corsair', 'RM850x', '850W, 80+ Gold, Modulaire', 139.99, 'Puissante pour stations hautes performances.'),
(6, 'Alimentation', 'be quiet! Straight Power 11', 'be quiet!', 'SP11 750W', '750W, 80+ Gold, Modulaire', 129.99, 'Silencieuse et efficace.'),
(6, 'Alimentation', 'Seasonic Focus GX-650', 'Seasonic', 'Focus GX', '650W, 80+ Gold, Semi-modulaire', 99.99, 'Fiable pour configurations moyennes.'),
(6, 'Alimentation', 'Cooler Master MWE Gold 550W', 'Cooler Master', 'MWE Gold', '550W, 80+ Gold', 69.99, 'Économique certifiée Gold.');

-- BOÎTIERS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(7, 'Boîtier', 'Fractal Design Define 7', 'Fractal Design', 'Define 7', 'ATX, insonorisation', 169.99, 'Premium insonorisé pour environnement calme.'),
(7, 'Boîtier', 'Corsair 4000D Airflow', 'Corsair', '4000D', 'ATX, Mesh frontal', 104.99, 'Moderne avec excellent flux d''air.'),
(7, 'Boîtier', 'NZXT H510', 'NZXT', 'H510', 'ATX, Design minimaliste', 89.99, 'Compact au design épuré.'),
(7, 'Boîtier', 'be quiet! Pure Base 500', 'be quiet!', 'Pure Base 500', 'ATX, Insonorisation', 79.99, 'Silencieux économique.');

-- REFROIDISSEMENT
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(8, 'Refroidissement', 'Noctua NH-D15 chromax', 'Noctua', 'NH-D15', 'Ventirad double tour, 2x140mm', 109.99, 'Ultra-performant et silencieux.'),
(8, 'Refroidissement', 'be quiet! Dark Rock Pro 4', 'be quiet!', 'Dark Rock Pro 4', 'Double tour, 135mm', 89.99, 'Excellent refroidissement silencieux.'),
(8, 'Refroidissement', 'Arctic Freezer 34 eSports', 'Arctic', 'Freezer 34', 'Bi-ventilateur, 120mm', 44.99, 'Abordable et efficace.'),
(8, 'Refroidissement', 'Cooler Master Hyper 212', 'Cooler Master', 'Hyper 212', 'Tour simple, 120mm', 34.99, 'Classique économique.'),
(8, 'Refroidissement', 'Corsair iCUE H150i Elite', 'Corsair', 'iCUE H150i', 'AIO 360mm, RGB, LCD', 289.99, 'Watercooling haut de gamme.');

-- =====================================================
-- INSERTION DES DONNÉES - PÉRIPHÉRIQUES
-- =====================================================

-- ÉCRANS
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(1, 'Écran', 'Dell UltraSharp U2723DE 27"', 'Dell', 'U2723DE', '27", QHD, IPS, USB-C 90W', 549.99, 'Écran professionnel pour développeurs avec USB-C.', FALSE),
(1, 'Écran', 'BenQ SW271C 27"', 'BenQ', 'SW271C', '27", 4K, 99% Adobe RGB', 899.99, 'Écran calibré pour designers graphiques.', FALSE),
(1, 'Écran', 'LG 27UK850-W', 'LG', '27UK850-W', '27", 4K, IPS, HDR10, USB-C', 449.99, 'Polyvalent pour design et multimédia.', FALSE),
(1, 'Écran', 'Dell P2422H 24"', 'Dell', 'P2422H', '24", Full HD, IPS, pivot', 179.99, 'Écran secondaire économique avec pivot.', FALSE),
(1, 'Écran', 'ASUS VA24EHE 24"', 'ASUS', 'VA24EHE', '24", Full HD, IPS, Eye Care', 129.99, 'Standard pour bureautique.', FALSE);

-- ÉCRANS ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(5, 'Écran Accessibilité', 'Samsung ViewFinity S8 32"', 'Samsung', 'ViewFinity S8', '32", 4K, fonctions accessibilité', 699.99, 'Grande taille 32" 4K pour grossissement sans perte qualité. Contraste élevé pour malvoyants.', TRUE),
(5, 'Écran Accessibilité', 'Dell UltraSharp 27" HC', 'Dell', 'U2722D', '27", QHD, contraste 1000:1', 449.99, 'Contraste et luminosité optimisés pour déficience visuelle.', TRUE);

-- CLAVIERS
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Clavier', 'Logitech MX Keys Advanced', 'Logitech', 'MX Keys', 'Sans fil, rétroéclairé, multi-device', 119.99, 'Premium pour développeurs avec rétroéclairage intelligent.', FALSE),
(2, 'Clavier', 'Keychron K8 Pro', 'Keychron', 'K8 Pro', 'Mécanique, sans fil, hot-swap', 119.99, 'Mécanique programmable pour développeurs.', FALSE),
(2, 'Clavier', 'Microsoft Sculpt Ergonomic', 'Microsoft', 'Sculpt', 'Ergonomique, sans fil', 89.99, 'Ergonomique pour confort longue durée.', FALSE),
(2, 'Clavier', 'Logitech K780', 'Logitech', 'K780', 'Multi-device, pavé numérique', 79.99, 'Polyvalent pour bureautique.', FALSE),
(2, 'Clavier', 'Logitech K270', 'Logitech', 'K270', 'Sans fil, compact', 24.99, 'Basique économique.', FALSE);

-- CLAVIERS ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(5, 'Clavier Accessibilité', 'MaxiAids Large Print', 'MaxiAids', 'Large Print', 'Grands caractères, rétroéclairé', 89.99, 'Caractères 3x plus grands avec contraste élevé et rétroéclairage LED.', TRUE),
(5, 'Clavier Accessibilité', 'Clevy Contrast II', 'Clevy', 'Contrast II', 'Contraste élevé, grands caractères', 69.99, 'Très contrasté pour malvoyants avec touches larges.', TRUE);

-- SOURIS
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Souris', 'Logitech MX Master 3S', 'Logitech', 'MX Master 3S', '8000 DPI, boutons programmables', 109.99, 'Professionnelle haut de gamme avec précision 8000 DPI.', FALSE),
(2, 'Souris', 'Logitech MX Vertical', 'Logitech', 'MX Vertical', 'Ergonomique verticale, 4000 DPI', 99.99, 'Ergonomique verticale réduisant tension musculaire.', FALSE),
(2, 'Souris', 'Logitech M720 Triathlon', 'Logitech', 'M720', 'Multi-device, 24 mois autonomie', 44.99, 'Polyvalente multi-appareils.', FALSE),
(2, 'Souris', 'Logitech M185', 'Logitech', 'M185', 'Sans fil, 12 mois autonomie', 12.99, 'Basique fiable.', FALSE);

-- SOURIS ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(5, 'Souris Accessibilité', 'Kensington Expert Trackball', 'Kensington', 'Expert Mouse', 'Trackball, 4 boutons, ergonomique', 119.99, 'Trackball stationnaire précis pour malvoyants avec boutons larges faciles à localiser.', TRUE),
(5, 'Souris Accessibilité', 'Contactic Joystick', 'Contactic', 'Joystick Mouse', 'Joystick adapté, grands boutons', 149.99, 'Dispositif de pointage alternatif pour difficultés motrices.', TRUE);

-- WEBCAMS
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(3, 'Webcam', 'Logitech Brio 4K Pro', 'Logitech', 'Brio 4K', '4K UHD, HDR, autofocus', 199.99, 'Professionnelle 4K pour visioconférences haute qualité.', FALSE),
(3, 'Webcam', 'Logitech C920 HD Pro', 'Logitech', 'C920', '1080p Full HD, micro stéréo', 74.99, 'Standard professionnelle 1080p.', FALSE),
(3, 'Webcam', 'Logitech C270', 'Logitech', 'C270', '720p HD, micro intégré', 29.99, 'Basique pour visioconférences occasionnelles.', FALSE);

-- CASQUES
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(3, 'Casque', 'Jabra Evolve2 75', 'Jabra', 'Evolve2 75', 'Bluetooth, ANC, 36h autonomie', 299.99, 'Premium avec réduction bruit active pour open space.', FALSE),
(3, 'Casque', 'Logitech Zone Wireless Plus', 'Logitech', 'Zone Wireless', 'Bluetooth, certifié Teams/Zoom', 229.99, 'Certifié pour visioconférences professionnelles.', FALSE),
(3, 'Casque', 'Logitech H390 USB', 'Logitech', 'H390', 'USB, micro antibruit', 44.99, 'Filaire économique pour appels fréquents.', FALSE);

-- CASQUE ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(5, 'Casque Accessibilité', 'Sennheiser RS 175 RF', 'Sennheiser', 'RS 175', 'Sans fil RF, amplification', 279.99, 'Casque amplifié pour malentendants avec volume indépendant et modes auditifs.', TRUE);

-- IMPRIMANTES
INSERT INTO peripheriques (id_categorie, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(4, 'Imprimante', 'HP LaserJet Pro MFP M479fdw', 'HP', 'M479fdw', 'Laser couleur, multifonction', 549.99, 'Multifonction laser couleur professionnelle.', FALSE),
(4, 'Imprimante', 'Brother MFC-L3770CDW', 'Brother', 'MFC-L3770CDW', 'Laser couleur, WiFi, NFC', 399.99, 'Complète pour bureau avec chargeur auto.', FALSE),
(4, 'Imprimante', 'HP LaserJet Pro M404dn', 'HP', 'M404dn', 'Laser mono, 38 ppm', 279.99, 'Laser monochrome rapide et économique.', FALSE),
(4, 'Imprimante', 'Epson EcoTank ET-4850', 'Epson', 'EcoTank ET-4850', 'Jet d''encre, réservoirs', 449.99, 'Éco-responsable sans cartouches.', FALSE);

-- =====================================================
-- INSERTION DES DONNÉES - LOGICIELS
-- =====================================================

-- SYSTÈMES D'EXPLOITATION
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Système', 'Windows 11 Pro', 'Microsoft', '23H2', 199.99, 'OEM/Volume', 'OS professionnel pour entreprise avec gestion domaine et sécurité renforcée.'),
('Système', 'Windows 11 Pro Workstations', 'Microsoft', '23H2', 299.99, 'Retail', 'Version optimisée pour stations hautes performances.'),
('Système', 'Ubuntu Desktop 24.04 LTS', 'Canonical', '24.04', 0.00, 'Open Source', 'Distribution Linux gratuite pour développement.');

-- SUITES BUREAUTIQUES
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Bureautique', 'Microsoft 365 Business Standard', 'Microsoft', 'Cloud', 129.99, 'Abonnement annuel', 'Suite complète Office + services cloud Teams, Exchange, OneDrive.'),
('Bureautique', 'Microsoft 365 Apps', 'Microsoft', 'Cloud', 99.99, 'Abonnement annuel', 'Applications Office uniquement sans services cloud avancés.'),
('Bureautique', 'LibreOffice', 'The Document Foundation', '24.2', 0.00, 'Open Source', 'Suite bureautique open source gratuite compatible Office.');

-- SÉCURITÉ
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Sécurité', 'Bitdefender GravityZone', 'Bitdefender', 'Cloud', 59.99, 'Annuel/poste', 'Solution antivirus professionnelle avec gestion centralisée.'),
('Sécurité', 'Kaspersky Endpoint Security', 'Kaspersky', 'Select', 54.99, 'Annuel/poste', 'Protection complète endpoints avec détection comportementale.'),
('Sécurité', 'Windows Defender', 'Microsoft', 'Intégré', 0.00, 'Inclus', 'Antivirus intégré Windows suffisant pour usage basique.'),
('Sécurité', 'Avast Business Pro', 'Avast', 'Cloud', 49.99, 'Annuel/poste', 'Solution antivirus professionnelle économique.');

-- DÉVELOPPEMENT
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Développement', 'Visual Studio Professional 2022', 'Microsoft', '2022', 499.00, 'Perpétuelle', 'IDE professionnel complet pour .NET, C++, Python.'),
('Développement', 'IntelliJ IDEA Ultimate', 'JetBrains', '2024.3', 199.00, 'Annuel', 'IDE Java premium avec support frameworks modernes.'),
('Développement', 'Visual Studio Code', 'Microsoft', 'Latest', 0.00, 'Open Source', 'Éditeur de code gratuit et extensible multi-langages.'),
('Développement', 'Docker Desktop', 'Docker Inc.', 'Latest', 0.00, 'Gratuit', 'Plateforme containerisation pour développement moderne.'),
('Développement', 'Git + GitHub', 'GitHub', 'Latest', 0.00, 'Open Source', 'Contrôle version distribué standard industrie.');

-- DESIGN
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Design', 'Adobe Creative Cloud', 'Adobe', 'CC 2024', 719.88, 'Annuel', 'Suite complète Adobe : Photoshop, Illustrator, InDesign, XD, Premiere Pro.'),
('Design', 'Figma Professional', 'Figma', 'Cloud', 144.00, 'Annuel', 'Design d''interface cloud collaboratif avec prototypage.'),
('Design', 'Adobe CC Express', 'Adobe', 'Express', 119.88, 'Annuel', 'Version allégée Adobe pour création graphique rapide.'),
('Design', 'Affinity Designer 2', 'Serif', 'V2', 74.99, 'Perpétuelle', 'Alternative économique Illustrator sans abonnement.'),
('Design', 'GIMP', 'GIMP Team', '2.10', 0.00, 'Open Source', 'Éditeur d''images open source gratuit.');

-- INFRASTRUCTURE
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Infrastructure', 'VMware Workstation Pro', 'VMware', '17', 199.99, 'Perpétuelle', 'Virtualisation desktop professionnelle.'),
('Infrastructure', 'Veeam Backup & Replication', 'Veeam', 'v12', 449.00, 'Par workload', 'Solution backup professionnelle VMs et serveurs.'),
('Infrastructure', 'Wireshark', 'Wireshark Foundation', 'Latest', 0.00, 'Open Source', 'Analyseur protocoles réseau open source.'),
('Infrastructure', 'PuTTY + WinSCP', 'Simon Tatham', 'Latest', 0.00, 'Open Source', 'Client SSH/Telnet et transfert fichiers SFTP.');

-- ACCESSIBILITÉ
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Accessibilité', 'JAWS Professional', 'Freedom Scientific', '2024', 1095.00, 'Perpétuelle', 'Lecteur d''écran professionnel leader pour aveugles. Standard professionnel avec synthèse vocale haute qualité.'),
('Accessibilité', 'ZoomText Magnifier/Reader', 'Freedom Scientific', '2024', 599.00, 'Perpétuelle', 'Logiciel grossissement écran jusqu''à 60x avec lecture vocale pour malvoyants.'),
('Accessibilité', 'NVDA', 'NV Access', 'Latest', 0.00, 'Open Source', 'Lecteur d''écran open source gratuit alternative viable à JAWS.'),
('Accessibilité', 'Windows Magnifier', 'Microsoft', 'Intégré', 0.00, 'Inclus', 'Loupe Windows intégrée suffisante pour déficience légère.');

-- =====================================================
-- INSERTION DES CONFIGURATIONS PAR MÉTIER
-- Note: Insertion simplifiée des configurations essentielles
-- Pour le détail complet, voir la documentation
-- =====================================================

-- Configuration Développement Logiciel (15 postes)
INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(1, 1, 1, 'essentiel', 'AMD Ryzen 9 pour développeurs seniors'),
(1, 2, 1, 'essentiel', 'Carte mère haut de gamme'),
(1, 6, 1, 'essentiel', '64GB DDR5 pour VMs'),
(1, 10, 1, 'essentiel', 'SSD 2TB ultra-rapide'),
(1, 16, 1, 'recommande', 'RTX 4060 pour CUDA'),
(1, 22, 1, 'essentiel', '850W pour marge'),
(1, 26, 1, 'recommande', 'Boîtier silencieux'),
(1, 30, 1, 'essentiel', 'Refroidissement premium');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite) VALUES
(1, 1, 2, 'essentiel'),  -- 2x écrans Dell 27"
(1, 6, 1, 'essentiel'),  -- Clavier MX Keys
(1, 11, 1, 'essentiel'), -- Souris MX Master 3S
(1, 17, 1, 'recommande');-- Webcam

INSERT INTO configurations_logiciels (id_metier, id_logiciel, priorite) VALUES
(1, 1, 'essentiel'),  -- Windows 11 Pro
(1, 4, 'essentiel'),  -- Microsoft 365
(1, 7, 'essentiel'),  -- Bitdefender
(1, 11, 'essentiel'), -- Visual Studio Pro
(1, 14, 'essentiel'), -- Docker
(1, 15, 'essentiel'); -- Git

-- Configuration Support Client (5 postes dont 1 adapté)
-- Poste standard (4 postes)
INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite) VALUES
(5, 5, 1, 'essentiel'),  -- AMD Ryzen 5
(5, 4, 1, 'essentiel'),  -- Carte mère B760
(5, 9, 1, 'essentiel'),  -- 32GB DDR4
(5, 13, 1, 'essentiel'), -- SSD 1TB
(5, 20, 1, 'essentiel'), -- Graphiques intégrés
(5, 25, 1, 'essentiel'), -- 550W
(5, 29, 1, 'essentiel'); -- Boîtier silencieux

-- Périphériques standard support
INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite) VALUES
(5, 5, 2, 'essentiel'),  -- 2x écrans ASUS 24"
(5, 10, 1, 'essentiel'), -- Clavier standard
(5, 14, 1, 'essentiel'), -- Souris basique
(5, 19, 1, 'essentiel'); -- Casque H390

-- PÉRIPHÉRIQUES ACCESSIBILITÉ (Poste adapté - 1 poste)
INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(5, 6, 1, 'essentiel', 'Écran 32" 4K avec accessibilité - POSTE ADAPTÉ'),
(5, 12, 1, 'essentiel', 'Clavier grands caractères - POSTE ADAPTÉ'),
(5, 16, 1, 'essentiel', 'Trackball ergonomique - POSTE ADAPTÉ'),
(5, 20, 1, 'recommande', 'Casque amplifié - POSTE ADAPTÉ');

-- Logiciels support
INSERT INTO configurations_logiciels (id_metier, id_logiciel, priorite) VALUES
(5, 1, 'essentiel'),  -- Windows 11 Pro
(5, 5, 'essentiel'),  -- Microsoft 365 Apps
(5, 9, 'essentiel');  -- Windows Defender

-- LOGICIELS ACCESSIBILITÉ (Poste adapté)
INSERT INTO configurations_logiciels (id_metier, id_logiciel, priorite, notes) VALUES
(5, 27, 'essentiel', 'JAWS Professional - POSTE ADAPTÉ'),
(5, 28, 'recommande', 'ZoomText - POSTE ADAPTÉ'),
(5, 29, 'essentiel', 'NVDA backup - POSTE ADAPTÉ'),
(5, 30, 'essentiel', 'Windows Magnifier - POSTE ADAPTÉ');

-- =====================================================
-- VUES SQL UTILES
-- =====================================================

-- Vue: Configuration complète par métier
CREATE OR REPLACE VIEW v_config_complete AS
SELECT 
    m.id as id_metier,
    m.nom_metier,
    m.nombre_postes,
    'Composant PC' as type_equipement,
    cc.nom_categorie as categorie,
    c.nom_composant,
    c.marque,
    c.prix_unitaire,
    cp.quantite,
    cp.priorite,
    c.justification,
    cp.notes
FROM metiers m
JOIN configurations_pc cp ON m.id = cp.id_metier
JOIN composants_pc c ON cp.id_composant = c.id
JOIN categories_composants cc ON c.id_categorie = cc.id

UNION ALL

SELECT 
    m.id,
    m.nom_metier,
    m.nombre_postes,
    'Périphérique',
    cpd.nom_categorie,
    p.nom_peripherique,
    p.marque,
    p.prix_unitaire,
    cpp.quantite,
    cpp.priorite,
    p.justification,
    cpp.notes
FROM metiers m
JOIN configurations_peripheriques cpp ON m.id = cpp.id_metier
JOIN peripheriques p ON cpp.id_peripherique = p.id
JOIN categories_peripheriques cpd ON p.id_categorie = cpd.id

UNION ALL

SELECT 
    m.id,
    m.nom_metier,
    m.nombre_postes,
    'Logiciel',
    l.type_logiciel,
    l.nom_logiciel,
    l.editeur,
    l.prix_unitaire,
    cl.quantite,
    cl.priorite,
    l.justification,
    cl.notes
FROM metiers m
JOIN configurations_logiciels cl ON m.id = cl.id_metier
JOIN logiciels l ON cl.id_logiciel = l.id;

-- Vue: Coût par métier
CREATE OR REPLACE VIEW v_cout_metier AS
SELECT 
    m.id,
    m.nom_metier,
    m.nombre_postes,
    COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) as cout_composants,
    COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) as cout_peripheriques,
    COALESCE(SUM(l.prix_unitaire * cl.quantite), 0) as cout_logiciels,
    (COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) + 
     COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) + 
     COALESCE(SUM(l.prix_unitaire * cl.quantite), 0)) as cout_total_poste,
    (COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) + 
     COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) + 
     COALESCE(SUM(l.prix_unitaire * cl.quantite), 0)) * m.nombre_postes as cout_total_departement
FROM metiers m
LEFT JOIN configurations_pc cp ON m.id = cp.id_metier
LEFT JOIN composants_pc c ON cp.id_composant = c.id
LEFT JOIN configurations_peripheriques cpp ON m.id = cpp.id_metier
LEFT JOIN peripheriques p ON cpp.id_peripherique = p.id
LEFT JOIN configurations_logiciels cl ON m.id = cl.id_metier
LEFT JOIN logiciels l ON cl.id_logiciel = l.id
GROUP BY m.id, m.nom_metier, m.nombre_postes;

-- Vue: Équipements d'accessibilité
CREATE OR REPLACE VIEW v_accessibilite AS
SELECT 
    m.nom_metier,
    p.type_peripherique,
    p.nom_peripherique,
    p.marque,
    p.prix_unitaire,
    p.justification,
    cpp.notes
FROM configurations_peripheriques cpp
JOIN peripheriques p ON cpp.id_peripherique = p.id
JOIN metiers m ON cpp.id_metier = m.id
WHERE p.accessibilite = TRUE
UNION ALL
SELECT 
    m.nom_metier,
    l.type_logiciel,
    l.nom_logiciel,
    l.editeur,
    l.prix_unitaire,
    l.justification,
    cl.notes
FROM configurations_logiciels cl
JOIN logiciels l ON cl.id_logiciel = l.id
JOIN metiers m ON cl.id_metier = m.id
WHERE l.type_logiciel = 'Accessibilité';

-- =====================================================
-- FIN DU SCRIPT
-- Base de données TechSolutions créée avec succès!
-- =====================================================

-- Pour vérifier l'installation:
-- SELECT COUNT(*) as total_composants FROM composants_pc;
-- SELECT COUNT(*) as total_peripheriques FROM peripheriques;
-- SELECT COUNT(*) as total_logiciels FROM logiciels;
-- SELECT * FROM v_cout_metier;
-- SELECT * FROM v_accessibilite;
