<?php
//change_password_b.php
session_start();
require_once "../backend/connection.php";

/* ===== USER ROLE ===== */
if (isset($_SESSION['adminID'])) {
    $table = "clinic_administrator";
    $idCol = "admin_id";
    $userID = $_SESSION['adminID'];
} elseif (isset($_SESSION['vetID'])) {
    $table = "veterinarian";
    $idCol = "vet_id";
    $userID = $_SESSION['vetID'];
} elseif (isset($_SESSION['ownerID'])) {
    $table = "owner";
    $idCol = "owner_id";
    $userID = $_SESSION['ownerID'];
} else {
    header("Location: ../frontend/userlogin.php");
    exit();
}

/* ===== FETCH CURRENT PASSWORD ===== */
$stmt = $conn->prepare("SELECT password FROM $table WHERE $idCol = :id");
$stmt->execute([':id' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: ../frontend/change_password.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = trim($_POST['current_password'] ?? '');
    $new = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    /* ===== REQUIRED ===== */
    if (!$current || !$new || !$confirm) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    /* ===== PASSWORD RULES ===== */
    if (strlen($new) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    if (!preg_match('/[A-Z]/', $new)) {
        $_SESSION['error_message'] = "Password must include at least one uppercase letter.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    if (!preg_match('/[\W_]/', $new)) {
        $_SESSION['error_message'] = "Password must include at least one symbol.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    if ($new !== $confirm) {
        $_SESSION['error_message'] = "New password and confirmation do not match.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    /* ===== VERIFY CURRENT PASSWORD ===== */
    $storedPassword = $user['password'];
    $isValid = false;

    // Hashed password
    if (password_verify($current, $storedPassword)) {
        $isValid = true;
    }

    // plaintext
    if (!$isValid && $current === $storedPassword) {
        $isValid = true;
    }

    if (!$isValid) {
        $_SESSION['error_message'] = "Current password is incorrect.";
        header("Location: ../frontend/change_password.php");
        exit();
    }

    /* ===== UPDATE PASSWORD ===== */
    $newHash = password_hash($new, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "UPDATE $table SET password = :pw WHERE $idCol = :id"
    );
    $stmt->execute([
        ':pw' => $newHash,
        ':id' => $userID
    ]);

    $_SESSION['success_message'] = "Password updated successfully";
    header("Location: ../frontend/change_password.php");
    exit();
}
