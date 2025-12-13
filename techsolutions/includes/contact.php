<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validation
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        header('Location: ../index.php?error=champs_requis#contact');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../index.php?error=email_invalide#contact');
        exit;
    }
    
    if (!isset($_POST['rgpd'])) {
        header('Location: ../index.php?error=rgpd#contact');
        exit;
    }
    
    try {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, telephone, sujet, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $sujet, $message]);
        
        // Envoi d'email (optionnel - nécessite configuration SMTP)
        $to = 'contact@techsolutions.fr';
        $subject = 'Nouveau message de contact - ' . $sujet;
        $body = "Nom: $nom\n";
        $body .= "Email: $email\n";
        $body .= "Téléphone: $telephone\n";
        $body .= "Sujet: $sujet\n\n";
        $body .= "Message:\n$message\n";
        $headers = "From: noreply@techsolutions.fr\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Décommenter pour activer l'envoi d'email
        // mail($to, $subject, $body, $headers);
        
        header('Location: ../index.php?success=1#contact');
        exit;
    } catch (PDOException $e) {
        error_log("Erreur contact form: " . $e->getMessage());
        header('Location: ../index.php?error=serveur#contact');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
