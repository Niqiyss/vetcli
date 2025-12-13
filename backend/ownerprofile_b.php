<?php
// backend/ownerprofile_b.php

session_start();

include "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];
$formErrors = [];

// Fetch current owner info
$stmt = $conn->prepare("SELECT * FROM owner WHERE owner_id = :owner_id");
$stmt->execute([':owner_id' => $ownerID]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (!preg_match("/^[0-9\-]+$/", $phone_num)) {
        $formErrors[] = "Phone number must contain only digits or dashes.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $formErrors[] = "Password must be at least 6 characters.";
    }

    // update
    if (empty($formErrors)) {
        try {
            $sql = "UPDATE owner
                    SET owner_name = :owner_name, phone_num = :phone_num, email = :email,
                        address = :address, username = :username, password = :password
                    WHERE owner_id = :owner_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':owner_name' => $owner_name,
                ':phone_num' => $phone_num,
                ':email' => $email,
                ':address' => $address,
                ':username' => $username,
                ':password' => $password,
                ':owner_id' => $ownerID
            ]);
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: ../frontend/ownerprofile.php");
            exit();
        } catch (PDOException $e) {
            $formErrors[] = "Database Error: " . $e->getMessage();
        }
    }
}

?>
