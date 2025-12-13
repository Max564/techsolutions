-- =====================================================
-- TABLE ÉQUIPEMENTS RÉSEAU - TECHSOLUTIONS
-- Routeurs, Switches, Serveurs, Firewalls, WiFi, etc.
-- =====================================================

USE techsolutions;

-- Table des catégories d'équipements réseau
CREATE TABLE IF NOT EXISTS categories_equipements_reseau (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icone VARCHAR(50),
    ordre_affichage INT DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    INDEX idx_actif (actif),
    INDEX idx_ordre (ordre_affichage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table principale des équipements réseau
CREATE TABLE IF NOT EXISTS equipements_reseau (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categorie_id INT NOT NULL,
    reference VARCHAR(50) UNIQUE NOT NULL,
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(150) NOT NULL,
    nom_commercial VARCHAR(200) NOT NULL,
    description_courte VARCHAR(300),
    description_longue TEXT,
    
    -- Spécifications techniques
    niveau_layer ENUM('Layer 2', 'Layer 3', 'Layer 4-7', 'N/A') DEFAULT 'N/A',
    nombre_ports INT,
    type_ports VARCHAR(100), -- Ex: "48x GE PoE+ + 4x SFP+ 10G"
    debit_switching VARCHAR(50), -- Ex: "160 Gbps"
    debit_throughput VARCHAR(50), -- Ex: "10 Gbps"
    support_poe BOOLEAN DEFAULT FALSE,
    budget_poe_watts INT, -- Puissance PoE totale en watts
    
    -- Pour routeurs
    debit_routage VARCHAR(50),
    support_vpn BOOLEAN DEFAULT FALSE,
    nombre_vpn_tunnels INT,
    
    -- Pour firewalls
    debit_firewall VARCHAR(50),
    debit_ips VARCHAR(50),
    debit_vpn_ipsec VARCHAR(50),
    nombre_sessions_max INT,
    support_ips_ids BOOLEAN DEFAULT FALSE,
    support_antivirus BOOLEAN DEFAULT FALSE,
    support_web_filtering BOOLEAN DEFAULT FALSE,
    
    -- Pour serveurs
    cpu VARCHAR(200),
    ram VARCHAR(100),
    stockage VARCHAR(200),
    raid VARCHAR(100),
    alimentation VARCHAR(100), -- Ex: "Double alimentation redondante"
    
    -- Pour WiFi
    standard_wifi VARCHAR(50), -- Ex: "WiFi 6 (802.11ax)"
    bandes VARCHAR(50), -- Ex: "Dual 2.4/5 GHz"
    mimo VARCHAR(20), -- Ex: "4x4"
    clients_max_par_ap INT,
    
    -- Caractéristiques générales
    format VARCHAR(50), -- Ex: "Rack 1U", "Tour", "Desktop"
    consommation_watts INT,
    temperature_fonctionnement VARCHAR(50),
    dimensions VARCHAR(100),
    poids_kg DECIMAL(6,2),
    
    -- Fonctionnalités
    fonctionnalites TEXT, -- JSON ou texte avec liste des fonctions
    ports_management VARCHAR(200),
    garantie_constructeur VARCHAR(100),
    
    -- Prix et disponibilité
    prix_achat DECIMAL(10,2) NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    tva DECIMAL(5,2) DEFAULT 20.00,
    marge_pourcentage DECIMAL(5,2),
    stock INT DEFAULT 0,
    stock_alerte INT DEFAULT 2,
    disponible BOOLEAN DEFAULT TRUE,
    delai_livraison_jours INT DEFAULT 7,
    
    -- Fournisseur
    fournisseur VARCHAR(100),
    reference_fournisseur VARCHAR(100),
    
    -- Images et documentation
    image_principale VARCHAR(255),
    images_secondaires TEXT, -- JSON array d'URLs
    fiche_technique_url VARCHAR(255),
    
    -- SEO et métadonnées
    slug VARCHAR(200) UNIQUE,
    meta_description TEXT,
    tags TEXT, -- Tags séparés par virgules
    
    -- Statistiques
    nombre_vues INT DEFAULT 0,
    nombre_devis INT DEFAULT 0,
    nombre_ventes INT DEFAULT 0,
    
    -- Dates
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME ON UPDATE CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE,
    
    -- Clés étrangères et index
    FOREIGN KEY (categorie_id) REFERENCES categories_equipements_reseau(id),
    INDEX idx_categorie (categorie_id),
    INDEX idx_marque (marque),
    INDEX idx_prix (prix_vente),
    INDEX idx_stock (stock),
    INDEX idx_actif (actif),
    INDEX idx_disponible (disponible),
    FULLTEXT INDEX idx_recherche (nom_commercial, description_courte, description_longue, marque, modele)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des accessoires et options pour équipements
CREATE TABLE IF NOT EXISTS accessoires_equipements_reseau (
    id INT PRIMARY KEY AUTO_INCREMENT,
    equipement_id INT NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    reference VARCHAR(50),
    prix_achat DECIMAL(10,2) NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    obligatoire BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (equipement_id) REFERENCES equipements_reseau(id) ON DELETE CASCADE,
    INDEX idx_equipement (equipement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des kits / bundles réseau pré-configurés
CREATE TABLE IF NOT EXISTS kits_reseau (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    type_entreprise VARCHAR(100), -- Ex: "TPE", "PME", "Grande entreprise"
    nombre_postes INT,
    prix_total DECIMAL(10,2),
    remise_pourcentage DECIMAL(5,2),
    image VARCHAR(255),
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_actif (actif),
    INDEX idx_type (type_entreprise)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison kit <-> équipements
CREATE TABLE IF NOT EXISTS kits_reseau_equipements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kit_id INT NOT NULL,
    equipement_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    FOREIGN KEY (kit_id) REFERENCES kits_reseau(id) ON DELETE CASCADE,
    FOREIGN KEY (equipement_id) REFERENCES equipements_reseau(id),
    UNIQUE KEY unique_kit_equipement (kit_id, equipement_id),
    INDEX idx_kit (kit_id),
    INDEX idx_equipement (equipement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTION DES CATÉGORIES
-- =====================================================

INSERT INTO categories_equipements_reseau (nom, description, icone, ordre_affichage) VALUES
('Firewalls', 'Pare-feux et solutions de sécurité réseau', 'shield', 1),
('Routeurs', 'Routeurs professionnels et équipements de routage', 'router', 2),
('Switches Core', 'Switches cœur de réseau haute performance', 'switch-core', 3),
('Switches Distribution', 'Switches de distribution et accès', 'switch', 4),
('Points d''Accès WiFi', 'Bornes WiFi et contrôleurs sans fil', 'wifi', 5),
('Serveurs', 'Serveurs rack et tour pour entreprise', 'server', 6),
('Stockage NAS', 'Solutions de stockage en réseau', 'storage', 7),
('Onduleurs UPS', 'Onduleurs et protection électrique', 'power', 8),
('Accessoires Réseau', 'Câbles, baies, patch panels, etc.', 'accessories', 9);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - FIREWALLS
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    debit_firewall, debit_ips, debit_vpn_ipsec, nombre_sessions_max,
    support_ips_ids, support_antivirus, support_web_filtering,
    support_vpn, nombre_vpn_tunnels,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    1, 'FW-FORTI-100F', 'Fortinet', 'FortiGate 100F', 
    'Firewall FortiGate 100F - Protection Nouvelle Génération',
    'Firewall NGFW haute performance avec IPS/IDS, antivirus et filtrage web intégré',
    'Le FortiGate 100F offre une protection complète pour les PME avec un débit firewall de 10 Gbps et des fonctions de sécurité avancées incluant IPS/IDS, antivirus gateway, filtrage web FortiGuard, anti-spam, VPN SSL (50 utilisateurs), VPN IPsec site-à-site, protection DDoS et authentification 2FA.',
    '10 Gbps', '2.4 Gbps', '9 Gbps', 2000000,
    TRUE, TRUE, TRUE,
    TRUE, 50,
    'Rack 1U', 65, '3 ans matériel',
    'IPS/IDS, Antivirus, Filtrage Web, VPN SSL (50 users), VPN IPsec, Protection DDoS, 2FA, FortiGuard',
    2800.00, 3500.00, 5, 'firewall-fortigate-100f'
),
(
    1, 'FW-FORTI-60F', 'Fortinet', 'FortiGate 60F',
    'Firewall FortiGate 60F - TPE/PME',
    'Firewall compact pour petites entreprises avec protection complète',
    'FortiGate 60F idéal pour TPE/PME jusqu''à 30 utilisateurs. Débit firewall 5 Gbps, IPS 900 Mbps, VPN SSL 20 utilisateurs.',
    '5 Gbps', '900 Mbps', '4 Gbps', 500000,
    TRUE, TRUE, TRUE,
    TRUE, 20,
    'Desktop', 35, '3 ans matériel',
    'IPS/IDS, Antivirus, Filtrage Web, VPN SSL (20 users), Protection DDoS',
    1200.00, 1800.00, 8, 'firewall-fortigate-60f'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - ROUTEURS
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    niveau_layer, nombre_ports, type_ports,
    debit_routage, support_vpn,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    2, 'RTR-CISCO-4331', 'Cisco', 'ISR 4331', 
    'Routeur Cisco ISR 4331 - Services Intégrés',
    'Routeur professionnel avec routage inter-VLAN, QoS et services avancés',
    'Le Cisco ISR 4331 offre des performances de routage jusqu''à 300 Mbps avec support de 7+ VLANs, serveur DHCP intégré, QoS avancé avec 5 niveaux de priorité, ACL sécurité et routage dynamique OSPF. Idéal pour entreprises 50-100 utilisateurs.',
    'Layer 3', 3, '3x GE + 2 slots NIM',
    '300 Mbps', TRUE,
    'Rack 1U', 90, '3 ans NBD',
    'Routage inter-VLAN, DHCP Server, QoS 5 niveaux, ACL, OSPF, VPN IPsec, 7+ VLANs',
    2200.00, 2800.00, 4, 'routeur-cisco-isr-4331'
),
(
    2, 'RTR-CISCO-4321', 'Cisco', 'ISR 4321',
    'Routeur Cisco ISR 4321 - PME',
    'Routeur services intégrés pour PME jusqu''à 50 utilisateurs',
    'Cisco ISR 4321 avec débit routage 150 Mbps, parfait pour PME. Support VLANs, DHCP, QoS, VPN.',
    'Layer 3', 3, '2x GE + 2 slots',
    '150 Mbps', TRUE,
    'Rack 1U', 75, '3 ans NBD',
    'Routage inter-VLAN, DHCP, QoS, VPN IPsec, ACL',
    1400.00, 1900.00, 6, 'routeur-cisco-isr-4321'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - SWITCHES CORE
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    niveau_layer, nombre_ports, type_ports,
    debit_switching, support_poe, budget_poe_watts,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    3, 'SW-CISCO-9300-48P', 'Cisco', 'Catalyst 9300-48P', 
    'Switch Core Cisco Catalyst 9300 - 48 Ports PoE+',
    'Switch cœur de réseau niveau 3 avec 48 ports PoE+ et 4 uplinks 10G',
    'Le Catalyst 9300-48P est le switch idéal pour le cœur de réseau des PME/ETI. 48 ports Gigabit PoE+ (740W total), 4 uplinks SFP+ 10 Gbps, switching 160 Gbps. Support complet Layer 3 avec routage, VLAN, agrégation LACP, Spanning Tree RSTP, QoS 802.1p, Port Security, IGMP Snooping. Empilable jusqu''à 8 unités via StackWise-480.',
    'Layer 3', 48, '48x GE PoE+ + 4x SFP+ 10G',
    '160 Gbps', TRUE, 740,
    'Rack 1U', 450, 'Lifetime limited',
    'Layer 3 Routing, VLAN, LACP, RSTP, QoS, Port Security, IGMP Snooping, StackWise-480',
    6800.00, 8500.00, 3, 'switch-core-catalyst-9300-48p'
),
(
    3, 'SW-CISCO-9300-24P', 'Cisco', 'Catalyst 9300-24P',
    'Switch Core Cisco Catalyst 9300 - 24 Ports PoE+',
    'Switch cœur de réseau 24 ports PoE+ pour petites structures',
    'Catalyst 9300-24P avec 24 ports GE PoE+ (435W), 4 uplinks 10G. Layer 3, empilable.',
    'Layer 3', 24, '24x GE PoE+ + 4x SFP+ 10G',
    '128 Gbps', TRUE, 435,
    'Rack 1U', 350, 'Lifetime limited',
    'Layer 3 Routing, VLAN, LACP, RSTP, QoS, Empilable',
    4500.00, 5800.00, 5, 'switch-core-catalyst-9300-24p'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - SWITCHES DISTRIBUTION
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    niveau_layer, nombre_ports, type_ports,
    debit_switching, support_poe, budget_poe_watts,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    4, 'SW-CISCO-SG350-28P', 'Cisco', 'SG350-28P', 
    'Switch Cisco SG350-28P - 28 Ports PoE',
    'Switch géré 24 ports PoE+ avec 2 uplinks Gigabit combo + 2 SFP',
    'Le SG350-28P est un switch géré niveau 3 basique parfait pour la distribution. 24 ports Gigabit PoE+ (195W total), 2 ports Gigabit/SFP combo, 2 ports SFP. Switching 56 Gbps. Support VLAN, trunking, QoS, LACP, Spanning Tree, IGMP Snooping. Idéal pour connecter les postes de travail et points d''accès WiFi.',
    'Layer 3', 28, '24x GE PoE+ + 2x combo + 2x SFP',
    '56 Gbps', TRUE, 195,
    'Rack 1U', 125, '5 ans limited',
    'Layer 3 basic, VLAN, Trunk, QoS, LACP, STP, IGMP Snooping, PoE 195W',
    950.00, 1200.00, 12, 'switch-cisco-sg350-28p'
),
(
    4, 'SW-CISCO-SG350-10', 'Cisco', 'SG350-10',
    'Switch Cisco SG350-10 - 10 Ports',
    'Switch géré 10 ports pour petits réseaux ou DMZ',
    'SG350-10 avec 8 ports GE + 2 combo GE/SFP. Switching 20 Gbps. Layer 3 basic, VLAN, QoS.',
    'Layer 3', 10, '8x GE + 2x combo GE/SFP',
    '20 Gbps', FALSE, 0,
    'Desktop/Rack', 35, '5 ans limited',
    'Layer 3 basic, VLAN, QoS, Compact',
    350.00, 450.00, 15, 'switch-cisco-sg350-10'
),
(
    4, 'SW-CISCO-SG250-26P', 'Cisco', 'SG250-26P',
    'Switch Cisco SG250-26P - 26 Ports PoE',
    'Switch smart 24 ports PoE+ entrée de gamme',
    'SG250-26P switch smart avec 24 ports GE PoE+ (180W) + 2 SFP. Switching 52 Gbps.',
    'Layer 2', 26, '24x GE PoE+ + 2x SFP',
    '52 Gbps', TRUE, 180,
    'Rack 1U', 110, '5 ans limited',
    'Layer 2 smart, VLAN, QoS, PoE 180W',
    680.00, 900.00, 10, 'switch-cisco-sg250-26p'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - WIFI
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    standard_wifi, bandes, mimo, clients_max_par_ap,
    support_poe, consommation_watts,
    format, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    5, 'WIFI-CISCO-2802I', 'Cisco', 'Aironet 2802i', 
    'Point d''Accès Cisco Aironet 2802i - WiFi 6',
    'Borne WiFi 6 professionnelle pour entreprise, dual-band, 200 clients',
    'Le Cisco Aironet 2802i est un point d''accès WiFi 6 (802.11ax) haute performance pour entreprise. Dual-band 2.4/5 GHz, MIMO 4x4, support jusqu''à 200 clients par AP. PoE 802.3at/af. Déploiement intérieur avec gestion centralisée par contrôleur ou cloud. Support des fonctions avancées: roaming rapide, authentification 802.1X, VLANs dynamiques, QoS, band steering.',
    'WiFi 6 (802.11ax)', 'Dual 2.4/5 GHz', '4x4', 200,
    TRUE, 25,
    'Plafond/Mural', '5 ans',
    'WiFi 6, Dual-band, MIMO 4x4, 200 clients, PoE, 802.1X, VLANs, QoS, Roaming',
    640.00, 800.00, 20, 'wifi-cisco-aironet-2802i'
),
(
    5, 'WIFI-CISCO-1542I', 'Cisco', 'Aironet 1542i',
    'Point d''Accès Cisco Aironet 1542i - WiFi 5',
    'Borne WiFi 5 professionnelle, bon rapport qualité/prix',
    'Aironet 1542i WiFi 5 (802.11ac Wave 2), dual-band, MIMO 2x2, 100 clients. PoE.',
    'WiFi 5 (802.11ac)', 'Dual 2.4/5 GHz', '2x2', 100,
    TRUE, 20,
    'Plafond/Mural', '5 ans',
    'WiFi 5, Dual-band, MIMO 2x2, 100 clients, PoE',
    380.00, 500.00, 25, 'wifi-cisco-aironet-1542i'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - SERVEURS
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    cpu, ram, stockage, raid, alimentation,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    6, 'SRV-DELL-R750', 'Dell', 'PowerEdge R750', 
    'Serveur Dell PowerEdge R750 - 2U Rack',
    'Serveur rack 2U haute performance pour virtualisation et bases de données',
    'Le Dell PowerEdge R750 est un serveur 2U offrant performances et flexibilité. Processeurs Intel Xeon Scalable 3ème génération, jusqu''à 256GB RAM DDR4 ECC extensible à 2TB, 8 slots de stockage hot-plug avec support RAID matériel. Connectivité réseau 2x 10GbE. Double alimentation redondante. Parfait pour virtualisation VMware/Hyper-V, bases de données SQL, Active Directory.',
    '2x Intel Xeon (configurable)', '128-256GB DDR4 ECC', '4-8x 2-4TB SSD/HDD', 'RAID 0/1/5/6/10/50/60', 'Double alimentation redondante hot-plug',
    'Rack 2U', 750, '3 ans ProSupport NBD',
    'Virtualisation, BDD, AD/DNS, File Server, iDRAC9, RAID, Hot-plug, Redondance',
    5200.00, 6500.00, 6, 'serveur-dell-poweredge-r750'
),
(
    6, 'SRV-DELL-R250', 'Dell', 'PowerEdge R250', 
    'Serveur Dell PowerEdge R250 - 1U Rack',
    'Serveur rack 1U compact pour applications légères et PME',
    'PowerEdge R250 serveur 1U entrée de gamme. Intel Xeon E-2300, jusqu''à 128GB RAM, 4 disques hot-plug. Idéal pour serveur web, mail, fichiers PME.',
    'Intel Xeon E-2300 (4-8 cœurs)', '16-64GB DDR4 ECC', '2-4x 1-2TB SSD/HDD', 'RAID 0/1/5/10', 'Simple alimentation (option redondante)',
    'Rack 1U', 350, '3 ans ProSupport NBD',
    'Web, Mail, Fichiers, App légères, iDRAC, RAID, Compact 1U',
    1900.00, 2500.00, 10, 'serveur-dell-poweredge-r250'
),
(
    6, 'SRV-HPE-DL380', 'HPE', 'ProLiant DL380 Gen10', 
    'Serveur HPE ProLiant DL380 Gen10 - 2U',
    'Serveur rack 2U polyvalent HPE pour toutes charges de travail',
    'HPE ProLiant DL380 Gen10 serveur 2U avec processeurs Intel Xeon Scalable, jusqu''à 3TB RAM, 30 disques maximum. iLO 5 management.',
    '2x Intel Xeon Scalable', '128-256GB DDR4 ECC', '4-12x 2-4TB SSD/HDD', 'HPE Smart Array RAID', 'Double alimentation redondante',
    'Rack 2U', 800, '3 ans garantie',
    'Virtualisation, BDD, Applications, iLO 5, RAID, Redondance',
    5500.00, 7200.00, 4, 'serveur-hpe-proliant-dl380-gen10'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - STOCKAGE NAS
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    cpu, ram, stockage, raid,
    nombre_ports, type_ports,
    format, consommation_watts, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    7, 'NAS-SYNOLOGY-RS1221', 'Synology', 'RackStation RS1221+', 
    'NAS Synology RS1221+ - 8 Baies Rack',
    'NAS rack 8 baies pour sauvegarde et partage de fichiers entreprise',
    'Le Synology RS1221+ est un NAS rack 1U avec 8 baies hot-swap. Processeur AMD Ryzen, 4GB RAM extensible à 32GB, 4 ports Gigabit agrégables. Support RAID 0/1/5/6/10, snapshots, réplication, sauvegarde cloud. Capacité jusqu''à 112TB brut (avec 8x 14TB). Parfait pour sauvegardes centralisées, partages de fichiers, surveillance vidéo.',
    'AMD Ryzen V1500B (4 cœurs)', '4GB DDR4 (max 32GB)', '8x baies 3.5" SATA (disques non inclus)', 'RAID 0/1/5/6/10 + SHR',
    4, '4x GbE (agrégation LACP)',
    'Rack 1U', 108, '3 ans garantie',
    'Backup, Partage fichiers, Snapshot, Réplication, Cloud sync, iSCSI, 8 baies hot-swap',
    2400.00, 3200.00, 8, 'nas-synology-rackstation-rs1221'
),
(
    7, 'NAS-QNAP-TS-873A', 'QNAP', 'TS-873A', 
    'NAS QNAP TS-873A - 8 Baies Tour',
    'NAS tour 8 baies AMD Ryzen haute performance',
    'QNAP TS-873A avec processeur AMD Ryzen V1500B, 8GB RAM (max 64GB), 8 baies 3.5"/2.5", 2 slots M.2 NVMe cache, 2x 2.5GbE + 2x GbE. RAID, virtualisation, snapshots.',
    'AMD Ryzen V1500B', '8GB DDR4 (max 64GB)', '8x baies 3.5"/2.5" + 2x M.2', 'RAID 0/1/5/6/10/50/60',
    4, '2x 2.5GbE + 2x GbE',
    'Tour', 150, '3 ans garantie',
    'Backup, Partage, Virtualisation, Snapshot, 2.5GbE, M.2 cache, 8 baies',
    2200.00, 2900.00, 6, 'nas-qnap-ts-873a'
);

-- =====================================================
-- INSERTION DES ÉQUIPEMENTS - ONDULEURS UPS
-- =====================================================

INSERT INTO equipements_reseau (
    categorie_id, reference, marque, modele, nom_commercial,
    description_courte, description_longue,
    consommation_watts, alimentation,
    format, garantie_constructeur,
    fonctionnalites,
    prix_achat, prix_vente, stock, slug
) VALUES
(
    8, 'UPS-APC-3000', 'APC', 'Smart-UPS 3000VA', 
    'Onduleur APC Smart-UPS 3000VA Rack',
    'Onduleur online 3000VA / 2700W pour protection serveurs et équipements critiques',
    'L''APC Smart-UPS 3000VA offre une protection électrique professionnelle avec technologie ligne interactive. 3000VA / 2700W, autonomie 10-30 minutes selon charge. Rack 2U avec batterie interne remplaçable à chaud. 8 prises IEC C13, carte réseau optionnelle pour management SNMP. Régulation automatique de tension AVR sans utiliser la batterie.',
    3000, 'Batterie interne remplaçable à chaud',
    'Rack 2U', '3 ans garantie',
    'Online, AVR, Hot-swap battery, LCD, USB/Série, 8x IEC C13, SNMP option',
    640.00, 800.00, 12, 'ups-apc-smart-ups-3000va'
),
(
    8, 'UPS-EATON-1500', 'Eaton', '5P 1550VA',
    'Onduleur Eaton 5P 1550VA Rack/Tour',
    'Onduleur line-interactive 1550VA pour serveurs et switches',
    'Eaton 5P 1550VA rack/tour convertible, 1550VA / 1100W, autonomie 15-30 min, LCD, AVR.',
    1550, 'Batterie remplaçable',
    'Rack 1U / Tour', '3 ans garantie',
    'Line-interactive, AVR, LCD, USB, 8 prises, Rack 1U',
    480.00, 650.00, 15, 'ups-eaton-5p-1550va'
);

-- =====================================================
-- INSERTION DES KITS RÉSEAU PRÉ-CONFIGURÉS
-- =====================================================

INSERT INTO kits_reseau (nom, description, type_entreprise, nombre_postes, prix_total, remise_pourcentage) VALUES
(
    'Kit Réseau TPE - 10 postes',
    'Solution réseau complète pour TPE: firewall FortiGate 60F, routeur Cisco 4321, switch 26 ports PoE, 2 bornes WiFi, onduleur',
    'TPE',
    10,
    8500.00,
    10.00
),
(
    'Kit Réseau PME - 50 postes',
    'Infrastructure réseau professionnelle PME: firewall FortiGate 100F, routeur Cisco 4331, switch core Catalyst 9300-24P, 2 switches distribution SG350-28P, 5 bornes WiFi 6, 3 onduleurs',
    'PME',
    50,
    22000.00,
    12.00
),
(
    'Kit Réseau Complet - 50 postes avec serveurs',
    'Solution TechSolutions complète: infrastructure réseau + 2 serveurs Dell R750 + 1 serveur Dell R250 + NAS Synology 8 baies',
    'PME',
    50,
    58000.00,
    15.00
);

-- Liaison des équipements au kit TPE
INSERT INTO kits_reseau_equipements (kit_id, equipement_id, quantite) VALUES
(1, (SELECT id FROM equipements_reseau WHERE reference = 'FW-FORTI-60F'), 1),
(1, (SELECT id FROM equipements_reseau WHERE reference = 'RTR-CISCO-4321'), 1),
(1, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-SG250-26P'), 1),
(1, (SELECT id FROM equipements_reseau WHERE reference = 'WIFI-CISCO-1542I'), 2),
(1, (SELECT id FROM equipements_reseau WHERE reference = 'UPS-EATON-1500'), 1);

-- Liaison des équipements au kit PME
INSERT INTO kits_reseau_equipements (kit_id, equipement_id, quantite) VALUES
(2, (SELECT id FROM equipements_reseau WHERE reference = 'FW-FORTI-100F'), 1),
(2, (SELECT id FROM equipements_reseau WHERE reference = 'RTR-CISCO-4331'), 1),
(2, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-9300-24P'), 1),
(2, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-SG350-28P'), 2),
(2, (SELECT id FROM equipements_reseau WHERE reference = 'WIFI-CISCO-2802I'), 5),
(2, (SELECT id FROM equipements_reseau WHERE reference = 'UPS-APC-3000'), 3);

-- Liaison des équipements au kit complet
INSERT INTO kits_reseau_equipements (kit_id, equipement_id, quantite) VALUES
(3, (SELECT id FROM equipements_reseau WHERE reference = 'FW-FORTI-100F'), 1),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'RTR-CISCO-4331'), 1),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-9300-48P'), 1),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-SG350-28P'), 2),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'SW-CISCO-SG350-10'), 1),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'WIFI-CISCO-2802I'), 5),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'SRV-DELL-R750'), 2),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'SRV-DELL-R250'), 2),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'NAS-SYNOLOGY-RS1221'), 1),
(3, (SELECT id FROM equipements_reseau WHERE reference = 'UPS-APC-3000'), 3);

