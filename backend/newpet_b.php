<?php
//newpet_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$formErrors = [];

//var
$pet_name = "";
$species = "";
$other_species = "";
$breed = "";
$other_breed = "";
$gender = "";
$color = "";
$dob = "";
$pet_image = null;
$preview_image_url = "";


$species_options = [
    "Cat",
    "Dog",
    "Rabbit",
    "Hamster",
    "Guinea Pig",
    "Gerbil",
    "Ferret",
    "Bird",
    "Parrot",
    "Fish",
    "Turtle",
    "Iguana",
    "Snake",
    "Hedgehog",
    "Sugar Glider",
    "Chinchilla",
    "Frog",
    "Goat",
    "Sheep",
    "Chicken",
    "Duck",
    "Other"
];


$breed_options = [
    "Cat" => ["Persian", "Siamese", "Maine Coon", "Ragdoll", "British Shorthair", "Bengal", "Sphynx", "Mixed", "Other"],
    "Dog" => ["Husky", "Bulldog", "Golden Retriever", "Chihuahua", "Poodle", "Beagle", "German Shepherd", "Mixed", "Other"],

    "Rabbit" => [],
    "Hamster" => [],
    "Guinea Pig" => [],
    "Gerbil" => [],
    "Ferret" => [],
    "Bird" => [],
    "Parrot" => [],
    "Fish" => [],
    "Turtle" => [],
    "Iguana" => [],
    "Snake" => [],
    "Hedgehog" => [],
    "Sugar Glider" => [],
    "Chinchilla" => [],
    "Frog" => [],
    "Goat" => [],
    "Sheep" => [],
    "Chicken" => [],
    "Duck" => [],

    "Other" => []
];


if ($_SERVER['REQUEST_METHOD'] == "POST") {


    $pet_name = trim($_POST['pet_name']);
    $species = $_POST['species'] ?? "";
    $other_species = $_POST['other_species'] ?? "";
    $breed = $_POST['breed'] ?? "";
    $other_breed = $_POST['other_breed'] ?? "";
    $gender = $_POST['gender'] ?? "";
    $color = trim($_POST['color']);
    $dob = $_POST['dob'] ?? "";

    //validation

    if (!$pet_name || !$species || !$gender || !$color || !$dob) {
        $formErrors[] = "All required fields must be filled.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $pet_name)) {
        $formErrors[] = "Pet name must contain letters only.";
    }

    if (!preg_match("/^[A-Za-z ,]+$/", $color)) {
        $formErrors[] = "Color must contain letters and commas only.";
    }

    if ($species === "Other" && empty($other_species)) {
        $formErrors[] = "Please specify species.";
    }

    if ($breed === "Other" && empty($other_breed)) {
        $formErrors[] = "Please specify breed.";
    }

    if ($dob > date("Y-m-d")) {
        $formErrors[] = "Invalid date of birth.";
    }


    $species_final = ($species === "Other") ? $other_species : $species;


    $breed_final = ($breed === "Other") ? $other_breed :
        ($breed === "" ? "" : $breed);



    //image validate
    $upload_dir = "../uploads/pets/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['pet_image']['name'])) {

        $file_tmp = $_FILES['pet_image']['tmp_name'];
        $file_name = time() . "_" . preg_replace("/[^A-Za-z0-9.\-_]/", "", $_FILES['pet_image']['name']);

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed_ext)) {
            $formErrors[] = "Only JPG, JPEG, PNG, GIF allowed.";
        }

        if ($_FILES['pet_image']['size'] > 2 * 1024 * 1024) {
            $formErrors[] = "Max image size is 2MB.";
        }

        if (empty($formErrors)) {
            $target = $upload_dir . $file_name;

            if (move_uploaded_file($file_tmp, $target)) {
                $pet_image = $file_name;
            } else {
                $formErrors[] = "Failed to upload image. Folder permission error.";
            }
        }
    }



    if (empty($formErrors)) {

        //petid
        $stmt = $conn->query("SELECT MAX(CAST(SUBSTRING(pet_id FROM 2) AS INTEGER)) AS max_num FROM pet");
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        $num = $last['max_num'] ? intval($last['max_num']) + 1 : 1;
        $pet_id = "P" . str_pad($num, 4, "0", STR_PAD_LEFT);

        $sql = "INSERT INTO pet 
                (pet_id, pet_name, species, breed, gender, color, dob, owner_id, pet_image)
                VALUES 
                (:pet_id, :pet_name, :species, :breed, :gender, :color, :dob, :owner_id, :pet_image)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':pet_id' => $pet_id,
            ':pet_name' => $pet_name,
            ':species' => $species_final,
            ':breed' => $breed_final,
            ':gender' => $gender,
            ':color' => $color,
            ':dob' => $dob,
            ':owner_id' => $_SESSION['ownerID'],
            ':pet_image' => $pet_image
        ]);

        $_SESSION['success_popup'] = "Pet ID: $pet_id";

        header("Location: ../frontend/newpet.php");
        exit();
    }
}
?>