<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    die(json_encode(['error' => 'Non autorisé']));
}

require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM actualites WHERE id = ?");
$stmt->execute([$id]);
$actu = $stmt->fetch();

if ($actu) {
    echo json_encode([
        'id' => $actu['id'],
        'titre' => htmlspecialchars($actu['titre']),
        'contenu' => htmlspecialchars($actu['contenu']),
        'auteur' => htmlspecialchars($actu['auteur']),
        'publie' => (bool)$actu['publie']
    ]);
} else {
    echo json_encode(['error' => 'Actualité non trouvée']);
}
