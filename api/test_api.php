<?php
$url = "http://10.48.74.199:81/vetcli/api/getPetbyOwner.php?owner_id=OW001";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-API-KEY: VETCLI_API_KEY_2025"
    ]
]);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";
