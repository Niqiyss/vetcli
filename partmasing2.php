<?php
session_start();
include "connect.php";   // MySQL
include "config.php";    // SQL Server (PDO → $pdo)

define('SSO_SECRET', 'FoodBank_SSO_2025_SECRET');

/* ================= TOKEN → SESSION BRIDGE ================= */

if (isset($_GET['token'])) {

    $token = $_GET['token'];
    $parts = explode('.', $token);

    if (count($parts) === 2) {

        [$payload_b64, $signature] = $parts;
        $expected = hash_hmac('sha256', $payload_b64, SSO_SECRET);

        if (hash_equals($expected, $signature)) {

            $payload = json_decode(base64_decode($payload_b64), true);

            if (!empty($payload['email'])) {
            $_SESSION['email'] = $payload['email'];
}

            if ($payload && ($payload['exp'] ?? 0) > time()) {

                $_SESSION['donor_ID']  = $payload['donor_ID'];
                $_SESSION['email']     = $payload['email'] ?? null;   // 🔥 INI PENTING
                $_SESSION['role']      = 'donor';
                $_SESSION['sso_token'] = $token;
            }
        }
    }
}

/* ================= AUTH ================= */

if (!isset($_SESSION['donor_ID']) || $_SESSION['role'] !== 'donor') {
    header("Location: login.php");
    exit;
}

$donorID = $_SESSION['donor_ID'];