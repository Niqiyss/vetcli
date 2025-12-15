<?php
//ownerregister_b.php

session_start();
include "../backend/connection.php";

$formErrors = [];
$owner_name = $phone_num = $email = $address = $username = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $owner_name = trim($_POST['owner_name']);
    $phone_num = trim($_POST['phone_num']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // validation
    if (!$owner_name || !$phone_num || !$email || !$address || !$username || !$password) {
        $formErrors[] = "All fields are required.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $owner_name)) {
        $formErrors[] = "Name can only contain letters and spaces.";
    }

    if (!preg_match("/^[0-9]+$/", $phone_num)) {
        $formErrors[] = "Phone number can only contain numbers.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $formErrors[] = "Password must be at least 6 characters.";
    }

    // username unique
    $stmt = $conn->prepare("SELECT 1 FROM owner WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        $formErrors[] = "Username already taken!";
    }

    // insert db
    if (empty($formErrors)) {
        try {
            //ownerid
            $stmt = $conn->query("SELECT MAX(CAST(SUBSTRING(owner_id FROM 3) AS INTEGER)) AS max_num FROM owner");
            $lastOwner = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $lastOwner['max_num'] ? intval($lastOwner['max_num']) + 1 : 1;
            $owner_id = "OW" . str_pad($num, 3, "0", STR_PAD_LEFT);

            $sql = "INSERT INTO owner (owner_id, owner_name, phone_num, email, address, username, password)
                    VALUES (:owner_id, :owner_name, :phone_num, :email, :address, :username, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':owner_id' => $owner_id,
                ':owner_name' => $owner_name,
                ':phone_num' => $phone_num,
                ':email' => $email,
                ':address' => $address,
                ':username' => $username,
                ':password' => $password
            ]);

            //popup success
            $_SESSION['success_popup'] = "Your Owner ID: $owner_id";
            header("Location: ../frontend/ownerregister.php");
            exit();

        } catch (PDOException $e) {
            $formErrors[] = "Database Error: " . $e->getMessage();
        }
    }
}
?>
