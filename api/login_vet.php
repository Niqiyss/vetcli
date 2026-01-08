<?php
require "auth.php";
require "connection.php"; // postgres

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$stmt = $conn->prepare("
    SELECT vet_id, vet_name 
    FROM veterinarian
    WHERE username = :u AND password = :p
");
$stmt->execute([
    ':u' => $username,
    ':p' => $password
]);

$vet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vet) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
    exit;
}

echo json_encode([
    "vetID"   => $vet['vet_id'],
    "vetName" => $vet['vet_name']
]);
