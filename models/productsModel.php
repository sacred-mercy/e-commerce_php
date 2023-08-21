<?php

require '..\config\db.php';

class ProductModel
{
    function getAllProducts()
    {
        $products = pg_query($GLOBALS['db'], "SELECT * FROM products");
        return pg_fetch_all($products);
    }

    function getProductById($id)
    {
        $product = pg_query($GLOBALS['db'], "SELECT * FROM products WHERE id = $id");
        return pg_fetch_all($product);
    }
}