<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

$message = '';
$error = '';

// Traitement des modifications
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_info':
                $nom = htmlspecialchars(trim($_POST['nom']));
                $prenom = htmlspecialchars(trim($_POST['prenom']));
                $telephone = htmlspecialchars(trim($_POST['telephone']));
                $entreprise = htmlspecialchars(trim($_POST['entreprise']));
                $adresse = htmlspecialchars(trim($_POST['adresse']));
                $ville = htmlspecialchars(trim($_POST['ville']));
                $code_postal = htmlspecialchars(trim($_POST['code_postal']));
                
                $stmt = $pdo->prepare("UPDATE clients SET nom = ?, prenom = ?, telephone = ?, entreprise = ?, adresse = ?, ville = ?, code_postal = ? WHERE id = ?");
                if ($stmt->execute([$nom, $prenom, $telephone, $entreprise, $adresse, $ville, $code_postal, $_SESSION['client_id']])) {
                    $message = 'Vos informations ont été mises à jour avec succès';
                    $_SESSION['client_nom'] = $nom;
                    $_SESSION['client_prenom'] = $prenom;
                } else {
                    $error = 'Erreur lors de la mise à jour';
                }
                break;
                
            case 'change_password':
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];
                
                // Vérifier le mot de passe actuel
                $stmt = $pdo->prepare("SELECT password FROM clients WHERE id = ?");
                $stmt->execute([$_SESSION['client_id']]);
                $client = $stmt->fetch();
                
                if (!password_verify($current_password, $client['password'])) {
                    $error = 'Mot de passe actuel incorrect';
                } elseif ($new_password !== $confirm_password) {
                    $error = 'Les nouveaux mots de passe ne correspondent pas';
                } elseif (strlen($new_password) < 6) {
                    $error = 'Le nouveau mot de passe doit contenir au moins 6 caractères';
                } else {
                    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE clients SET password = ? WHERE id = ?");
                    if ($stmt->execute([$new_hash, $_SESSION['client_id']])) {
                        $message = 'Votre mot de passe a été modifié avec succès';
                    }
                }
                break;
                
            case 'delete_account':
                if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'SUPPRIMER') {
                    // Anonymiser les données au lieu de supprimer (RGPD)
                    $stmt = $pdo->prepare("UPDATE clients SET nom = 'Utilisateur', prenom = 'Supprimé', email = CONCAT('deleted_', id, '@deleted.com'), telephone = '', entreprise = '', adresse = '', actif = FALSE WHERE id = ?");
                    if ($stmt->execute([$_SESSION['client_id']])) {
                        session_destroy();
                        header('Location: ../index.php?account_deleted=1');
                        exit;
                    }
                }
                break;
        }
    }
}

