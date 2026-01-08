<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$SECRET_KEY = "vetclinic";

/* ================= JWT VERIFY ================= */
function verifyJWT(string $jwt, string $secret)
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    [$h, $p, $s] = $parts;

    $expected = rtrim(
        strtr(
            base64_encode(
                hash_hmac('sha256', $h . "." . $p, $secret, true)
            ),
            '+/',
            '-_'
        ),
        '='
    );

    if (!hash_equals($expected, $s)) return false;

    $data = json_decode(base64_decode(strtr($p, '-_', '+/')), true);
    if (!$data || ($data['exp'] ?? 0) < time()) return false;

    return $data;
}

/* ================= AUTH ================= */
if (isset($_SESSION['adminID'])) {
    // OK
}
elseif (isset($_GET['token'])) {

    $data = verifyJWT($_GET['token'], $SECRET_KEY);

    if (!$data || (int)$data['role'] !== 1) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['adminID']   = $data['user_id'];
    $_SESSION['adminName'] = $data['name'];
}
else {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

/* ================= DATABASE ================= */
require "../backend/connection.php";

/* ⚠️ NEVER expose password */
$stmt = $conn->query("
    SELECT
        admin_id,
        admin_name,
        phone_num,
        username
    FROM admin
    ORDER BY admin_name
");

$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($admins);


