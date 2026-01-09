<?php
//adminprofile_b.php

session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$adminID = $_SESSION['adminID'];
$formErrors = [];

//fetch admin
$stmt = $conn->prepare("
    SELECT * FROM clinic_administrator
    WHERE admin_id = :admin_id
");
$stmt->execute([':admin_id' => $adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}

//update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $adminName = trim($_POST['admin_name'] ?? '');
    $phoneNum  = trim($_POST['phone_num'] ?? '');
    $username  = trim($_POST['username'] ?? '');

    //validation
    if (!$adminName || !$phoneNum || !$username) {
        $formErrors[] = "All fields are required.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $adminName)) {
        $formErrors[] = "Name can only contain letters and spaces.";
    }

    if (!preg_match("/^[0-9\-]+$/", $phoneNum)) {
        $formErrors[] = "Phone number must contain digits or dashes only.";
    }


    if ($username !== $admin['username']) {
        $stmt = $conn->prepare("
            SELECT 1 FROM clinic_administrator
            WHERE username = :username
              AND admin_id <> :admin_id
        ");
        $stmt->execute([
            ':username' => $username,
            ':admin_id' => $adminID
        ]);

        if ($stmt->fetch()) {
            $formErrors[] = "Username already exists.";
        }
    }

    if (!empty($formErrors)) {
        $_SESSION['error_popup'] = $formErrors;
        header("Location: ../frontend/adminprofile.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("
            UPDATE clinic_administrator
            SET admin_name = :admin_name,
                phone_num = :phone_num,
                username   = :username
            WHERE admin_id = :admin_id
        ");

        $stmt->execute([
            ':admin_name' => $adminName,
            ':phone_num'  => $phoneNum,
            ':username'   => $username,
            ':admin_id'   => $adminID
        ]);

        $_SESSION['success_message'] = "";
        header("Location: ../frontend/adminprofile.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_popup'] = ["Database error occurred. Please try again."];
        header("Location: ../frontend/adminprofile.php");
        exit();
    }
}
