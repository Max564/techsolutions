<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Récupérer les statistiques
$stats = [
    'messages' => $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = FALSE")->fetchColumn(),
    'clients' => $pdo->query("SELECT COUNT(*) FROM clients WHERE actif = TRUE")->fetchColumn(),
    'devis' => $pdo->query("SELECT COUNT(*) FROM demandes_devis WHERE statut = 'en_attente'")->fetchColumn(),
    'actualites' => $pdo->query("SELECT COUNT(*) FROM actualites WHERE publie = TRUE")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Administration TechSolutions</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <a href="dashboard.php" class="sidebar-logo">TECHSOLUTIONS</a>
            <div class="sidebar-subtitle">Administration</div>
            
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">📊 TABLEAU DE BORD</a></li>
                <li><a href="messages.php">✉️ MESSAGES</a></li>
                <li><a href="clients.php">👥 CLIENTS</a></li>
                <li><a href="devis.php">📄 DEMANDES DE DEVIS</a></li>
                <li><a href="actualites.php">📰 ACTUALITÉS</a></li>
                <li><a href="equipements_reseau.php">🌐 ÉQUIPEMENTS RÉSEAU</a></li>
                <li><a href="composants_pc.php">💻 COMPOSANTS PC</a></li>
                <li><a href="configurations_completes.php">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>TABLEAU DE BORD</h1>
                <div>
                    Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong>
                </div>
            </div>

            <!-- STATISTIQUES -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['messages'] ?></div>
                    <div class="stat-label">Messages non lus</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['clients'] ?></div>
                    <div class="stat-label">Clients actifs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['devis'] ?></div>
                    <div class="stat-label">Devis en attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['actualites'] ?></div>
                    <div class="stat-label">Actualités publiées</div>
                </div>
            </div>

            <!-- VUE D'ENSEMBLE PROJET -->
            <div class="content-section">
                <h2 class="section-title">VUE D'ENSEMBLE PROJET TECHSOLUTIONS</h2>
                
                <div class="stats-grid" style="margin-top: 1.5rem;">
                    <div class="stat-card">
                        <div class="stat-value">7</div>
                        <div class="stat-label">Départements</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">50</div>
                        <div class="stat-label">Postes de travail</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">172 200 €</div>
                        <div class="stat-label">Budget HT</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">298 380 €</div>
                        <div class="stat-label">Budget TTC</div>
                    </div>
                </div>
            </div>

            <!-- DERNIERS MESSAGES -->
            <div class="content-section">
                <h2 class="section-title">DERNIERS MESSAGES</h2>
                <?php
                $stmt = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC LIMIT 5");
                $messages = $stmt->fetchAll();
                
                if (count($messages) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($msg['nom']) ?></strong></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= htmlspecialchars(substr($msg['sujet'], 0, 30)) ?><?= strlen($msg['sujet']) > 30 ? '...' : '' ?></td>
                                    <td><?= date('d/m/Y', strtotime($msg['date_envoi'])) ?></td>
                                    <td>
                                        <?php if ($msg['lu']): ?>
                                            <span class="badge success">Lu</span>
                                        <?php else: ?>
                                            <span class="badge warning">Non lu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="messages.php?view=<?= $msg['id'] ?>" class="btn-small">Voir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <a href="messages.php" class="btn-primary">VOIR TOUS LES MESSAGES</a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucun message pour le moment</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- DERNIÈRES DEMANDES DE DEVIS -->
            <div class="content-section">
                <h2 class="section-title">DERNIÈRES DEMANDES DE DEVIS</h2>
                <?php
                $stmt = $pdo->query("SELECT * FROM demandes_devis ORDER BY date_demande DESC LIMIT 5");
                $devis = $stmt->fetchAll();
                
                if (count($devis) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Contact</th>
                                <th>Besoin</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($devis as $d): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($d['entreprise']) ?></strong></td>
                                    <td><?= htmlspecialchars($d['nom']) ?></td>
                                    <td><?= htmlspecialchars(substr($d['besoin'], 0, 40)) ?><?= strlen($d['besoin']) > 40 ? '...' : '' ?></td>
                                    <td><?= date('d/m/Y', strtotime($d['date_demande'])) ?></td>
                                    <td>
                                        <?php
                                        $status_colors = [
                                            'en_attente' => 'warning',
                                            'en_cours' => 'primary',
                                            'envoye' => 'success',
                                            'accepte' => 'success',
                                            'refuse' => 'danger'
                                        ];
                                        $status_labels = [
                                            'en_attente' => 'En attente',
                                            'en_cours' => 'En cours',
                                            'envoye' => 'Envoyé',
                                            'accepte' => 'Accepté',
                                            'refuse' => 'Refusé'
                                        ];
                                        ?>
                                        <span class="badge <?= $status_colors[$d['statut']] ?? '' ?>">
                                            <?= $status_labels[$d['statut']] ?? $d['statut'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="devis.php?view=<?= $d['id'] ?>" class="btn-small">Voir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <a href="devis.php" class="btn-primary">VOIR TOUS LES DEVIS</a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucune demande de devis pour le moment</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- LIENS RAPIDES -->
            <div class="content-section">
                <h2 class="section-title">ACCÈS RAPIDES</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <a href="configurations_completes.php" class="card" style="text-decoration: none; color: inherit;">
                        <div class="card-title">🖥️ Configurations Complètes</div>
                        <div class="card-body">50 postes détaillés par département</div>
                    </a>
                    <a href="equipements_reseau.php" class="card" style="text-decoration: none; color: inherit;">
                        <div class="card-title">🌐 Équipements Réseau</div>
                        <div class="card-body">Infrastructure réseau complète</div>
                    </a>
                    <a href="composants_pc.php" class="card" style="text-decoration: none; color: inherit;">
                        <div class="card-title">💻 Composants PC</div>
                        <div class="card-body">Catalogue des composants</div>
                    </a>
                    <a href="actualites.php" class="card" style="text-decoration: none; color: inherit;">
                        <div class="card-title">📰 Actualités</div>
                        <div class="card-body">Publier des news</div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
