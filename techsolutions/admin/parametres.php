<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = 'Paramètres enregistrés avec succès';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Administration TechSolutions</title>
    <link rel="stylesheet" href="../css/admin.css">
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
                <li><a href="configurations_completes.php">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php" class="active">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>⚙️ Paramètres</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong></div>
            </div>

            <?php if ($message): ?>
                <div class="message success" style="margin: 2rem 3rem;">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- INFORMATIONS SITE -->
            <div class="content-section">
                <h2 class="section-title">Informations du site</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Nom du site</label>
                        <input type="text" name="nom_site" value="TechSolutions" required>
                    </div>

                    <div class="form-group">
                        <label>Email de contact</label>
                        <input type="email" name="email_contact" value="contact@techsolutions.fr" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="telephone" value="+33 1 23 45 67 89">
                    </div>

                    <div class="form-group">
                        <label>Adresse</label>
                        <textarea name="adresse">123 Avenue de la République
75011 Paris, France</textarea>
                    </div>

                    <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                </form>
            </div>

            <!-- CONFIGURATION EMAIL -->
            <div class="content-section">
                <h2 class="section-title">Configuration email</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Serveur SMTP</label>
                        <input type="text" name="smtp_host" value="smtp.gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Port SMTP</label>
                        <input type="number" name="smtp_port" value="587">
                    </div>

                    <div class="form-group">
                        <label>Email d'envoi</label>
                        <input type="email" name="smtp_email" value="noreply@techsolutions.fr">
                    </div>

                    <div class="form-group">
                        <label>Mot de passe SMTP</label>
                        <input type="password" name="smtp_password" value="••••••••">
                    </div>

                    <button type="submit" class="btn-primary">Enregistrer la configuration</button>
                </form>
            </div>

            <!-- SÉCURITÉ -->
            <div class="content-section">
                <h2 class="section-title">Sécurité</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Nom d'utilisateur</label>
                        <input type="text" value="<?= $_SESSION['admin_nom'] ?? 'admin' ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" placeholder="Laisser vide pour ne pas changer">
                    </div>

                    <div class="form-group">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe">
                    </div>

                    <button type="submit" class="btn-primary">Changer le mot de passe</button>
                </form>
            </div>

            <!-- MAINTENANCE -->
            <div class="content-section">
                <h2 class="section-title">Maintenance</h2>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Mode maintenance</div>
                        <label class="checkbox-group">
                            <input type="checkbox" name="maintenance">
                            <span>Activer</span>
                        </label>
                    </div>
                    <div class="card-body">
                        Active le mode maintenance sur le site public. L'administration reste accessible.
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Nettoyage base de données</div>
                        <button class="btn-secondary" onclick="return confirm('Supprimer les anciennes données ?')">
                            Nettoyer
                        </button>
                    </div>
                    <div class="card-body">
                        Supprime les messages lus de plus de 30 jours et les sessions expirées.
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Sauvegarde base de données</div>
                        <button class="btn-secondary">Télécharger</button>
                    </div>
                    <div class="card-body">
                        Exporte toute la base de données au format SQL.
                    </div>
                </div>
            </div>

            <!-- INFORMATIONS SYSTÈME -->
            <div class="content-section">
                <h2 class="section-title">Informations système</h2>
                
                <table class="table">
                    <tr>
                        <td><strong>Version PHP</strong></td>
                        <td><?= phpversion() ?></td>
                    </tr>
                    <tr>
                        <td><strong>Version MySQL</strong></td>
                        <td>8.0+</td>
                    </tr>
                    <tr>
                        <td><strong>Serveur web</strong></td>
                        <td>Apache 2.4</td>
                    </tr>
                    <tr>
                        <td><strong>Espace disque</strong></td>
                        <td>45 GB disponibles</td>
                    </tr>
                    <tr>
                        <td><strong>Mémoire PHP</strong></td>
                        <td><?= ini_get('memory_limit') ?></td>
                    </tr>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
