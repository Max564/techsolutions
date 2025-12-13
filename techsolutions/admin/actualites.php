<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Traiter actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'ajouter') {
            $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, auteur, publie) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['titre'],
                $_POST['contenu'],
                $_POST['auteur'],
                isset($_POST['publie']) ? 1 : 0
            ]);
            $message = 'Actualité ajoutée avec succès';
        } elseif ($_POST['action'] == 'modifier') {
            $stmt = $pdo->prepare("UPDATE actualites SET titre = ?, contenu = ?, auteur = ?, publie = ? WHERE id = ?");
            $stmt->execute([
                $_POST['titre'],
                $_POST['contenu'],
                $_POST['auteur'],
                isset($_POST['publie']) ? 1 : 0,
                $_POST['id']
            ]);
            $message = 'Actualité modifiée avec succès';
        } elseif ($_POST['action'] == 'supprimer') {
            $stmt = $pdo->prepare("DELETE FROM actualites WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $message = 'Actualité supprimée avec succès';
        }
    }
}

// Récupérer actualités
$stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC");
$actualites = $stmt->fetchAll();

// Stats
$total = count($actualites);
$publiees = $pdo->query("SELECT COUNT(*) FROM actualites WHERE publie = TRUE")->fetchColumn();
$brouillons = $total - $publiees;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - Administration TechSolutions</title>
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
                <li><a href="actualites.php" class="active">📰 ACTUALITÉS</a></li>
                <li><a href="composants_pc.php">💻 COMPOSANTS PC</a></li>
                <li><a href="configurations_completes.php">🖥️ CONFIGURATIONS COMPLÈTES</a></li>
                <li><a href="parametres.php">⚙️ PARAMÈTRES</a></li>
                <li><a href="logout.php">🚪 DÉCONNEXION</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="admin-header">
                <h1>📰 Actualités</h1>
                <div>Connecté: <strong><?= $_SESSION['admin_nom'] ?? 'Admin' ?></strong></div>
            </div>

            <?php if ($message): ?>
                <div class="message success" style="margin: 2rem 3rem;">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Total actualités</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $publiees ?></div>
                    <div class="stat-label">Publiées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $brouillons ?></div>
                    <div class="stat-label">Brouillons</div>
                </div>
            </div>

            <!-- FORMULAIRE AJOUT -->
            <div class="content-section">
                <h2 class="section-title">Nouvelle actualité</h2>
                
                <form method="POST">
                    <input type="hidden" name="action" value="ajouter">
                    
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" name="titre" required>
                    </div>

                    <div class="form-group">
                        <label>Contenu</label>
                        <textarea name="contenu" rows="6" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Auteur</label>
                        <input type="text" name="auteur" value="<?= $_SESSION['admin_nom'] ?? 'Admin' ?>" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="publie" id="publie">
                        <label for="publie">Publier immédiatement</label>
                    </div>

                    <button type="submit" class="btn-primary">Ajouter l'actualité</button>
                </form>
            </div>

            <!-- LISTE ACTUALITÉS -->
            <div class="content-section">
                <h2 class="section-title">Liste des actualités</h2>

                <?php if ($total > 0): ?>
                    <?php foreach ($actualites as $actu): ?>
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <div class="card-title"><?= htmlspecialchars($actu['titre']) ?></div>
                                    <div style="font-size: 0.875rem; color: var(--medium-gray); margin-top: 0.25rem;">
                                        Par <?= htmlspecialchars($actu['auteur']) ?> • 
                                        <?= date('d/m/Y à H:i', strtotime($actu['date_publication'])) ?>
                                    </div>
                                </div>
                                <div>
                                    <?php if ($actu['publie']): ?>
                                        <span class="badge success">Publiée</span>
                                    <?php else: ?>
                                        <span class="badge draft">Brouillon</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars(substr($actu['contenu'], 0, 200))) ?>
                                <?= strlen($actu['contenu']) > 200 ? '...' : '' ?>
                            </div>
                            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                                <button onclick="editActu(<?= $actu['id'] ?>)" class="btn-small edit">Modifier</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette actualité ?')">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="id" value="<?= $actu['id'] ?>">
                                    <button type="submit" class="btn-small delete">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucune actualité</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- MODAL ÉDITION -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Modifier l'actualité</h3>
                <button class="modal-close" onclick="closeModal()">✕ Fermer</button>
            </div>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="modifier">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titre" id="edit_titre" required>
                </div>

                <div class="form-group">
                    <label>Contenu</label>
                    <textarea name="contenu" id="edit_contenu" rows="6" required></textarea>
                </div>

                <div class="form-group">
                    <label>Auteur</label>
                    <input type="text" name="auteur" id="edit_auteur" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="publie" id="edit_publie">
                    <label for="edit_publie">Publier</label>
                </div>

                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <script>
        function editActu(id) {
            fetch(`get_actualite.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_titre').value = data.titre;
                    document.getElementById('edit_contenu').value = data.contenu;
                    document.getElementById('edit_auteur').value = data.auteur;
                    document.getElementById('edit_publie').checked = data.publie;
                    document.getElementById('editModal').classList.add('active');
                });
        }

        function closeModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
