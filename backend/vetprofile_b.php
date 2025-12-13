<?php
// backend/vetprofile_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['vetID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$vetID = $_SESSION['vetID'];
$formErrors = [];

// Fetch current vet info
$stmt = $conn->prepare("SELECT * FROM veterinarian WHERE vet_id = :vet_id");
$stmt->execute([':vet_id' => $vetID]);
$vet = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vet_name = trim($_POST['vet_name']);
    $phone_num = trim($_POST['phone_num']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $availability = trim($_POST['availability']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // validation
    if (!$vet_name || !$phone_num || !$email || !$specialization || !$availability || !$username || !$password) {
        $formErrors[] = "All fields are required.";
    }
    if (!preg_match("/^[A-Za-z ]+$/", $vet_name)) {
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

    // Update DB
    if (empty($formErrors)) {
        try {
            $sql = "UPDATE veterinarian
                    SET vet_name = :vet_name, phone_num = :phone_num, email = :email,
                        specialization = :specialization, availability = :availability,
                        username = :username, password = :password
                    WHERE vet_id = :vet_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':vet_name' => $vet_name,
                ':phone_num' => $phone_num,
                ':email' => $email,
                ':specialization' => $specialization,
                ':availability' => $availability,
                ':username' => $username,
                ':password' => $password,
                ':vet_id' => $vetID
            ]);

            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: ../frontend/vetprofile.php");
            exit();

        } catch (PDOException $e) {
            $formErrors[] = "Database Error: " . $e->getMessage();
        }
    }
}
?>
