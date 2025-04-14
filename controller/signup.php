<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../config/config.php';

// Log des données reçues
file_put_contents('debug.log', "\n=== NOUVELLE INSCRIPTION ===\n", FILE_APPEND);
file_put_contents('debug.log', date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Afficher les données brutes reçues
$rawData = file_get_contents('php://input');
file_put_contents('debug.log', "Données brutes reçues:\n{$rawData}\n\n", FILE_APPEND);

// Afficher les données POST
file_put_contents('debug.log', "Données POST:\n" . print_r($_POST, true) . "\n\n", FILE_APPEND);

try {
    // Vérification des données POST
    if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || 
        !isset($_POST['phone']) || !isset($_POST['role']) || !isset($_POST['password'])) {
        throw new Exception('Tous les champs sont requis');
    }

    // Récupération et nettoyage des données
    $nom = trim($_POST['lastname']);
    $prenom = trim($_POST['firstname']);
    $mail = trim($_POST['email']);
    $telephone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $mot_de_passe = $_POST['password'];

    // Connexion à la base de données
    $db = config::getConnexion();
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Database connection successful\n', FILE_APPEND);

    // Vérifier si l'email existe déjà
    $checkEmailSql = "SELECT COUNT(*) FROM user WHERE mail = ?";
    $checkEmailStmt = $db->prepare($checkEmailSql);
    $checkEmailStmt->execute([$mail]);
    $emailExists = $checkEmailStmt->fetchColumn() > 0;

    if ($emailExists) {
        throw new Exception('Cet email est déjà utilisé. Veuillez en choisir un autre.');
    }

    // Log l'email reçu
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Email reçu: {$mail}\n", FILE_APPEND);

    // Préparation de la requête d'insertion
    $sql = "INSERT INTO user (nom, prenom, mail, telephone, role, `mot de passe`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    
    // Exécution de la requête avec les valeurs
    $params = [$nom, $prenom, $mail, $telephone, $role, $mot_de_passe];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Executing query with params: ' . print_r($params, true) . "\n", FILE_APPEND);
    
    $success = $stmt->execute($params);
    
    if (!$success) {
        file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - SQL Error: ' . print_r($stmt->errorInfo(), true) . "\n", FILE_APPEND);
    }
    
    if (!$success) {
        throw new Exception('Erreur lors de l\'insertion : ' . implode(', ', $stmt->errorInfo()));
    }

    $newUserId = $db->lastInsertId();
    if (!$newUserId) {
        throw new Exception('L\'utilisateur a été créé mais impossible de récupérer son ID');
    }

    echo json_encode([
        'success' => true, 
        'message' => 'Inscription réussie !'
    ]);

} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'debug' => [
            'post_data' => $_POST,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?>
