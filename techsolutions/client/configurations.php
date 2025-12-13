<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

// Pas besoin de DB pour cette page - données statiques
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurations PC - TechSolutions</title>
    <link rel="stylesheet" href="../css/client.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 32px;
        }
        
        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .configs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .config-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .config-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .config-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .config-header h3 {
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        
        .config-header .department {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .config-body {
            padding: 20px;
        }
        
        .config-quick-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quick-spec {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .quick-spec-icon {
            font-size: 24px;
        }
        
        .quick-spec-text {
            flex: 1;
        }
        
        .quick-spec-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .quick-spec-value {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .config-price {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .price-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .price-value {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
        }
        
        .view-details-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .view-details-btn:hover {
            background: #5568d3;
        }
        
        .quantity-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #28a745;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        /* Modal pour détails */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background-color: white;
            margin: 50px auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow: hidden;
            animation: slideIn 0.3s;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .close {
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .close:hover {
            transform: scale(1.2);
        }
        
        .modal-body {
            padding: 30px;
            max-height: calc(85vh - 100px);
            overflow-y: auto;
        }
        
        .component-section {
            margin-bottom: 25px;
        }
        
        .component-section h3 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .component-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .component-info {
            flex: 1;
        }
        
        .component-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 15px;
            margin-bottom: 5px;
        }
        
        .component-specs {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .component-justification {
            font-size: 12px;
            color: #495057;
            font-style: italic;
            margin-top: 8px;
            padding: 8px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #667eea;
        }
        
        .component-price {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
            white-space: nowrap;
            margin-left: 15px;
        }
        
        .total-section {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            color: white;
        }
        
        .total-section h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .total-price {
            font-size: 36px;
            font-weight: 700;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .summary-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .summary-value {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="client-nav">
        <div class="container">
            <div class="nav-brand">
                <h2>TechSolutions</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="configurations.php" class="active">Configurations PC</a></li>
                <li><a href="mes_devis.php">Mes devis</a></li>
                <li><a href="mon_compte.php">Mon compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>🖥️ Configurations PC par Département</h1>
            <p>Découvrez les 50 postes configurés pour chaque métier de l'entreprise</p>
        </div>
    </div>

    <div class="container">
        <!-- Summary -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon">💼</div>
                <div class="summary-value">7</div>
                <div class="summary-label">Départements</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">🖥️</div>
                <div class="summary-value">50</div>
                <div class="summary-label">Postes de travail</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">💰</div>
                <div class="summary-value">172 200 €</div>
                <div class="summary-label">Budget total HT</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">⚡</div>
                <div class="summary-value">3 444 €</div>
                <div class="summary-label">Prix moyen / poste</div>
            </div>
        </div>

        <!-- Configurations Grid -->
        <div class="configs-grid">
            <!-- Config 1: Développement -->
            <div class="config-card" onclick="openModal('dev')">
                <div class="config-header">
                    <h3>💻 Développement <span class="quantity-badge">15 postes</span></h3>
                    <div class="department">VLAN 10 - 192.168.10.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Ryzen 9 7950X3D</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">64GB DDR5</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🎮</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">GPU</div>
                                <div class="quick-spec-value">RTX 4060 8GB</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Stockage</div>
                                <div class="quick-spec-value">2TB NVMe</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">4 500 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 2: Infrastructure -->
            <div class="config-card" onclick="openModal('infra')">
                <div class="config-header">
                    <h3>🔧 Infrastructure <span class="quantity-badge">5 postes</span></h3>
                    <div class="department">VLAN 20 - 192.168.20.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Ryzen 9 7950X3D</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">64GB DDR5</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écrans</div>
                                <div class="quick-spec-value">3x 27" QHD</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Stockage</div>
                                <div class="quick-spec-value">2TB NVMe + 4TB HDD</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">4 200 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 3: Design -->
            <div class="config-card" onclick="openModal('design')">
                <div class="config-header">
                    <h3>🎨 Design <span class="quantity-badge">5 postes</span></h3>
                    <div class="department">VLAN 30 - 192.168.30.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Ryzen 7 7800X3D</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🎮</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">GPU</div>
                                <div class="quick-spec-value">RTX 4070 12GB</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écran</div>
                                <div class="quick-spec-value">27" 4K Calibré</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🎨</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Tablette</div>
                                <div class="quick-spec-value">Wacom Intuos Pro</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">4 800 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 4: Marketing -->
            <div class="config-card" onclick="openModal('marketing')">
                <div class="config-header">
                    <h3>📢 Marketing <span class="quantity-badge">10 postes</span></h3>
                    <div class="department">VLAN 40 - 192.168.40.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Core i5-14600K</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">32GB DDR4</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écran</div>
                                <div class="quick-spec-value">27" 4K</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Stockage</div>
                                <div class="quick-spec-value">1TB NVMe</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">2 200 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 5: Support -->
            <div class="config-card" onclick="openModal('support')">
                <div class="config-header">
                    <h3>🎧 Support <span class="quantity-badge">5 postes</span></h3>
                    <div class="department">VLAN 50 - 192.168.50.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Ryzen 5 7600X</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">32GB DDR4</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écrans</div>
                                <div class="quick-spec-value">2x 24" Full HD</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">♿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Accessibilité</div>
                                <div class="quick-spec-value">1 poste adapté</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire moyen</div>
                        <div class="price-value">2 340 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 6: RH/Admin -->
            <div class="config-card" onclick="openModal('rh')">
                <div class="config-header">
                    <h3>📋 RH / Administration <span class="quantity-badge">5 postes</span></h3>
                    <div class="department">VLAN 60 - 192.168.60.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Ryzen 5 7600X</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">16GB DDR4</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écran</div>
                                <div class="quick-spec-value">24" Full HD</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Stockage</div>
                                <div class="quick-spec-value">500GB SSD</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">1 200 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>

            <!-- Config 7: Direction -->
            <div class="config-card" onclick="openModal('direction')">
                <div class="config-header">
                    <h3>👔 Direction <span class="quantity-badge">5 postes</span></h3>
                    <div class="department">VLAN 70 - 192.168.70.0/24</div>
                </div>
                <div class="config-body">
                    <div class="config-quick-specs">
                        <div class="quick-spec">
                            <div class="quick-spec-icon">⚡</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Processeur</div>
                                <div class="quick-spec-value">Core i7-14700K</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💾</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">RAM</div>
                                <div class="quick-spec-value">32GB DDR5</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">🖥️</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Écran</div>
                                <div class="quick-spec-value">27" 4K Calibré</div>
                            </div>
                        </div>
                        <div class="quick-spec">
                            <div class="quick-spec-icon">💿</div>
                            <div class="quick-spec-text">
                                <div class="quick-spec-label">Stockage</div>
                                <div class="quick-spec-value">1TB NVMe</div>
                            </div>
                        </div>
                    </div>
                    <div class="config-price">
                        <div class="price-label">Prix unitaire HT</div>
                        <div class="price-value">4 000 €</div>
                    </div>
                    <button class="view-details-btn">Voir les détails complets</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour détails (à implémenter avec vraies données BDD) -->
    <div id="configModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Configuration Détaillée</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <script>
        function openModal(configType) {
            const modal = document.getElementById('configModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            const configs = {
                'dev': {
                    title: '💻 Configuration Développement',
                    quantity: 15,
                    price: 4500,
                    total: 67500,
                    components: [
                        { cat: 'Processeur', name: 'AMD Ryzen 9 7950X3D', specs: '16 cœurs, 32 threads, 5.7 GHz boost', price: 699, justif: 'Performance exceptionnelle pour compilation et développement multi-thread' },
                        { cat: 'Carte mère', name: 'ASUS ROG STRIX X670E-E', specs: 'Chipset X670E, PCIe 5.0, WiFi 6E', price: 459, justif: 'Compatibilité parfaite avec Ryzen 9, extensibilité maximale' },
                        { cat: 'RAM', name: 'G.Skill Trident Z5 64GB', specs: 'DDR5 5600MHz CL36, 2x32GB', price: 249, justif: 'Capacité importante pour VMs et conteneurs Docker' },
                        { cat: 'GPU', name: 'NVIDIA RTX 4060 8GB', specs: 'GDDR6, Ray Tracing, DLSS 3', price: 329, justif: 'Accélération CUDA pour machine learning et développement graphique' },
                        { cat: 'Stockage', name: 'Samsung 990 Pro 2TB', specs: 'NVMe M.2, 7450 MB/s lecture', price: 189, justif: 'Vitesse maximale pour compilation et chargement projets' },
                        { cat: 'Écrans', name: '2x Dell U2723DE 27" QHD', specs: '2560x1440, IPS, USB-C', price: 898, justif: 'Double écran pour productivité maximale' },
                        { cat: 'Clavier', name: 'Logitech MX Keys Advanced', specs: 'Sans fil, rétro-éclairé', price: 109, justif: 'Confort de frappe pour longues sessions de code' },
                        { cat: 'Souris', name: 'Logitech MX Master 3S', specs: 'Sans fil, ergonomique, 8K DPI', price: 99, justif: 'Précision et ergonomie professionnelle' },
                        { cat: 'Casque', name: 'Sony WH-1000XM5', specs: 'Réduction bruit active', price: 349, justif: 'Concentration maximale en open space' },
                        { cat: 'Alimentation', name: 'Corsair RM850x 850W', specs: '80+ Gold, modulaire', price: 129, justif: 'Puissance et efficacité pour config exigeante' },
                        { cat: 'Boîtier', name: 'Fractal Design Torrent', specs: 'ATX, ventilation optimale', price: 189, justif: 'Refroidissement excellent et design sobre' },
                        { cat: 'Refroidissement', name: 'Noctua NH-D15', specs: 'Double ventilateur 140mm', price: 99, justif: 'Refroidissement silencieux et efficace' }
                    ]
                },
                'infra': {
                    title: '🔧 Configuration Infrastructure',
                    quantity: 5,
                    price: 4200,
                    total: 21000,
                    components: [
                        { cat: 'Processeur', name: 'AMD Ryzen 9 7950X3D', specs: '16 cœurs, 32 threads', price: 699, justif: 'Puissance nécessaire pour administration serveurs' },
                        { cat: 'RAM', name: '64GB DDR5 5600MHz', specs: '2x32GB', price: 249, justif: 'Multitâche intense et virtualisation' },
                        { cat: 'Stockage', name: '2TB NVMe + 4TB HDD', specs: 'Dual storage', price: 289, justif: 'SSD pour OS, HDD pour données et logs' },
                        { cat: 'Écrans', name: '3x Dell U2723DE 27"', specs: 'Triple écran QHD', price: 1347, justif: 'Surveillance multi-systèmes simultanée' },
                        { cat: 'Accessoires', name: 'KVM Switch professionnel', specs: '4 ports', price: 149, justif: 'Gestion multiple serveurs physiques' }
                    ]
                },
                'design': {
                    title: '🎨 Configuration Design',
                    quantity: 5,
                    price: 4800,
                    total: 24000,
                    components: [
                        { cat: 'Processeur', name: 'AMD Ryzen 7 7800X3D', specs: '8 cœurs, 5.0 GHz', price: 449, justif: 'Excellent pour applications créatives' },
                        { cat: 'GPU', name: 'NVIDIA RTX 4070 12GB', specs: 'GDDR6X, Ray Tracing', price: 649, justif: 'Rendu 3D et traitement vidéo haute qualité' },
                        { cat: 'RAM', name: '32GB DDR5', specs: '5600MHz', price: 159, justif: 'Suffisant pour Photoshop et Illustrator' },
                        { cat: 'Écran', name: 'BenQ SW271C 27" 4K', specs: 'Calibré usine, Delta E<2', price: 899, justif: 'Précision colorimétrique professionnelle' },
                        { cat: 'Tablette', name: 'Wacom Intuos Pro M', specs: '8192 niveaux pression', price: 379, justif: 'Dessin numérique professionnel' }
                    ]
                },
                'marketing': {
                    title: '📢 Configuration Marketing',
                    quantity: 10,
                    price: 2200,
                    total: 22000,
                    components: [
                        { cat: 'Processeur', name: 'Intel Core i5-14600K', specs: '14 cœurs, 5.3 GHz', price: 319, justif: 'Performance équilibrée pour bureautique' },
                        { cat: 'RAM', name: '32GB DDR4', specs: '3200MHz', price: 89, justif: 'Multitâche et applications web' },
                        { cat: 'Stockage', name: '1TB NVMe Samsung 980', specs: '3500 MB/s', price: 89, justif: 'Rapidité pour fichiers marketing' },
                        { cat: 'Écran', name: 'LG 27UK850-W 27" 4K', specs: 'IPS, USB-C', price: 449, justif: 'Qualité image pour contenu marketing' }
                    ]
                },
                'support': {
                    title: '🎧 Configuration Support',
                    quantity: 5,
                    price: 2340,
                    total: 11700,
                    components: [
                        { cat: 'Processeur', name: 'AMD Ryzen 5 7600X', specs: '6 cœurs, 5.3 GHz', price: 249, justif: 'Performance suffisante pour support client' },
                        { cat: 'RAM', name: '32GB DDR4', specs: '3200MHz', price: 89, justif: 'Multiples applications simultanées' },
                        { cat: 'Écrans', name: '2x ASUS VA24EHE 24"', specs: 'Full HD IPS', price: 258, justif: 'Double écran pour tickets et documentation' },
                        { cat: 'Casque', name: 'Sennheiser PC 8 USB', specs: 'Microphone anti-bruit', price: 39, justif: 'Audio clair pour appels support' },
                        { cat: 'ADAPTÉ', name: 'Poste adapté handicap visuel', specs: 'JAWS Pro + ZoomText + Écran 32"', price: 4500, justif: 'Accessibilité complète pour malvoyants (1 poste sur 5)' }
                    ]
                },
                'rh': {
                    title: '📋 Configuration RH / Administration',
                    quantity: 5,
                    price: 1200,
                    total: 6000,
                    components: [
                        { cat: 'Processeur', name: 'AMD Ryzen 5 7600X', specs: '6 cœurs', price: 249, justif: 'Bureautique classique' },
                        { cat: 'RAM', name: '16GB DDR4', specs: '3200MHz', price: 45, justif: 'Suffisant pour Office et SIRH' },
                        { cat: 'Stockage', name: '500GB SSD', specs: 'SATA', price: 49, justif: 'Capacité adaptée aux documents RH' },
                        { cat: 'Écran', name: 'ASUS VA24EHE 24"', specs: 'Full HD', price: 129, justif: 'Confort lecture documents' }
                    ]
                },
                'direction': {
                    title: '👔 Configuration Direction',
                    quantity: 5,
                    price: 4000,
                    total: 20000,
                    components: [
                        { cat: 'Processeur', name: 'Intel Core i7-14700K', specs: '20 cœurs, 5.6 GHz', price: 419, justif: 'Performance premium pour direction' },
                        { cat: 'RAM', name: '32GB DDR5', specs: '5600MHz', price: 159, justif: 'Fluidité applications multiples' },
                        { cat: 'Stockage', name: '1TB NVMe Samsung 990 Pro', specs: '7450 MB/s', price: 139, justif: 'Rapidité et fiabilité maximales' },
                        { cat: 'Écran', name: 'BenQ SW271C 27" 4K', specs: 'Calibré, premium', price: 899, justif: 'Image premium pour présentations' }
                    ]
                }
            };
            
            const config = configs[configType];
            modalTitle.textContent = config.title;
            
            let html = `
                <div class="component-section">
                    <h3>📊 Résumé de la configuration</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #667eea;">${config.quantity}</div>
                            <div style="font-size: 12px; color: #6c757d;">Postes</div>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #28a745;">${config.price.toLocaleString()} €</div>
                            <div style="font-size: 12px; color: #6c757d;">Prix unitaire HT</div>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #667eea;">${config.total.toLocaleString()} €</div>
                            <div style="font-size: 12px; color: #6c757d;">Total HT</div>
                        </div>
                    </div>
                </div>
                
                <div class="component-section">
                    <h3>🔧 Composants détaillés</h3>
            `;
            
            config.components.forEach(comp => {
                html += `
                    <div class="component-item">
                        <div class="component-info">
                            <div class="component-name">${comp.cat}: ${comp.name}</div>
                            <div class="component-specs">${comp.specs}</div>
                            <div class="component-justification">💡 ${comp.justif}</div>
                        </div>
                        <div class="component-price">${comp.price.toLocaleString()} €</div>
                    </div>
                `;
            });
            
            html += `
                </div>
                <div class="total-section">
                    <h3>💰 Total configuration</h3>
                    <div class="total-price">${config.price.toLocaleString()} € HT / poste</div>
                    <div style="margin-top: 10px; opacity: 0.9;">
                        ${config.quantity} postes × ${config.price.toLocaleString()} € = ${config.total.toLocaleString()} € HT
                    </div>
                </div>
            `;
            
            modalBody.innerHTML = html;
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('configModal').style.display = 'none';
        }
        
        // Fermer modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('configModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
