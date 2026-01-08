<?php
//connection.php

//iqin
$host = "10.48.74.199";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "password";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;", $user, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

//aniq
/*
$mb_host = '10.48.74.61';
$mb_port = 3309; 
$mb_db   = 'vet_clinic';
$mb_user = 'root';
$mb_pass = '1234';

try {
    $connMaria = new PDO("mysql:host=$mb_host;port=$mb_port;dbname=$mb_db", $mb_user, $mb_pass);
    $connMaria->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "MariaDB connected!";
} catch (PDOException $e) {
    echo "MariaDB connection failed: " . $e->getMessage();
}*/


//rukai


?>