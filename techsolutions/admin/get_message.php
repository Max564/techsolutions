<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    die(json_encode(['error' => 'Non autorisé']));
}

require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM messages_contact WHERE id = ?");
$stmt->execute([$id]);
$msg = $stmt->fetch();

if ($msg) {
    echo json_encode([
        'nom' => htmlspecialchars($msg['nom']),
        'email' => htmlspecialchars($msg['email']),
        'telephone' => htmlspecialchars($msg['telephone']),
        'sujet' => htmlspecialchars($msg['sujet']),
        'message' => nl2br(htmlspecialchars($msg['message'])),
        'date' => date('d/m/Y à H:i', strtotime($msg['date_envoi']))
    ]);
} else {
    echo json_encode(['error' => 'Message non trouvé']);
}
