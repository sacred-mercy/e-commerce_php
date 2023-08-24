<?php

require_once dirname(__DIR__) . '/models/productsModel.php';

class ProductController
{

    function getAllProducts()
    {
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

    function getProductById($id)
    {
        // get product from model
        $productModel = new ProductModel();
        $product = $productModel->getProductById($id);
        if ($product['statusCode'] === '200') {
            return array(
                'product' => $product['product'],
                'statusCode' => '200'
            );
        } else {
            return array(
                'error' => $product['error'],
                'statusCode' => '400'
            );
        }
    }

    function updateProduct($data)
    {
        // update product in model
        $productModel = new ProductModel();
        $product = $productModel->updateProduct($data);
        if ($product['statusCode'] === '200') {
            return array(
                'product' => $product['product'],
                'statusCode' => '200'
            );
        } else {
            return array(
                'error' => $product['error'],
                'statusCode' => '400'
            );
        }
    }
}