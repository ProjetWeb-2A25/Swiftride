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

try {
    // Inclure la configuration
    require_once '../config.php';
    $config = new config();
    $pdo = $config->getConnexion();

    // Vérifier l'ID du produit
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('Product ID is required');
    }

    $productId = intval($_POST['id']);
    
    // Préparer et exécuter la suppression
    $stmt = $pdo->prepare("DELETE FROM product WHERE id_product = ?");
    $success = $stmt->execute([$productId]);
    
    if ($success && $stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found or already deleted'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
