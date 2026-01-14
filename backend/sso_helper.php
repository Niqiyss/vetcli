<?php
/* =========================
   SSO CONFIG
========================= */
define('SSO_SECRET', 'VETCLINIC_SSO_2026_SECRET');
define('SSO_EXPIRE', 300); // 5 minutes

/* =========================
   CREATE TOKEN
========================= */
function createSSOToken($id, $name, $type)
{
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
