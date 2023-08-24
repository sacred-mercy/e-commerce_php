<?php

require_once dirname(__DIR__) . '/controllers/adminController.php';

$adminController = new AdminController();

function getMostSoldProducts($timePeriod){
    $products = $GLOBALS['adminController']->getMostSoldProducts($timePeriod);
    echo json_encode($products);
}


$request = $_GET['request'];

switch ($request) {
    case 'getMostSoldProducts':
        $timePeriod = $_GET['timePeriod'];
        getMostSoldProducts($timePeriod);
        break;
    case 'addProduct':
        addToCart();
        break;
    default:
        # code...
        break;
}