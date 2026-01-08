<?php
//vet_avail_b.php

session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

include "../backend/connection.php";

$vet_id = $_POST['vet_id'] ?? '';
$day = $_POST['day_of_week'] ?? '';
$start = $_POST['start_time'] ?? '';
$end = $_POST['end_time'] ?? '';

// validation
if (!$vet_id || !$day || !$start || !$end) {
    $_SESSION['error_popup'] = "All fields are required.";
    header("Location: ../frontend/vet_avail.php");
    exit();
}

// time range validate
$minTime = "09:00";
$maxTime = "18:00";

if ($start < $minTime || $end > $maxTime) {
    $_SESSION['error_popup'] = "Availability must be between 9:00 AM and 6:00 PM only.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}

if ($start >= $end) {
    $_SESSION['error_popup'] = "Start time must be earlier than end time.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}

// overlap
$sql = "
SELECT COUNT(*) FROM vet_availability
WHERE vet_id = :vet
AND day_of_week = :day
AND NOT (end_time <= :start OR start_time >= :end)
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':vet' => $vet_id,
    ':day' => $day,
    ':start' => $start,
    ':end' => $end
]);

if ($stmt->fetchColumn() > 0) {
    $_SESSION['error_popup'] = "This vet already scheduled to work on this day";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}

//insert
try {
    $sql = "
    INSERT INTO vet_availability (vet_id, day_of_week, start_time, end_time)
    VALUES (:vet, :day, :start, :end)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':vet' => $vet_id,
        ':day' => $day,
        ':start' => $start,
        ':end' => $end
    ]);

    $_SESSION['success_popup'] = "Availability added successfully";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_popup'] = "Database error occurred.";
    header("Location: ../frontend/vet_avail.php");
    exit();
}
