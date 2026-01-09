<?php
/* =========================
   SESSION & AUTH
========================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../backend/connection.php";

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$ownerID = $_SESSION['ownerID'];

/* =========================
   LOAD ENV (.env)
========================= */
$env = parse_ini_file(__DIR__ . '/../.env');
$HF_API_KEY = $env['HF_API_KEY'] ?? null;

if (!$HF_API_KEY) {
    die("HuggingFace API key missing");
}

/* =========================
   AI FUNCTION – COLOR ONLY
========================= */
function detectPetColorWithAI(string $imagePath, string $apiKey): string
{
    if (!file_exists($imagePath)) {
        error_log("IMAGE NOT FOUND: $imagePath");
        return 'Unknown';
    }

    $url = "https://router.huggingface.co/hf-inference/models/Salesforce/blip-vqa-base";

    $postFields = [
        'question' => 'What is the color of the animal?',
        'file'     => new CURLFile($imagePath)
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POSTFIELDS     => $postFields,
        CURLOPT_TIMEOUT        => 60
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        error_log("CURL ERROR: " . curl_error($ch));
        curl_close($ch);
        return 'Unknown';
    }

    curl_close($ch);

    $json = json_decode($response, true);
    error_log("AI COLOR RESPONSE: " . print_r($json, true));

    return ucfirst(trim($json[0]['answer'] ?? 'Unknown'));
}



/* =========================
   STEP 1: PETS NEEDING AI
   (POSTGRES SAFE)
========================= */
$stmt = $conn->prepare("
    SELECT pet_id, pet_image
    FROM pet
    WHERE owner_id = :owner_id
      AND (
            color IS NULL
         OR color = ''
         OR color ILIKE 'Unknown'
      )
");
$stmt->execute([':owner_id' => $ownerID]);
$petsNeedAI = $stmt->fetchAll(PDO::FETCH_ASSOC);

error_log("PETS NEED AI COUNT: " . count($petsNeedAI));

/* =========================
   STEP 2: RUN AI
========================= */
foreach ($petsNeedAI as $pet) {

    if (empty($pet['pet_image'])) {
        error_log("SKIP PET {$pet['pet_id']} (NO IMAGE)");
        continue;
    }

    $imagePath = __DIR__ . "/../uploads/pets/" . $pet['pet_image'];
    $color = detectPetColorWithAI($imagePath, $HF_API_KEY);

    if ($color !== 'Unknown') {
        $update = $conn->prepare("
            UPDATE pet
            SET color = :color
            WHERE pet_id = :pet_id
        ");
        $update->execute([
            ':color'  => $color,
            ':pet_id' => $pet['pet_id']
        ]);
    }
}

/* =========================
   STEP 3: FETCH FINAL PETS
========================= */
$stmt = $conn->prepare("
    SELECT *
    FROM pet
    WHERE owner_id = :owner_id
    ORDER BY pet_name
");
$stmt->execute([':owner_id' => $ownerID]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
