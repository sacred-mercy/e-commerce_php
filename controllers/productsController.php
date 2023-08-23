<?php

require '..\models\productsModel.php';

class ProductController{
    
    function getAllProducts(){
        // get products from model
        $productModel = new ProductModel();
        $products = $productModel->getAllProducts();
        if ($products['statusCode'] === '200') {
            return array(
                'products' => $products['products'],
                'statusCode' => '200'
            );
        } else {
            return array(
                'error' => $products['error'],
                'statusCode' => '400'
            );
        }
    }
}