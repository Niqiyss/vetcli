<?php
session_start();

require_once "../backend/connection.php";
require_once "../backend/selectmysql.php";


if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit;
}

$ownerID  = $_SESSION['ownerID'];
$password = $_POST['password'] ?? '';

$conn = getMySQLConnection();

//check pending payment
if (getLatestUnpaidPaymentByOwner($ownerID)) {
    $_SESSION['error_popup'] = [
        "Account deletion is not allowed",
        "You still have pending payments",
        "Please settle all payments before deleting your account"
    ];
    header("Location: ../frontend/ownerprofile.php");
    exit;
}

//verify
$passStmt = $conn->prepare("
    SELECT password
    FROM owner
    WHERE owner_id = :owner_id
");
$passStmt->execute([
    ':owner_id' => $ownerID
]);

$user = $passStmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error_popup'] = ["Incorrect password"];
    header("Location: ../frontend/ownerprofile.php");
    exit;
}

//delete acc
try {
    $conn->beginTransaction();

    $conn->prepare("
        DELETE FROM owner
        WHERE owner_id = :owner_id
    ")->execute([
        ':owner_id' => $ownerID
    ]);

    $conn->commit();

    session_destroy();
    header("Location: ../frontend/userlogin.php");
    exit;

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error_popup'] = ["Failed to delete account"];
    header("Location: ../frontend/ownerprofile.php");
    exit;
}
