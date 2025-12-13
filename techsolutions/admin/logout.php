<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
    // Log de l'activité
    require_once '../includes/db.php';
    $stmt = $pdo->prepare("INSERT INTO logs_activite (utilisateur_type, utilisateur_id, action, ip_address) VALUES ('admin', ?, 'Déconnexion', ?)");
    $stmt->execute([$admin_id, $_SERVER['REMOTE_ADDR']]);
}

session_destroy();
header('Location: login.php');
exit;
?>
