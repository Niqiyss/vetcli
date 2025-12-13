<?php
header("Content-Type: application/json");

// PostgreSQL connection settings
$host = "10.48.74.199";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "password";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query Clinic Administrator table
    $sql = "SELECT admin_id, admin_name, phone_num, username, password FROM clinic_administrator";
    $stmt = $conn->query($sql);

    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON
    echo json_encode($admins);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}
?>
