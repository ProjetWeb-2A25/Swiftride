<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    // Validate required fields
    $requiredFields = ['name', 'category', 'price', 'stock'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("$field is required");
        }
    }

    // Sanitize and prepare data
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = isset($_POST['image']) ? htmlspecialchars($_POST['image']) : null;
    $added_date = date('Y-m-d');

    // Validate numeric fields
    if ($price <= 0) {
        throw new Exception('Price must be greater than 0');
    }
    if ($stock < 0) {
        throw new Exception('Stock cannot be negative');
    }

    // Insert the product
    $stmt = $pdo->prepare("INSERT INTO product (name, category, description, price, stock, image, added_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$name, $category, $description, $price, $stock, $image, $added_date]);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Product added successfully',
            'productId' => $pdo->lastInsertId()
        ]);
        exit;
    } else {
        throw new Exception('Failed to add product');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
