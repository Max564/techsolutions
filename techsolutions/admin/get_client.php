<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    die(json_encode(['error' => 'Non autorisé']));
}

require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if ($client) {
    echo json_encode([
        'nom' => htmlspecialchars($client['nom']),
        'prenom' => htmlspecialchars($client['prenom']),
        'email' => htmlspecialchars($client['email']),
        'telephone' => htmlspecialchars($client['telephone']),
        'entreprise' => htmlspecialchars($client['entreprise']),
        'adresse' => htmlspecialchars($client['adresse']),
        'ville' => htmlspecialchars($client['ville']),
        'code_postal' => htmlspecialchars($client['code_postal']),
        'date_inscription' => date('d/m/Y', strtotime($client['date_inscription'])),
        'actif' => (bool)$client['actif']
    ]);
} else {
    echo json_encode(['error' => 'Client non trouvé']);
}
