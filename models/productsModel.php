<?php

require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/functions/getSafeValue.php';

class ProductModel
{
    function getTotalProducts()
    {
        try {
            $count = pg_query($GLOBALS['db'], "SELECT COUNT(*) FROM products");

            return array(
                'count' => pg_fetch_all($count),
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }
    function getAllProducts($page, $limit, $sort_by, $sort_order)
    {
        try {
            $page = getSafeValue($page);
            $limit = getSafeValue($limit);
            $sort_by = getSafeValue($sort_by);
            $sort_order = getSafeValue($sort_order);

            $offset = ($page - 1) * $limit;

            $products = pg_query(
                $GLOBALS['db'],
                "SELECT * FROM products ORDER BY $sort_by $sort_order LIMIT $limit OFFSET $offset"
            );

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
            $id = getSafeValue($id);

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
            $data = array(
                'title' => getSafeValue($data['title']),
                'description' => getSafeValue($data['description']),
                'price' => getSafeValue($data['price']),
                'brand' => getSafeValue($data['brand']),
                'stock' => getSafeValue($data['stock']),
                'product_id' => getSafeValue($data['product_id'])
            );

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

            $data = array(
                'title' => getSafeValue($data['title']),
                'description' => getSafeValue($data['description']),
                'price' => getSafeValue($data['price']),
                'brand' => getSafeValue($data['brand']),
                'stock' => getSafeValue($data['stock']),
                'image' => $data['image']
            );

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