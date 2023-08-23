<?php

require_once '../models/cartModel.php';

class CartController{
    function getCartItems($id){
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems($id);
        return $cartItems;
    }

    function addToCart($productId, $userId){
        $cartModel = new CartModel();
        $cartItems = $cartModel->addToCart($productId, $userId);
        return $cartItems;
    }

    function deleteCartItem($productId, $userId){
        $cartModel = new CartModel();
        $cartItems = $cartModel->deleteCartItem($productId, $userId);
        return $cartItems;
    }
}