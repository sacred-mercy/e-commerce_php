<?php

require_once dirname(__DIR__) . '/config/db.php';

class CartModel
{
    function getCartItems($id)
    {
        $query = "SELECT p.*, c.quantity FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = $id";

        $result = pg_query($GLOBALS['db'], $query);

        if ($result === false) {
            return array(
                'error' => "Query execution failed: " . pg_last_error($GLOBALS['db']),
                'statusCode' => '400'
            );
        }

        return array(
            'cartItems' => pg_fetch_all($result),
            'statusCode' => '200'
        );
    }


    function addToCart($productId, $userId)
    {
        // check if product is already in cart
        $result = pg_query($GLOBALS['db'], "SELECT * FROM cart WHERE product_id = $productId AND user_id = $userId");
        $product = pg_fetch_all($result);
        if ($product) {
            // if product is already in cart, increase quantity
            $result = pg_query($GLOBALS['db'], "UPDATE cart SET quantity = quantity + 1 WHERE product_id = $productId AND user_id = $userId");
            return pg_fetch_all($result);
        }
        $result = pg_query($GLOBALS['db'], "INSERT INTO cart (product_id, user_id, quantity) VALUES ($productId, $userId, 1)");
        return pg_fetch_all($result);
    }

    function deleteCartItem($productId, $userId)
    {
        $result = pg_query($GLOBALS['db'], "DELETE FROM cart WHERE product_id = $productId AND user_id = $userId");
        return pg_fetch_all($result);
    }
}
?>