<?php
require_once 'include/header.php';
// check if payment mode is cod
if ($_POST['paymentMethod'] == 'cod') {
    // create order session
    $_SESSION['order'] = array(
        'paymentMethod' => $_POST['paymentMethod'],
        'totalPrice' => $_POST['totalPrice']
    );
    // go to order.php 
    header('location: order.php');
    exit();
}
require('razorpay-php/config.php');
require('razorpay-php/Razorpay.php');

// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$orderData = [
    'amount' => $_POST['totalPrice'] * 100,
    // rupees in paise
    'currency' => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

$checkout = 'automatic';

$data = [
    "key" => $keyId,
    "amount" => $amount,
    "name" => "E-commerce Website",
    "description" => "Product Billing",
    "image" => "https://www.x-cart.com/img/16591/ecommerce-p1500.jpg",
    "prefill" => [
        "name" => $_SESSION['user']['name'],
        "email" => $_SESSION['user']['email'],
    ],
    "theme" => [
        "color" => "#F37254"
    ],
    "order_id" => $razorpayOrderId,
];

$json = json_encode($data);

require("razorpayCheckout.php");