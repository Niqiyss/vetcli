<?php
// ../backend/myschedule_b.php
session_start();
require_once "../backend/connection.php";

if (!isset($_SESSION['vetID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

$vet_id = $_SESSION['vetID'];
$stmt_name = $conn->prepare("SELECT vet_name FROM veterinarian WHERE vet_id = :vid");
$stmt_name->execute([':vid' => $vet_id]);
$vet_data = $stmt_name->fetch(PDO::FETCH_ASSOC);
$vet_name = $vet_data['vet_name'] ?? 'Veterinarian';

// 2. FETCH SCHEDULE
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$schedule_map = [];

// Initialize empty array for all days
foreach ($days_of_week as $day) {
    $schedule_map[$day] = [];
}

try {
    $stmt = $conn->prepare("
        SELECT day_of_week, start_time, end_time 
        FROM vet_availability 
        WHERE vet_id = :vid 
        ORDER BY start_time
    ");
    $stmt->execute([':vid' => $vet_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $d = $row['day_of_week'];
        $time_str = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
        $schedule_map[$d][] = $time_str;
    }
} catch (PDOException $e) {
    // error
    $schedule_map = [];
}

// 3. CURRENT DAY HELPER
$current_day = date('l'); 
?>