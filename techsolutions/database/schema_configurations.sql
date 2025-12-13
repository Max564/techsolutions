-- =====================================================
-- BASE DE DONNÉES TECHSOLUTIONS - CONFIGURATIONS PC
-- Gestion des configurations matérielles par métier
-- =====================================================

CREATE DATABASE IF NOT EXISTS techsolutions_config CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techsolutions_config;

-- =====================================================
-- TABLE: MÉTIERS
-- =====================================================
CREATE TABLE IF NOT EXISTS metiers (
    id_metier INT PRIMARY KEY AUTO_INCREMENT,
    nom_metier VARCHAR(100) NOT NULL,
    description TEXT,
    nombre_postes INT DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: CATÉGORIES DE COMPOSANTS
-- =====================================================
CREATE TABLE IF NOT EXISTS categories_composants (
    id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: COMPOSANTS PC
-- =====================================================
CREATE TABLE IF NOT EXISTS composants_pc (
    id_composant INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie INT NOT NULL,
    type_composant VARCHAR(50) NOT NULL,
    nom_composant VARCHAR(200) NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(100),
    specifications TEXT,
    prix_unitaire DECIMAL(10,2),
    justification TEXT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories_composants(id_categorie) ON DELETE CASCADE,
    INDEX idx_type (type_composant),
    INDEX idx_categorie (id_categorie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: CATÉGORIES DE PÉRIPHÉRIQUES
-- =====================================================
CREATE TABLE IF NOT EXISTS categories_peripheriques (
    id_categorie_periph INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: PÉRIPHÉRIQUES
-- =====================================================
CREATE TABLE IF NOT EXISTS peripheriques (
    id_peripherique INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie_periph INT NOT NULL,
    type_peripherique VARCHAR(50) NOT NULL,
    nom_peripherique VARCHAR(200) NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(100),
    specifications TEXT,
    prix_unitaire DECIMAL(10,2),
    justification TEXT NOT NULL,
    accessibilite BOOLEAN DEFAULT FALSE,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie_periph) REFERENCES categories_peripheriques(id_categorie_periph) ON DELETE CASCADE,
    INDEX idx_type_periph (type_peripherique),
    INDEX idx_accessibilite (accessibilite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: LOGICIELS
-- =====================================================
CREATE TABLE IF NOT EXISTS logiciels (
    id_logiciel INT PRIMARY KEY AUTO_INCREMENT,
    type_logiciel VARCHAR(50) NOT NULL,
    nom_logiciel VARCHAR(100) NOT NULL,
    editeur VARCHAR(100),
    version VARCHAR(50),
    prix_unitaire DECIMAL(10,2),
    type_licence VARCHAR(50),
    justification TEXT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type_logiciel (type_logiciel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: CONFIGURATIONS PC (Association métier-composants)
-- =====================================================
CREATE TABLE IF NOT EXISTS configurations_pc (
    id_configuration_pc INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_composant INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id_metier) ON DELETE CASCADE,
    FOREIGN KEY (id_composant) REFERENCES composants_pc(id_composant) ON DELETE CASCADE,
    UNIQUE KEY unique_config (id_metier, id_composant),
    INDEX idx_metier_config (id_metier),
    INDEX idx_composant_config (id_composant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: CONFIGURATIONS PÉRIPHÉRIQUES (Association métier-périphériques)
-- =====================================================
CREATE TABLE IF NOT EXISTS configurations_peripheriques (
    id_configuration_peripherique INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_peripherique INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id_metier) ON DELETE CASCADE,
    FOREIGN KEY (id_peripherique) REFERENCES peripheriques(id_peripherique) ON DELETE CASCADE,
    UNIQUE KEY unique_config_periph (id_metier, id_peripherique),
    INDEX idx_metier_periph (id_metier),
    INDEX idx_peripherique_config (id_peripherique)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: CONFIGURATIONS LOGICIELS (Association métier-logiciels)
-- =====================================================
CREATE TABLE IF NOT EXISTS configurations_logiciels (
    id_configuration_logiciel INT PRIMARY KEY AUTO_INCREMENT,
    id_metier INT NOT NULL,
    id_logiciel INT NOT NULL,
    quantite INT DEFAULT 1,
    priorite ENUM('essentiel', 'recommande', 'optionnel') DEFAULT 'essentiel',
    notes TEXT,
    date_configuration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_metier) REFERENCES metiers(id_metier) ON DELETE CASCADE,
    FOREIGN KEY (id_logiciel) REFERENCES logiciels(id_logiciel) ON DELETE CASCADE,
    UNIQUE KEY unique_config_log (id_metier, id_logiciel),
    INDEX idx_metier_log (id_metier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTION DES DONNÉES: MÉTIERS
-- =====================================================
INSERT INTO metiers (nom_metier, description, nombre_postes) VALUES
('Développement logiciel', 'Création et maintenance des logiciels sur mesure pour les clients', 15),
('Gestion des infrastructures systèmes et réseau', 'Mise en place et entretien des infrastructures informatiques, incluant les réseaux et les serveurs', 5),
('Design UX/UI', 'Conception d''interfaces utilisateur attrayantes et fonctionnelles', 5),
('Marketing et vente', 'Stratégie commerciale, promotion des services et relation client', 10),
('Support client', 'Assistance technique et support nécessaire aux clients', 5),
('Ressources humaines et administration', 'Gestion du personnel, recrutement et aspects administratifs', 5),
('Direction', 'Gestion globale et stratégie de l''entreprise', 5);

-- =====================================================
-- INSERTION DES DONNÉES: CATÉGORIES DE COMPOSANTS
-- =====================================================
INSERT INTO categories_composants (nom_categorie, description) VALUES
('Processeur', 'Unités centrales de traitement (CPU)'),
('Carte Mère', 'Cartes mères et chipsets'),
('Mémoire', 'Modules de mémoire RAM'),
('Stockage', 'Disques durs, SSD et autres supports de stockage'),
('Carte Graphique', 'Cartes graphiques dédiées (GPU)'),
('Alimentation', 'Blocs d''alimentation (PSU)'),
('Boîtier', 'Boîtiers et châssis PC'),
('Refroidissement', 'Solutions de refroidissement CPU et boîtier');

-- =====================================================
-- INSERTION DES DONNÉES: CATÉGORIES DE PÉRIPHÉRIQUES
-- =====================================================
INSERT INTO categories_peripheriques (nom_categorie, description) VALUES
('Affichage', 'Écrans et moniteurs'),
('Saisie', 'Claviers et souris'),
('Audio/Vidéo', 'Casques, webcams et microphones'),
('Impression', 'Imprimantes et scanners'),
('Accessibilité', 'Équipements adaptés pour personnes en situation de handicap');

-- =====================================================
-- INSERTION DES DONNÉES: COMPOSANTS PC
-- =====================================================

-- PROCESSEURS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(1, 'Processeur', 'AMD Ryzen 9 7950X3D', 'AMD', '7950X3D', '16 cœurs, 32 threads, 4.2-5.7 GHz, 128MB Cache L3 3D V-Cache', 699.99, 'Processeur haut de gamme offrant une puissance de calcul exceptionnelle pour la compilation de code complexe, l''exécution de machines virtuelles et le multitâche intensif. Le cache 3D améliore les performances pour les workloads de développement.'),
(1, 'Processeur', 'Intel Core i7-14700K', 'Intel', '14700K', '20 cœurs (8P+12E), 28 threads, jusqu''à 5.6 GHz', 449.99, 'Excellent équilibre performance/prix pour le développement logiciel. Les cœurs de performance gèrent les tâches lourdes tandis que les cœurs d''efficacité optimisent le multitâche.'),
(1, 'Processeur', 'AMD Ryzen 7 7800X3D', 'AMD', '7800X3D', '8 cœurs, 16 threads, 4.2-5.0 GHz, 96MB Cache L3 3D', 449.99, 'Processeur performant pour le design et le développement. Cache 3D bénéfique pour les applications créatives et la compilation.'),
(1, 'Processeur', 'Intel Core i5-14600K', 'Intel', '14600K', '14 cœurs (6P+8E), 20 threads, jusqu''à 5.3 GHz', 319.99, 'Solution équilibrée pour les postes de travail standard. Gère efficacement les applications bureautiques et la navigation web professionnelle.'),
(1, 'Processeur', 'AMD Ryzen 5 7600X', 'AMD', '7600X', '6 cœurs, 12 threads, 4.7-5.3 GHz', 249.99, 'Processeur efficace pour les tâches bureautiques standards, navigation web et applications de gestion. Excellent rapport performance/prix.');

-- CARTES MÈRES
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(2, 'Carte Mère', 'ASUS ROG Crosshair X670E Hero', 'ASUS', 'ROG Crosshair X670E Hero', 'Socket AM5, Chipset X670E, DDR5, PCIe 5.0, WiFi 6E', 699.99, 'Carte mère premium offrant toutes les fonctionnalités pour stations de travail haut de gamme. Support DDR5 et PCIe 5.0 pour évolutivité maximale.'),
(2, 'Carte Mère', 'MSI MPG Z790 Carbon WiFi', 'MSI', 'MPG Z790 Carbon WiFi', 'Socket LGA1700, Chipset Z790, DDR5, PCIe 5.0, WiFi 6E', 449.99, 'Excellente carte mère pour processeurs Intel de 13e et 14e génération. Connectivité complète et overclocking supporté.'),
(2, 'Carte Mère', 'GIGABYTE B650 AORUS Elite AX', 'GIGABYTE', 'B650 AORUS Elite AX', 'Socket AM5, Chipset B650, DDR5, PCIe 4.0, WiFi 6', 249.99, 'Carte mère milieu de gamme offrant un excellent rapport qualité/prix. Support DDR5 et connectivité moderne.'),
(2, 'Carte Mère', 'ASUS TUF Gaming B760-Plus WiFi D4', 'ASUS', 'TUF B760-Plus WiFi D4', 'Socket LGA1700, Chipset B760, DDR4, PCIe 4.0, WiFi 6', 189.99, 'Solution économique compatible DDR4 pour réduire les coûts sur les postes standards tout en gardant de bonnes performances.');

-- MÉMOIRE RAM
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(3, 'Mémoire RAM', 'G.Skill Trident Z5 RGB 64GB (2x32GB) DDR5-6000', 'G.Skill', 'Trident Z5', 'DDR5-6000 MHz, CL30, 64GB (2x32GB)', 299.99, 'Mémoire haute performance pour le développement logiciel. 64GB permettent l''exécution simultanée de multiples environnements de développement, machines virtuelles et conteneurs Docker.'),
(3, 'Mémoire RAM', 'Corsair Vengeance 64GB (2x32GB) DDR5-5600', 'Corsair', 'Vengeance DDR5', 'DDR5-5600 MHz, CL36, 64GB (2x32GB)', 249.99, 'Capacité importante pour les workloads de développement et les machines virtuelles. Fréquence élevée pour les tâches intensives.'),
(3, 'Mémoire RAM', 'Kingston FURY Beast 32GB (2x16GB) DDR5-5200', 'Kingston', 'FURY Beast DDR5', 'DDR5-5200 MHz, CL40, 32GB (2x16GB)', 149.99, 'Configuration 32GB adaptée au design graphique et aux applications créatives. Suffisant pour Photoshop, Illustrator et Figma simultanément.'),
(3, 'Mémoire RAM', 'Corsair Vengeance LPX 32GB (2x16GB) DDR4-3200', 'Corsair', 'Vengeance LPX', 'DDR4-3200 MHz, CL16, 32GB (2x16GB)', 89.99, 'Solution DDR4 économique pour postes bureautiques standards. 32GB confortable pour multitâche et navigation intensive.'),
(3, 'Mémoire RAM', 'Crucial 16GB (2x8GB) DDR4-3200', 'Crucial', 'Standard DDR4', 'DDR4-3200 MHz, CL22, 16GB (2x8GB)', 44.99, '16GB suffisant pour bureautique légère, navigation web et applications RH/admin.');

-- STOCKAGE SSD
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(4, 'Stockage SSD', 'Samsung 990 PRO 2TB', 'Samsung', '990 PRO', 'M.2 NVMe PCIe 4.0, 2TB, 7450/6900 MB/s', 189.99, 'SSD ultra-rapide pour développeurs. Performances PCIe 4.0 maximales pour compilation rapide, temps de chargement réduits des IDE et gestion efficace des repos Git volumineux.'),
(4, 'Stockage SSD', 'WD Black SN850X 2TB', 'Western Digital', 'Black SN850X', 'M.2 NVMe PCIe 4.0, 2TB, 7300/6600 MB/s', 179.99, 'SSD haute performance pour charges de travail intensives. Excellent pour infrastructures systèmes et virtualisation.'),
(4, 'Stockage SSD', 'Samsung 980 PRO 1TB', 'Samsung', '980 PRO', 'M.2 NVMe PCIe 4.0, 1TB, 7000/5000 MB/s', 119.99, 'Capacité 1TB adaptée au design. Vitesses élevées pour le chargement rapide de fichiers volumineux (PSD, vidéos).'),
(4, 'Stockage SSD', 'Crucial P3 Plus 1TB', 'Crucial', 'P3 Plus', 'M.2 NVMe PCIe 4.0, 1TB, 5000/3600 MB/s', 79.99, 'SSD performant à prix accessible pour postes bureautiques. 1TB suffisant pour documents et applications standards.'),
(4, 'Stockage SSD', 'Kingston NV2 500GB', 'Kingston', 'NV2', 'M.2 NVMe PCIe 4.0, 500GB, 3500/2100 MB/s', 44.99, 'Solution économique pour postes d''entrée de gamme. 500GB suffisant pour OS, applications et documents courants.');

-- STOCKAGE HDD (Sauvegarde)
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(4, 'Stockage HDD', 'Seagate IronWolf 4TB', 'Seagate', 'IronWolf', 'SATA 3.5", 4TB, 5900 RPM, 256MB Cache', 119.99, 'Disque dur pour sauvegarde locale. Capacité importante pour archives de projets et backups incrémentaux.'),
(4, 'Stockage HDD', 'WD Blue 2TB', 'Western Digital', 'Blue', 'SATA 3.5", 2TB, 5400 RPM, 256MB Cache', 59.99, 'Disque économique pour stockage secondaire et archives. Complément au SSD principal.');

-- CARTES GRAPHIQUES
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(5, 'Carte Graphique', 'NVIDIA RTX 4070 12GB', 'NVIDIA', 'GeForce RTX 4070', '12GB GDDR6X, 5888 CUDA cores, DLSS 3', 629.99, 'Carte graphique puissante pour le design graphique. Accélération GPU pour Photoshop, Premiere Pro, After Effects. Ray tracing pour prévisualisation 3D.'),
(5, 'Carte Graphique', 'AMD Radeon RX 7700 XT 12GB', 'AMD', 'Radeon RX 7700 XT', '12GB GDDR6, 3456 Stream processors', 449.99, 'Alternative AMD performante pour applications créatives. Excellente pour design 2D/3D et rendu graphique.'),
(5, 'Carte Graphique', 'NVIDIA RTX 4060 8GB', 'NVIDIA', 'GeForce RTX 4060', '8GB GDDR6, 3072 CUDA cores, DLSS 3', 329.99, 'Carte graphique polyvalente pour développement et double écran. Support CUDA pour développement IA/ML. Encodage vidéo matériel pour visioconférences.'),
(5, 'Carte Graphique Intégrée', 'Intel UHD Graphics 770', 'Intel', 'UHD 770', 'Graphiques intégrés processeur Intel', 0.00, 'Graphiques intégrés suffisants pour bureautique, navigation web et applications de gestion. Économie sur poste sans besoin graphique.');

-- ALIMENTATIONS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(6, 'Alimentation', 'Corsair RM850x 850W', 'Corsair', 'RM850x', '850W, 80+ Gold, Modulaire complet', 139.99, 'Alimentation puissante pour stations hautes performances. Certification Gold pour efficacité énergétique. Modulaire pour gestion câbles optimale.'),
(6, 'Alimentation', 'be quiet! Straight Power 11 750W', 'be quiet!', 'Straight Power 11', '750W, 80+ Gold, Modulaire complet', 129.99, 'Alimentation silencieuse et efficace. Idéale pour environnements de travail calmes. Certification Gold et câbles modulaires.'),
(6, 'Alimentation', 'Seasonic Focus GX-650 650W', 'Seasonic', 'Focus GX-650', '650W, 80+ Gold, Semi-modulaire', 99.99, 'Alimentation fiable pour configurations moyennes. 650W suffisant pour la majorité des configurations sans GPU haute performance.'),
(6, 'Alimentation', 'Cooler Master MWE Gold 550W', 'Cooler Master', 'MWE Gold 550', '550W, 80+ Gold, Non modulaire', 69.99, 'Solution économique certifiée Gold. 550W adapté aux configurations bureautiques avec graphiques intégrés ou GPU entrée de gamme.');

-- BOÎTIERS
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(7, 'Boîtier', 'Fractal Design Define 7', 'Fractal Design', 'Define 7', 'ATX, E-ATX, insonorisation, 9 emplacements 3.5"', 169.99, 'Boîtier premium insonorisé pour environnement professionnel silencieux. Excellente gestion thermique et câbles. Support multi-disques.'),
(7, 'Boîtier', 'Corsair 4000D Airflow', 'Corsair', '4000D Airflow', 'ATX, Mesh frontal, 2 ventilateurs 120mm inclus', 104.99, 'Boîtier moderne avec excellent flux d''air. Design sobre professionnel. Bon rapport qualité/prix.'),
(7, 'Boîtier', 'NZXT H510', 'NZXT', 'H510', 'ATX, Design minimaliste, Gestion câbles intégrée', 89.99, 'Boîtier compact au design épuré. Parfait pour environnement de bureau moderne. Facile à assembler.'),
(7, 'Boîtier', 'be quiet! Pure Base 500', 'be quiet!', 'Pure Base 500', 'ATX, Insonorisation, 2 ventilateurs Pure Wings 2', 79.99, 'Boîtier silencieux économique. Insonorisation pour environnement calme. Construction solide.');

-- REFROIDISSEMENT
INSERT INTO composants_pc (id_categorie, type_composant, nom_composant, marque, modele, specifications, prix_unitaire, justification) VALUES
(8, 'Refroidissement CPU', 'Noctua NH-D15 chromax.black', 'Noctua', 'NH-D15', 'Ventirad double tour, 2x140mm, silence extrême', 109.99, 'Refroidissement air ultra-performant et silencieux. Idéal pour processeurs haute performance sous charge intensive. Fiabilité légendaire.'),
(8, 'Refroidissement CPU', 'be quiet! Dark Rock Pro 4', 'be quiet!', 'Dark Rock Pro 4', 'Ventirad double tour, 135mm, silence optimisé', 89.99, 'Excellent refroidissement silencieux. Design noir élégant. Performance thermique élevée pour workstations.'),
(8, 'Refroidissement CPU', 'Arctic Freezer 34 eSports DUO', 'Arctic', 'Freezer 34 eSports DUO', 'Ventirad bi-ventilateur, 120mm', 44.99, 'Solution abordable et efficace pour processeurs moyens. Bon rapport performance/prix/silence.'),
(8, 'Refroidissement CPU', 'Cooler Master Hyper 212 Black Edition', 'Cooler Master', 'Hyper 212', 'Ventirad tour simple, 120mm', 34.99, 'Refroidissement économique fiable. Classique éprouvé pour configurations standards.'),
(8, 'Refroidissement Watercooling', 'Corsair iCUE H150i Elite LCD', 'Corsair', 'iCUE H150i Elite LCD', 'AIO 360mm, RGB, LCD', 289.99, 'Watercooling haut de gamme avec écran LCD. Performance maximale pour overclocking. Esthétique premium pour direction.');

-- =====================================================
-- INSERTION DES DONNÉES: PÉRIPHÉRIQUES
-- =====================================================

-- ÉCRANS
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(1, 'Écran Principal', 'Dell UltraSharp U2723DE 27"', 'Dell', 'U2723DE', '27", QHD (2560x1440), IPS, USB-C 90W, Hub USB', 549.99, 'Écran professionnel haute qualité pour développeurs. Résolution QHD pour plus d''espace code. USB-C avec Power Delivery pour connexion unique laptop/dock.', FALSE),
(1, 'Écran Principal', 'BenQ SW271C 27"', 'BenQ', 'SW271C', '27", 4K UHD, IPS, 99% Adobe RGB, calibrage matériel', 899.99, 'Écran professionnel pour designers graphiques. Calibrage colorimétrique précis. Couverture Adobe RGB complète pour travail print.', FALSE),
(1, 'Écran Principal', 'LG 27UK850-W 27"', 'LG', '27UK850-W', '27", 4K UHD, IPS, HDR10, USB-C', 449.99, 'Écran 4K polyvalent pour design et multimédia. HDR pour preview contenu. USB-C pratique.', FALSE),
(1, 'Écran Secondaire', 'Dell P2422H 24"', 'Dell', 'P2422H', '24", Full HD (1920x1080), IPS, pivot', 179.99, 'Écran secondaire économique et fiable. Pivot pour orientation portrait (code, documents). Dalle IPS pour angles de vision.', FALSE),
(1, 'Écran Standard', 'ASUS VA24EHE 24"', 'ASUS', 'VA24EHE', '24", Full HD, IPS, Eye Care', 129.99, 'Écran standard pour bureautique. Technologies Eye Care pour confort visuel longue durée. Bon rapport qualité/prix.', FALSE);

-- ÉCRANS ACCESSIBILITÉ (Handicap visuel)
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(1, 'Écran Accessibilité', 'Samsung ViewFinity S8 32"', 'Samsung', 'ViewFinity S8', '32", 4K UHD, lecteur d''écran intégré, contraste élevé', 699.99, 'Écran grande taille avec fonctions d''accessibilité intégrées. Taille 32" et 4K permettent grossissement important sans perte qualité. Contraste élevé pour malvoyants.', TRUE),
(1, 'Écran Accessibilité', 'Dell UltraSharp 27" High Contrast', 'Dell', 'UltraSharp U2722D', '27", QHD, contraste 1000:1, luminosité 350cd/m²', 449.99, 'Écran avec contraste et luminosité optimisés pour déficience visuelle. Compatible logiciels de grossissement.', TRUE);

-- CLAVIERS
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Clavier', 'Logitech MX Keys Advanced', 'Logitech', 'MX Keys', 'Sans fil, rétroéclairé, touches concaves, multi-device', 119.99, 'Clavier premium pour développeurs. Rétroéclairage intelligent. Frappe précise et silencieuse. Multi-device pour switcher laptop/desktop.', FALSE),
(2, 'Clavier', 'Keychron K8 Pro', 'Keychron', 'K8 Pro', 'Mécanique, sans fil, hot-swap, Mac/Windows', 119.99, 'Clavier mécanique programmable pour développeurs. Hot-swap pour personnalisation switches. Compatible Mac et Windows.', FALSE),
(2, 'Clavier', 'Microsoft Sculpt Ergonomic', 'Microsoft', 'Sculpt Ergonomic', 'Ergonomique, sans fil, repose-poignet', 89.99, 'Clavier ergonomique pour confort longue durée. Design courbé naturel. Réduit fatigue poignets et avant-bras.', FALSE),
(2, 'Clavier', 'Logitech K780 Multi-Device', 'Logitech', 'K780', 'Sans fil, multi-device, pavé numérique, support tablette', 79.99, 'Clavier polyvalent pour bureautique. Pavé numérique pour saisie données. Support intégré tablette/smartphone.', FALSE),
(2, 'Clavier', 'Logitech K270', 'Logitech', 'K270', 'Sans fil, compact, plug-and-play', 24.99, 'Clavier basique économique pour postes standards. Fiable et simple.', FALSE);

-- CLAVIERS ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Clavier Accessibilité', 'MaxiAids Clavier grands caractères', 'MaxiAids', 'Large Print', 'Clavier grands caractères contrastés, rétroéclairé, USB', 89.99, 'Clavier avec caractères 3x plus grands. Contraste jaune sur noir ou blanc sur noir. Rétroéclairage LED pour améliorer visibilité.', TRUE),
(2, 'Clavier Accessibilité', 'Clevy Clavier contraste élevé', 'Clevy', 'Contrast II', 'Touches jaunes/noires contrastées, grands caractères', 69.99, 'Clavier très contrasté pour malvoyants. Touches larges espacées. Frappe tactile claire.', TRUE);

-- SOURIS
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Souris', 'Logitech MX Master 3S', 'Logitech', 'MX Master 3S', 'Sans fil, 8000 DPI, boutons programmables, scroll horizontal', 109.99, 'Souris professionnelle haut de gamme. Précision 8000 DPI pour double écran 4K. Boutons programmables pour productivité. Ergonomie premium.', FALSE),
(2, 'Souris', 'Logitech MX Vertical', 'Logitech', 'MX Vertical', 'Ergonomique verticale, sans fil, 4000 DPI, réduction pression 10%', 99.99, 'Souris ergonomique verticale. Réduit tension musculaire et syndrome canal carpien. Idéale pour usage intensif prolongé.', FALSE),
(2, 'Souris', 'Logitech M720 Triathlon', 'Logitech', 'M720 Triathlon', 'Sans fil, multi-device, 1000 DPI, 24 mois autonomie', 44.99, 'Souris polyvalente multi-appareils. Switch rapide entre 3 devices. Excellente autonomie batterie.', FALSE),
(2, 'Souris', 'Logitech M185', 'Logitech', 'M185', 'Sans fil, compact, plug-and-play, 12 mois autonomie', 12.99, 'Souris basique fiable pour bureautique standard. Économique et durable.', FALSE);

-- SOURIS ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(2, 'Souris Accessibilité', 'Kensington Expert Mouse Trackball', 'Kensington', 'Expert Mouse', 'Trackball filaire, 4 boutons, repose-poignets', 119.99, 'Trackball ergonomique stationnaire. Réduit mouvements bras. Précis pour malvoyants avec logiciels de grossissement. Boutons larges faciles à localiser.', TRUE),
(2, 'Souris Accessibilité', 'Contactic Joystick adapté', 'Contactic', 'Joystick Mouse', 'Joystick adapté, boutons grands format, USB', 149.99, 'Dispositif de pointage alternatif pour personnes avec difficultés motrices. Joystick précis avec boutons facilement activables.', TRUE);

-- WEBCAMS
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(3, 'Webcam', 'Logitech Brio 4K Pro', 'Logitech', 'Brio 4K', '4K UHD, HDR, autofocus, correction lumière', 199.99, 'Webcam professionnelle 4K pour visioconférences haute qualité. HDR et correction lumière automatique. Field of view ajustable.', FALSE),
(3, 'Webcam', 'Logitech C920 HD Pro', 'Logitech', 'C920', '1080p Full HD, autofocus, micro stéréo', 74.99, 'Webcam standard professionnelle 1080p. Excellent rapport qualité/prix. Micro intégré correct pour réunions.', FALSE),
(3, 'Webcam', 'Logitech C270', 'Logitech', 'C270', '720p HD, micro intégré, USB', 29.99, 'Webcam basique pour visioconférences occasionnelles. Économique et fonctionnelle.', FALSE);

-- CASQUES/MICROS
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(3, 'Casque', 'Jabra Evolve2 75', 'Jabra', 'Evolve2 75', 'Bluetooth + USB-A, ANC, micro antibruit, 36h autonomie', 299.99, 'Casque professionnel premium avec réduction bruit active. Idéal open space. Micro antibruit IA pour appels clairs. Multi-connexion.', FALSE),
(3, 'Casque', 'Logitech Zone Wireless Plus', 'Logitech', 'Zone Wireless Plus', 'Bluetooth + USB-A, micro antibruit, certifié Teams/Zoom', 229.99, 'Casque pro certifié pour visioconférences. Commandes intuitives. Qualité audio excellente appels et multimédia.', FALSE),
(3, 'Casque', 'Logitech H390 USB', 'Logitech', 'H390', 'USB, micro antibruit, commandes inline, confortable', 44.99, 'Casque filaire économique pour appels fréquents. Confortable pour port prolongé. Commandes accessibles.', FALSE);

-- CASQUES ACCESSIBILITÉ
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(3, 'Casque Accessibilité', 'Sennheiser RS 175 RF', 'Sennheiser', 'RS 175 RF', 'Sans fil RF, amplification réglable, modes audition', 279.99, 'Casque TV/PC amplifié pour malentendants. Transmission RF sans compression. Modes auditifs personnalisables. Volume indépendant.', TRUE);

-- IMPRIMANTES
INSERT INTO peripheriques (id_categorie_periph, type_peripherique, nom_peripherique, marque, modele, specifications, prix_unitaire, justification, accessibilite) VALUES
(4, 'Imprimante', 'HP LaserJet Pro MFP M479fdw', 'HP', 'LaserJet M479fdw', 'Laser couleur, multifonction, recto-verso auto, réseau', 549.99, 'Multifonction laser couleur professionnelle. Rapide et économique pour volume moyen. Scan réseau pour partage documents.', FALSE),
(4, 'Imprimante', 'Brother MFC-L3770CDW', 'Brother', 'MFC-L3770CDW', 'Laser couleur, multifonction, WiFi, NFC, 250 feuilles', 399.99, 'Imprimante couleur complète pour bureau. Chargeur auto documents. Coût page faible.', FALSE),
(4, 'Imprimante', 'HP LaserJet Pro M404dn', 'HP', 'LaserJet M404dn', 'Laser mono, 38 ppm, recto-verso, réseau', 279.99, 'Imprimante laser monochrome rapide. Idéale pour documents texte volume important. Économique.', FALSE),
(4, 'Imprimante', 'Epson EcoTank ET-4850', 'Epson', 'EcoTank ET-4850', 'Jet d''encre, réservoirs rechargeables, multifonction, A4', 449.99, 'Imprimante éco-responsable sans cartouches. Coût page très faible. Idéale usage fréquent.', FALSE);

-- =====================================================
-- INSERTION DES DONNÉES: LOGICIELS
-- =====================================================

-- SYSTÈMES D'EXPLOITATION
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Système d''exploitation', 'Windows 11 Pro', 'Microsoft', '23H2', 199.99, 'OEM/Volume', 'OS professionnel pour entreprise. Gestion domaine, BitLocker, Hyper-V, Remote Desktop. Support long terme et sécurité renforcée.'),
('Système d''exploitation', 'Windows 11 Pro for Workstations', 'Microsoft', '23H2', 299.99, 'Retail', 'Version optimisée pour stations de travail hautes performances. Support processeurs serveur, ReFS, SMB Direct. Pour développement et infra.'),
('Système d''exploitation', 'Ubuntu Desktop 24.04 LTS', 'Canonical', '24.04 LTS', 0.00, 'Open Source', 'Distribution Linux gratuite pour développement. Support long terme 5 ans. Environnement de développement natif pour web/backend.');

-- SUITES BUREAUTIQUES
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Suite bureautique', 'Microsoft 365 Business Standard', 'Microsoft', 'Cloud', 129.99, 'Abonnement annuel', 'Suite complète Office + services cloud. Collaboration Teams, Exchange, OneDrive 1TB. Idéal pour bureautique moderne et travail hybride.'),
('Suite bureautique', 'Microsoft 365 Apps for Business', 'Microsoft', 'Cloud', 99.99, 'Abonnement annuel', 'Applications Office uniquement. Word, Excel, PowerPoint, Outlook. Sans services cloud avancés. Économique pour postes standards.'),
('Suite bureautique', 'LibreOffice', 'The Document Foundation', '24.2', 0.00, 'Open Source', 'Suite bureautique open source gratuite. Compatible formats Microsoft Office. Writer, Calc, Impress. Alternative économique viable.');

-- ANTIVIRUS ET SÉCURITÉ
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Sécurité', 'Bitdefender GravityZone Business Security', 'Bitdefender', 'Cloud', 59.99, 'Abonnement annuel par poste', 'Solution antivirus professionnelle avec gestion centralisée. Protection endpoint, anti-ransomware, firewall. Console cloud pour administration.'),
('Sécurité', 'Kaspersky Endpoint Security for Business', 'Kaspersky', 'Select', 54.99, 'Abonnement annuel par poste', 'Protection complète endpoints. Détection comportementale avancée, contrôle applications, chiffrement. Gestion centralisée.'),
('Sécurité', 'Windows Defender pour Entreprises', 'Microsoft', 'Intégré', 0.00, 'Inclus Windows', 'Antivirus intégré Windows suffisant pour usage basique. Gratuit mais moins de fonctionnalités gestion entreprise.'),
('Sécurité', 'Avast Business Antivirus Pro', 'Avast', 'Cloud', 49.99, 'Abonnement annuel par poste', 'Solution antivirus professionnelle économique. Protection temps réel, sandbox, firewall. Console web simple.');

-- LOGICIELS DE DÉVELOPPEMENT
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Développement', 'Visual Studio Professional 2022', 'Microsoft', '2022', 499.00, 'Licence perpétuelle', 'IDE professionnel complet pour développement .NET, C++, Python. Debugging avancé, profiling, collaboration. Standard industrie.'),
('Développement', 'JetBrains IntelliJ IDEA Ultimate', 'JetBrains', '2024.3', 199.00, 'Abonnement annuel', 'IDE Java premium avec support frameworks modernes (Spring, Jakarta EE). Refactoring intelligent, outils DB intégrés.'),
('Développement', 'Visual Studio Code', 'Microsoft', 'Latest', 0.00, 'Open Source', 'Éditeur de code gratuit et extensible. Support multi-langages via extensions. Léger et performant. Intégration Git native.'),
('Développement', 'Docker Desktop', 'Docker Inc.', 'Latest', 0.00, 'Gratuit (<250 employés)', 'Plateforme containerisation pour développement moderne. Isolation environnements, déploiement cohérent. Essentiel DevOps.'),
('Développement', 'Git + GitHub', 'GitHub', 'Latest', 0.00, 'Open Source / Freemium', 'Contrôle version distribué standard industrie. Collaboration code, CI/CD, gestion projet. GitHub gratuit usage personnel.');

-- LOGICIELS DESIGN
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Design', 'Adobe Creative Cloud All Apps', 'Adobe', 'CC 2024', 719.88, 'Abonnement annuel', 'Suite complète Adobe : Photoshop, Illustrator, InDesign, XD, Premiere Pro, After Effects. Standard industrie design graphique et vidéo.'),
('Design', 'Figma Professional', 'Figma', 'Cloud', 144.00, 'Abonnement annuel', 'Outil design d''interface cloud collaboratif. Prototypage interactif, design system, collaboration temps réel. Moderne et efficace.'),
('Design', 'Adobe Creative Cloud Express', 'Adobe', 'CC Express', 119.88, 'Abonnement annuel', 'Version allégée Adobe pour création graphique rapide. Templates, images stock. Suffisant pour marketing non-spécialisé.'),
('Design', 'Affinity Designer 2', 'Serif', 'V2', 74.99, 'Licence perpétuelle', 'Alternative économique Illustrator. Achat unique sans abonnement. Performances excellentes, compatible formats Adobe.'),
('Design', 'GIMP', 'GIMP Team', '2.10', 0.00, 'Open Source', 'Éditeur d''images open source gratuit. Alternative Photoshop pour retouche basique. Communauté active, plugins nombreux.');

-- LOGICIELS INFRASTRUCTURE
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Infrastructure', 'VMware Workstation Pro', 'VMware', '17', 199.99, 'Licence perpétuelle', 'Virtualisation desktop professionnelle. Création VMs Windows/Linux, snapshots, clones. Test environnements isolés.'),
('Infrastructure', 'Veeam Backup & Replication', 'Veeam', 'v12', 449.00, 'Licence par workload', 'Solution backup professionnelle VMs et serveurs physiques. Restauration rapide, réplication site distant. Fiabilité éprouvée.'),
('Infrastructure', 'Wireshark', 'Wireshark Foundation', 'Latest', 0.00, 'Open Source', 'Analyseur protocoles réseau open source. Debugging réseau, analyse trafic, détection problèmes. Outil essentiel admin réseau.'),
('Infrastructure', 'PuTTY + WinSCP', 'Simon Tatham', 'Latest', 0.00, 'Open Source', 'Client SSH/Telnet et transfert fichiers SFTP gratuits. Administration serveurs Linux à distance. Légers et fiables.');

-- LOGICIELS ACCESSIBILITÉ
INSERT INTO logiciels (type_logiciel, nom_logiciel, editeur, version, prix_unitaire, type_licence, justification) VALUES
('Accessibilité', 'JAWS Professional', 'Freedom Scientific', '2024', 1095.00, 'Licence perpétuelle', 'Lecteur d''écran professionnel leader pour aveugles. Synthèse vocale qualité, support braille, macros personnalisables. Standard professionnel.'),
('Accessibilité', 'ZoomText Magnifier/Reader', 'Freedom Scientific', '2024', 599.00, 'Licence perpétuelle', 'Logiciel grossissement écran avec lecture vocale. Grossissement jusqu''à 60x, couleurs contrastées, suivi focus. Pour malvoyants.'),
('Accessibilité', 'NVDA (NonVisual Desktop Access)', 'NV Access', 'Latest', 0.00, 'Open Source', 'Lecteur d''écran open source gratuit pour Windows. Alternative viable JAWS. Synthèse vocale, braille. Communauté active.'),
('Accessibilité', 'Windows Magnifier', 'Microsoft', 'Intégré', 0.00, 'Inclus Windows', 'Loupe Windows intégrée. Grossissement écran basique gratuit. Modes plein écran, lentille, ancré. Suffisant pour déficience visuelle légère.');

-- =====================================================
-- CONFIGURATIONS PAR MÉTIER
-- =====================================================

-- ===== 1. DÉVELOPPEMENT LOGICIEL (15 postes) =====
-- Configuration haute performance pour compilation, VMs, containers

-- Composants PC - Développement Logiciel
INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
-- Config 1 : Station haute performance (5 postes seniors)
(1, 1, 1, 'essentiel', 'AMD Ryzen 9 7950X3D pour développeurs seniors - Projets complexes'),
(1, 2, 1, 'essentiel', 'Carte mère X670E - Support DDR5 et évolutivité'),
(1, 6, 1, 'essentiel', '64GB DDR5-6000 - Multiples VMs et conteneurs Docker'),
(1, 10, 1, 'essentiel', 'SSD 2TB Samsung 990 PRO - Rapidité compilation'),
(1, 14, 1, 'recommande', 'HDD 4TB pour backups locaux projets'),
(1, 18, 1, 'recommande', 'RTX 4060 8GB - Support CUDA pour ML/AI'),
(1, 22, 1, 'essentiel', 'Alimentation 850W - Marge confortable'),
(1, 26, 1, 'recommande', 'Boîtier Fractal Define 7 - Silencieux'),
(1, 30, 1, 'essentiel', 'Noctua NH-D15 - Refroidissement premium'),

-- Config 2 : Station performance (10 postes juniors/intermédiaires)
(1, 2, 1, 'essentiel', 'Intel i7-14700K pour développeurs juniors/mid'),
(1, 4, 1, 'essentiel', 'Carte mère B650 - Bon rapport qualité/prix'),
(1, 7, 1, 'essentiel', '64GB DDR5-5600 - Capacité suffisante'),
(1, 11, 1, 'essentiel', 'SSD 2TB WD Black SN850X'),
(1, 14, 1, 'optionnel', 'HDD 4TB backup'),
(1, 18, 1, 'recommande', 'RTX 4060 pour développement GPU'),
(1, 23, 1, 'essentiel', 'Alimentation 750W'),
(1, 27, 1, 'essentiel', 'Boîtier Corsair 4000D'),
(1, 31, 1, 'essentiel', 'be quiet! Dark Rock Pro 4');

-- Périphériques - Développement Logiciel
INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(1, 1, 2, 'essentiel', 'Double écran Dell U2723DE 27" QHD pour code'),
(1, 6, 1, 'essentiel', 'Clavier Logitech MX Keys - Frappe confortable'),
(1, 11, 1, 'essentiel', 'Souris Logitech MX Master 3S - Productivité'),
(1, 15, 1, 'recommande', 'Webcam Logitech C920 - Réunions teams'),
(1, 17, 1, 'recommande', 'Casque Jabra Evolve2 75 - Focus et réunions');

-- Logiciels - Développement Logiciel
INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(1, 1, 1, 'essentiel', 'Windows 11 Pro - OS principal'),
(1, 3, 1, 'recommande', 'Ubuntu Desktop - Dual boot pour dev natif Linux'),
(1, 4, 1, 'essentiel', 'Microsoft 365 Business Standard - Collaboration'),
(1, 7, 1, 'essentiel', 'Bitdefender - Protection endpoint'),
(1, 10, 1, 'essentiel', 'Visual Studio Professional 2022 - IDE principal .NET'),
(1, 11, 1, 'recommande', 'IntelliJ IDEA Ultimate - Développement Java'),
(1, 12, 1, 'essentiel', 'Visual Studio Code - Éditeur polyvalent'),
(1, 13, 1, 'essentiel', 'Docker Desktop - Containerisation'),
(1, 14, 1, 'essentiel', 'Git + GitHub - Contrôle version');

-- ===== 2. GESTION DES INFRASTRUCTURES SYSTÈMES ET RÉSEAU (5 postes) =====
-- Configuration serveur-grade pour virtualisation et admin réseau

INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(2, 1, 1, 'essentiel', 'AMD Ryzen 9 pour VMs multiples'),
(2, 2, 1, 'essentiel', 'Carte mère X670E - Stabilité maximale'),
(2, 6, 1, 'essentiel', '64GB DDR5 minimum pour hyperviseur'),
(2, 10, 1, 'essentiel', 'SSD 2TB ultra-rapide pour VMs'),
(2, 14, 1, 'essentiel', 'HDD 4TB pour stockage configs et backups'),
(2, 19, 1, 'optionnel', 'GPU basique si besoin graphique'),
(2, 22, 1, 'essentiel', 'Alimentation 850W robuste'),
(2, 26, 1, 'recommande', 'Boîtier professionnel silencieux'),
(2, 30, 1, 'essentiel', 'Refroidissement premium pour charge constante');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(2, 1, 2, 'essentiel', 'Double écran 27" pour surveillance systèmes'),
(2, 7, 1, 'essentiel', 'Clavier Keychron K8 Pro - Configuration serveurs'),
(2, 11, 1, 'essentiel', 'Souris MX Master 3S'),
(2, 15, 1, 'recommande', 'Webcam réunions'),
(2, 17, 1, 'recommande', 'Casque qualité pour support');

INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(2, 2, 1, 'essentiel', 'Windows 11 Pro Workstations - Hyper-V natif'),
(2, 3, 1, 'essentiel', 'Ubuntu - Administration serveurs Linux'),
(2, 4, 1, 'essentiel', 'Microsoft 365 - Collaboration'),
(2, 8, 1, 'essentiel', 'Kaspersky Endpoint Security'),
(2, 21, 1, 'essentiel', 'VMware Workstation - Lab virtualisation'),
(2, 22, 1, 'essentiel', 'Veeam Backup - Stratégie sauvegarde'),
(2, 23, 1, 'essentiel', 'Wireshark - Analyse réseau'),
(2, 24, 1, 'essentiel', 'PuTTY/WinSCP - Admin SSH');

-- ===== 3. DESIGN UX/UI (5 postes) =====
-- Configuration créative avec GPU puissant

INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(3, 3, 1, 'essentiel', 'AMD Ryzen 7 7800X3D - Performance créative'),
(3, 4, 1, 'essentiel', 'Carte mère B650 avec WiFi'),
(3, 8, 1, 'essentiel', '32GB DDR5 pour Adobe Suite'),
(3, 12, 1, 'essentiel', 'SSD 1TB Samsung 980 PRO'),
(3, 15, 1, 'optionnel', 'HDD 2TB archives projets'),
(3, 16, 1, 'essentiel', 'RTX 4070 12GB - Accélération GPU Adobe'),
(3, 23, 1, 'essentiel', 'Alimentation 750W'),
(3, 27, 1, 'essentiel', 'Boîtier Corsair 4000D'),
(3, 31, 1, 'recommande', 'Refroidissement be quiet!');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(3, 2, 1, 'essentiel', 'BenQ SW271C 27" 4K - Écran calibré Adobe RGB'),
(3, 4, 1, 'recommande', 'Dell P2422H secondaire - Références'),
(3, 8, 1, 'essentiel', 'Clavier Microsoft Sculpt - Confort design'),
(3, 11, 1, 'essentiel', 'Souris MX Master 3S - Précision'),
(3, 15, 1, 'recommande', 'Webcam présentations clients'),
(3, 18, 1, 'recommande', 'Casque Logitech Zone - Calls clients');

INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(3, 1, 1, 'essentiel', 'Windows 11 Pro'),
(3, 4, 1, 'essentiel', 'Microsoft 365 Standard'),
(3, 7, 1, 'essentiel', 'Bitdefender protection'),
(3, 15, 1, 'essentiel', 'Adobe Creative Cloud All Apps - Suite complète'),
(3, 16, 1, 'essentiel', 'Figma Professional - Design UI/UX'),
(3, 19, 1, 'recommande', 'Affinity Designer - Alternative économique');

-- ===== 4. MARKETING ET VENTE (10 postes) =====
-- Configuration bureautique renforcée multimédia

INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(4, 4, 1, 'essentiel', 'Intel i5-14600K - Multitâche efficace'),
(4, 5, 1, 'essentiel', 'Carte mère B760 DDR4 - Économique'),
(4, 9, 1, 'essentiel', '32GB DDR4 - Navigation intensive + présentations'),
(4, 13, 1, 'essentiel', 'SSD 1TB Crucial P3'),
(4, 20, 1, 'essentiel', 'Intel UHD 770 - Graphiques intégrés suffisants'),
(4, 24, 1, 'essentiel', 'Alimentation 650W'),
(4, 28, 1, 'essentiel', 'Boîtier NZXT H510'),
(4, 32, 1, 'recommande', 'Refroidissement Arctic Freezer');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(4, 3, 1, 'essentiel', 'LG 27UK850 27" 4K - Présentations et contenus'),
(4, 4, 1, 'optionnel', 'Écran secondaire si besoin'),
(4, 9, 1, 'essentiel', 'Clavier Logitech K780 - Multi-device'),
(4, 13, 1, 'essentiel', 'Souris Logitech M720 - Polyvalente'),
(4, 16, 1, 'essentiel', 'Webcam Logitech Brio 4K - Présence professionnelle'),
(4, 18, 1, 'essentiel', 'Casque Logitech Zone - Appels clients fréquents'),
(4, 21, 1, 'recommande', 'Imprimante HP LaserJet couleur partagée');

INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(4, 1, 1, 'essentiel', 'Windows 11 Pro'),
(4, 4, 1, 'essentiel', 'Microsoft 365 Business Standard - Outils collaboration'),
(4, 7, 1, 'essentiel', 'Bitdefender protection'),
(4, 17, 1, 'recommande', 'Adobe CC Express - Création contenu rapide');

-- ===== 5. SUPPORT CLIENT (5 postes dont 1 adapté handicap visuel) =====
-- Configuration standard + 1 poste accessibilité

-- Configuration standard (4 postes)
INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(5, 5, 1, 'essentiel', 'AMD Ryzen 5 - Performance suffisante'),
(5, 5, 1, 'essentiel', 'Carte mère B650 basique'),
(5, 9, 1, 'essentiel', '32GB DDR4 - Multi-sessions support'),
(5, 13, 1, 'essentiel', 'SSD 1TB - Rapidité accès tickets'),
(5, 20, 1, 'essentiel', 'Graphiques intégrés'),
(5, 25, 1, 'essentiel', 'Alimentation 550W économique'),
(5, 29, 1, 'essentiel', 'Boîtier be quiet! Pure Base - Silence'),
(5, 33, 1, 'recommande', 'Refroidissement Cooler Master');

-- Périphériques standard support
INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(5, 5, 2, 'essentiel', 'Double écran ASUS 24" - Tickets + documentation'),
(5, 10, 1, 'essentiel', 'Clavier Logitech K270 standard'),
(5, 14, 1, 'essentiel', 'Souris Logitech M185 basique'),
(5, 17, 1, 'essentiel', 'Webcam C270 pour support vidéo'),
(5, 19, 1, 'essentiel', 'Casque Logitech H390 - Calls constants');

-- **POSTE ADAPTÉ HANDICAP VISUEL (1 poste)**
INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
-- Écran accessibilité
(5, (SELECT id_peripherique FROM peripheriques WHERE nom_peripherique = 'Samsung ViewFinity S8 32"'), 1, 'essentiel', '32" 4K avec fonctions accessibilité intégrées - Poste adapté'),

-- Clavier accessibilité
(5, (SELECT id_peripherique FROM peripheriques WHERE nom_peripherique = 'MaxiAids Clavier grands caractères'), 1, 'essentiel', 'Grands caractères contrastés + rétroéclairage - Poste adapté'),

-- Souris accessibilité
(5, (SELECT id_peripherique FROM peripheriques WHERE nom_peripherique = 'Kensington Expert Mouse Trackball'), 1, 'essentiel', 'Trackball ergonomique précis - Poste adapté'),

-- Casque accessibilité
(5, (SELECT id_peripherique FROM peripheriques WHERE nom_peripherique = 'Sennheiser RS 175 RF'), 1, 'recommande', 'Casque amplifié si nécessaire - Poste adapté');

-- Logiciels Support Client
INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(5, 1, 1, 'essentiel', 'Windows 11 Pro'),
(5, 5, 1, 'essentiel', 'Microsoft 365 Apps - Office uniquement'),
(5, 9, 1, 'essentiel', 'Windows Defender - Protection basique'),
(5, 12, 1, 'essentiel', 'VS Code - Scripts support');

-- **LOGICIELS ACCESSIBILITÉ (Poste adapté)**
INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(5, 25, 1, 'essentiel', 'JAWS Professional - Lecteur écran principal - Poste adapté'),
(5, 26, 1, 'recommande', 'ZoomText - Grossissement complémentaire - Poste adapté'),
(5, 27, 1, 'essentiel', 'NVDA - Lecteur écran backup gratuit - Poste adapté'),
(5, 28, 1, 'essentiel', 'Windows Magnifier - Loupe native - Poste adapté');

-- ===== 6. RESSOURCES HUMAINES ET ADMINISTRATION (5 postes) =====
-- Configuration bureautique standard

INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(6, 5, 1, 'essentiel', 'AMD Ryzen 5 - Bureautique'),
(6, 5, 1, 'essentiel', 'Carte mère B650'),
(6, 10, 1, 'essentiel', '16GB DDR4 - Suffisant bureautique'),
(6, 14, 1, 'essentiel', 'SSD 500GB Kingston NV2'),
(6, 20, 1, 'essentiel', 'Graphiques intégrés'),
(6, 25, 1, 'essentiel', 'Alimentation 550W'),
(6, 30, 1, 'essentiel', 'Boîtier be quiet!'),
(6, 33, 1, 'recommande', 'Refroidissement basique');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(6, 5, 1, 'essentiel', 'ASUS 24" Full HD - Bureautique'),
(6, 10, 1, 'essentiel', 'Clavier Logitech K270'),
(6, 14, 1, 'essentiel', 'Souris Logitech M185'),
(6, 17, 1, 'recommande', 'Webcam réunions RH'),
(6, 19, 1, 'recommande', 'Casque entretiens'),
(6, 23, 1, 'essentiel', 'Imprimante Brother MFC partagée');

INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(6, 1, 1, 'essentiel', 'Windows 11 Pro'),
(6, 4, 1, 'essentiel', 'Microsoft 365 Business Standard - Outils collaboration'),
(6, 7, 1, 'essentiel', 'Bitdefender protection');

-- ===== 7. DIRECTION (5 postes) =====
-- Configuration premium + portables

INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite, notes) VALUES
(7, 2, 1, 'essentiel', 'Intel i7-14700K - Performance executive'),
(7, 3, 1, 'essentiel', 'Carte mère Z790 premium'),
(7, 8, 1, 'essentiel', '32GB DDR5 - Multitâche'),
(7, 12, 1, 'essentiel', 'SSD 1TB Samsung 980 PRO'),
(7, 17, 1, 'recommande', 'AMD RX 7700 XT - Présentations haute qualité'),
(7, 23, 1, 'essentiel', 'Alimentation 750W'),
(7, 27, 1, 'essentiel', 'Boîtier Corsair premium'),
(7, 34, 1, 'recommande', 'Watercooling Corsair Elite - Esthétique');

INSERT INTO configurations_peripheriques (id_metier, id_peripherique, quantite, priorite, notes) VALUES
(7, 2, 1, 'essentiel', 'BenQ 27" 4K calibré - Qualité premium'),
(7, 3, 1, 'recommande', 'LG 27" 4K secondaire'),
(7, 7, 1, 'essentiel', 'Clavier Microsoft Sculpt - Ergonomie'),
(7, 11, 1, 'essentiel', 'Souris MX Master 3S - Premium'),
(7, 16, 1, 'essentiel', 'Webcam Logitech Brio 4K - Visioconférences'),
(7, 17, 1, 'essentiel', 'Casque Jabra Evolve2 75 - Qualité audio'),
(7, 21, 1, 'recommande', 'Imprimante HP LaserJet Pro couleur');

INSERT INTO configurations_logiciels (id_metier, id_logiciel, quantite, priorite, notes) VALUES
(7, 1, 1, 'essentiel', 'Windows 11 Pro'),
(7, 4, 1, 'essentiel', 'Microsoft 365 Business Standard'),
(7, 7, 1, 'essentiel', 'Bitdefender GravityZone'),
(7, 17, 1, 'recommande', 'Adobe CC Express - Créations rapides');

-- =====================================================
-- VUES UTILES POUR CONSULTATION
-- =====================================================

-- Vue : Configuration complète par métier
CREATE OR REPLACE VIEW v_configurations_completes AS
SELECT 
    m.id_metier,
    m.nom_metier,
    m.nombre_postes,
    'Composant PC' as type_equipement,
    cc.nom_categorie as categorie,
    c.type_composant,
    c.nom_composant,
    c.marque,
    c.prix_unitaire,
    cp.quantite,
    cp.priorite,
    c.justification,
    cp.notes
FROM metiers m
JOIN configurations_pc cp ON m.id_metier = cp.id_metier
JOIN composants_pc c ON cp.id_composant = c.id_composant
JOIN categories_composants cc ON c.id_categorie = cc.id_categorie

UNION ALL

SELECT 
    m.id_metier,
    m.nom_metier,
    m.nombre_postes,
    'Périphérique' as type_equipement,
    cp.nom_categorie as categorie,
    p.type_peripherique,
    p.nom_peripherique,
    p.marque,
    p.prix_unitaire,
    cpp.quantite,
    cpp.priorite,
    p.justification,
    cpp.notes
FROM metiers m
JOIN configurations_peripheriques cpp ON m.id_metier = cpp.id_metier
JOIN peripheriques p ON cpp.id_peripherique = p.id_peripherique
JOIN categories_peripheriques cp ON p.id_categorie_periph = cp.id_categorie_periph

UNION ALL

SELECT 
    m.id_metier,
    m.nom_metier,
    m.nombre_postes,
    'Logiciel' as type_equipement,
    l.type_logiciel as categorie,
    l.type_logiciel as type_composant,
    l.nom_logiciel,
    l.editeur as marque,
    l.prix_unitaire,
    cl.quantite,
    cl.priorite,
    l.justification,
    cl.notes
FROM metiers m
JOIN configurations_logiciels cl ON m.id_metier = cl.id_metier
JOIN logiciels l ON cl.id_logiciel = l.id_logiciel
ORDER BY id_metier, type_equipement, categorie;

-- Vue : Coût total par métier
CREATE OR REPLACE VIEW v_cout_par_metier AS
SELECT 
    m.id_metier,
    m.nom_metier,
    m.nombre_postes,
    COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) as cout_composants,
    COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) as cout_peripheriques,
    COALESCE(SUM(l.prix_unitaire * cl.quantite), 0) as cout_logiciels,
    COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) + 
    COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) + 
    COALESCE(SUM(l.prix_unitaire * cl.quantite), 0) as cout_total_par_poste,
    (COALESCE(SUM(c.prix_unitaire * cp.quantite), 0) + 
     COALESCE(SUM(p.prix_unitaire * cpp.quantite), 0) + 
     COALESCE(SUM(l.prix_unitaire * cl.quantite), 0)) * m.nombre_postes as cout_total_departement
FROM metiers m
LEFT JOIN configurations_pc cp ON m.id_metier = cp.id_metier
LEFT JOIN composants_pc c ON cp.id_composant = c.id_composant
LEFT JOIN configurations_peripheriques cpp ON m.id_metier = cpp.id_metier
LEFT JOIN peripheriques p ON cpp.id_peripherique = p.id_peripherique
LEFT JOIN configurations_logiciels cl ON m.id_metier = cl.id_metier
LEFT JOIN logiciels l ON cl.id_logiciel = l.id_logiciel
GROUP BY m.id_metier, m.nom_metier, m.nombre_postes
ORDER BY cout_total_departement DESC;

-- Vue : Équipements d'accessibilité
CREATE OR REPLACE VIEW v_equipements_accessibilite AS
SELECT 
    m.nom_metier,
    p.type_peripherique,
    p.nom_peripherique,
    p.marque,
    p.prix_unitaire,
    p.justification,
    cpp.notes
FROM configurations_peripheriques cpp
JOIN peripheriques p ON cpp.id_peripherique = p.id_peripherique
JOIN metiers m ON cpp.id_metier = m.id_metier
WHERE p.accessibilite = TRUE
ORDER BY m.nom_metier;

-- =====================================================
-- REQUÊTES EXEMPLES UTILES
-- =====================================================

-- Requête 1 : Configuration complète Développement Logiciel
-- SELECT * FROM v_configurations_completes WHERE nom_metier = 'Développement logiciel';

-- Requête 2 : Coût total par métier
-- SELECT * FROM v_cout_par_metier;

-- Requête 3 : Tous les équipements d'accessibilité
-- SELECT * FROM v_equipements_accessibilite;

-- Requête 4 : Liste des logiciels par métier
-- SELECT m.nom_metier, l.nom_logiciel, l.editeur, l.prix_unitaire, cl.priorite
-- FROM configurations_logiciels cl
-- JOIN logiciels l ON cl.id_logiciel = l.id_logiciel
-- JOIN metiers m ON cl.id_metier = m.id_metier
-- ORDER BY m.nom_metier, l.type_logiciel;

-- Requête 5 : Récapitulatif budget global entreprise
-- SELECT 
--     SUM(cout_total_departement) as budget_total_entreprise,
--     COUNT(DISTINCT id_metier) as nombre_departements,
--     SUM(nombre_postes) as nombre_total_postes,
--     AVG(cout_total_par_poste) as cout_moyen_par_poste
-- FROM v_cout_par_metier;

-- =====================================================
-- FIN DU SCRIPT
-- Base de données techsolutions_config créée avec succès
-- =====================================================
