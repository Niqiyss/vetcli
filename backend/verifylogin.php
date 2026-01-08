<?php
// verifylogin.php

session_start();
include "../backend/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {

        /* ================= ADMIN (HASHED) ================= */
        $sql = "SELECT admin_id, username, admin_name, password
                FROM clinic_administrator
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        if ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($password, $admin['password'])) {

                $_SESSION['adminID']    = $admin['admin_id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['adminname']  = $admin['admin_name'];

                header("Location: ../frontend/adminhome.php");
                exit();
            }
        }

        /* ================= VETERINARIAN (HASHED) ================= */
        $sql = "SELECT vet_id, username, vet_name, password
                FROM veterinarian
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        if ($vet = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($password, $vet['password'])) {

                $_SESSION['vetID']    = $vet['vet_id'];
                $_SESSION['username'] = $vet['username'];
                $_SESSION['vetname']  = $vet['vet_name'];

                header("Location: ../frontend/vethome.php");
                exit();
            }
        }

        /* ================= OWNER (HASHED) ================= */
        $sql = "SELECT owner_id, username, owner_name, password
                FROM owner
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        if ($owner = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (password_verify($password, $owner['password'])) {

                $_SESSION['ownerID']   = $owner['owner_id'];
                $_SESSION['username']  = $owner['username'];
                $_SESSION['ownername'] = $owner['owner_name'];

                header("Location: ../frontend/ownerhome.php");
                exit();
            }
        }

        /* ================= INVALID LOGIN ================= */
        $_SESSION['error_popup'] = "Invalid username or password.";
        header("Location: ../frontend/userlogin.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_popup'] = "System error. Please try again.";
        header("Location: ../frontend/userlogin.php");
        exit();
    }

} else {
    header("Location: ../frontend/userlogin.php");
    exit();
}
