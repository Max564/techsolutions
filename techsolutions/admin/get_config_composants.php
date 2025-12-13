<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    die(json_encode(['error' => 'Non autorisé']));
}

require_once '../includes/db.php';

$dept_id = isset($_GET['dept_id']) ? (int)$_GET['dept_id'] : 0;

// Récupérer le département
$stmt = $pdo->prepare("SELECT * FROM departements WHERE id_departement = ?");
$stmt->execute([$dept_id]);
$dept = $stmt->fetch();

if (!$dept) {
    die(json_encode(['error' => 'Département non trouvé']));
}

// Récupérer la configuration
$stmt = $pdo->prepare("SELECT * FROM configurations_pc WHERE id_departement = ? LIMIT 1");
$stmt->execute([$dept_id]);
$config = $stmt->fetch();

if (!$config) {
    die(json_encode(['error' => 'Configuration non trouvée']));
}

// Récupérer les composants de cette configuration
$stmt = $pdo->prepare("
    SELECT 
        cat.nom_categorie as categorie,
        cp.nom_composant,
        cp.specifications,
        cp.prix_unitaire,
        pcd.quantite
    FROM pc_composants_detail pcd
    JOIN composants_pc cp ON pcd.id_composant = cp.id_composant
    JOIN categories_composants cat ON cp.id_categorie = cat.id_categorie
    WHERE pcd.id_config = ?
    ORDER BY cat.nom_categorie, cp.prix_unitaire DESC
");
$stmt->execute([$config['id_config']]);
$composants = $stmt->fetchAll();

echo json_encode([
    'nom_departement' => $dept['nom_departement'],
    'prix_total' => number_format($config['prix_total'], 2, ',', ' '),
    'composants' => array_map(function($comp) {
        return [
            'categorie' => $comp['categorie'],
            'nom_composant' => htmlspecialchars($comp['nom_composant']),
            'specifications' => htmlspecialchars($comp['specifications']),
            'prix_unitaire' => number_format($comp['prix_unitaire'], 2, ',', ' '),
            'quantite' => $comp['quantite']
        ];
    }, $composants)
]);
