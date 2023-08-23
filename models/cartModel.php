<?php

require_once '../config/db.php';

class CartModel{
    function getCartItems($id){
        $products = pg_query($GLOBALS['db'], "SELECT * FROM cart WHERE user_id = $id");
        $products = pg_fetch_all($products);
        $cartItems = [];
        foreach ($products as $product) {
            $productId = $product['product_id'];
            $productDetails = pg_query($GLOBALS['db'], "SELECT * FROM products WHERE id = $productId");
            $productDetails = pg_fetch_all($productDetails);
            $productDetails[0]['quantity'] = $product['quantity'];
            array_push($cartItems, $productDetails[0]);
        }
        return $cartItems;
    }

    function addToCart($productId, $userId){
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

    function deleteCartItem($productId, $userId){
        $result = pg_query($GLOBALS['db'], "DELETE FROM cart WHERE product_id = $productId AND user_id = $userId");
        return pg_fetch_all($result);
    }
}
?>