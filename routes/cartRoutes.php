<?php
session_start();
require_once '../controllers/cartController.php';

$cartController = new CartController();

function getCart()
{
    $cartItems = $GLOBALS['cartController']->getCartItems($_SESSION['user']['id']);
    echo json_encode($cartItems);
}

function addToCart()
{
    $productId = $_GET['productId'];
    if (!isset($_SESSION['user'])) {
        return "notLoggedIn";
    }
    $userId = $_SESSION['user']['id'];
    $cartItems = $GLOBALS['cartController']->addToCart($productId, $userId);
    echo json_encode($cartItems);
}


$request = $_GET['request'];

switch ($request) {
    case 'getCartItems':
        getCart();
        break;
    case 'addProduct':
        addToCart();
        break;
    default:
        # code...
        break;
}
?>