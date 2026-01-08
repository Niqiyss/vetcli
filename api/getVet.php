<?php
require "auth.php";
include "../backend/connection.php";

$stmt = $conn->query("
    SELECT vet_id, vet_name
    FROM veterinarian
    ORDER BY vet_name
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
