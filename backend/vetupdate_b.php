<?php
// backend/vetupdate_b.php

session_start();
include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$formErrors = [];
$vet_id = "";
$vet_name = $phone_num = $email = $specialization = $username = $password = "";

// Fetch existing vet info
if (isset($_GET['id'])) {
    $vet_id = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM veterinarian WHERE vet_id = :vet_id");
        $stmt->execute([':vet_id' => $vet_id]);
        $vet = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vet) {
            echo "<script>alert('Veterinarian not found!'); window.location='../frontend/vetlist.php';</script>";
            exit();
        }

        $vet_name = $vet['vet_name'];
        $phone_num = $vet['phone_num'];
        $email = $vet['email'];
        $specialization = $vet['specialization'];
        $username = $vet['username'];
        $password = $vet['password'];

    } catch (PDOException $e) {
        $formErrors[] = "Database Error: " . $e->getMessage();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $vet_id = $_POST['update'];
    $vet_name = trim($_POST['vet_name']);
    $phone_num = trim($_POST['phone_num']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // validation
    if (!$vet_name || !$phone_num || !$email || !$specialization || !$username || !$password) {
        $formErrors[] = "All fields are required.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $vet_name)) {
        $formErrors[] = "Full name can only contain letters and spaces.";
    }

    if (!preg_match("/^[0-9\-]+$/", $phone_num)) {
        $formErrors[] = "Phone number must contain only digits or dashes.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $formErrors[] = "Password must be at least 6 characters.";
    }

    try {
        // check duplicate username except current vet
        $stmt = $conn->prepare("SELECT 1 FROM veterinarian WHERE username = :username AND vet_id != :vet_id");
        $stmt->execute([':username' => $username, ':vet_id' => $vet_id]);

        if ($stmt->fetch()) {
            $formErrors[] = "Username already taken!";
        }

        if (empty($formErrors)) {
            // update vet
            $sql = "UPDATE veterinarian SET
                        vet_name = :vet_name,
                        phone_num = :phone_num,
                        email = :email,
                        specialization = :specialization,
                        username = :username,
                        password = :password
                    WHERE vet_id = :vet_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':vet_name' => $vet_name,
                ':phone_num' => $phone_num,
                ':email' => $email,
                ':specialization' => $specialization,
                ':username' => $username,
                ':password' => $password,
                ':vet_id' => $vet_id
            ]);

            // SweetAlert2 success popup
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Veterinarian ID $vet_id updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location='../frontend/vetlist.php';
                });
            </script>
            ";
            exit();
        }

    } catch (PDOException $e) {
        $formErrors[] = "Database Error: " . $e->getMessage();
    }
}
?>
