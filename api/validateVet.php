<?php
require "auth.php";
include "../backend/connection.php";

$data = json_decode(file_get_contents("php://input"), true);
$vet_id = $data['vet_id'] ?? null;

$stmt = $conn->prepare("
    SELECT 1 FROM veterinarian WHERE vet_id = ?
");
$stmt->execute([$vet_id]);

echo json_encode([
    "valid" => $stmt->rowCount() > 0
]);
