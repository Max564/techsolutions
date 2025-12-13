<?php
session_start();

if (isset($_SESSION['client_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/db.php';

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide';
    } elseif ($password !== $password_confirm) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Un compte existe déjà avec cet email';
        } else {
            // Créer le compte
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, telephone, password) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$nom, $prenom, $email, $telephone, $password_hash])) {
                $client_id = $pdo->lastInsertId();
                
                // Connexion automatique
                $_SESSION['client_id'] = $client_id;
                $_SESSION['client_nom'] = $nom;
                $_SESSION['client_prenom'] = $prenom;
                $_SESSION['client_email'] = $email;
                
                // Log de l'activité
                $stmt = $pdo->prepare("INSERT INTO logs_activite (utilisateur_type, utilisateur_id, action, ip_address) VALUES ('client', ?, 'Inscription', ?)");
                $stmt->execute([$client_id, $_SERVER['REMOTE_ADDR']]);
                
                header('Location: dashboard.php?welcome=1');
                exit;
            } else {
                $error = 'Une erreur est survenue lors de la création du compte';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - TechSolutions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--off-white); padding: 2rem 0; }
        .login-box { background: var(--white); padding: 3rem; max-width: 500px; width: 100%; border: 2px solid var(--black); }
        .login-box h1 { font-size: 2rem; margin-bottom: 0.5rem; }
        .login-box p { color: var(--medium-gray); margin-bottom: 2rem; }
        .error-message { background: var(--black); color: var(--white); padding: 1rem; margin-bottom: 1.5rem; }
        .login-box .form-group { margin-bottom: 1.5rem; }
        .login-box label { display: block; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        .login-box input { width: 100%; padding: 1rem; border: 2px solid var(--black); background: var(--white); font-family: inherit; font-size: 1rem; }
        .login-box input:focus { outline: none; background: var(--off-white); border-color: var(--blue); }
        .login-box button { width: 100%; padding: 1.2rem; background: var(--black); color: var(--white); border: 2px solid var(--black); font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 2px; }
        .login-box button:hover { background: var(--blue); border-color: var(--blue); }
        .back-link { text-align: center; margin-top: 1.5rem; }
        .back-link a { color: var(--blue); text-decoration: none; }
        .login-link { text-align: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--light-gray); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Créer un compte</h1>
            <p>Inscrivez-vous pour accéder à votre espace personnel</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe * (min. 6 caractères)</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                <button type="submit">Créer mon compte</button>
            </form>
            
            <div class="login-link">
                <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
            </div>
            
            <div class="back-link">
                <a href="../index.php">← Retour au site</a>
            </div>
        </div>
    </div>
</body>
</html>
