<?php

$host= 'localhost';
$db = 'e-commerce';
$user = 'postgres';
$password = 'root';

$db = pg_connect("host=$host dbname=$db user=$user password=$password");