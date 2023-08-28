<?php
session_start();
require_once '../controllers/cartController.php';

$cartController = new CartController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cartItems = $GLOBALS['cartController']->getCartItems($_SESSION['user']['id']);
    echo json_encode($cartItems);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!isset($_SESSION['user'])) {
        echo "notLoggedIn";
        exit();
    }
    $productId = $_GET['productId'];
    $userId = $_SESSION['user']['id'];
    $cartItems = $GLOBALS['cartController']->addToCart($productId, $userId);
    echo json_encode($cartItems);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $productId = $_GET['id'];
    $userId = $_SESSION['user']['id'];
    return $GLOBALS['cartController']->deleteCartItem($productId, $userId);
}
?>