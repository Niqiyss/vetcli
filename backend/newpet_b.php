<?php
//newpet_b.php

session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$formErrors = [];

//color mapping
function mapColorName($r, $g, $b) {

    // BLACK
    if ($r < 60 && $g < 60 && $b < 60) {
        return "Black";
    }

    // WHITE
    if ($r > 200 && $g > 200 && $b > 200) {
        return "White";
    }

    // GREY
    if (abs($r - $g) < 15 && abs($r - $b) < 15 && $r < 200) {
        return "Grey";
    }

    // ORANGE / GINGER
    if ($r > 180 && $g > 100 && $g < 180 && $b < 100) {
        return "Orange";
    }

    // BROWN
    if ($r > 120 && $r > $g && $g > $b) {
        return "Brown";
    }

    return "Other";
}

function detectDominantColor($imagePath) {
    $img = @imagecreatefromstring(file_get_contents($imagePath));
    if (!$img) return null;

    $width  = imagesx($img);
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

    if (empty($colorCount)) {
        return null;
    }

    arsort($colorCount);
    $topColors = array_keys($colorCount);

    // One dominant color
    if (count($topColors) === 1) {
        return $topColors[0];
    }

    // Two dominant colors
    return $topColors[0] . " & " . $topColors[1];
}

//form
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $pet_name = trim($_POST['pet_name'] ?? "");
    $species = $_POST['species'] ?? "";
    $other_species = trim($_POST['other_species'] ?? "");
    $breed = $_POST['breed'] ?? "";
    $other_breed = trim($_POST['other_breed'] ?? "");
    $gender = $_POST['gender'] ?? "";
    $dob = $_POST['dob'] ?? "";

    $color = "";       
    $pet_image = null;

    /* VALIDATION */
    if (!$pet_name || !$species || !$gender || !$dob) {
        $formErrors[] = "All required fields must be filled";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $pet_name)) {
        $formErrors[] = "Pet name must contain letters only";
    }

    if ($species === "Other" && $other_species === "") {
        $formErrors[] = "Please specify species";
    }

    if ($breed === "Other" && $other_breed === "") {
        $formErrors[] = "Please specify breed";
    }

    if ($dob > date("Y-m-d")) {
        $formErrors[] = "Date of birth cannot be in the future";
    }

    $species_final = ($species === "Other") ? $other_species : $species;
    $breed_final =
        ($breed === "Other") ? $other_breed :
        (($breed === "None" || $breed === "") ? null : $breed);

    /* IMAGE UPLOAD */
    $upload_dir = "../uploads/pets/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['pet_image']['name'])) {

        $file_tmp = $_FILES['pet_image']['tmp_name'];
        $safe_name = preg_replace("/[^A-Za-z0-9.\-_]/", "", $_FILES['pet_image']['name']);
        $file_name = time() . "_" . $safe_name;

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed_ext)) {
            $formErrors[] = "Only JPG, JPEG, PNG, or GIF images are allowed";
        }

        if ($_FILES['pet_image']['size'] > 2 * 1024 * 1024) {
            $formErrors[] = "Pet image must not exceed 2MB";
        }

        if (empty($formErrors)) {
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $pet_image = $file_name;

                // AUTO-DETECT COLOR
                $detectedColor = detectDominantColor($upload_dir . $file_name);
                if ($detectedColor !== null) {
                    $color = $detectedColor;
                }
            } else {
                $formErrors[] = "Failed to upload pet image";
            }
        }
    }

    /* FINAL SAFETY FALLBACK */
    if (empty($color)) {
        $color = "Other";
    }

    /* INSERT */
    if (empty($formErrors)) {
        try {
            $sql = "
                INSERT INTO pet
                (pet_name, species, breed, gender, color, dob, owner_id, pet_image)
                VALUES
                (:pet_name, :species, :breed, :gender, :color, :dob, :owner_id, :pet_image)
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':pet_name'  => $pet_name,
                ':species'   => $species_final,
                ':breed'     => $breed_final,
                ':gender'    => $gender,
                ':color'     => $color,
                ':dob'       => $dob,
                ':owner_id'  => $_SESSION['ownerID'],
                ':pet_image' => $pet_image
            ]);

            $_SESSION['success_popup'] = "";
            header("Location: ../frontend/ownerpetlist.php");
            exit();

        } catch (PDOException $e) {
            die($e->getMessage()); 
        }
    }
}
?>