-- =====================================================
-- VUES UTILES POUR REQUÊTES
-- =====================================================

-- Vue catalogue complet avec catégories
CREATE OR REPLACE VIEW vue_catalogue_equipements_reseau AS
SELECT 
    e.*,
    c.nom AS categorie_nom,
    c.icone AS categorie_icone,
    ROUND(e.prix_vente * (1 + e.tva/100), 2) AS prix_ttc,
    CASE 
        WHEN e.stock = 0 THEN 'Rupture'
        WHEN e.stock <= e.stock_alerte THEN 'Stock faible'
        ELSE 'Disponible'
    END AS statut_stock
FROM equipements_reseau e
INNER JOIN categories_equipements_reseau c ON e.categorie_id = c.id
WHERE e.actif = TRUE AND c.actif = TRUE;

-- Vue kits avec détails
CREATE OR REPLACE VIEW vue_kits_reseau_details AS
SELECT 
    k.*,
    COUNT(ke.equipement_id) AS nombre_equipements,
    SUM(ke.quantite) AS total_equipements,
    ROUND(k.prix_total * (1 - k.remise_pourcentage/100), 2) AS prix_remise
FROM kits_reseau k
LEFT JOIN kits_reseau_equipements ke ON k.id = ke.kit_id
WHERE k.actif = TRUE
GROUP BY k.id;

