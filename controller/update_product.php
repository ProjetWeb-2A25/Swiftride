<?php
// Désactiver l'affichage des erreurs
error_reporting(0);
ini_set('display_errors', 0);

// S'assurer que rien n'a été envoyé avant
if (headers_sent()) {
    die(json_encode(['success' => false, 'message' => 'Headers already sent']));
}

// Définir l'en-tête JSON
header('Content-Type: application/json');

require_once '../config.php';

try {
    // Inclure la configuration
    $config = new config();
    $pdo = $config->getConnexion();

    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('Product ID is required');
    }

    // Récupérer les données du formulaire
    $id = $_POST['id'];
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    // Valider les données
    if (empty($name) || empty($category) || empty($description) || $price <= 0 || $stock < 0) {
        throw new Exception('All fields are required and must be valid');
    }

    // Mettre à jour le produit dans la base de données
    $sql = "UPDATE product SET 
            name = ?, 
            category = ?, 
            description = ?, 
            price = ?, 
            stock = ? 
            WHERE id_product = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $category, $description, $price, $stock, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        throw new Exception('Product not found or no changes made');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}