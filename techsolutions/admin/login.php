<?php
session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_nom'] = $admin['nom_complet'];
            
            // Log de l'activité
            $stmt = $pdo->prepare("INSERT INTO logs_activite (utilisateur_type, utilisateur_id, action, ip_address) VALUES ('admin', ?, 'Connexion', ?)");
            $stmt->execute([$admin['id'], $_SERVER['REMOTE_ADDR']]);
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Identifiants incorrects';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - TechSolutions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--off-white);
        }
        .login-box {
            background: var(--white);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            border: 2px solid var(--black);
        }
        .login-box h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .login-box p {
            color: var(--medium-gray);
            margin-bottom: 2rem;
        }
        .error-message {
            background: var(--black);
            color: var(--white);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .login-box .form-group {
            margin-bottom: 1.5rem;
        }
        .login-box label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .login-box input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--black);
            background: var(--white);
            font-family: inherit;
            font-size: 1rem;
        }
        .login-box input:focus {
            outline: none;
            background: var(--off-white);
            border-color: var(--blue);
        }
        .login-box button {
            width: 100%;
            padding: 1.2rem;
            background: var(--black);
            color: var(--white);
            border: 2px solid var(--black);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .login-box button:hover {
            background: var(--blue);
            border-color: var(--blue);
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: var(--blue);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Administration</h1>
            <p>Connexion à l'espace administrateur</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
            
            <div class="back-link">
                <a href="../index.php">← Retour au site</a>
            </div>
        </div>
    </div>
</body>
</html>
