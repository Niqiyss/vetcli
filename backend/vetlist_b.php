<?php
// backend/vetlist_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

// For search
$search = "";
if (isset($_GET['txtsearch'])) {
    $search = trim($_GET['txtsearch']);
}

try {
    if ($search !== "") {
        // Search by vet_id or vet_name
        $stmt = $conn->prepare("SELECT * FROM veterinarian 
                                WHERE vet_id ILIKE :search OR vet_name ILIKE :search
                                ORDER BY vet_id ASC");
        $stmt->execute([':search' => "%$search%"]);
    } else {
        $stmt = $conn->query("SELECT * FROM veterinarian ORDER BY vet_id ASC");
    }

    $vets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "Database Error: " . $e->getMessage();
}
