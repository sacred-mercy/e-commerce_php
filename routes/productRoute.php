<?php

require '..\controllers\productsController.php';

$productController = new ProductController();
$products = $productController->getAllProducts();

echo json_encode($products);