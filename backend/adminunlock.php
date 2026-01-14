<?php
session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$userType = $_POST['user_type'] ?? '';
$userId   = $_POST['user_id'] ?? '';

if ($userType === '' || $userId === '') {
    $_SESSION['error_popup'] = "Invalid request";
    header("Location: ../frontend/admin_security.php");
    exit();
}

try {

    if ($userType === 'owner') {

        $stmt = $conn->prepare("
            UPDATE owner
            SET failed_attempts = 0
            WHERE owner_id = :id
            RETURNING owner_id
        ");

    } elseif ($userType === 'vet') {

        $stmt = $conn->prepare("
            UPDATE veterinarian
            SET failed_attempts = 0
            WHERE vet_id = :id
            RETURNING vet_id
        ");

    } else {
        throw new Exception("Invalid user type");
    }

    $stmt->execute([':id' => $userId]);


    if (!$stmt->fetch()) {
        throw new Exception("No account updated");
    }

    $_SESSION['success_popup'] = "Account unlocked successfully";

} catch (Exception $e) {
    $_SESSION['error_popup'] = "Failed to unlock account";
}

header("Location: ../frontend/admin_security.php");
exit();
