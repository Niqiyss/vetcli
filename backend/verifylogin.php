<?php
//verifylogin.php

session_start();
include "../backend/connection.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // -------- Clinic_Administrator --------
        $sql = "SELECT admin_id, username, password 
                FROM clinic_administrator 
                WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $password]);

        if ($stmt->rowCount() === 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['adminID'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: ../frontend/adminhome.php");
            exit();
        }

        // ---------- Veterinarian ----------
        $sql = "SELECT vet_id, username, password 
                FROM veterinarian 
                WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $password]);

        if ($stmt->rowCount() === 1) {
            $vet = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['vetID'] = $vet['vet_id'];
            $_SESSION['username'] = $vet['username'];
            header("Location: ../frontend/vethome.php");
            exit();
        }

        // ---------- Owner ----------
        $sql = "SELECT owner_id, username, password 
                FROM owner 
                WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $password]);

        if ($stmt->rowCount() === 1) {
            $owner = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['ownerID'] = $owner['owner_id'];
            $_SESSION['username'] = $owner['username'];
            header("Location: ../frontend/ownerhome.php");
            exit();
        }

        // ---------- Invalid login ----------
        echo "<script>
                alert('Invalid username or password!');
                window.location='../frontend/userlogin.php';
              </script>";

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    // Prevent direct access
    header("Location: ../frontend/userlogin.php");
    exit();
}