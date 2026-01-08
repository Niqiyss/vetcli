<?php
header("Content-Type: application/json");

include "../api/auth.php";
require_once "../backend/connection.php";

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // ❗ DO NOT SELECT PASSWORD
    $sql = "
        SELECT admin_id, admin_name, phone_num, username
        FROM clinic_administrator
        ORDER BY admin_id
    ";

    $stmt = $conn->query($sql);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $admins
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
