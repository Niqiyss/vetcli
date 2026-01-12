<?php
// delete_owner_account.php
session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID  = $_SESSION['ownerID'];
$password = $_POST['password'] ?? '';

if (!$password) {
    $_SESSION['error_popup'] = ["Password is required to delete account."];
    header("Location: ../frontend/ownerprofile.php");
    exit();
}

// fetch password hash
$stmt = $conn->prepare("SELECT password FROM owner WHERE owner_id = :id");
$stmt->execute([':id' => $ownerID]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$owner || !password_verify($password, $owner['password'])) {
    $_SESSION['error_popup'] = ["Incorrect password"];
    header("Location: ../frontend/ownerprofile.php");
    exit();
}

try {
    $conn->beginTransaction();

    // delete owner (add other related tables here if needed)
    $stmt = $conn->prepare("DELETE FROM owner WHERE owner_id = :id");
    $stmt->execute([':id' => $ownerID]);

    $conn->commit();

    session_destroy();
    header("Location: ../frontend/userlogin.php?deleted=1");
    exit();

} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['error_popup'] = ["Unable to delete account. Please try again"];
    header("Location: ../frontend/ownerprofile.php");
    exit();
}
