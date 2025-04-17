<?php
require_once __DIR__ . '/../configuration/config.php';

header('Content-Type: application/json');

try {
    // Check if ID_T is provided
    if (!isset($_POST['ID_T']) || empty($_POST['ID_T'])) {
        throw new Exception('Trajectory ID is required');
    }

    $id = $_POST['ID_T'];

    // Connect to database
    $db = config::getConnexion();
    
    // Prepare and execute delete query
    $sql = "DELETE FROM trajectory WHERE ID_T = :id";
    $query = $db->prepare($sql);
    $result = $query->execute(['id' => $id]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Trajectory deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete trajectory');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
