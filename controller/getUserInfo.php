<?php
error_reporting(0);
header('Content-Type: application/json');
require_once __DIR__ . '/userC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userC = new UserController();
    $user = $userC->getUserByEmailAndPassword($email, $password);

    if ($user) {
        echo json_encode([
            'success' => true,
            'user' => [
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ]);
    }
}
?>
