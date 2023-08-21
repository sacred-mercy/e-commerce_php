<?php

require '..\models\productsModel.php';

class ProductController{
    
    function getAllProducts(){
        // get products from model
        $productModel = new ProductModel();
        $products = $productModel->getAllProducts();
        return $products;
    }
}