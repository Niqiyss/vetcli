<?php
// ownerpetlist_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];

$stmt = $conn->prepare("
    SELECT *
    FROM pet
    WHERE owner_id = :owner_id
    ORDER BY pet_name ASC
");
$stmt->execute([':owner_id' => $ownerID]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
