<?php
session_start();

if (isset($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
    
    // Log de l'activité
    require_once '../includes/db.php';
    $stmt = $pdo->prepare("INSERT INTO logs_activite (utilisateur_type, utilisateur_id, action, ip_address) VALUES ('client', ?, 'Déconnexion', ?)");
    $stmt->execute([$client_id, $_SERVER['REMOTE_ADDR']]);
}

session_destroy();
header('Location: ../index.php');
exit;
?>
