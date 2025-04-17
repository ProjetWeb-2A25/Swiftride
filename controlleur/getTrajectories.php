<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../modele/trajectoire.php';

header('Content-Type: application/json');

// Set default values for pagination and sorting
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 10;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ID_T';
$order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';

// Validate sort column to prevent SQL injection
$allowedSortColumns = ['ID_T', 'ville_D', 'ville_A', 'date_D', 'distance', 'statue', 'temps_est'];
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'ID_T';
}

try {
    $db = config::getConnexion();
    
    // Get total count for pagination
    $countQuery = $db->query('SELECT COUNT(*) FROM trajectory');
    $totalItems = $countQuery->fetchColumn();
    $totalPages = ceil($totalItems / $limit);
    
    // Adjust page if it exceeds total pages
    $page = min($page, $totalPages);
    $offset = ($page - 1) * $limit;
    
    // Prepare and execute paginated query with sorting
    $sql = "SELECT * FROM trajectory ORDER BY {$sort} {$order} LIMIT :limit OFFSET :offset";
    $query = $db->prepare($sql);
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    
    // Fetch trajectories
    $trajectories = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates for display
    foreach ($trajectories as &$trajectory) {
        if (isset($trajectory['date_D'])) {
            $date = new DateTime($trajectory['date_D']);
            $trajectory['date_D'] = $date->format('Y-m-d H:i:s');
        }
        if (isset($trajectory['temps_est'])) {
            $time = new DateTime($trajectory['temps_est']);
            $trajectory['temps_est'] = $time->format('H:i:s');
        }
    }

    // Return success response with pagination metadata
    echo json_encode([
        'success' => true,
        'data' => $trajectories,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_items' => $totalItems,
            'total_pages' => $totalPages
        ],
        'sort' => [
            'column' => $sort,
            'order' => $order
        ]
    ]);

} catch (PDOException $e) {
    // Database-specific error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error_code' => $e->getCode()
    ]);
} catch (Exception $e) {
    // General error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred',
        'error_code' => $e->getCode()
    ]);
}
?>
