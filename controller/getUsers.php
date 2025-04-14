<?php
require_once '../config/config.php';

try {
    $conn = config::getConnexion();
    $query = "SELECT nom, prenom, mail FROM user";
    $statement = $conn->prepare($query);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($users);

} catch(PDOException $e) {
    error_log("Database error in getUsers.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    error_log("General error in getUsers.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
