<?php
header("Content-Type: application/json");

require "auth.php";
include "../backend/connection.php";

$owner_id = $_GET['owner_id'] ?? null;

if (!$owner_id) {
    http_response_code(400);
    echo json_encode(["error" => "owner_id required"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT pet_id, pet_name, species
    FROM pet
    WHERE owner_id = :oid
");
$stmt->execute([':oid' => $owner_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
