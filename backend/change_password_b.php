<?php
session_start();
include "../backend/connection.php";

//user
if (isset($_SESSION['vetID'])) {
    $table  = "veterinarian";
    $idCol  = "vet_id";
    $userID = $_SESSION['vetID'];
} elseif (isset($_SESSION['ownerID'])) {
    $table  = "owner";
    $idCol  = "owner_id";
    $userID = $_SESSION['ownerID'];
} else {
    header("Location: ../frontend/userlogin.php");
    exit();
}

//fetch password
$stmt = $conn->prepare("SELECT password FROM $table WHERE $idCol = :id");
$stmt->execute([':id' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: ../frontend/change_password.php");
    exit();
}

//validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    /* Required fields */
    if (!$current || !$new || !$confirm) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    if (strlen($new) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    /* Confirm password */
    if ($new !== $confirm) {
        $_SESSION['error_message'] = "New password and confirmation do not match.";
        header("Location: ../frontend/change_password.php");
        exit();
    }


    $storedPassword = $user['password'];
    $isValid = false;

    // Case 1: hashed password
    if (password_verify($current, $storedPassword)) {
        $isValid = true;
    }

    // Case 2: old plaintext password
    if (!$isValid && $current === $storedPassword) {
        $isValid = true;
    }

    if (!$isValid) {
        $_SESSION['error_message'] = "Current password is incorrect.";
        header("Location: ../frontend/change_password.php");
        exit();
    }


    $newHash = password_hash($new, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "UPDATE $table SET password = :pw WHERE $idCol = :id"
    );
    $stmt->execute([
        ':pw' => $newHash,
        ':id' => $userID
    ]);

    $_SESSION['success_message'] = "Password updated successfully.";
    header("Location: ../frontend/change_password.php");
    exit();
}
