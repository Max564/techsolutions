<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Configurations - TechSolutions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            text-align: center;
        }
        p {
            color: #6c757d;
            margin-bottom: 2rem;
            text-align: center;
            line-height: 1.6;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            transition: transform 0.3s;
            margin-bottom: 1rem;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        .info h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .info p {
            margin: 0;
            font-size: 0.9rem;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🖥️ Configurations PC TechSolutions</h1>
        <p>Accédez aux configurations détaillées des 50 postes de travail répartis sur 7 départements</p>
        
        <?php
        // Créer une session simple pour tester
        session_start();
        if (!isset($_SESSION['client_id'])) {
            $_SESSION['client_id'] = 1;
            $_SESSION['client_email'] = 'test@techsolutions.fr';
            $_SESSION['client_nom'] = 'Test';
            $_SESSION['client_prenom'] = 'Utilisateur';
        }
        ?>
        
        <a href="configurations.php" class="btn">📊 Voir toutes les configurations</a>
        <a href="dashboard_simple.php" class="btn">🏠 Dashboard</a>
        
        <div class="info">
            <h3>📦 Ce qui vous attend:</h3>
            <p>
                ✅ 7 départements avec configurations détaillées<br>
                ✅ 50 postes de travail complets<br>
                ✅ Budget total: 172 200 € HT<br>
                ✅ Justifications techniques pour chaque composant<br>
                ✅ Prix et spécifications détaillés
            </p>
        </div>
        
        <div class="info" style="margin-top: 1rem;">
            <h3>🏢 Départements:</h3>
            <p>
                💻 Développement (15 postes)<br>
                🔧 Infrastructure (5 postes)<br>
                🎨 Design (5 postes)<br>
                📢 Marketing (10 postes)<br>
                🎧 Support (5 postes)<br>
                📋 RH/Admin (5 postes)<br>
                👔 Direction (5 postes)
            </p>
        </div>
    </div>
</body>
</html>
