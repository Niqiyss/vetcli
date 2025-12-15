<?php
//vetlist_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$errorMsg = "";
$search = "";

//search
if (isset($_GET['txtsearch'])) {
    $search = trim($_GET['txtsearch']);
}

try {
    if ($search !== "") {
        $stmt = $conn->prepare("
            SELECT vet_id, vet_name, phone_num, email, specialization, vet_image
            FROM veterinarian
            WHERE vet_id ILIKE :search OR vet_name ILIKE :search
            ORDER BY vet_id ASC
        ");
        $stmt->execute([':search' => "%$search%"]);
    } else {
        $stmt = $conn->query("
            SELECT vet_id, vet_name, phone_num, email, specialization, vet_image
            FROM veterinarian
            ORDER BY vet_id ASC
        ");
    }

    $vets = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMsg = "Database error occurred.";
}
