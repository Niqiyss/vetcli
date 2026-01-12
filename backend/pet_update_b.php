<?php
// backend/pet_update_b.php

session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];
$formErrors = [];

/* =====================================================
   COLOR DETECTION FUNCTIONS (SAME AS REGISTER)
===================================================== */
function mapColorName($r, $g, $b)
{

    if ($r < 60 && $g < 60 && $b < 60) {
        return "Black";
    }

    if ($r > 200 && $g > 200 && $b > 200) {
        return "White";
    }

    if (abs($r - $g) < 15 && abs($r - $b) < 15 && $r < 200) {
        return "Grey";
    }

    if ($r > 180 && $g > 100 && $g < 180 && $b < 100) {
        return "Orange";
    }

    if ($r > 120 && $r > $g && $g > $b) {
        return "Brown";
    }

    return "Other";
}

function detectDominantColor($imagePath)
{
    $img = @imagecreatefromstring(file_get_contents($imagePath));
    if (!$img)
        return null;

    $width = imagesx($img);
    $height = imagesy($img);

    $colorCount = [];

    for ($x = 0; $x < $width; $x += 10) {
        for ($y = 0; $y < $height; $y += 10) {

            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $color = mapColorName($r, $g, $b);
            if ($color !== "Other") {
                $colorCount[$color] = ($colorCount[$color] ?? 0) + 1;
            }
        }
    }

    imagedestroy($img);

    if (empty($colorCount))
        return null;

    arsort($colorCount);
    $top = array_keys($colorCount);

    return count($top) === 1 ? $top[0] : $top[0] . " & " . $top[1];
}

/* =====================================================
   INPUT
===================================================== */
$pet_id = $_POST['pet_id'] ?? '';
$pet_name = trim($_POST['pet_name'] ?? '');
$gender = $_POST['gender'] ?? '';
$color = trim($_POST['color'] ?? '');
$dob = $_POST['dob'] ?? '';

/* =====================================================
   VALIDATION
===================================================== */
if (!$pet_id || !$pet_name || !$gender || !$dob) {
    $formErrors[] = "All required fields must be filled";
}

if (!preg_match("/^[A-Za-z ]+$/", $pet_name)) {
    $formErrors[] = "Pet name must contain letters only";
}

if ($color && !preg_match("/^[A-Za-z ]+( & [A-Za-z ]+)?$/", $color)) {
    $formErrors[] = "Invalid color format";
}

if ($dob > date("Y-m-d")) {
    $formErrors[] = "Date of birth cannot be in the future";
}

/* =====================================================
   FETCH CURRENT PET (SECURITY)
===================================================== */
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

$species = $currentPet['species'];
$breed = $currentPet['breed'];
$pet_image = $currentPet['pet_image'];

/* =====================================================
   IMAGE UPLOAD
===================================================== */
$upload_dir = "../uploads/pets/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$imageChanged = false;

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
            $imageChanged = true;
        } else {
            $formErrors[] = "Failed to upload pet image";
        }
    }
}

/* =====================================================
   AUTO-DETECT COLOR (ONLY IF IMAGE CHANGED & COLOR EMPTY)
===================================================== */
if ($imageChanged && empty($color)) {
    $detectedColor = detectDominantColor($upload_dir . $pet_image);
    $color = $detectedColor ?? "Other";
}

/* FINAL SAFETY */
if (empty($color)) {
    $color = "Other";
}

/* =====================================================
   UPDATE
===================================================== */
if (!empty($formErrors)) {
    $_SESSION['error_popup'] = implode("\n", $formErrors);
    header("Location: ../frontend/ownerpetlist.php");
    exit();
}

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
