<?php
//ownerpetlist_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];

//fetch table pet from ownerid
$stmt = $conn->prepare("SELECT * FROM pet WHERE owner_id = :owner_id");
$stmt->bindParam(':owner_id', $ownerID, PDO::PARAM_STR);
$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
