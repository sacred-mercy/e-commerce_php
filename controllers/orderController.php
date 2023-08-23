<?php

require_once 'models/orderModel.php';

class OrderController{
    public function createOrder($data){
        $orderModel = new OrderModel();
        $orderModel->createOrder($data);
    }
}