<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Données statiques des composants (en attendant import BDD)
$categories = [
    1 => ['id' => 1, 'nom' => 'Processeur', 'icone' => '⚡'],
    2 => ['id' => 2, 'nom' => 'Carte mère', 'icone' => '🔲'],
    3 => ['id' => 3, 'nom' => 'Mémoire RAM', 'icone' => '💾'],
    4 => ['id' => 4, 'nom' => 'Stockage', 'icone' => '💿'],
    5 => ['id' => 5, 'nom' => 'Carte graphique', 'icone' => '🎮'],
    6 => ['id' => 6, 'nom' => 'Alimentation', 'icone' => '🔌'],
    7 => ['id' => 7, 'nom' => 'Boîtier', 'icone' => '📦'],
    8 => ['id' => 8, 'nom' => 'Refroidissement', 'icone' => '❄️']
];

$composants = [
    // Développement
    ['id' => 1, 'categorie_id' => 1, 'nom' => 'AMD Ryzen 9 7950X3D', 'marque' => 'AMD', 'modele' => 'Ryzen 9 7950X3D', 'type' => 'Processeur Desktop', 'specs' => '16 cœurs, 32 threads, 5.7 GHz boost', 'prix' => 699, 'justif' => 'Performance exceptionnelle pour compilation et développement multi-thread'],
    ['id' => 2, 'categorie_id' => 2, 'nom' => 'ASUS ROG STRIX X670E-E', 'marque' => 'ASUS', 'modele' => 'ROG STRIX X670E-E', 'type' => 'Carte mère AM5', 'specs' => 'Chipset X670E, PCIe 5.0, WiFi 6E', 'prix' => 459, 'justif' => 'Compatibilité parfaite avec Ryzen 9, extensibilité maximale'],
    ['id' => 3, 'categorie_id' => 3, 'nom' => 'G.Skill Trident Z5 64GB', 'marque' => 'G.Skill', 'modele' => 'Trident Z5', 'type' => 'RAM DDR5', 'specs' => 'DDR5 5600MHz CL36, 2x32GB', 'prix' => 249, 'justif' => 'Capacité importante pour VMs et conteneurs Docker'],
    ['id' => 4, 'categorie_id' => 5, 'nom' => 'NVIDIA RTX 4060 8GB', 'marque' => 'NVIDIA', 'modele' => 'GeForce RTX 4060', 'type' => 'Carte graphique', 'specs' => '8GB GDDR6, Ray Tracing, DLSS 3', 'prix' => 329, 'justif' => 'Accélération CUDA pour machine learning'],
    ['id' => 5, 'categorie_id' => 4, 'nom' => 'Samsung 990 Pro 2TB', 'marque' => 'Samsung', 'modele' => '990 Pro', 'type' => 'SSD NVMe', 'specs' => '2TB, 7450 MB/s lecture, PCIe 4.0', 'prix' => 189, 'justif' => 'Vitesse maximale pour compilation'],
    ['id' => 6, 'categorie_id' => 6, 'nom' => 'Corsair RM850x 850W', 'marque' => 'Corsair', 'modele' => 'RM850x', 'type' => 'Alimentation', 'specs' => '850W, 80+ Gold, modulaire', 'prix' => 129, 'justif' => 'Puissance et efficacité'],
    ['id' => 7, 'categorie_id' => 7, 'nom' => 'Fractal Design Torrent', 'marque' => 'Fractal Design', 'modele' => 'Torrent', 'type' => 'Boîtier ATX', 'specs' => 'ATX, ventilation optimale', 'prix' => 189, 'justif' => 'Refroidissement excellent'],
    ['id' => 8, 'categorie_id' => 8, 'nom' => 'Noctua NH-D15', 'marque' => 'Noctua', 'modele' => 'NH-D15', 'type' => 'Ventirad CPU', 'specs' => 'Double ventilateur 140mm', 'prix' => 99, 'justif' => 'Refroidissement silencieux'],
    
    // Marketing
    ['id' => 9, 'categorie_id' => 1, 'nom' => 'Intel Core i5-14600K', 'marque' => 'Intel', 'modele' => 'Core i5-14600K', 'type' => 'Processeur Desktop', 'specs' => '14 cœurs, 5.3 GHz boost', 'prix' => 319, 'justif' => 'Performance équilibrée bureautique'],
    ['id' => 10, 'categorie_id' => 3, 'nom' => 'Corsair Vengeance 32GB', 'marque' => 'Corsair', 'modele' => 'Vengeance', 'type' => 'RAM DDR4', 'specs' => '32GB DDR4 3200MHz', 'prix' => 89, 'justif' => 'Multitâche applications web'],
    
    // Design
    ['id' => 11, 'categorie_id' => 1, 'nom' => 'AMD Ryzen 7 7800X3D', 'marque' => 'AMD', 'modele' => 'Ryzen 7 7800X3D', 'type' => 'Processeur Desktop', 'specs' => '8 cœurs, 5.0 GHz boost', 'prix' => 449, 'justif' => 'Excellent pour applications créatives'],
    ['id' => 12, 'categorie_id' => 5, 'nom' => 'NVIDIA RTX 4070 12GB', 'marque' => 'NVIDIA', 'modele' => 'GeForce RTX 4070', 'type' => 'Carte graphique', 'specs' => '12GB GDDR6X, Ray Tracing', 'prix' => 649, 'justif' => 'Rendu 3D et traitement vidéo'],
    
    // Support
    ['id' => 13, 'categorie_id' => 1, 'nom' => 'AMD Ryzen 5 7600X', 'marque' => 'AMD', 'modele' => 'Ryzen 5 7600X', 'type' => 'Processeur Desktop', 'specs' => '6 cœurs, 5.3 GHz boost', 'prix' => 249, 'justif' => 'Performance suffisante support'],
    
    // Direction
    ['id' => 14, 'categorie_id' => 1, 'nom' => 'Intel Core i7-14700K', 'marque' => 'Intel', 'modele' => 'Core i7-14700K', 'type' => 'Processeur Desktop', 'specs' => '20 cœurs, 5.6 GHz boost', 'prix' => 419, 'justif' => 'Performance premium direction'],
];

