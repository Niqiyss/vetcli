<?php
session_start();
header("Content-Type: application/json");

require_once "../backend/connection.php"; // your PDO connection

$data = json_decode(file_get_contents("php://input"), true);

$adminID = $data['admin_id'] ?? null;
$password = $data['password'] ?? null;

if (!$adminID || !$password) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

try {
    $sql = "
        SELECT admin_id, admin_name, password
        FROM clinic_administrator
        WHERE admin_id = :adminID
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':adminID' => $adminID]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || $password !== $admin['password']) {
        echo json_encode(["success" => false, "message" => "Invalid login"]);
        exit;
    }

    // ✅ SET SESSION
    $_SESSION['adminID']   = $admin['admin_id'];
    $_SESSION['adminName'] = $admin['admin_name'];
    $_SESSION['role']      = 'admin';

    echo json_encode([
        "success" => true,
        "admin" => [
            "admin_id" => $admin['admin_id'],
            "admin_name" => $admin['admin_name']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error"]);
}
