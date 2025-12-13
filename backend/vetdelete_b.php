<?php
// backend/vetdelete_b.php

session_start();
include "../backend/connection.php";

// Must login
if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

// Check ID exist
if (!isset($_GET['vet_id'])) {
    header("Location: ../frontend/vetlist.php?msg=invalid");
    exit();
}

$vetID = $_GET['vet_id'];

try {
    // Perform delete
    $stmt = $conn->prepare("DELETE FROM veterinarian WHERE vet_id = :vet_id");
    $stmt->execute([':vet_id' => $vetID]);

    // Redirect with success message
    header("Location: ../frontend/vetlist.php?msg=deleted");
    exit();

} catch (PDOException $e) {
    header("Location: ../frontend/vetlist.php?msg=error");
    exit();
}
?>
