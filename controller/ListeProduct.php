<?php

include '../Controller/ProductC.php';
$pc = new ProductC();
$liste = $pc->ListeProduct();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>
    <ul>
        <?php foreach ($liste as $p) { ?>
            <li>
                <strong>ID:</strong> <?= $p['id_product']; ?> <br>
                <strong>Name:</strong> <?= htmlspecialchars($p['name']); ?> <br>
                <strong>Category:</strong> <?= htmlspecialchars($p['category']); ?> <br>
                <strong>Description:</strong> <?= htmlspecialchars($p['description']); ?> <br>
                <strong>Price:</strong> <?= $p['price']; ?> €<br>
                <strong>Stock:</strong> <?= $p['stock']; ?> units<br>
                <strong>Added Date:</strong> <?= $p['added_date']; ?> <br>
                <strong>Image:</strong> <?= htmlspecialchars($p['image']); ?> <br>
                <a href="delete_product.php?id=<?= $p['id_product']; ?>">Delete</a>
                <hr>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
