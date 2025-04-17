<?php
error_reporting(0);
ini_set('display_errors', 0);

if (headers_sent()) {
    die(json_encode(['success' => false, 'message' => 'Headers already sent']));
}

header('Content-Type: application/json');

try {
    require_once '../config.php';
    $config = new config();
    $pdo = $config->getConnexion();

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('Product ID is required');
    }

    $productId = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id_product = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('Product not found');
    }

    echo json_encode([
        'success' => true,
        'data' => $product
    ]);

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}