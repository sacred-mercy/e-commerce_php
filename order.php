<?php

require_once 'include/header.php';

// check if session is set
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

// check if a post request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'controllers/orderController.php';

    $orderController = new OrderController();

    $userId = $_SESSION['user']['id'];

    $data = array(
        'paymentMethod' => $_POST['paymentMethod'],
        'totalPrice' => $_POST['totalPrice']
    );

    $result = $orderController->createOrder($data, $userId);
} else {
    header('location: index.php');
    exit();
}

?>

<div>
    <?php if ($result['statusCode'] == '200') { ?>
        <div class="flex justify-center items-center h-96">
            <h1 class="text-2xl font-semibold">Order placed successfully</h1>
            <a href="index.php"
                class="text-white bg-blue-700 hover:bg-blue-800 mx-3 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Go
                to shop</a>
        </div>
    <?php } else { ?>
        <div class="flex justify-center items-center h-96">
            <h1 class="text-2xl font-semibold">Something went wrong</h1>
            <a href="cart.php"
                class="text-white bg-blue-700 hover:bg-blue-800 mx-3 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Go
                to cart</a>
        </div>
    <?php } ?>
</div>