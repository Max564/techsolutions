<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$client_nom = isset($_SESSION['client_nom']) ? $_SESSION['client_nom'] : 'Utilisateur';
$client_prenom = isset($_SESSION['client_prenom']) ? $_SESSION['client_prenom'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - TechSolutions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar h1 {
            font-size: 1.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .nav-links a:hover {
            opacity: 0.8;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .welcome-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .welcome-section h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        
        .welcome-section p {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        
        .card p {
            color: #6c757d;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.3s;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <h1>🚀 TechSolutions</h1>
            <ul class="nav-links">
                <li><a href="dashboard.php">Accueil</a></li>
                <li><a href="configurations.php">Configurations PC</a></li>
                <li><a href="../index.php">Site</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>👋 Bienvenue <?php echo htmlspecialchars($client_prenom . ' ' . $client_nom); ?></h2>
            <p>Accédez à toutes vos informations et configurations TechSolutions</p>
        </div>

        <!-- Stats -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-value">7</div>
                <div class="stat-label">Départements</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">50</div>
                <div class="stat-label">Postes PC</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">172 200 €</div>
                <div class="stat-label">Budget HT</div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="cards-grid">
            <a href="configurations.php" class="card">
                <div class="card-icon">💻</div>
                <h3>Configurations PC</h3>
                <p>Consultez toutes les configurations des 50 postes par département avec détails des composants et justifications techniques.</p>
                <button class="btn">Voir les configurations</button>
            </a>

            <div class="card">
                <div class="card-icon">📊</div>
                <h3>Statistiques</h3>
                <p>Visualisez les statistiques complètes du projet: 7 départements, 50 postes, budget de 172 200 € HT.</p>
                <button class="btn">Voir les stats</button>
            </div>

            <div class="card">
                <div class="card-icon">🌐</div>
                <h3>Infrastructure Réseau</h3>
                <p>Architecture réseau complète avec 7 VLANs, firewall, routeurs, switches et serveurs.</p>
                <button class="btn">Voir l'infrastructure</button>
            </div>

            <div class="card">
                <div class="card-icon">📄</div>
                <h3>Documentation</h3>
                <p>Accédez à toute la documentation technique du projet TechSolutions.</p>
                <button class="btn">Voir la doc</button>
            </div>
        </div>

        <!-- Info Section -->
        <div class="welcome-section" style="margin-top: 3rem;">
            <h3 style="margin-bottom: 1rem;">📦 Projet TechSolutions</h3>
            <p style="margin-bottom: 1rem;">
                Infrastructure informatique complète pour 50 employés répartis sur 7 départements.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>💻 Développement</strong><br>
                    15 postes - 4 500 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>🔧 Infrastructure</strong><br>
                    5 postes - 4 200 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>🎨 Design</strong><br>
                    5 postes - 4 800 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>📢 Marketing</strong><br>
                    10 postes - 2 200 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>🎧 Support</strong><br>
                    5 postes - 2 340 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>📋 RH/Admin</strong><br>
                    5 postes - 1 200 € / poste
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <strong>👔 Direction</strong><br>
                    5 postes - 4 000 € / poste
                </div>
            </div>
        </div>
    </div>
</body>
</html>
