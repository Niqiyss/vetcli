<?php
// verifylogin.php
session_start();
session_regenerate_id(true);

require_once "../backend/connection.php";

/* =========================
   SSO CONFIG
========================= */
define('SSO_SECRET', 'VETCLINIC_SSO_2026_SECRET');
define('SSO_EXPIRE', 300);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

/* =========================
   CREATE SSO TOKEN
========================= */
function createSSOToken($id, $name, $type) {
    $payload = [
        'id'   => $id,
        'name' => $name,
        'type' => $type,
        'exp'  => time() + SSO_EXPIRE
    ];
    $payload_b64 = base64_encode(json_encode($payload));
    $signature   = hash_hmac('sha256', $payload_b64, SSO_SECRET);
    return $payload_b64 . '.' . $signature;
}

/* =========================
   REMAINING LOCK TIME
========================= */
function getRemainingTime($lockUntil) {
    $now = new DateTime('now');
    $lockTime = new DateTime($lockUntil);

    if ($lockTime <= $now) {
        return "a moment";
    }

    $diff = $now->diff($lockTime);

    if ($diff->i > 0) {
        return $diff->i . " minute(s) " . $diff->s . " second(s)";
    }

    return $diff->s . " second(s)";
}

try {

    /* =====================================================
       🔐 ADMIN LOGIN
    ===================================================== */
    $stmt = $conn->prepare("
        SELECT admin_id, username, admin_name, password,
               failed_attempts, lock_until
        FROM clinic_administrator
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {

        if ($admin['lock_until'] !== null && strtotime($admin['lock_until']) > time()) {
            $wait = getRemainingTime($admin['lock_until']);
            $_SESSION['error_popup'] =
                "Your account is locked! Please try again in $wait";
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
            $_SESSION['username']  = $admin['username'];
            $_SESSION['adminname'] = $admin['admin_name'];
            $_SESSION['sso_token'] = createSSOToken(
                $admin['admin_id'],
                $admin['admin_name'],
                'admin'
            );

            header("Location: ../frontend/adminhome.php");
            exit();
        } else {
            $conn->prepare("
                UPDATE clinic_administrator
                SET failed_attempts = failed_attempts + 1
                WHERE admin_id = :id
            ")->execute([':id' => $admin['admin_id']]);
        }
    }

    /* =====================================================
       🩺 VETERINARIAN LOGIN
    ===================================================== */
    $stmt = $conn->prepare("
        SELECT vet_id, username, vet_name, password,
               failed_attempts, lock_until
        FROM veterinarian
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $vet = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vet) {

        if ($vet['lock_until'] !== null && strtotime($vet['lock_until']) > time()) {
            $wait = getRemainingTime($vet['lock_until']);
            $_SESSION['error_popup'] =
                "Your account is locked! Please try again in $wait";
            header("Location: ../frontend/userlogin.php");
            exit();
        }

        if (password_verify($password, $vet['password'])) {

            $conn->prepare("
                UPDATE veterinarian
                SET failed_attempts = 0
                WHERE vet_id = :id
            ")->execute([':id' => $vet['vet_id']]);

            $_SESSION['vetID']    = $vet['vet_id'];
            $_SESSION['username'] = $vet['username'];
            $_SESSION['vetname']  = $vet['vet_name'];
            $_SESSION['sso_token'] = createSSOToken(
                $vet['vet_id'],
                $vet['vet_name'],
                'vet'
            );

            header("Location: ../frontend/vethome.php");
            exit();
        } else {
            $conn->prepare("
                UPDATE veterinarian
                SET failed_attempts = failed_attempts + 1
                WHERE vet_id = :id
            ")->execute([':id' => $vet['vet_id']]);
        }
    }

    /* =====================================================
       🐾 OWNER LOGIN
    ===================================================== */
    $stmt = $conn->prepare("
        SELECT owner_id, username, owner_name, password,
               failed_attempts, lock_until
        FROM owner
        WHERE username = :username
    ");
    $stmt->execute([':username' => $username]);
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($owner) {

        if ($owner['lock_until'] !== null && strtotime($owner['lock_until']) > time()) {
            $wait = getRemainingTime($owner['lock_until']);
            $_SESSION['error_popup'] =
                "Your account is locked! Please try again in $wait";
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
            $_SESSION['username']  = $owner['username'];
            $_SESSION['ownername'] = $owner['owner_name'];
            $_SESSION['sso_token'] = createSSOToken(
                $owner['owner_id'],
                $owner['owner_name'],
                'owner'
            );

            header("Location: ../frontend/ownerhome.php");
            exit();
        } else {
            $conn->prepare("
                UPDATE owner
                SET failed_attempts = failed_attempts + 1
                WHERE owner_id = :id
            ")->execute([':id' => $owner['owner_id']]);
        }
    }

    /* ================= INVALID LOGIN ================= */
    $_SESSION['error_popup'] = "Invalid username or password";
    header("Location: ../frontend/userlogin.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_popup'] = "System error. Please try again";
    header("Location: ../frontend/userlogin.php");
    exit();
}
