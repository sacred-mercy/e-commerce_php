<?php

require_once dirname(__DIR__) . '/config/db.php';

class ProductModel
{
    function getAllProducts()
    {
        try {
            $products = pg_query($GLOBALS['db'], "SELECT * FROM products");

            return array(
                'products' => pg_fetch_all($products),
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function getProductById($id)
    {
        try {
            $product = pg_query_params($GLOBALS['db'], "SELECT * FROM products WHERE id = $1", array($id));

            return array(
                'product' => pg_fetch_all($product),
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function updateProduct($data)
    {
        try {
            $product = pg_query($GLOBALS['db'], "UPDATE products SET title = '$data[title]', description = '$data[description]', price = $data[price], brand = '$data[brand]', stock = $data[stock] WHERE id = $data[product_id]");

            return array(
                'product' => pg_fetch_all($product),
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function createProduct($data)
    {
        try {

            $imagePath = '';

            if (isset($data['image'])) {
                $imagePath = uniqid() . $data['image']['name'];
                move_uploaded_file($data['image']['tmp_name'], dirname(__DIR__) . '/assets/images/' . $imagePath);
            }

            $product = pg_query($GLOBALS['db'], "INSERT INTO products (title, description, price, brand, stock, thumbnail)
                                                    VALUES ('$data[title]', '$data[description]', $data[price], '$data[brand]', $data[stock], '$imagePath')");

            return array(
                'product' => pg_fetch_all($product),
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }
}