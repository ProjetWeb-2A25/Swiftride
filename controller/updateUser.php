<?php
error_reporting(0);
header('Content-Type: application/json');
require_once __DIR__ . '/userC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $newData = [
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'role' => $_POST['role'] ?? ''
    ];

    $userC = new UserController();
    
    // Vérifier d'abord si l'utilisateur existe
    $user = $userC->getUserByEmailAndPassword($email, $password);
    
    if ($user) {
        // Mettre à jour l'utilisateur
        if ($userC->updateUser($email, $newData)) {
            echo json_encode([
                'success' => true,
                'message' => 'Informations mises à jour avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ]);
    }
}
?>
