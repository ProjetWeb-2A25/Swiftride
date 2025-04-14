<?php
header('Content-Type: application/json');
require_once '../config/config.php';

try {
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Email et mot de passe requis');
    }

    $email = $data['email'];
    $password = $data['password'];

    // Connexion à la base de données
    $db = config::getConnexion();

    // Vérifier les identifiants
    $stmt = $db->prepare("SELECT * FROM user WHERE mail = ? AND `mot de passe` = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('Email ou mot de passe incorrect');
    }

    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'mail' => $user['mail'],
            'telephone' => $user['telephone']
        ]
    ]);

} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
