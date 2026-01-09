<?php
//pet_update_b.php

session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];
$formErrors = [];

//input
$pet_id = $_POST['pet_id'] ?? '';
$pet_name = trim($_POST['pet_name'] ?? '');
$gender = $_POST['gender'] ?? '';
$color = trim($_POST['color'] ?? '');
$dob = $_POST['dob'] ?? '';

//validation
if (!$pet_id || !$pet_name || !$gender || !$color || !$dob) {
    $formErrors[] = "All fields are required";
}

if (!preg_match("/^[A-Za-z ]+$/", $pet_name)) {
    $formErrors[] = "Pet name must contain letters only";
}

if (!preg_match("/^[A-Za-z ,]+$/", $color)) {
    $formErrors[] = "Color must contain letters only";
}

if ($dob > date("Y-m-d")) {
    $formErrors[] = "Date of birth cannot be in the future";
}

//fetch current pet
$stmt = $conn->prepare("
    SELECT species, breed, pet_image
    FROM pet
    WHERE pet_id = :pid AND owner_id = :oid
");
$stmt->execute([
    ':pid' => $pet_id,
    ':oid' => $ownerID
]);

$currentPet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentPet) {
    $_SESSION['error_popup'] = "Unauthorized pet update";
    header("Location: ../frontend/ownerpetlist.php");
    exit();
}

//keep
$species = $currentPet['species'];
$breed = $currentPet['breed'];
$pet_image = $currentPet['pet_image'];

//image upload
$upload_dir = "../uploads/pets/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES['pet_image']['name'])) {

    $ext = strtolower(pathinfo($_FILES['pet_image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        $formErrors[] = "Only JPG, JPEG, PNG or GIF images allowed";
    }

    if ($_FILES['pet_image']['size'] > 2 * 1024 * 1024) {
        $formErrors[] = "Image must not exceed 2MB";
    }

    if (empty($formErrors)) {
        $newName = "pet_" . $pet_id . "_" . time() . "." . $ext;
        $target = $upload_dir . $newName;

        if (move_uploaded_file($_FILES['pet_image']['tmp_name'], $target)) {

            if ($pet_image && file_exists($upload_dir . $pet_image)) {
                unlink($upload_dir . $pet_image);
            }

            $pet_image = $newName;
        } else {
            $formErrors[] = "Failed to upload pet image";
        }
    }
}

//error
if (!empty($formErrors)) {
    $_SESSION['error_popup'] = implode("\n", $formErrors);
    header("Location: ../frontend/ownerpetlist.php");
    exit();
}

//update
$stmt = $conn->prepare("
    UPDATE pet SET
        pet_name  = :name,
        species   = :species,
        breed     = :breed,
        gender    = :gender,
        color     = :color,
        dob       = :dob,
        pet_image = :img
    WHERE pet_id = :pid AND owner_id = :oid
");

$stmt->execute([
    ':name' => $pet_name,
    ':species' => $species,
    ':breed' => $breed,
    ':gender' => $gender,
    ':color' => $color,
    ':dob' => $dob,
    ':img' => $pet_image,
    ':pid' => $pet_id,
    ':oid' => $ownerID
]);

$_SESSION['success_popup'] = "Pet updated successfully";
header("Location: ../frontend/ownerpetlist.php");
exit();
