<?php
//vet_avail_del_b.php

session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

include "../backend/connection.php";

$id = $_GET['id'] ?? '';
$vet_id = $_GET['vet_id'] ?? '';

if (!$id) {
    $_SESSION['error_popup'] = "Invalid availability ID.";
    header("Location: ../frontend/vet_avail.php");
    exit();
}

//delete
try {
    $stmt = $conn->prepare("DELETE FROM vet_availability WHERE availability_id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['success_popup'] = "Availability deleted successfully";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_popup'] = "Failed to delete vet availability";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}
