<?php
require "auth.php";
include "../backend/connection.php";

$date = $_GET['date'] ?? null;
$time = $_GET['time'] ?? null;

if (!$date || !$time) {
    echo json_encode(["available" => false]);
    exit;
}

/* Get full day name: Monday, Tuesday, etc */
$day = date('l', strtotime($date));

/* ❌ Sunday closed */
if ($day === 'Sunday') {
    echo json_encode(["available" => false]);
    exit;
}

/* ⏰ Working hours */
if ($time < "09:00" || $time >= "17:00") {
    echo json_encode(["available" => false]);
    exit;
}

/* 🍽 Lunch break rules */
if (
    in_array($day, ['Monday','Tuesday','Wednesday','Thursday','Saturday']) &&
    $time >= "13:00" && $time < "14:00"
) {
    echo json_encode(["available" => false]);
    exit;
}

if (
    $day === 'Friday' &&
    $time >= "12:30" && $time < "14:30"
) {
    echo json_encode(["available" => false]);
    exit;
}

/* ✅ CHECK vet_availability (USE day_of_week) */
$stmt = $conn->prepare("
    SELECT vet_id
    FROM vet_availability
    WHERE day_of_week = :day
      AND :time BETWEEN start_time AND end_time
    LIMIT 1
");

$stmt->execute([
    ':day'  => $day,
    ':time' => $time
]);

$vet_id = $stmt->fetchColumn();

if ($vet_id) {
    echo json_encode([
        "available" => true,
        "vet_id" => $vet_id
    ]);
} else {
    echo json_encode([
        "available" => false
    ]);
}
