<?php
session_start();
require_once '../config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0); // On loggue sans afficher à l'écran
ini_set('log_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Log des données reçues
error_log('=== Début de la requête de connexion ===');
error_log('Méthode: ' . $_SERVER['REQUEST_METHOD']);
error_log('Content-Type: ' . $_SERVER['CONTENT_TYPE']);
error_log('POST data: ' . print_r($_POST, true));
error_log('GET data: ' . print_r($_GET, true));
error_log('Raw input: ' . file_get_contents('php://input'));
error_log('Headers: ' . print_r(getallheaders(), true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    error_log('Données reçues:');
    error_log('- Email: ' . ($email ?: 'non fourni'));
    error_log('- Password: ' . ($password ? 'fourni' : 'non fourni'));

    if (!$email || !$password) {
        $response = [
            'success' => false,
            'message' => 'Email et mot de passe requis',
            'debug' => [
                'email_present' => !empty($email),
                'password_present' => !empty($password)
            ]
        ];
        error_log('Validation échouée: ' . json_encode($response));
        echo json_encode($response);
        exit;
    }

    try {
        $db = config::getConnexion();
        $query = "SELECT * FROM user WHERE mail = :email AND `mot de passe` = :password";
        $stmt = $db->prepare($query);
        error_log('Exécution de la requête SQL avec: ' . json_encode([
            'email' => $email,
            'password' => '***'
        ]));
        $stmt->execute([
            'email' => $email,
            'password' => $password
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            error_log('Utilisateur trouvé: ' . print_r($user, true));
            $response = [
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => [
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'email' => $user['mail'],
                    'phone' => $user['phone'],
                    'role' => $user['role']
                ]
            ];
            error_log('Réponse envoyée: ' . json_encode($response));
            echo json_encode($response);
        } else {
            error_log('Aucun utilisateur trouvé avec ces identifiants');
            echo json_encode([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ]);
        }
    } catch (Exception $e) {
        error_log("Erreur DB: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de base de données'
        ]);
    }

    exit;
}
