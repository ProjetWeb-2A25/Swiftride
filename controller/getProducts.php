<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    require_once __DIR__.'/../model/Product.php';
    
    $productManager = new Product();
    $products = $productManager->getAllProducts();
    
    if ($products === false) {
        throw new Exception('Failed to fetch products');
    }
    
    // Formatage des données selon la structure de votre BD
    $formattedProducts = array();
    foreach ($products as $product) {
        $formattedProducts[] = [
            'id' => $product['id_product'],
            'name' => $product['name'],
            'category' => $product['category'],
            'description' => $product['description'],
            'price' => $product['price'],
            'stock' => $product['stock'],
            'added_date' => $product['added_date'],
            'image' => $product['image'] ?? null // Utilisez le champ exact de votre BD
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $formattedProducts
    ]);
} catch (Exception $e) {
    error_log("Error in getProducts.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>