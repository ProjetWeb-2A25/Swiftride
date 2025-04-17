<?php
// Toujours commencer par les headers
header('Content-Type: application/json');

// Activation du reporting d'erreurs (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Ne pas afficher les erreurs au client
ini_set('log_errors', 1);

require_once __DIR__.'/../model/Product.php';

try {
    // Vérification méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée", 405);
    }

    // Validation des données
    $required = ['name', 'category', 'price', 'stock'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Le champ $field est requis", 400);
        }
    }
    
    // Handle image
    $imagePath = '';
    if (!empty($_POST['image'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        $imagePath = basename($_POST['image']); // Get just the filename, no path
        
        // Verify the file exists in uploads directory
        if (!file_exists($uploadDir . $imagePath)) {
            throw new Exception('Image file not found in uploads directory');
        }

        // Validate file type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $uploadDir . $imagePath);
        finfo_close($fileInfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Type de fichier non autorisé. Seuls JPG, PNG et GIF sont acceptés.');
        }
        // No need to move file since it's already in the uploads directory
    }

    // Préparation des données
    $productData = [
        'name' => htmlspecialchars($_POST['name']),
        'category' => htmlspecialchars($_POST['category']),
        'description' => htmlspecialchars($_POST['description'] ?? ''),
        'price' => floatval($_POST['price']),
        'stock' => intval($_POST['stock']),
        'added_date' => date('Y-m-d'),
        'image' => $imagePath
    ];

    // Enregistrement
    $productManager = new Product();
    $result = $productManager->addProduct($productData);

    if (!$result) {
        throw new Exception("Échec de l'enregistrement", 500);
    }

    // Notify WebSocket server about the new product
    $ch = curl_init('http://localhost:8080/notify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'type' => 'productUpdate',
        'data' => 'Product added'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);

    // Réponse JSON en cas de succès
    echo json_encode([
        'success' => true,
        'message' => 'Produit enregistré',
        'id' => $result, // L'ID du nouveau produit
        'insertId' => $result // Alternative selon votre implémentation
    ]);

} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}