<?php
header("Content-Type: application/json");
require "auth.php";
include "../backend/connection.php";

$owner_id = $_GET['owner_id'] ?? null;
$pet_id   = $_GET['pet_id'] ?? null;

if (!$owner_id || !$pet_id) {
    http_response_code(400);
    echo json_encode(["error" => "owner_id and pet_id required"]);
    exit;
}

/* Owner */
$stmtOwner = $conn->prepare("
    SELECT owner_id, owner_name, phone_num, email
    FROM owner
    WHERE owner_id = :oid
");
$stmtOwner->execute([':oid' => $owner_id]);
$owner = $stmtOwner->fetch(PDO::FETCH_ASSOC);

/* Pet */
$stmtPet = $conn->prepare("
    SELECT pet_id, pet_name, species, breed, gender
    FROM pet
    WHERE pet_id = :pid AND owner_id = :oid
");
$stmtPet->execute([
    ':pid' => $pet_id,
    ':oid' => $owner_id
]);
$pet = $stmtPet->fetch(PDO::FETCH_ASSOC);

if (!$owner || !$pet) {
    http_response_code(404);
    echo json_encode(["error" => "Owner or pet not found"]);
    exit;
}

echo json_encode([
    "owner" => $owner,
    "pet"   => $pet
]);
