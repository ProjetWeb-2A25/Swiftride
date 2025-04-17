<?php
include '../config.php';

class Produit {
    public function listeProduits()
    {
        $db = config::getConnexion();
        try {
            $liste = $db->query('SELECT * FROM product');
            return $liste;
        }
        catch(Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteProduit($id_product)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
                DELETE FROM product WHERE id_product=:id_product
            ');
            $req->execute([
                'id_product' => $id_product
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function ajouterProduit($produit)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
                INSERT INTO product (name, category, description, price, stock, added_date, image) 
                VALUES (:name, :category, :description, :price, :stock, :added_date, :image)
            ');
            $req->execute([
                'name' => $produit->getName(),
                'category' => $produit->getCategory(),
                'description' => $produit->getDescription(),
                'price' => $produit->getPrice(),
                'stock' => $produit->getStock(),
                'added_date' => $produit->getAddedDate(),
                'image' => $produit->getImage()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getProduit($id_product)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('SELECT * FROM product WHERE id_product=:id_product');
            $req->execute(['id_product' => $id_product]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateProduit($id_product, $produit)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
                UPDATE product SET 
                    name=:name, 
                    category=:category, 
                    description=:description, 
                    price=:price, 
                    stock=:stock, 
                    added_date=:added_date,
                    image=:image
                WHERE id_product=:id_product
            ');
            $req->execute([
                'id_product' => $id_product,
                'name' => $produit->getName(),
                'category' => $produit->getCategory(),
                'description' => $produit->getDescription(),
                'price' => $produit->getPrice(),
                'stock' => $produit->getStock(),
                'added_date' => $produit->getAddedDate(),
                'image' => $produit->getImage()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}