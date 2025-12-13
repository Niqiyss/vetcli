<?php
// backend/vetregister_b.php

include "../backend/connection.php";

$formErrors = [];
$vet_name = $phone_num = $email = $specialization = $username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vet_name = trim($_POST['vet_name']);
    $phone_num = trim($_POST['phone_num']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $admin_id = $_SESSION['adminID'];

    // Validation
    if (!$vet_name || !$phone_num || !$email || !$specialization || !$username || !$password) {
        $formErrors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    if (empty($formErrors)) {
        try {
            // Generate Vet ID
            $stmt = $conn->query("SELECT COUNT(*) FROM veterinarian");
            $count = $stmt->fetchColumn() + 1;
            $vet_id = 'VT' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Insert veterinarian
            $sql = "
                INSERT INTO veterinarian
                (vet_id, vet_name, phone_num, email, specialization, username, password, admin_id)
                VALUES
                (:vet_id, :vet_name, :phone, :email, :spec, :username, :password, :admin)
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':vet_id'   => $vet_id,
                ':vet_name' => $vet_name,
                ':phone'    => $phone_num,
                ':email'    => $email,
                ':spec'     => $specialization,
                ':username' => $username,
                ':password' => $password,
                ':admin'    => $admin_id
            ]);

            $_SESSION['success_popup'] = "Veterinarian registered. Please set availability.";

            // 👉 Redirect directly to availability page
            header("Location: ../frontend/admin/availability/availability_add.php?vet_id=$vet_id");
            exit();

        } catch (PDOException $e) {
            $formErrors[] = "Database error: " . $e->getMessage();
        }
    }
}
