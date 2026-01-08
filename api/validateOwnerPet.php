<?php
require "auth.php";
include "../backend/connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$owner_id = $data['owner_id'] ?? null;
$pet_id   = $data['pet_id'] ?? null;

$stmt = $conn->prepare("
    SELECT 1 FROM pet
    WHERE owner_id = :oid AND pet_id = :pid
");
$stmt->execute([
    ':oid' => $owner_id,
    ':pid' => $pet_id
]);

echo json_encode([
    "valid" => $stmt->rowCount() > 0
]);