// Filtres
$categorie_filter = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filtrer les composants
$composants_filtered = $composants;
if ($categorie_filter > 0) {
    $composants_filtered = array_filter($composants, function($c) use ($categorie_filter) {
        return $c['categorie_id'] == $categorie_filter;
    });
}
if (!empty($search)) {
    $composants_filtered = array_filter($composants_filtered, function($c) use ($search) {
        return stripos($c['nom'], $search) !== false || 
               stripos($c['marque'], $search) !== false ||
               stripos($c['type'], $search) !== false;
    });
}

// Statistiques
$total_composants = count($composants);
$total_categories = count($categories);
$prix_moyen = array_sum(array_column($composants, 'prix')) / $total_composants;
$valeur_totale = array_sum(array_column($composants, 'prix'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composants PC - Admin TechSolutions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
        }
        
        .admin-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
        }
        
        .admin-logo {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 2rem;
        }
        
        .admin-logo h2 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .admin-logo p {
            font-size: 0.85rem;
            opacity: 0.7;
        }
        
        .admin-nav a {
            display: block;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid #667eea;
        }
        
        .admin-main {
            padding: 2rem;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .admin-header h1 {
            font-size: 2rem;
            color: #2c3e50;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .filter-bar {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .filter-bar form {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .filter-bar select,
        .filter-bar input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        
        .filter-bar input[type="search"] {
            flex: 1;
        }
        
        .component-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: grid;
            grid-template-columns: 70px 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }
        
        .component-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .component-info {
            flex: 1;
        }
        
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .cat-processeur { background: #e74c3c; color: white; }
        .cat-carte-mere { background: #3498db; color: white; }
        .cat-memoire-ram { background: #2ecc71; color: white; }
        .cat-stockage { background: #f39c12; color: white; }
        .cat-carte-graphique { background: #9b59b6; color: white; }
        .cat-alimentation { background: #e67e22; color: white; }
        .cat-boitier { background: #34495e; color: white; }
        .cat-refroidissement { background: #1abc9c; color: white; }
        
        .component-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }
        
        .component-details {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .component-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .spec-badge {
            padding: 0.25rem 0.75rem;
            background: #e9ecef;
            border-radius: 12px;
            font-size: 0.75rem;
            color: #495057;
        }
        
        .justification-text {
            font-size: 0.8rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        
        .component-pricing {
            text-align: right;
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>TechSolutions</h2>
                <p>Administration</p>
            </div>
            
            <nav class="admin-nav">
                <a href="dashboard.php">
                    <span>📊</span> Tableau de bord
                </a>
                <a href="messages.php">
                    <span>✉️</span> Messages
                </a>
                <a href="clients.php">
                    <span>👥</span> Clients
                </a>
                <a href="devis.php">
                    <span>📄</span> Demandes de devis
                </a>
                <a href="actualites.php">
                    <span>📰</span> Actualités
                </a>
                <a href="equipements_reseau.php">
                    <span>🌐</span> Équipements Réseau
                </a>
                <a href="composants_pc.php" class="active">
                    <span>💻</span> Composants PC
                </a>
                <a href="parametres.php">
                    <span>⚙️</span> Paramètres
                </a>
                <a href="logout.php">
                    <span>🚪</span> Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Gestion des Composants PC</h1>
                <div>
                    <button class="btn btn-primary" onclick="alert('Fonctionnalité à venir: Ajout de composant')">
                        + Ajouter un composant
                    </button>
                </div>
            </div>

            <div class="alert alert-info">
                💡 <strong>Mode démo:</strong> Cette page affiche des données statiques. Importez le schéma SQL pour activer la gestion complète de la base de données.
            </div>

            <!-- Filtres -->
            <div class="filter-bar">
                <form method="GET">
                    <select name="categorie" onchange="this.form.submit()">
                        <option value="0">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categorie_filter == $cat['id'] ? 'selected' : '' ?>>
                                <?= $cat['icone'] ?> <?= $cat['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="search" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <?php if ($categorie_filter > 0 || !empty($search)): ?>
                        <a href="composants_pc.php" class="btn btn-secondary">Réinitialiser</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_composants ?></div>
                    <div class="stat-label">Composants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $total_categories ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($prix_moyen, 0) ?> €</div>
                    <div class="stat-label">Prix moyen</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($valeur_totale, 0) ?> €</div>
                    <div class="stat-label">Valeur catalogue</div>
                </div>
            </div>

            <!-- Liste des composants -->
            <?php if (count($composants_filtered) > 0): ?>
                <?php foreach ($composants_filtered as $comp): ?>
                    <?php $cat = $categories[$comp['categorie_id']]; ?>
                    <div class="component-card">
                        <div class="component-icon">
                            <?= $cat['icone'] ?>
                        </div>
                        
                        <div class="component-info">
                            <span class="category-badge cat-<?= strtolower(str_replace([' ', 'é'], ['', 'e'], $cat['nom'])) ?>">
                                <?= $cat['nom'] ?>
                            </span>
                            
                            <div class="component-title">
                                <?= htmlspecialchars($comp['marque']) ?> <?= htmlspecialchars($comp['modele']) ?>
                            </div>
                            
                            <div class="component-details">
                                <?= htmlspecialchars($comp['type']) ?> | <?= htmlspecialchars($comp['nom']) ?>
                            </div>
                            
                            <div class="component-specs">
                                <?php 
                                $specs_arr = explode(',', $comp['specs']);
                                foreach (array_slice($specs_arr, 0, 3) as $spec): 
                                ?>
                                    <span class="spec-badge"><?= htmlspecialchars(trim($spec)) ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="justification-text">
                                💡 <?= htmlspecialchars($comp['justif']) ?>
                            </div>
                        </div>
                        
                        <div class="component-pricing">
                            <div class="price-tag"><?= number_format($comp['prix'], 0) ?> €</div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary" onclick="alert('Modifier composant #<?= $comp['id'] ?>')">
                                    ✏️ Modifier
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="alert('Prix: <?= $comp['prix'] ?> €')">
                                    💰 Prix
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Aucun composant trouvé avec ces critères.
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
