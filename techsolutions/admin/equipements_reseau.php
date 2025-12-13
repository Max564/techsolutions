<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin/login.php');
    exit();
}

require_once '../includes/db.php';

// Récupérer les catégories
$sql_categories = "SELECT * FROM categories_equipements_reseau WHERE actif = TRUE ORDER BY ordre_affichage";
$result_categories = $conn->query($sql_categories);

// Récupérer les équipements avec pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$categorie_filter = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$where_clauses = ["e.actif = TRUE"];
if ($categorie_filter > 0) {
    $where_clauses[] = "e.categorie_id = $categorie_filter";
}
if (!empty($search)) {
    $where_clauses[] = "(e.nom_commercial LIKE '%$search%' OR e.marque LIKE '%$search%' OR e.modele LIKE '%$search%')";
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

$sql_count = "SELECT COUNT(*) as total FROM equipements_reseau e $where_sql";
$result_count = $conn->query($sql_count);
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $per_page);

$sql = "SELECT e.*, c.nom AS categorie_nom, c.icone AS categorie_icone
        FROM equipements_reseau e
        INNER JOIN categories_equipements_reseau c ON e.categorie_id = c.id
        $where_sql
        ORDER BY c.ordre_affichage, e.nom_commercial
        LIMIT $per_page OFFSET $offset";
$result = $conn->query($sql);

