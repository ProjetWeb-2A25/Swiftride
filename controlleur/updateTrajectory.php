<?php
require_once __DIR__ . '/../configuration/config.php';

header('Content-Type: application/json');

try {
    // Check if required fields are provided
    if (!isset($_POST['ID_T']) || empty($_POST['ID_T']) || 
        !isset($_POST['field']) || empty($_POST['field']) || 
        !isset($_POST['value']) || $_POST['value'] === '') {
        throw new Exception('ID_T, field, and value are required');
    }

    $id = $_POST['ID_T'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    
    $allowedFields = ['ville_D', 'ville_A', 'date_D', 'distance', 'statue', 'temps_est'];
    if (!in_array($field, $allowedFields)) {
        throw new Exception('Invalid field name');
    }

    
    switch ($field) {
        
        
        case 'distance':
            if (!isset($formData['distance']) || intval($formData['distance']) <= 0) {
                throw new Exception('Distance must be greater than 0');
            }
            break;
        case 'statue':
            if (!is_numeric($value) || $value < 0 || $value > 5) {
                throw new Exception('Status must be a number between 0 and 5');
            }
            break;
        case 'date_D':
                $timestamp = strtotime($value);
                $today = strtotime(date('Y-m-d'));
            
                if (!$timestamp) {
                    throw new Exception('Invalid date format');
                }
            
                if ($timestamp < $today) {
                    throw new Exception('The departure date must be today or a future date.');
            }
            break;
            
            $value = date('Y-m-d H:i:s', strtotime($value));
            break;
        case 'temps_est':
            if (!strtotime($value)) {
                throw new Exception('Invalid time format');
            }
            $value = date('H:i:s', strtotime($value));
            break;
    }

    
    $db = config::getConnexion();
    

    $sql = "UPDATE trajectory SET $field = :value WHERE ID_T = :id";
    $query = $db->prepare($sql);
    $result = $query->execute([
        'value' => $value,
        'id' => $id
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Trajectory updated successfully'
        ]);
    } else {
        throw new Exception('Failed to update trajectory');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
