<?php
//emergency_b.php
session_start();
include "../backend/connection.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Detect if Guest or Registered
    $owner_id = $_SESSION['ownerID'] ?? null;
    $guest_name = null;

    //Input
    $pet_name = trim($_POST['pet_name']);
    $species = trim($_POST['species']);
    $severity = $_POST['severity'];
    $symptoms = trim($_POST['symptoms']);
    $contact_number = trim($_POST['contact_number']);

    //Validation
    if (!$owner_id) {
        // If guest, we MUST have a name
        $guest_name = trim($_POST['guest_name']);
        if (empty($guest_name)) $errors[] = "Your name is required.";
    }

    if (empty($pet_name)) $errors[] = "Pet name is required.";
    if (empty($contact_number)) $errors[] = "Contact number is required.";
    if (empty($symptoms)) $errors[] = "Please describe the symptoms.";

    // 4. Insert into Database
    if (empty($errors)) {
        try {
            // We insert owner_id (can be null) AND guest_name (can be null)
            $sql = "INSERT INTO emergency_cases (owner_id, guest_name, pet_name, species, severity, symptoms, contact_number) 
                    VALUES (:oid, :gname, :pname, :species, :sev, :sym, :num)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':oid' => $owner_id,
                ':gname' => $guest_name,
                ':pname' => $pet_name,
                ':species' => $species,
                ':sev' => $severity,
                ':sym' => $symptoms,
                ':num' => $contact_number
            ]);

            $_SESSION['success_popup'] = "Emergency alert sent! Our team is preparing for your arrival.";
            
            // Redirect Logic: Owners go to dashboard, Guests go to index/home
            if ($owner_id) {
                header("Location: ../frontend/ownerhome.php"); 
            } else {
                // If guest, maybe go back to the emergency page or home
                header("Location: ../frontend/home.php"); 
            }
            exit();

        } catch (PDOException $e) {
            $errors[] = "Database Error: " . $e->getMessage();
            $_SESSION['error_popup'] = $errors;
            header("Location: ../frontend/emergency.php");
            exit();
        }
    } else {
        $_SESSION['error_popup'] = $errors;
        header("Location: ../frontend/emergency.php");
        exit();
    }
}
?>