<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

try {
    // Récupérer toutes les configurations avec les informations des départements
    $stmt = $pdo->query("
        SELECT
            c.id_config,
            c.nom_config,
            c.prix_total,
            d.nom_departement,
            d.nombre_postes,
            d.vlan,
            d.reseau
        FROM configurations_pc c
        JOIN departements d ON c.id_departement = d.id_departement
        ORDER BY c.id_config
    ");
    $configurations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Stats globales
    $total_configs = count($configurations);
    $total_postes = $pdo->query("SELECT SUM(nombre_postes) FROM departements")->fetchColumn();
    $budget_total = $pdo->query("
        SELECT SUM(nombre_postes * prix_total)
        FROM configurations_pc c
        JOIN departements d ON c.id_departement = d.id_departement
    ")->fetchColumn();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurations Complètes - Administration TechSolutions</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .config-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .config-card:hover {
            box-shadow: 0 4px 16px var(--shadow);
            transform: translateY(-2px);
        }
        .config-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .config-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .config-dept {
            color: var(--medium-gray);
            font-size: 1rem;
            font-weight: 500;
        }
        .config-prix {
            text-align: right;
        }
        .prix-unitaire {
            font-size: 2rem;
            font-weight: 900;
            color: var(--black);
            letter-spacing: -0.02em;
        }
        .prix-total {
            color: var(--medium-gray);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .config-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--medium-gray);
            letter-spacing: 0.05em;
        }
        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--black);
        }
        .composants-grid {
            display: grid;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .composant-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            padding: 1rem;
            background: var(--off-white);
            border-radius: 8px;
            align-items: center;
        }
        .composant-cat {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--medium-gray);
            letter-spacing: 0.05em;
        }
        .composant-nom {
            font-weight: 600;
            color: var(--black);
        }
        .composant-specs {
            font-size: 0.875rem;
            color: var(--medium-gray);
            margin-top: 0.25rem;
        }
        .composant-prix {
            font-weight: 700;
            color: var(--black);
            white-space: nowrap;
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
                <li><a href="composants_pc.php">💻 COMPOSANTS PC</a></li>
                <li><a href="configurations_completes.php" class="active">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>🖥️ Configurations Complètes</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Administrateur' ?></strong></div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_configs ?></div>
                    <div class="stat-label">Configurations PC</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $total_postes ?></div>
                    <div class="stat-label">Postes de travail</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($budget_total, 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Budget total HT</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($budget_total * 1.2, 0, ',', ' ') ?> €</div>
                    <div class="stat-label">Budget total TTC</div>
                </div>
            </div>

            <!-- CONTENU -->
            <div class="content-section">
                <h2 class="section-title">Toutes les configurations (<?= $total_configs ?>)</h2>

                <?php foreach ($configurations as $config): ?>
                    <?php
                    // Récupérer les composants de cette configuration
                    $stmt_comp = $pdo->prepare("
                        SELECT
                            c.nom_composant,
                            c.marque,
                            c.specifications,
                            c.prix_unitaire,
                            cat.nom_categorie,
                            pcd.quantite,
                            pcd.justification
                        FROM pc_composants_detail pcd
                        JOIN composants_pc c ON pcd.id_composant = c.id_composant
                        JOIN categories_composants cat ON c.id_categorie = cat.id_categorie
                        WHERE pcd.id_config = ?
                        ORDER BY cat.id_categorie, c.prix_unitaire DESC
                    ");
                    $stmt_comp->execute([$config['id_config']]);
                    $composants = $stmt_comp->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="config-card">
                        <div class="config-header">
                            <div>
                                <div class="config-title"><?= htmlspecialchars($config['nom_config']) ?></div>
                                <div class="config-dept"><?= htmlspecialchars($config['nom_departement']) ?></div>
                            </div>
                            <div class="config-prix">
                                <div class="prix-unitaire"><?= number_format($config['prix_total'], 0, ',', ' ') ?> €</div>
                                <div class="prix-total">par poste</div>
                            </div>
                        </div>

                        <div class="config-info">
                            <div class="info-item">
                                <div class="info-label">Nombre de postes</div>
                                <div class="info-value"><?= $config['nombre_postes'] ?> postes</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">VLAN</div>
                                <div class="info-value"><?= htmlspecialchars($config['vlan']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Réseau</div>
                                <div class="info-value"><?= htmlspecialchars($config['reseau']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Budget département</div>
                                <div class="info-value"><?= number_format($config['prix_total'] * $config['nombre_postes'], 0, ',', ' ') ?> € HT</div>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                            <h4 style="font-weight: 700; margin-bottom: 1rem; font-size: 1rem;">
                                Composants (<?= count($composants) ?>)
                            </h4>

                            <div class="composants-grid">
                                <?php foreach ($composants as $comp): ?>
                                    <div class="composant-item">
                                        <div style="text-align: center;">
                                            <div class="composant-cat"><?= htmlspecialchars($comp['nom_categorie']) ?></div>
                                            <?php if ($comp['quantite'] > 1): ?>
                                                <div style="font-size: 0.75rem; color: var(--medium-gray); margin-top: 0.25rem;">
                                                    × <?= $comp['quantite'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="composant-nom"><?= htmlspecialchars($comp['nom_composant']) ?></div>
                                            <div class="composant-specs"><?= htmlspecialchars($comp['specifications']) ?></div>
                                            <?php if ($comp['justification']): ?>
                                                <div style="font-size: 0.8125rem; color: var(--medium-gray); margin-top: 0.5rem; font-style: italic;">
                                                    → <?= htmlspecialchars($comp['justification']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="composant-prix">
                                            <?= number_format($comp['prix_unitaire'] * $comp['quantite'], 0, ',', ' ') ?> €
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--off-white); border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong>Total configuration:</strong>
                            </div>
                            <div style="font-size: 1.5rem; font-weight: 900; color: var(--black);">
                                <?= number_format($config['prix_total'] * $config['nombre_postes'], 0, ',', ' ') ?> € HT
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
