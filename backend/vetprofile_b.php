<?php
//vetprofile_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['vetID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$vet_id = $_SESSION['vetID'];
$formErrors = [];

//get vet
$stmt = $conn->prepare("SELECT * FROM veterinarian WHERE vet_id = :id");
$stmt->execute([':id' => $vet_id]);
$vet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vet) {
    die("Veterinarian not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vet_name = trim($_POST['vet_name'] ?? '');
    $phone = trim($_POST['phone_num'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $vet_image = $vet['vet_image'];

    //validation
    if (!$vet_name || !$phone || !$email || !$username) {
        $formErrors[] = "All fields are required.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $vet_name)) {
        $formErrors[] = "Full name may contain letters and spaces only.";
    }

    if (!preg_match("/^[0-9]+$/", $phone)) {
        $formErrors[] = "Phone number must contain digits only.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    //unique username
    $stmt = $conn->prepare("
        SELECT 1 FROM veterinarian 
        WHERE username = :u AND vet_id <> :id
    ");
    $stmt->execute([
        ':u' => $username,
        ':id' => $vet_id
    ]);

    if ($stmt->fetch()) {
        $formErrors[] = "Username already exists. Please choose another.";
    }

    //image uplaod
    $upload_dir = "../uploads/vets/";

    if (!empty($_FILES['vet_image']['name'])) {

        $ext = strtolower(pathinfo($_FILES['vet_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $formErrors[] = "Only JPG and PNG images are allowed.";
        }

        if ($_FILES['vet_image']['size'] > 2 * 1024 * 1024) {
            $formErrors[] = "Image must be under 2MB.";
        }

        if (empty($formErrors)) {
            $file_name = "vet_" . $vet_id . "_" . time() . "." . $ext;

            if (move_uploaded_file($_FILES['vet_image']['tmp_name'], $upload_dir . $file_name)) {

                if (!empty($vet['vet_image']) && $vet['vet_image'] !== 'default.png') {
                    @unlink($upload_dir . $vet['vet_image']);
                }

                $vet_image = $file_name;
            } else {
                $formErrors[] = "Failed to upload image.";
            }
        }
    }

    //error
    if (!empty($formErrors)) {
        $_SESSION['error_popup'] = $formErrors;
        header("Location: ../frontend/vetprofile.php");
        exit();
    }

    //update
    $stmt = $conn->prepare("
        UPDATE veterinarian SET
            vet_name  = :n,
            phone_num= :p,
            email    = :e,
            username = :u,
            vet_image= :i
        WHERE vet_id = :id
    ");

    $stmt->execute([
        ':n' => $vet_name,
        ':p' => $phone,
        ':e' => $email,
        ':u' => $username,
        ':i' => $vet_image,
        ':id' => $vet_id
    ]);

    $_SESSION['success_message'] = "";
    header("Location: ../frontend/vetprofile.php");
    exit();
}
