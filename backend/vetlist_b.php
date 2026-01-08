<?php
//vetlist_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$errorMsg = "";
$search   = "";

//searching
if (isset($_GET['txtsearch'])) {
    $search = trim($_GET['txtsearch']);
}

try {

    if ($search !== "") {

        //search by name, specialization, phonenum, email
        $stmt = $conn->prepare("
            SELECT vet_id, vet_name, phone_num, email, specialization, vet_image
            FROM veterinarian
            WHERE
                vet_id ILIKE :search
                OR vet_name ILIKE :search
                OR specialization ILIKE :search
                OR phone_num ILIKE :search
                OR email ILIKE :search
            ORDER BY vet_name ASC
        ");

        $stmt->execute([
            ':search' => "%$search%"
        ]);

    } else {

        $stmt = $conn->query("
            SELECT vet_id, vet_name, phone_num, email, specialization, vet_image, created_at
            FROM veterinarian
            ORDER BY created_at ASC
        ");
    }

    $vets = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMsg = "Database error occurred. Please try again.";
}
