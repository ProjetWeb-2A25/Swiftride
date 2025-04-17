<?php
class Product
{
    private $db;

    public function __construct()
    {
        try {
            require_once __DIR__ . '/../config.php';
            $this->db = config::getConnexion();
        } catch (Exception $e) {
            error_log("Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public function addProduct($data) {
        try {
            $query = "
                INSERT INTO product 
                (name, category, description, price, stock, added_date, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['name'],
                $data['category'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['added_date'],
                $data['image']
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Database Error in addProduct: " . $e->getMessage());
            throw new Exception("Failed to add product to database");
        }
    }

    public function getAllProducts() {
        try {
            $query = "
                SELECT 
                    id_product,
                    name,
                    category,
                    description,
                    price,
                    stock,
                    DATE_FORMAT(added_date, '%Y-%m-%d') AS added_date,
                    image
                FROM product
                ORDER BY id_product ASC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($results === false) {
                error_log("Query returned no results");
                return [];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Database Error in getAllProducts: " . $e->getMessage());
            throw new Exception("Failed to fetch products from database");
        }
    }
}
?>