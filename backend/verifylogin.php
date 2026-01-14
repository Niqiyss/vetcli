<?php
session_start();
session_regenerate_id(true);

require_once "../backend/connection.php";
require_once "../backend/sso_helper.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$lockMessage =
    "Your account has been locked due to multiple failed login attempts. Please wait for admin to unlock your account.";

try {

    //adminlogin
    $stmt = $conn->prepare("
        SELECT admin_id, username, admin_name, password, failed_attempts
        FROM clinic_administrator
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {

        if ($admin['failed_attempts'] >= 3) {
            $_SESSION['error_popup'] = $lockMessage;
            header("Location: ../frontend/userlogin.php");
            exit();
        }

        if (password_verify($password, $admin['password'])) {

            $conn->prepare("
                UPDATE clinic_administrator
                SET failed_attempts = 0
                WHERE admin_id = :id
            ")->execute([':id' => $admin['admin_id']]);

            $_SESSION['adminID']   = $admin['admin_id'];
            $_SESSION['adminname'] = $admin['admin_name'];
            $_SESSION['username']  = $admin['username'];
            $_SESSION['userType']  = 'admin';

            // create sso token
            $_SESSION['sso_token'] = createSSOToken(
                $admin['admin_id'],
                $admin['admin_name'],
                'admin'
            );

            header("Location: ../frontend/adminhome.php");
            exit();
        }

        $conn->prepare("
            UPDATE clinic_administrator
            SET failed_attempts = LEAST(failed_attempts + 1, 3)
            WHERE admin_id = :id
        ")->execute([':id' => $admin['admin_id']]);
    }

    //vetlogin
    $stmt = $conn->prepare("
        SELECT vet_id, username, vet_name, password, failed_attempts
        FROM veterinarian
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $vet = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vet) {

        if ($vet['failed_attempts'] >= 3) {
            $_SESSION['error_popup'] = $lockMessage;
            header("Location: ../frontend/userlogin.php");
            exit();
        }

        if (password_verify($password, $vet['password'])) {

            $conn->prepare("
                UPDATE veterinarian
                SET failed_attempts = 0
                WHERE vet_id = :id
            ")->execute([':id' => $vet['vet_id']]);

            $_SESSION['vetID']   = $vet['vet_id'];
            $_SESSION['vetname'] = $vet['vet_name'];
            $_SESSION['username'] = $vet['username'];
            $_SESSION['userType'] = 'veterinarian';

            $_SESSION['sso_token'] = createSSOToken(
                $vet['vet_id'],
                $vet['vet_name'],
                'veterinarian'
            );

            header("Location: ../frontend/vethome.php");
            exit();
        }

        $conn->prepare("
            UPDATE veterinarian
            SET failed_attempts = LEAST(failed_attempts + 1, 3)
            WHERE vet_id = :id
        ")->execute([':id' => $vet['vet_id']]);
    }

    //ownerlogin
    $stmt = $conn->prepare("
        SELECT owner_id, username, owner_name, password, failed_attempts
        FROM owner
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($owner) {

        if ($owner['failed_attempts'] >= 3) {
            $_SESSION['error_popup'] = $lockMessage;
            header("Location: ../frontend/userlogin.php");
            exit();
        }

        if (password_verify($password, $owner['password'])) {

            $conn->prepare("
                UPDATE owner
                SET failed_attempts = 0
                WHERE owner_id = :id
            ")->execute([':id' => $owner['owner_id']]);

            $_SESSION['ownerID']   = $owner['owner_id'];
            $_SESSION['ownername'] = $owner['owner_name'];
            $_SESSION['username']  = $owner['username'];
            $_SESSION['userType']  = 'owner';

            $_SESSION['sso_token'] = createSSOToken(
                $owner['owner_id'],
                $owner['owner_name'],
                'owner'
            );

            header("Location: ../frontend/ownerhome.php");
            exit();
        }

        $conn->prepare("
            UPDATE owner
            SET failed_attempts = LEAST(failed_attempts + 1, 3)
            WHERE owner_id = :id
        ")->execute([':id' => $owner['owner_id']]);
    }

    $_SESSION['error_popup'] = "Invalid username or password";
    header("Location: ../frontend/userlogin.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_popup'] = "System error. Please try again.";
    header("Location: ../frontend/userlogin.php");
    exit();
}
