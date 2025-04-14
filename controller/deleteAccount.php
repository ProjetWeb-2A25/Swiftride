<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

    if (!$email) {
        echo json_encode([
            'success' => false,
            'message' => 'Email is required'
        ]);
        exit;
    }

    try {
        $db = config::getConnexion();
        $query = "DELETE FROM user WHERE mail = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Account not found'
            ]);
        }
    } catch (Exception $e) {
        error_log('Error deleting account: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error deleting account'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