// Gestion des actions (ajout, modification, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $id = (int)$_POST['id'];
                $sql_delete = "UPDATE equipements_reseau SET actif = FALSE WHERE id = $id";
                $conn->query($sql_delete);
                header('Location: equipements_reseau.php?msg=deleted');
                exit();
                break;
            
            case 'update_stock':
                $id = (int)$_POST['id'];
                $stock = (int)$_POST['stock'];
                $sql_update = "UPDATE equipements_reseau SET stock = $stock WHERE id = $id";
                $conn->query($sql_update);
                header('Location: equipements_reseau.php?msg=stock_updated');
                exit();
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Équipements Réseau - Admin TechSolutions</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .equipment-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
        }
        
        .equipment-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }
        
        .equipment-info {
            flex: 1;
        }
        
        .equipment-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .equipment-reference {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .equipment-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        
        .spec-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #e9ecef;
            border-radius: 12px;
            font-size: 12px;
            color: #495057;
        }
        
        .equipment-pricing {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }
        
        .price-tag {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
        }
        
        .stock-indicator {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .stock-ok { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-out { background: #f8d7da; color: #721c24; }
        
        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .filter-bar select,
        .filter-bar input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .cat-firewall { background: #dc3545; color: white; }
        .cat-router { background: #007bff; color: white; }
        .cat-switch { background: #28a745; color: white; }
        .cat-wifi { background: #fd7e14; color: white; }
        .cat-server { background: #6c757d; color: white; }
        .cat-nas { background: #17a2b8; color: white; }
        .cat-ups { background: #ffc107; color: black; }
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
                <a href="equipements_reseau.php" class="active">
                    <span>🌐</span> Équipements Réseau
                </a>
                <a href="composants_pc.php">
                    <span>💻</span> Composants PC
                </a>
                <a href="configurations_completes.php">
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
                <h1>Gestion des Équipements Réseau</h1>
                <div class="admin-actions">
                    <button class="btn btn-primary" onclick="window.location.href='equipement_add.php'">
                        + Ajouter un équipement
                    </button>
                </div>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch ($_GET['msg']) {
                        case 'deleted': echo 'Équipement supprimé avec succès'; break;
                        case 'stock_updated': echo 'Stock mis à jour avec succès'; break;
                        case 'added': echo 'Équipement ajouté avec succès'; break;
                        case 'updated': echo 'Équipement modifié avec succès'; break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Filtres et recherche -->
            <div class="filter-bar">
                <form method="GET" style="display: flex; gap: 15px; flex: 1;">
                    <select name="categorie" onchange="this.form.submit()">
                        <option value="0">Toutes les catégories</option>
                        <?php $result_categories->data_seek(0); ?>
                        <?php while ($cat = $result_categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categorie_filter == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <input type="search" name="search" placeholder="Rechercher..." 
                           value="<?= htmlspecialchars($search) ?>" style="flex: 1;">
                    
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <?php if ($categorie_filter > 0 || !empty($search)): ?>
                        <a href="equipements_reseau.php" class="btn btn-secondary">Réinitialiser</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Statistiques rapides -->
            <div class="stats-grid">
                <?php
                $sql_stats = "SELECT 
                    COUNT(*) as total,
                    SUM(stock) as total_stock,
                    ROUND(AVG(prix_vente), 2) as prix_moyen,
                    SUM(stock * prix_vente) as valeur_stock
                    FROM equipements_reseau WHERE actif = TRUE";
                $result_stats = $conn->query($sql_stats);
                $stats = $result_stats->fetch_assoc();
                ?>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total'] ?></div>
                    <div class="stat-label">Équipements</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_stock'] ?></div>
                    <div class="stat-label">Unités en stock</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($stats['prix_moyen'], 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Prix moyen</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($stats['valeur_stock'], 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Valeur stock</div>
                </div>
            </div>

            <!-- Liste des équipements -->
            <div class="equipment-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($equip = $result->fetch_assoc()): ?>
                        <div class="equipment-card">
                            <div class="equipment-icon">
                                <?php
                                $icons = [
                                    'Firewalls' => '🛡️',
                                    'Routeurs' => '🔀',
                                    'Switches Core' => '📊',
                                    'Switches Distribution' => '🔌',
                                    'Points d\'Accès WiFi' => '📡',
                                    'Serveurs' => '🖥️',
                                    'Stockage NAS' => '💾',
                                    'Onduleurs UPS' => '🔋'
                                ];
                                echo $icons[$equip['categorie_nom']] ?? '📦';
                                ?>
                            </div>
                            
                            <div class="equipment-info">
                                <div class="equipment-title">
                                    <?= htmlspecialchars($equip['marque']) ?> <?= htmlspecialchars($equip['modele']) ?>
                                </div>
                                <div class="equipment-reference">
                                    Réf: <?= htmlspecialchars($equip['reference']) ?>
                                </div>
                                <span class="category-badge cat-<?= strtolower(str_replace(' ', '-', $equip['categorie_nom'])) ?>">
                                    <?= htmlspecialchars($equip['categorie_nom']) ?>
                                </span>
                                
                                <div class="equipment-specs">
                                    <?php if ($equip['nombre_ports']): ?>
                                        <span class="spec-badge">🔌 <?= $equip['nombre_ports'] ?> ports</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($equip['support_poe']): ?>
                                        <span class="spec-badge">⚡ PoE <?= $equip['budget_poe_watts'] ?>W</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($equip['niveau_layer'] && $equip['niveau_layer'] != 'N/A'): ?>
                                        <span class="spec-badge">📡 <?= $equip['niveau_layer'] ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($equip['debit_switching']): ?>
                                        <span class="spec-badge">⚡ <?= $equip['debit_switching'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="equipment-pricing">
                                <div class="price-tag"><?= number_format($equip['prix_vente'], 0, ',', ' ') ?> €</div>
                                
                                <?php
                                $stock_class = 'stock-ok';
                                $stock_text = 'En stock';
                                if ($equip['stock'] == 0) {
                                    $stock_class = 'stock-out';
                                    $stock_text = 'Rupture';
                                } elseif ($equip['stock'] <= $equip['stock_alerte']) {
                                    $stock_class = 'stock-low';
                                    $stock_text = 'Stock faible';
                                }
                                ?>
                                <span class="stock-indicator <?= $stock_class ?>">
                                    <?= $equip['stock'] ?> unités - <?= $stock_text ?>
                                </span>
                                
                                <div style="display: flex; gap: 10px; margin-top: 10px;">
                                    <button class="btn btn-sm btn-primary" 
                                            onclick="window.location.href='equipement_edit.php?id=<?= $equip['id'] ?>'">
                                        ✏️ Modifier
                                    </button>
                                    <button class="btn btn-sm btn-secondary" 
                                            onclick="updateStock(<?= $equip['id'] ?>, <?= $equip['stock'] ?>)">
                                        📦 Stock
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="deleteEquipment(<?= $equip['id'] ?>)">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?= $i ?>&categorie=<?= $categorie_filter ?>&search=<?= urlencode($search) ?>" 
                                   class="<?= $i == $page ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucun équipement réseau trouvé.</p>
                        <button class="btn btn-primary" onclick="window.location.href='equipement_add.php'">
                            + Ajouter le premier équipement
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function deleteEquipment(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function updateStock(id, currentStock) {
            const newStock = prompt('Nouveau stock:', currentStock);
            if (newStock !== null && !isNaN(newStock)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_stock">
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="stock" value="${newStock}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
