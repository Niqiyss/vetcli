<?php
session_start();
require_once "../backend/connection.php";

/* ===============================
   AUTH CHECK
================================ */
if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$formErrors = [];

/* ===============================
   DEFAULT VALUES
================================ */
$pet_name = "";
$species = "";
$other_species = "";
$breed = "";
$other_breed = "";
$gender = "";
$dob = "";
$pet_image = null;

/* ===============================
   FORM SUBMISSION
================================ */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $pet_name = trim($_POST['pet_name'] ?? "");
    $species = $_POST['species'] ?? "";
    $other_species = trim($_POST['other_species'] ?? "");
    $breed = $_POST['breed'] ?? "";
    $other_breed = trim($_POST['other_breed'] ?? "");
    $gender = $_POST['gender'] ?? "";
    $dob = $_POST['dob'] ?? "";

    /* ---------- VALIDATION ---------- */
    if (!$pet_name || !$species || !$gender || !$dob) {
        $formErrors[] = "All required fields must be filled.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $pet_name)) {
        $formErrors[] = "Pet name must contain letters only.";
    }

    if ($dob > date("Y-m-d")) {
        $formErrors[] = "Date of birth cannot be in the future.";
    }

    if ($species === "Other" && $other_species === "") {
        $formErrors[] = "Please specify species.";
    }

    if ($breed === "Other" && $other_breed === "") {
        $formErrors[] = "Please specify breed.";
    }

    /* ---------- FINAL VALUES ---------- */
    $species_final = ($species === "Other") ? $other_species : $species;

    $breed_final =
        ($breed === "Other") ? $other_breed :
        (($breed === "None" || $breed === "") ? null : $breed);

    /* ===============================
       IMAGE UPLOAD
    ================================ */
    $upload_dir = "../uploads/pets/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['pet_image']['name'])) {

        $tmp = $_FILES['pet_image']['tmp_name'];
        $safe = preg_replace("/[^A-Za-z0-9.\-_]/", "", $_FILES['pet_image']['name']);
        $file_name = time() . "_" . $safe;

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed)) {
            $formErrors[] = "Only JPG, JPEG, PNG, or GIF images are allowed.";
        }

        if ($_FILES['pet_image']['size'] > 2 * 1024 * 1024) {
            $formErrors[] = "Pet image must not exceed 2MB.";
        }

        if (empty($formErrors)) {
            if (move_uploaded_file($tmp, $upload_dir . $file_name)) {
                $pet_image = $file_name;
            } else {
                $formErrors[] = "Image upload failed.";
            }
        }
    }

    /* ===============================
       GENERATE PET ID
    ================================ */
    $stmt = $conn->query("
        SELECT MAX(CAST(SUBSTRING(pet_id FROM 2) AS INTEGER)) AS max_num 
        FROM pet
    ");
    $last = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $last['max_num'] ? intval($last['max_num']) + 1 : 1;
    $pet_id = "P" . str_pad($num, 4, "0", STR_PAD_LEFT);

    /* ===============================
       INSERT TO DATABASE
    ================================ */
    if (empty($formErrors)) {
        try {
            $sql = "
                INSERT INTO pet
                (pet_id, pet_name, species, breed, gender, color, dob, owner_id, pet_image)
                VALUES
                (:pet_id, :pet_name, :species, :breed, :gender, 'Unknown', :dob, :owner_id, :pet_image)
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':pet_id'   => $pet_id,
                ':pet_name' => $pet_name,
                ':species'  => $species_final,
                ':breed'    => $breed_final,
                ':gender'   => $gender,
                ':dob'      => $dob,
                ':owner_id' => $_SESSION['ownerID'],
                ':pet_image'=> $pet_image
            ]);

            $_SESSION['success_popup'] = "Pet registered successfully!";
            header("Location: ../frontend/ownerpetlist.php");
            exit();

        } catch (PDOException $e) {
            $formErrors[] = "Database error occurred.";
        }
    }
}
?>
