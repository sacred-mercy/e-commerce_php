<?php

require '..\config\db.php';

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
        $product = pg_query($GLOBALS['db'], "SELECT * FROM products WHERE id = $id");
        return pg_fetch_all($product);
    }
}