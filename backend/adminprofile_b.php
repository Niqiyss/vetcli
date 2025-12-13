<?php
// backend/adminprofile_b.php
session_start();

include "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$adminID = $_SESSION['adminID'];
$formErrors = [];

// Fetch admin data
$stmt = $conn->prepare("SELECT * FROM clinic_administrator WHERE admin_id = :admin_id");
$stmt->execute([':admin_id' => $adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $adminName = trim($_POST['admin_name']);
    $phoneNum = trim($_POST['phone_num']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validations
    if (!$adminName || !$phoneNum || !$username || !$password) {
        $formErrors[] = "All fields are required.";
    }
    if (!preg_match("/^[A-Za-z ]+$/", $adminName)) {
        $formErrors[] = "Name can only contain letters and spaces.";
    }
    if (!preg_match("/^[0-9\-]+$/", $phoneNum)) {
        $formErrors[] = "Phone number must contain only digits or dashes.";
    }
    if (strlen($password) < 6) {
        $formErrors[] = "Password must be at least 6 characters.";
    }

    if (empty($formErrors)) {
        try {
            $sql = "UPDATE clinic_administrator
                    SET admin_name = :admin_name, 
                        phone_num = :phone_num, 
                        username = :username, 
                        password = :password
                    WHERE admin_id = :admin_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':admin_name' => $adminName,
                ':phone_num' => $phoneNum,
                ':username' => $username,
                ':password' => $password,
                ':admin_id' => $adminID
            ]);

            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: ../frontend/adminprofile.php");
            exit();

        } catch (PDOException $e) {
            $formErrors[] = "Database Error: " . $e->getMessage();
        }
    }
}
?>