-- Vue statistiques par catégorie
CREATE OR REPLACE VIEW vue_stats_categories AS
SELECT 
    c.nom AS categorie,
    COUNT(e.id) AS nombre_produits,
    SUM(e.stock) AS stock_total,
    ROUND(AVG(e.prix_vente), 2) AS prix_moyen,
    SUM(e.nombre_vues) AS vues_total,
    SUM(e.nombre_ventes) AS ventes_total
FROM categories_equipements_reseau c
LEFT JOIN equipements_reseau e ON c.id = e.categorie_id AND e.actif = TRUE
WHERE c.actif = TRUE
GROUP BY c.id, c.nom
ORDER BY c.ordre_affichage;

-- =====================================================
-- INDEX SUPPLÉMENTAIRES POUR PERFORMANCES
-- =====================================================

CREATE INDEX idx_equipement_categorie_prix ON equipements_reseau(categorie_id, prix_vente);
CREATE INDEX idx_equipement_marque_modele ON equipements_reseau(marque, modele);
CREATE INDEX idx_kit_type ON kits_reseau(type_entreprise, actif);

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================

SELECT 'Base de données équipements réseau créée avec succès!' AS message;
SELECT COUNT(*) AS total_equipements FROM equipements_reseau;
SELECT COUNT(*) AS total_categories FROM categories_equipements_reseau;
SELECT COUNT(*) AS total_kits FROM kits_reseau;
