<?php

$host = 'localhost';
$db = 'e-commerce';
$user = 'postgres';
$password = 'root';

try {
    $db = pg_connect("host=$host dbname=$db user=$user password=$password");

    if (!$db) {
        throw new Exception("Error : Database connection failed");
    }
} catch (Exception $e) {
    echo '<div class="bg-red-900 text-white text-2xl flex justify-center p-3">'. $e->getMessage() .' </div>';
}