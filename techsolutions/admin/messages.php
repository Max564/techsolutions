<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Traiter actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'marquer_lu') {
        $stmt = $pdo->prepare("UPDATE messages_contact SET lu = TRUE WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    } elseif ($_POST['action'] == 'supprimer') {
        $stmt = $pdo->prepare("DELETE FROM messages_contact WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
    header('Location: messages.php');
    exit;
}

// Filtres
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'tous';
$where = $filtre == 'non_lus' ? 'WHERE lu = FALSE' : '';

// Récupérer messages
$stmt = $pdo->query("SELECT * FROM messages_contact $where ORDER BY date_envoi DESC");
$messages = $stmt->fetchAll();

// Stats
$total = $pdo->query("SELECT COUNT(*) FROM messages_contact")->fetchColumn();
$non_lus = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = FALSE")->fetchColumn();
$lus = $total - $non_lus;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Administration TechSolutions</title>
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
                <li><a href="messages.php" class="active">✉️ MESSAGES</a></li>
                <li><a href="clients.php">👥 CLIENTS</a></li>
                <li><a href="actualites.php">📰 ACTUALITÉS</a></li>
                <li><a href="composants_pc.php">💻 COMPOSANTS PC</a></li>
                <li><a href="configurations_completes.php">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>✉️ Messages</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong></div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Total messages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $non_lus ?></div>
                    <div class="stat-label">Non lus</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $lus ?></div>
                    <div class="stat-label">Lus</div>
                </div>
            </div>

            <!-- CONTENU -->
            <div class="content-section">
                <h2 class="section-title">Liste des messages</h2>

                <!-- FILTRES -->
                <div class="filter-tabs">
                    <a href="?filtre=tous" class="filter-tab <?= $filtre == 'tous' ? 'active' : '' ?>">
                        Tous (<?= $total ?>)
                    </a>
                    <a href="?filtre=non_lus" class="filter-tab <?= $filtre == 'non_lus' ? 'active' : '' ?>">
                        Non lus (<?= $non_lus ?>)
                    </a>
                </div>

                <?php if (count($messages) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Sujet</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr style="<?= !$msg['lu'] ? 'font-weight: 600;' : '' ?>">
                                    <td>
                                        <?php if ($msg['lu']): ?>
                                            <span class="badge">Lu</span>
                                        <?php else: ?>
                                            <span class="badge warning">Non lu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
                                    <td><?= htmlspecialchars($msg['nom']) ?></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= htmlspecialchars($msg['sujet']) ?></td>
                                    <td>
                                        <button onclick="showMessage(<?= $msg['id'] ?>)" class="btn-small">Voir</button>
                                        <?php if (!$msg['lu']): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="marquer_lu">
                                                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                                <button type="submit" class="btn-small edit">Marquer lu</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce message ?')">
                                            <input type="hidden" name="action" value="supprimer">
                                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                            <button type="submit" class="btn-small delete">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucun message<?= $filtre == 'non_lus' ? ' non lu' : '' ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- MODAL -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Détails du message</h3>
                <button class="modal-close" onclick="closeModal()">✕ Fermer</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        function showMessage(id) {
            fetch(`get_message.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('modalBody').innerHTML = `
                        <p><strong>De:</strong> ${data.nom}</p>
                        <p><strong>Email:</strong> <a href="mailto:${data.email}">${data.email}</a></p>
                        ${data.telephone ? `<p><strong>Téléphone:</strong> ${data.telephone}</p>` : ''}
                        <p><strong>Sujet:</strong> ${data.sujet}</p>
                        <p><strong>Date:</strong> ${data.date}</p>
                        <div style="margin-top: 1.5rem; padding: 1.5rem; background: var(--off-white); border-radius: 8px;">
                            <strong>Message:</strong><br><br>
                            ${data.message}
                        </div>
                    `;
                    document.getElementById('messageModal').classList.add('active');
                });
        }

        function closeModal() {
            document.getElementById('messageModal').classList.remove('active');
        }

        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
