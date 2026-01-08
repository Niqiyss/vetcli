<?php
header("Content-Type: application/json");

$VALID_API_KEY = "VETCLI_API_KEY_2025";

$headers = array_change_key_case(getallheaders(), CASE_UPPER);
$apiKey =
    $headers['X-API-KEY']
    ?? $headers['HTTP_X_API_KEY']
    ?? null;

if ($apiKey !== $VALID_API_KEY) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}
