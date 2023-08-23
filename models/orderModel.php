<?php

require_once 'config/db.php';
require_once 'smtp/smtpMailer.php';

class OrderModel{
    function getSafeValue($value){
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }
    function createOrder($data, $userId){
        $user_id = $this->getSafeValue($userId);
        $pay_method = $this->getSafeValue($data['paymentMethod']);
        $price = $this->getSafeValue($data['totalPrice']);
        $status = $this->getSafeValue("pending");
        $date_time = $this->getSafeValue(date("Y-m-d H:i:s"));

        $order = pg_query($GLOBALS['db'], "INSERT INTO orders (user_id, pay_method, price, status, date_time) VALUES ('$user_id', '$pay_method', '$price', '$status', '$date_time')");

        if($order){
            // save order details
            $order_id = pg_fetch_assoc(pg_query($GLOBALS['db'], "SELECT id FROM orders WHERE user_id = '$user_id' AND date_time = '$date_time'"))['id'];
            $cartItems = pg_query($GLOBALS['db'], "SELECT * FROM cart WHERE user_id = '$user_id'");
            $cartItems = pg_fetch_all($cartItems);
            foreach($cartItems as $cartItem){
                $product_id = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];
                pg_query($GLOBALS['db'], "INSERT INTO orders_detail (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$quantity')");
            }

            // delete cart items
            pg_query($GLOBALS['db'], "DELETE FROM cart WHERE user_id = '$user_id'");
            
            return array(
                'message' => 'Order created successfully',
                'orderId' => $order_id,
                'statusCode' => '200'
            );
        }else{
            return array(
                'error' => 'Something went wrong',
                'statusCode' => '400'
            );
        }
    }
}