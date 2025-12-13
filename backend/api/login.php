<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require "../backend/connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$password) {
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit();
}

// Try login for each role
$roles = [
    ["table" => "clinic_administrator", "id" => "admin_id", "page" => "admin"],
    ["table" => "veterinarian", "id" => "vet_id", "page" => "vet"],
    ["table" => "owner", "id" => "owner_id", "page" => "owner"]
];

foreach ($roles as $role) {

    $sql = "SELECT {$role['id']}, username, password 
            FROM {$role['table']} 
            WHERE username = :username AND password = :password";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":username" => $username,
        ":password" => $password
    ]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "role" => $role['page'],
            "user" => $user
        ]);
        exit();
    }
}

echo json_encode(["success" => false, "message" => "Invalid username or password"]);
?>