// Récupérer les informations du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$_SESSION['client_id']]);
$client = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - TechSolutions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .dashboard-container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .dashboard-header { background: var(--black); color: var(--white); padding: 2rem; margin: -2rem -2rem 2rem -2rem; }
        .dashboard-header h1 { font-size: 2rem; margin-bottom: 0.5rem; }
        .dashboard-header p { color: var(--light-gray); }
        .dashboard-nav { display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid var(--black); }
        .dashboard-nav a { padding: 1rem 2rem; text-decoration: none; color: var(--black); font-weight: 600; border-bottom: 3px solid transparent; transition: all 0.3s ease; }
        .dashboard-nav a.active, .dashboard-nav a:hover { border-bottom-color: var(--blue); color: var(--blue); }
        .dashboard-section { background: var(--white); padding: 2rem; margin-bottom: 2rem; border: 2px solid var(--black); }
        .section-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 1rem; border: 2px solid var(--black); font-family: inherit; font-size: 1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .btn-primary { padding: 1rem 2rem; background: var(--black); color: var(--white); border: 2px solid var(--black); font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; }
        .btn-primary:hover { background: var(--blue); border-color: var(--blue); }
        .btn-danger { padding: 1rem 2rem; background: #e74c3c; color: var(--white); border: 2px solid #e74c3c; font-weight: 700; cursor: pointer; }
        .message { padding: 1rem; margin-bottom: 1.5rem; background: var(--blue); color: var(--white); }
        .error-message { background: var(--black); }
        .info-box { background: var(--off-white); padding: 1.5rem; border-left: 4px solid var(--blue); margin: 1.5rem 0; }
        .danger-zone { background: #fee; border: 2px solid #e74c3c; padding: 2rem; margin-top: 2rem; }
        .back-link { margin-top: 2rem; }
        .back-link a { color: var(--blue); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="dashboard-container" style="padding-top: 6rem;">
        <div class="dashboard-header">
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['client_prenom']); ?> !</h1>
            <p>Gérez vos informations personnelles et vos données</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['welcome'])): ?>
            <div class="message">Bienvenue sur votre espace personnel ! Vous pouvez maintenant gérer vos informations.</div>
        <?php endif; ?>
        
        <div class="dashboard-section">
            <h2 class="section-title">Mes Informations Personnelles</h2>
            <p style="color: var(--medium-gray); margin-bottom: 2rem;">
                Conformément au RGPD, vous avez le droit de modifier, consulter et supprimer vos données personnelles.
            </p>
            <form method="POST">
                <input type="hidden" name="action" value="update_info">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email (non modifiable)</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($client['email']); ?>" disabled>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($client['telephone']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="entreprise">Entreprise</label>
                        <input type="text" id="entreprise" name="entreprise" value="<?php echo htmlspecialchars($client['entreprise']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($client['adresse']); ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($client['ville']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="code_postal">Code Postal</label>
                        <input type="text" id="code_postal" name="code_postal" value="<?php echo htmlspecialchars($client['code_postal']); ?>">
                    </div>
                </div>
                <button type="submit" class="btn-primary">Mettre à jour mes informations</button>
            </form>
        </div>
        
        <div class="dashboard-section">
            <h2 class="section-title">Changer mon mot de passe</h2>
            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="form-group">
                    <label for="current_password">Mot de passe actuel *</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe * (min. 6 caractères)</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le nouveau mot de passe *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-primary">Changer le mot de passe</button>
            </form>
        </div>
        
        <div class="dashboard-section">
            <h2 class="section-title">Mes Données et Vie Privée (RGPD)</h2>
            <div class="info-box">
                <h3 style="margin-bottom: 0.5rem;">Vos Droits</h3>
                <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants :</p>
                <ul style="margin-left: 2rem; margin-top: 0.5rem;">
                    <li>Droit d'accès à vos données personnelles</li>
                    <li>Droit de rectification de vos données</li>
                    <li>Droit à l'effacement de vos données</li>
                    <li>Droit à la portabilité de vos données</li>
                </ul>
            </div>
            
            <p><strong>Compte créé le :</strong> <?php echo date('d/m/Y à H:i', strtotime($client['date_inscription'])); ?></p>
            <p><strong>Dernière connexion :</strong> <?php echo $client['derniere_connexion'] ? date('d/m/Y à H:i', strtotime($client['derniere_connexion'])) : 'Jamais'; ?></p>
            
            <div class="danger-zone" style="margin-top: 2rem;">
                <h3 style="color: #e74c3c; margin-bottom: 1rem;">Zone Dangereuse</h3>
                <p style="margin-bottom: 1rem;">La suppression de votre compte est irréversible. Toutes vos données personnelles seront anonymisées.</p>
                <form method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                    <input type="hidden" name="action" value="delete_account">
                    <div class="form-group">
                        <label for="confirm_delete">Tapez "SUPPRIMER" pour confirmer</label>
                        <input type="text" id="confirm_delete" name="confirm_delete" required>
                    </div>
                    <button type="submit" class="btn-danger">Supprimer définitivement mon compte</button>
                </form>
            </div>
        </div>
        
        <div class="back-link">
            <a href="../index.php">← Retour au site</a> | 
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>
