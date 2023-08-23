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
    if (!isset($_SESSION['user'])) {
        echo "notLoggedIn";
        exit();
    }
    $productId = $_GET['productId'];
    $userId = $_SESSION['user']['id'];
    $cartItems = $GLOBALS['cartController']->addToCart($productId, $userId);
    echo json_encode($cartItems);
}

function deleteCartItem()
{
    $productId = $_GET['id'];
    $userId = $_SESSION['user']['id'];
    return $GLOBALS['cartController']->deleteCartItem($productId, $userId);
}


$request = $_GET['request'];

switch ($request) {
    case 'getCartItems':
        getCart();
        break;
    case 'addProduct':
        addToCart();
        break;
    case 'deleteCartItem':
        deleteCartItem();
        break;
    default:
        # code...
        break;
}
?>