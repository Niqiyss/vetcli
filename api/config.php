<?php

session_start();
include "../backend/connection.php";

/* ================= JWT CONFIG ================= */
$SECRET_KEY = "vetclinic";

/* ================= JWT GENERATOR ================= */
function generateJWT(array $payload, string $secret): string
{
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $base64Header = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $base64Payload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

    $signature = hash_hmac(
        'sha256',
        $base64Header . "." . $base64Payload,
        $secret,
        true
    );

    $base64Signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    return $base64Header . "." . $base64Payload . "." . $base64Signature;
}

/* ================= LOGIN ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {

        /* ========== CLINIC ADMIN ========== */
        $sql = "SELECT *
                FROM clinic_administrator
                WHERE username = :username AND password = :password";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);

        if ($stmt->rowCount() === 1) {

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // 🔐 SESSION
            session_regenerate_id(true);
            $_SESSION['adminID']   = $admin['admin_id'];
            $_SESSION['adminName'] = $admin['admin_name'];
            $_SESSION['username']  = $admin['username'];
            $_SESSION['role']      = 1; // ADMIN

            // 🔑 JWT (ADMIN ONLY)
            $payload = [
                "user_id" => $admin['admin_id'],
                "name"    => $admin['admin_name'],
                "email"   => $admin['username'],
                "role"    => 1,
                "iat"     => time(),
                "exp"     => time() + 3600
            ];

            $jwt_token = generateJWT($payload, $SECRET_KEY);

            // ✅ STORE JWT
            $_SESSION['jwt_token'] = $jwt_token;

            // ✅ REDIRECT TO YOUR DASHBOARD
            header("Location: http://10.48.74.39/Workshop%202/frontend/report.php?token=" . urlencode($jwt_token));
            exit();
        }

        /* ========== VETERINARIAN ========== */
        $sql = "SELECT *
                FROM veterinarian
                WHERE username = :username AND password = :password";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);

        if ($stmt->rowCount() === 1) {

            $vet = $stmt->fetch(PDO::FETCH_ASSOC);

            session_regenerate_id(true);
            $_SESSION['vetID']    = $vet['vet_id'];
            $_SESSION['vetname']  = $vet['vet_name'];
            $_SESSION['username'] = $vet['username'];
            $_SESSION['role']     = 2; // VET

            header("Location: ../frontend/vethome.php");
            exit();
        }

        /* ========== OWNER ========== */
        $sql = "SELECT *
                FROM owner
                WHERE username = :username AND password = :password";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);

        if ($stmt->rowCount() === 1) {

            $owner = $stmt->fetch(PDO::FETCH_ASSOC);

            session_regenerate_id(true);
            $_SESSION['ownerID']   = $owner['owner_id'];
            $_SESSION['ownerName'] = $owner['owner_name'];
            $_SESSION['username']  = $owner['username'];
            $_SESSION['role']      = 3; // OWNER

            header("Location: ../frontend/ownerhome.php");
            exit();
        }

        /* ========== INVALID LOGIN ========== */
        header("Location: ../frontend/userlogin.php?error=invalid");
        exit();

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }

} else {
    header("Location: ../frontend/userlogin.php");
    exit();
}
