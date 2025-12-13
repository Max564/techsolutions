<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Récupérer clients
$stmt = $pdo->query("SELECT * FROM clients ORDER BY date_inscription DESC");
$clients = $stmt->fetchAll();

// Stats
$total = count($clients);
$actifs = $pdo->query("SELECT COUNT(*) FROM clients WHERE actif = TRUE")->fetchColumn();
$inactifs = $total - $actifs;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - Administration TechSolutions</title>
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
                <li><a href="clients.php" class="active">👥 CLIENTS</a></li>
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
                <h1>👥 Clients</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong></div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Total clients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $actifs ?></div>
                    <div class="stat-label">Clients actifs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $inactifs ?></div>
                    <div class="stat-label">Clients inactifs</div>
                </div>
            </div>

            <!-- CONTENU -->
            <div class="content-section">
                <h2 class="section-title">Liste des clients</h2>

                <!-- RECHERCHE -->
                <input type="text" id="searchBox" class="search-box" placeholder="🔍 Rechercher un client...">

                <?php if ($total > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Statut</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Entreprise</th>
                                <th>Téléphone</th>
                                <th>Date inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTable">
                            <?php foreach ($clients as $client): ?>
                                <tr data-search="<?= strtolower($client['nom'] . ' ' . $client['prenom'] . ' ' . $client['email'] . ' ' . $client['entreprise']) ?>">
                                    <td>
                                        <?php if ($client['actif']): ?>
                                            <span class="badge success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge draft">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($client['email']) ?></td>
                                    <td><?= htmlspecialchars($client['entreprise'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($client['telephone'] ?? '-') ?></td>
                                    <td><?= date('d/m/Y', strtotime($client['date_inscription'])) ?></td>
                                    <td>
                                        <button onclick="showClient(<?= $client['id'] ?>)" class="btn-small">Voir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucun client enregistré</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- MODAL -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Détails du client</h3>
                <button class="modal-close" onclick="closeModal()">✕ Fermer</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        // Recherche en temps réel
        document.getElementById('searchBox').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#clientsTable tr');
            
            rows.forEach(row => {
                const searchText = row.getAttribute('data-search');
                if (searchText.includes(search)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function showClient(id) {
            // Simuler récupération données
            fetch(`get_client.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('modalBody').innerHTML = `
                        <div style="display: grid; gap: 1rem;">
                            <p><strong>Nom complet:</strong> ${data.prenom} ${data.nom}</p>
                            <p><strong>Email:</strong> <a href="mailto:${data.email}">${data.email}</a></p>
                            <p><strong>Téléphone:</strong> ${data.telephone || '-'}</p>
                            <p><strong>Entreprise:</strong> ${data.entreprise || '-'}</p>
                            <p><strong>Adresse:</strong> ${data.adresse || '-'}</p>
                            <p><strong>Ville:</strong> ${data.ville || '-'} ${data.code_postal || ''}</p>
                            <p><strong>Date inscription:</strong> ${data.date_inscription}</p>
                            <p><strong>Statut:</strong> ${data.actif ? '<span class="badge success">Actif</span>' : '<span class="badge draft">Inactif</span>'}</p>
                        </div>
                    `;
                    document.getElementById('clientModal').classList.add('active');
                })
                .catch(err => {
                    alert('Erreur lors du chargement des données');
                });
        }

        function closeModal() {
            document.getElementById('clientModal').classList.remove('active');
        }

        document.getElementById('clientModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
