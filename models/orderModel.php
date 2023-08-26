<?php

require_once 'config/db.php';
require_once 'smtp/smtpMailer.php';

class OrderModel
{
    function getSafeValue($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }
    function createOrder($data, $userId)
    {
        $user_id = $this->getSafeValue($userId);
        $pay_method = $this->getSafeValue($data['paymentMethod']);
        $price = $this->getSafeValue($data['totalPrice']);
        $status = $this->getSafeValue("pending");
        $date_time = $this->getSafeValue(date("Y-m-d H:i:s"));
        $address_id = $this->getSafeValue($data['addressId']);

        $order = pg_query($GLOBALS['db'], "INSERT INTO orders (user_id, pay_method, price, status, date_time, address_id) 
                                            VALUES ('$user_id', '$pay_method', '$price', '$status', '$date_time', '$address_id')");

        if ($order) {
            // save order details
            $order_id = pg_fetch_assoc(pg_query($GLOBALS['db'], "SELECT id FROM orders WHERE user_id = '$user_id' AND date_time = '$date_time'"))['id'];
            $cartItems = pg_query($GLOBALS['db'], "SELECT * FROM cart WHERE user_id = '$user_id'");
            $cartItems = pg_fetch_all($cartItems);
            foreach ($cartItems as $cartItem) {
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
        } else {
            return array(
                'error' => 'Something went wrong',
                'statusCode' => '400'
            );
        }
    }

    function isAuthorized($order_id, $user_id)
    {
        $order = pg_query($GLOBALS['db'], "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'");
        $order = pg_fetch_assoc($order);
        if ($order) {
            return true;
        } else {
            return false;
        }
    }

    function getAllOrders()
    {
        try {
            $orders = pg_query($GLOBALS['db'], "SELECT id, status, date_time AT TIME ZONE 'Asia/Kolkata' AS date_time FROM orders");
            $orders = pg_fetch_all($orders);
            return array(
                'orders' => $orders,
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function getOrders($userId)
    {
        try {
            $user_id = $this->getSafeValue($userId);
            $orders = pg_query($GLOBALS['db'], "SELECT id, status, date_time AT TIME ZONE 'Asia/Kolkata' AS date_time FROM orders WHERE user_id = '$user_id'");
            $orders = pg_fetch_all($orders);
            return array(
                'orders' => $orders,
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function getOrderById($id)
    {
        try {
            $order = pg_query($GLOBALS['db'], "SELECT od.quantity , p.title, p.price 
                                            FROM orders o
                                            JOIN orders_detail od 
                                            ON o.id = od.order_id
                                            JOIN products p 
                                            ON od.product_id = p.id
                                            WHERE od.order_id ='$id'");
            $order = pg_fetch_all($order);
            return array(
                'order' => $order,
                'order_info' => pg_fetch_assoc(pg_query($GLOBALS['db'], "SELECT status , price FROM orders WHERE id = '$id'")),
                'address' => pg_fetch_assoc(pg_query($GLOBALS['db'], "SELECT * FROM address WHERE id = (SELECT address_id FROM orders WHERE id = '$id')")),
                'user' => pg_fetch_assoc(pg_query($GLOBALS['db'], "SELECT name, email FROM users WHERE id = (SELECT user_id FROM orders WHERE id = '$id')")),
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