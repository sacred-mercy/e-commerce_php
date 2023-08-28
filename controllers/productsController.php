<?php

require_once dirname(__DIR__) . '/models/productsModel.php';

class ProductController
{

    function getTotalProducts()
    {
        // get total products from model
        $productModel = new ProductModel();
        $count = $productModel->getTotalProducts();
        if ($count['statusCode'] === '200') {
            return array(
                'count' => $count['count'][0]['count'],
                'statusCode' => '200'
            );
        } else {
            return array(
                'error' => $count['error'],
                'statusCode' => '400'
            );
        }
    }

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

    function productsWithPaggingAndSorting($page, $limit, $sort_by, $sort_order)
    {
        // get products from model
        $productModel = new ProductModel();
        $products = $productModel->productsWithPaggingAndSorting($page, $limit, $sort_by, $sort_order);
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

    function createProduct($data)
    {
        // create product in model
        $productModel = new ProductModel();
        $product = $productModel->createProduct($data);
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