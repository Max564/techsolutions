<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Récupérer tous les composants
$stmt = $pdo->query("
    SELECT 
        c.id_composant,
        c.nom_composant,
        c.marque,
        c.modele,
        c.specifications,
        c.prix_unitaire,
        c.stock,
        cat.nom_categorie,
        cat.icone
    FROM composants_pc c
    JOIN categories_composants cat ON c.id_categorie = cat.id_categorie
    ORDER BY cat.nom_categorie, c.prix_unitaire DESC
");
$composants = $stmt->fetchAll();

// Récupérer stats
$total_composants = count($composants);
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories_composants")->fetchColumn();
$valeur_stock = $pdo->query("SELECT SUM(prix_unitaire * stock) FROM composants_pc")->fetchColumn();
$prix_moyen = $pdo->query("SELECT AVG(prix_unitaire) FROM composants_pc")->fetchColumn();

// Filtrer par catégorie si demandé
$filtre_categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composants PC - Administration TechSolutions</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .composant-card {
            background: var(--white);
            border: 2px solid var(--black);
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            align-items: start;
            transition: all 0.3s ease;
        }
        .composant-card:hover {
            background: var(--off-white);
        }
        .composant-icone {
            font-size: 2.5rem;
            text-align: center;
        }
        .composant-info {
            flex: 1;
        }
        .composant-nom {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .composant-marque {
            color: var(--medium-gray);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        .composant-specs {
            color: var(--medium-gray);
            font-size: 0.85rem;
            line-height: 1.6;
            margin: 0.5rem 0;
        }
        .composant-prix {
            text-align: right;
        }
        .prix-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--blue);
        }
        .stock-info {
            font-size: 0.75rem;
            color: var(--medium-gray);
            margin-top: 0.25rem;
        }
        .categorie-header {
            background: var(--black);
            color: var(--white);
            padding: 1rem 1.5rem;
            margin: 2rem 0 1rem 0;
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .categorie-header:first-of-type {
            margin-top: 0;
        }
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 0.75rem 1.5rem;
            background: var(--white);
            color: var(--black);
            border: 2px solid var(--black);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .filter-tab:hover,
        .filter-tab.active {
            background: var(--black);
            color: var(--white);
        }
        .pc-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--blue);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 0.5rem;
            cursor: pointer;
        }
        .pc-badge:hover {
            background: var(--black);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: var(--white);
            padding: 2rem;
            border: 3px solid var(--black);
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--black);
        }
        .modal-close {
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 700;
            cursor: pointer;
        }
        .pc-list {
            list-style: none;
        }
        .pc-list li {
            padding: 0.75rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pc-list li:last-child {
            border-bottom: none;
        }
        .search-box {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--black);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <a href="dashboard.php" class="sidebar-logo">TECHSOLUTIONS</a>
            <div class="sidebar-subtitle">Administration</div>
            
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 TABLEAU DE BORD</a></li>
                <li><a href="messages.php">✉️ MESSAGES</a></li>
                <li><a href="clients.php">👥 CLIENTS</a></li>
                <li><a href="actualites.php">📰 ACTUALITÉS</a></li>
                <li><a href="composants_pc.php" class="active">💻 COMPOSANTS PC</a></li>
                <li><a href="configurations_completes.php">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>💻 COMPOSANTS PC</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong></div>
            </div>

            <!-- STATISTIQUES -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_composants ?></div>
                    <div class="stat-label">Composants uniques</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $total_categories ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($valeur_stock, 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Valeur stock</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($prix_moyen, 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Prix moyen</div>
                </div>
            </div>

            <!-- CONTENU -->
            <div class="content-section">
                <h2 class="section-title">CATALOGUE COMPLET - <?= $total_composants ?> COMPOSANTS</h2>

                <!-- RECHERCHE -->
                <input type="text" id="searchBox" class="search-box" placeholder="🔍 Rechercher un composant, marque ou modèle...">

                <!-- FILTRES CATÉGORIES -->
                <div class="filter-tabs">
                    <a href="?categorie=tous" class="filter-tab <?= $filtre_categorie === 'tous' ? 'active' : '' ?>">
                        Tous (<?= $total_composants ?>)
                    </a>
                    <?php
                    $cats = $pdo->query("
                        SELECT cat.nom_categorie, cat.icone, COUNT(c.id_composant) as nb
                        FROM categories_composants cat
                        LEFT JOIN composants_pc c ON cat.id_categorie = c.id_categorie
                        GROUP BY cat.id_categorie
                        ORDER BY cat.nom_categorie
                    ")->fetchAll();
                    foreach ($cats as $cat):
                    ?>
                        <a href="?categorie=<?= urlencode($cat['nom_categorie']) ?>" 
                           class="filter-tab <?= $filtre_categorie === $cat['nom_categorie'] ? 'active' : '' ?>">
                            <?= $cat['icone'] ?> <?= $cat['nom_categorie'] ?> (<?= $cat['nb'] ?>)
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- LISTE COMPOSANTS PAR CATÉGORIE -->
                <div id="composantsList">
                <?php
                $current_categorie = '';
                foreach ($composants as $comp):
                    // Filtre catégorie
                    if ($filtre_categorie !== 'tous' && $comp['nom_categorie'] !== $filtre_categorie) {
                        continue;
                    }

                    // Header catégorie
                    if ($current_categorie !== $comp['nom_categorie']) {
                        if ($current_categorie !== '') {
                            echo '</div>'; // Fermer la div précédente
                        }
                        $current_categorie = $comp['nom_categorie'];
                        echo '<div class="categorie-header">';
                        echo $comp['icone'] . ' ' . $comp['nom_categorie'];
                        echo '</div>';
                    }

                    // Trouver dans quels PC ce composant est utilisé
                    $stmt_pc = $pdo->prepare("
                        SELECT DISTINCT cfg.nom_config, cfg.id_config
                        FROM pc_composants_detail pcd
                        JOIN configurations_pc cfg ON pcd.id_config = cfg.id_config
                        WHERE pcd.id_composant = ?
                    ");
                    $stmt_pc->execute([$comp['id_composant']]);
                    $pcs = $stmt_pc->fetchAll();
                ?>
                    <div class="composant-card" data-search="<?= strtolower($comp['nom_composant'] . ' ' . $comp['marque'] . ' ' . $comp['modele']) ?>">
                        <div class="composant-icone">
                            <?= $comp['icone'] ?>
                        </div>
                        <div class="composant-info">
                            <div class="composant-nom"><?= htmlspecialchars($comp['nom_composant']) ?></div>
                            <div class="composant-marque">
                                <?= htmlspecialchars($comp['marque']) ?> • <?= htmlspecialchars($comp['modele']) ?>
                            </div>
                            <div class="composant-specs"><?= htmlspecialchars($comp['specifications']) ?></div>
                            
                            <?php if (count($pcs) > 0): ?>
                                <div style="margin-top: 0.75rem;">
                                    <strong style="font-size: 0.85rem;">✓ Utilisé dans <?= count($pcs) ?> PC:</strong>
                                    <?php foreach ($pcs as $pc): ?>
                                        <span class="pc-badge" onclick="alert('Configuration: <?= htmlspecialchars($pc['nom_config']) ?>')">
                                            <?= htmlspecialchars($pc['nom_config']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="composant-prix">
                            <div class="prix-value"><?= number_format($comp['prix_unitaire'], 2, ',', ' ') ?> €</div>
                            <div class="stock-info">Stock: <?= $comp['stock'] ?> unités</div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Recherche en temps réel
        document.getElementById('searchBox').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.composant-card');
            const headers = document.querySelectorAll('.categorie-header');
            
            // Cacher tous les headers
            headers.forEach(h => h.style.display = 'none');
            
            let visibleCount = 0;
            cards.forEach(card => {
                const searchText = card.getAttribute('data-search');
                if (searchText.includes(search)) {
                    card.style.display = 'grid';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Afficher message si aucun résultat
            if (visibleCount === 0 && search !== '') {
                document.getElementById('composantsList').innerHTML = '<div style="text-align: center; padding: 3rem; color: var(--medium-gray);"><p style="font-size: 1.2rem;">Aucun composant trouvé pour "' + search + '"</p></div>';
            }
        });
    </script>
</body>
</html>
