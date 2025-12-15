<?php
//vetdelete_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

if (!isset($_GET['vet_id'])) {
    header("Location: ../frontend/vetlist.php?msg=invalid");
    exit();
}

$vetID = $_GET['vet_id'];

try {
    //delete
    $stmt = $conn->prepare("DELETE FROM veterinarian WHERE vet_id = :vet_id");
    $stmt->execute([':vet_id' => $vetID]);

    //success message
    header("Location: ../frontend/vetlist.php?msg=deleted");
    exit();

} catch (PDOException $e) {
    header("Location: ../frontend/vetlist.php?msg=error");
    exit();
}
?>
