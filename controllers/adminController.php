<?php

require_once dirname(__DIR__) . '/models/adminModel.php';

class AdminController {
    function getDashboardData() {
        $adminModel = new AdminModel();
        $data = $adminModel->getDashboardData();
        return $data;
    }

    function getMostSoldProducts($timePeriod) {
        $adminModel = new AdminModel();
        $products = $adminModel->getMostSoldProducts($timePeriod);
        return $products;
    }
}