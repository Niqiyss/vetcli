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



//rukai

function getMySQLConnection() {
    $mysql_host = "10.48.74.38";
    $mysql_port = "3306";
    $mysql_db   = "vet_clinic";
    $mysql_user = "rukaini";
    $mysql_pass = "12345678";

    try {
        $connMySQL = new PDO(
            "mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_db;charset=utf8",
            $mysql_user,
            $mysql_pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );

        return $connMySQL;

    } catch (PDOException $e) {
        die("MySQL connection failed: " . $e->getMessage());
    }
}



//aniq
function getMariaDBConnection() {
    static $connMaria = null;

    if ($connMaria === null) {
        $maria_host = "10.48.74.61";
        $maria_port = "3309";
        $maria_db   = "vet_clinic";
        $maria_user = "root";
        $maria_pass = "1234";

        try {
            $connMaria = new PDO(
                "mysql:host=$maria_host;port=$maria_port;dbname=$maria_db;charset=utf8",
                $maria_user,
                $maria_pass
            );
            $connMaria->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connMaria->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("MariaDB connection failed: " . $e->getMessage());
        }
    }

    return $connMaria;
}


?>