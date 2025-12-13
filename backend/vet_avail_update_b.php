<?php
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

include "../backend/connection.php";

/* ===== GET POST DATA ===== */
$availability_id = $_POST['id'] ?? '';
$vet_id = $_POST['vet_id'] ?? '';
$day = $_POST['day_of_week'] ?? '';
$start = $_POST['start_time'] ?? '';
$end = $_POST['end_time'] ?? '';

/* ===== BASIC VALIDATION ===== */
if (!$availability_id || !$vet_id || !$day || !$start || !$end) {
    $_SESSION['error_popup'] = "All fields are required.";
    header("Location: ../frontend/vet_avail.php");
    exit();
}

if ($start >= $end) {
    $_SESSION['error_popup'] = "Start time must be earlier than end time.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}

/* 🚫 OVERLAP CHECK (EXCLUDE CURRENT ROW) */
$sql = "
SELECT COUNT(*) FROM vet_availability
WHERE vet_id = :vet
AND day_of_week = :day
AND availability_id <> :id
AND NOT (end_time <= :start OR start_time >= :end)
";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':vet' => $vet_id,
    ':day' => $day,
    ':start' => $start,
    ':end' => $end,
    ':id' => $availability_id
]);

if ($stmt->fetchColumn() > 0) {
    $_SESSION['error_popup'] = "Overlapping availability detected for this day.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}

/* ✅ UPDATE */
try {
    $sql = "
    UPDATE vet_availability
    SET day_of_week = :day,
        start_time = :start,
        end_time = :end
    WHERE availability_id = :id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':day' => $day,
        ':start' => $start,
        ':end' => $end,
        ':id' => $availability_id
    ]);

    $_SESSION['success_popup'] = "Availability updated successfully.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_popup'] = "Database error occurred.";
    header("Location: ../frontend/vet_avail.php?vet_id=$vet_id");
    exit();
}
