<?php
// ownerregister_b.php

session_start();
require_once "../backend/connection.php";

$formErrors = [];

$owner_name = $phone_num = $email = $username = "";
$password = "";
$address  = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //input
    $owner_name = trim($_POST['owner_name'] ?? '');
    $phone_num  = trim($_POST['phone_num'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    $street   = trim($_POST['street'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $state    = trim($_POST['state'] ?? '');

    //required
    if ($owner_name === '' || $phone_num === '' || $email === '' || $username === '' || $password === '') {
        $formErrors[] = "All required fields must be filled.";
    }

    //format
    if ($owner_name !== '' && !preg_match("/^[A-Za-z ]+$/", $owner_name)) {
        $formErrors[] = "Full name may contain letters and spaces only.";
    }

    if ($phone_num !== '' && !preg_match("/^[0-9]+$/", $phone_num)) {
        $formErrors[] = "Phone number must contain digits only.";
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    //pass validation
    if ($password !== '') {
        if (strlen($password) < 6) {
            $formErrors[] = "Password must be at least 6 characters.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $formErrors[] = "Password must include at least one uppercase letter.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $formErrors[] = "Password must include at least one symbol.";
        }
    }

    //opt address
    if ($street || $postcode || $city || $state) {

        if (!$street || !$postcode || !$city || !$state) {
            $formErrors[] = "Please complete the full address if you choose to provide it.";
        }

        if ($postcode && !preg_match('/^[0-9]{5}$/', $postcode)) {
            $formErrors[] = "Postal code must be exactly 5 digits.";
        }

        if (empty($formErrors)) {
            $address = "$street, $postcode $city, $state";
        }
    }

    //unique username
    if ($username !== '') {
        $stmt = $conn->prepare("SELECT 1 FROM owner WHERE username = :username");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            $formErrors[] = "Username already exists.";
        }
    }

    //popup error
    if (!empty($formErrors)) {
        $_SESSION['error_popup'] = implode("\n", $formErrors);
        header("Location: ../frontend/ownerregister.php");
        exit();
    }

    try {

        //hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        //insert
        $sql = "
            INSERT INTO owner
            (owner_name, phone_num, email, address, username, password)
            VALUES
            (:name, :phone, :email, :address, :username, :password)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([

            ':name'     => $owner_name,
            ':phone'    => $phone_num,
            ':email'    => $email,
            ':address'  => $address,
            ':username' => $username,
            ':password' => $hashedPassword
        ]);

        $_SESSION['success_popup'] = "Thank you for registering!";
        header("Location: ../frontend/ownerregister.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_popup'] = "Registration failed. Please try again later";
        header("Location: ../frontend/ownerregister.php");
        exit();
    }
}
