<?php
//ownerprofile_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];
$formErrors = [];

//fetch owner
$stmt = $conn->prepare("SELECT * FROM owner WHERE owner_id = :id");
$stmt->execute([':id' => $ownerID]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);

//split addr
$addressParts = [
    'street' => '',
    'postcode' => '',
    'city' => '',
    'state' => ''
];

if (!empty($owner['address'])) {
    if (preg_match('/^(.*),\s(\d{5})\s(.*),\s(.*)$/', $owner['address'], $m)) {
        $addressParts = [
            'street' => $m[1],
            'postcode' => $m[2],
            'city' => $m[3],
            'state' => $m[4]
        ];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $owner_name = trim($_POST['owner_name'] ?? '');
    $phone_num = trim($_POST['phone_num'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');

    $street = trim($_POST['street'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');

    //validation
    if (!$owner_name || !$phone_num || !$email || !$username) {
        $formErrors[] = "All required fields must be filled.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $owner_name)) {
        $formErrors[] = "Full name may contain letters and spaces only.";
    }

    if (!preg_match("/^[0-9]+$/", $phone_num)) {
        $formErrors[] = "Phone number must contain digits only.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    //address validation
    if ($street || $postcode || $city || $state) {

        if (!$street || !$postcode || !$city || !$state) {
            $formErrors[] = "Please complete the full address.";
        }

        if ($postcode && !preg_match('/^[0-9]{5}$/', $postcode)) {
            $formErrors[] = "Postal code must be exactly 5 digits.";
        }
    }

    //username unique
    if ($username !== '') {

        $stmt = $conn->prepare("
        SELECT 1
        FROM owner
        WHERE LOWER(username) = LOWER(:username)
          AND owner_id <> :owner_id
        LIMIT 1
    ");

        $stmt->execute([
            ':username' => $username,
            ':owner_id' => $ownerID
        ]);

        if ($stmt->fetch()) {
            $formErrors[] = "Username already exists.";
        }
    }



    //error
    if (!empty($formErrors)) {
        $_SESSION['error_popup'] = $formErrors;
        header("Location: ../frontend/ownerprofile.php");
        exit();
    }

    //address
    $address = ($street)
        ? "$street, $postcode $city, $state"
        : null;

    //update
    try {
        $sql = "UPDATE owner
                SET owner_name = :name,
                    phone_num  = :phone,
                    email      = :email,
                    username   = :username,
                    address    = :address
                WHERE owner_id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $owner_name,
            ':phone' => $phone_num,
            ':email' => $email,
            ':username' => $username,
            ':address' => $address,
            ':id' => $ownerID
        ]);

        $_SESSION['success_message'] = "";
        header("Location: ../frontend/ownerprofile.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_popup'] = ["Database error occurred. Please try again."];
        header("Location: ../frontend/ownerprofile.php");
        exit();
    }
}
