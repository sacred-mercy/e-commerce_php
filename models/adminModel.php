<?php

require_once dirname(__DIR__) . '/config\db.php';
require_once dirname(__DIR__) . '/functions\getSafeValue.php';

class AdminModel
{
    function getDashboardData()
    {
        try {
            $totalOrders = pg_query($GLOBALS['db'], "SELECT COUNT(*) FROM orders WHERE status != 'cancelled'");
            $totalOrders = pg_fetch_row($totalOrders)[0];

            $totalProducts = pg_query($GLOBALS['db'], "SELECT COUNT(*) FROM products");
            $totalProducts = pg_fetch_row($totalProducts)[0];

            $totalUsers = pg_query($GLOBALS['db'], "SELECT COUNT(*) FROM users");
            $totalUsers = pg_fetch_row($totalUsers)[0];

            $totalRevenue = pg_query($GLOBALS['db'], "SELECT SUM(price) FROM orders WHERE status != 'cancelled'");
            $totalRevenue = pg_fetch_row($totalRevenue)[0];

            $todayOrders = pg_query($GLOBALS['db'], "SELECT COUNT(*) FROM orders WHERE date_time >= CURRENT_DATE AND status != 'cancelled'");
            $todayOrders = pg_fetch_row($todayOrders)[0];

            $todayRevenue = pg_query($GLOBALS['db'], "SELECT SUM(price) FROM orders WHERE date_time >= CURRENT_DATE AND status != 'cancelled'");
            $todayRevenue = pg_fetch_row($todayRevenue)[0];

            return array(
                'totalOrders' => $totalOrders,
                'totalProducts' => $totalProducts,
                'totalUsers' => $totalUsers,
                'totalRevenue' => $totalRevenue,
                'todayOrders' => $todayOrders,
                'todayRevenue' => $todayRevenue,
                'statusCode' => '200'
            );
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function getMostSoldProducts($timePeriod)
    {
        try {
            $timePeriod = getSafeValue($timePeriod);

            $query = "SELECT p.title, COUNT(*) * SUM(od.quantity) AS product_count
            FROM orders AS o
            JOIN orders_detail AS od ON o.id = od.order_id
            JOIN products AS p ON p.id = od.product_id WHERE "
                . (($timePeriod === 'lifetime') ? "" : "o.date_time >= DATE_TRUNC('" . $timePeriod . "', CURRENT_DATE) AND")
                . " o.status != 'cancelled'
                    GROUP BY p.title
                    ORDER BY product_count DESC
                    LIMIT 5";

            $products = pg_query($GLOBALS['db'], $query);
            $products = pg_fetch_all($products);

            return array(
                'products' => $products,
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