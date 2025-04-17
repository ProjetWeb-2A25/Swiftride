<?php
class ProduitEntity {
    private $name, $category, $description, $price, $stock, $added_date, $image;

    public function __construct($name, $category, $description, $price, $stock, $added_date, $image) {
        $this->name = $name;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->added_date = $added_date;
        $this->image = $image;
    }

    public function getName() { return $this->name; }
    public function getCategory() { return $this->category; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getStock() { return $this->stock; }
    public function getAddedDate() { return $this->added_date; }
    public function getImage() { return $this->image; }
}
?>
