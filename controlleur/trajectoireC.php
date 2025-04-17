<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../error_log.txt');

try {
    require_once __DIR__ . '/../configuration/config.php';
    require_once __DIR__ . '/../modele/trajectoire.php';

    header('Content-Type: application/json');

    // Handle GET request to fetch all trajectories
    /*if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch all trajectories from the database
        $db = config::getConnexion();
        $sql = "SELECT * FROM trajectory ORDER BY date_D DESC"; 
        $query = $db->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            // Return all trajectories data as JSON
            echo json_encode([
                'success' => true,
                'trajectories' => $results
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No trajectories found']);
        }

    }*/ //else
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        

        $formData = $data['data'];

        // Vérification des champs
        if (
            !isset($formData['ville_D']) || trim($formData['ville_D']) === '' ||
            !isset($formData['ville_A']) || trim($formData['ville_A']) === '' ||
            !isset($formData['date_D']) || trim($formData['date_D']) === '' ||
            !isset($formData['distance']) || trim((string)$formData['distance']) === '' ||
            !isset($formData['temps_est']) || trim($formData['temps_est']) === ''
        ) {
            throw new Exception('All fields are required');
        }

        if (!preg_match('/^[\p{L}\s\-]+$/u', $formData['ville_D'])) {
            throw new Exception('The name of the Departure city has to contain only letters');
        }
        
        if (!preg_match('/^[\p{L}\s\-]+$/u', $formData['ville_A'])) {
            throw new Exception('The name of the arriva city has to contain only letters');
        }
        

        if (!isset($formData['statue']) || $formData['statue'] < 0 || $formData['statue'] > 5) {
            throw new Exception('Status must be between 0 and 5');
        }

        if (!isset($formData['distance']) || intval($formData['distance']) <= 0) {
            throw new Exception('Distance must be greater than 0');
        }
        

        $dateD = strtotime($formData['date_D']);
$today = strtotime(date('Y-m-d')); // today's date at midnight

if ($dateD < $today) {
    throw new Exception('The departure date must be a future date.');
}


        // Création de l'objet trajectory
        // Format the time FIRST
        $temps_est = DateTime::createFromFormat('H:i:s', $formData['temps_est'])
        ?: DateTime::createFromFormat('H:i', $formData['temps_est']);  

        $trajectory = new trajectory(
            $formData['ville_D'],
            $formData['ville_A'],
            $formData['distance'],
            intval($formData['statue']),
            $temps_est
        );

        // Now just set the date (this is fine)
        $trajectory->setDate_D(new DateTime($formData['date_D']));

        $db = config::getConnexion();

        $sql = "INSERT INTO trajectory (ville_D, ville_A, date_D, distance, statue, temps_est) 
                VALUES (:ville_D, :ville_A, :date_D, :distance, :statue, :temps_est)";
        $query = $db->prepare($sql);
        
        // Assurez-vous que vous obtenez une chaîne de caractères dans les paramètres
        $result = $query->execute([
            'ville_D' => $trajectory->getVille_D(),
            'ville_A' => $trajectory->getVille_A(),
            'date_D' => $trajectory->getDate_D(),  // Utilisation de la version formatée de `date_D`
            'distance' => $trajectory->getDistance(),
            'statue' => $trajectory->getStatue(),
            'temps_est' => $trajectory->getTemps_est()  // Utilisation de la version formatée de `temps_est`
        ]);

        if ($result) {
            // Get the complete trajectory data including the new ID
            $newTrajectory = $trajectory;
            $newTrajectory->setID_T($db->lastInsertId());
            
            // Return success response with the new trajectory data
            echo json_encode([
                'success' => true,
                'message' => 'Trajectory added successfully',
                'trajectory' => [
                    'ID_T' => $newTrajectory->getID_T(),
                    'ville_D' => $newTrajectory->getVille_D(),
                    'ville_A' => $newTrajectory->getVille_A(),
                    'date_D' => $newTrajectory->getDate_D(),
                    'distance' => $newTrajectory->getDistance(),
                    'statue' => $newTrajectory->getStatue(),
                    'temps_est' => $newTrajectory->getTemps_est()
                ]
            ]);
        } else {
            throw new Exception('Failed to add trajectory');
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (Exception $e) {
    error_log('Trajectory Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    
}
?>
