<?php
$data = json_encode([
    "username" => "vetusername",   // guna username sebenar
    "password" => "vetpassword"    // guna password sebenar
]);

$ch = curl_init("http://10.48.74.199:81/vetcli/api/login_vet.php");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-API-KEY: VETCLI_API_KEY_2025",
        "X-PROVIDER: vetclinic"
    ],
    CURLOPT_POSTFIELDS => $data
]);

$response = curl_exec($ch);

if ($response === false) {
    echo "Curl Error: " . curl_error($ch);
} else {
    echo "<pre>";
    print_r(json_decode($response, true));
    echo "</pre>";
}

curl_close($ch);
