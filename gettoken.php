<?php
session_start();
include "connect.php";

define('SSO_SECRET', 'FoodBank_SSO_2025_SECRET');

/* ================= AUTH ================= */
if (isset($_SESSION['donor_ID']) && $_SESSION['role'] === 'donor') {
    $donor_ID = $_SESSION['donor_ID'];
} elseif (isset($_GET['token'])) {

    $token = $_GET['token'];
    $parts = explode('.', $token);

    if (count($parts) !== 2) {
        header("Location: login.php"); exit;
    }

    [$payload_b64, $signature] = $parts;

    $expected_sig = hash_hmac('sha256', $payload_b64, SSO_SECRET);
    if (!hash_equals($expected_sig, $signature)) {
        header("Location: login.php"); exit;
    }

    $payload = json_decode(base64_decode($payload_b64), true);

    if (!$payload || $payload['exp'] < time()) {
        header("Location: login.php"); exit;
    }

    $_SESSION['donor_ID'] = $payload['donor_ID'];
    $_SESSION['email'] = $payload['email'] ?? null;
    $_SESSION['role'] = 'donor';

    header("Location: donor_interface.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
/* ================= SSO TOKEN ================= */
$payload = json_encode([
    'donor_ID' => $donor_ID,
    'exp' => time() + 1800
]);
$payload_b64 = base64_encode($payload);
$signature = hash_hmac('sha256', $payload_b64, SSO_SECRET);
$token = $payload_b64 . '.' . $signature;
$_SESSION['sso_token'] = $token;

/* ================= ORGANIZATION INFO ================= */
$orgName = "Donor";
$businessType = "";

$stmt = $conn->prepare("
    SELECT organization_name, business_type 
    FROM donors 
    WHERE donor_ID = ?
");
$stmt->bind_param("s", $donor_ID);
$stmt->execute();
$stmt->bind_result($organization_name, $bt);

if ($stmt->fetch()) {
    if (!empty($organization_name)) {
        $orgName = $organization_name;
    }
    if (!empty($bt)) {
        $businessType = $bt;
    }
}
$stmt->close();

/* ================= ADDRESS ================= */
$address = 'Welcome back';
$apiUrl = "http://10.168.125.59/food_donation/api_get_donor.php?donor_ID=" . urlencode($donor_ID);
$response = @file_get_contents($apiUrl);
if ($response !== false) {
    $json = json_decode($response, true);
    if (!empty($json['address'])) {
        $address = $json['address'];
    }
}
/* ================= OVERRIDE FROM LOCAL DB ================= */
$stmt = $conn->prepare("
    SELECT address, postcode , state
    FROM donor_contact
    WHERE donor_ID = ?
");
$stmt->bind_param("s", $donor_ID);
$stmt->execute();
$stmt->bind_result($addr, $postcode, $state);
if ($stmt->fetch() && !empty($addr)) {
    $address = $addr 
             . ($postcode ? ", $postcode" : "") 
             . ($state ? ", $state" : "");
}
$stmt->close();

/* ================= DONATION COUNT ================= */
$totalDonation = 0;
$stmt = $conn->prepare("SELECT COUNT(*) FROM donation WHERE donor_ID = ?");
$stmt->bind_param("s", $donor_ID);
$stmt->execute();
$stmt->bind_result($totalDonation);
$stmt->fetch();
$stmt->close();

$currentPage = basename($_SERVER['PHP_SELF']);

/* ================= GREETING ================= */
date_default_timezone_set("Asia/Kuala_Lumpur"); // penting untuk Malaysia

$hour = date("H");

if ($hour >= 5 && $hour < 12) {
    $greeting = "Good morning";
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good afternoon";
} else {
    $greeting = "Good evening";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Donor Dashboard</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h2>Food<br>Distribution<br>System</h2>
        </div>
    </div>

    <nav class="sidebar-menu">

        <a href="donor_interface.php"
           class="sidebar-link <?= $currentPage=='donor_interface.php'?'active':'' ?>">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>

        <a href="http://10.168.125.241/foodbank/post_food.php?token=<?= urlencode($token) ?>"
           class="sidebar-link">
            <i class="fa-solid fa-hand-holding-heart"></i>
            <span>Donate Food</span>
        </a>

        <!-- ===== MY DONATIONS ===== -->
        <div class="sidebar-group <?= in_array($currentPage,['task_history.php','impact_metrics.php'])?'open':'' ?>">

            <div class="sidebar-link parent"
                 onclick="toggleDonations(this)">
                <i class="fa-solid fa-box"></i>
                <span>My Donations</span>
                <i class="fa-solid fa-chevron-down toggle-icon"></i>
            </div>

            <div class="sidebar-submenu">
                <a href="/foodbank/task_history.php"
                   class="<?= $currentPage=='task_history.php'?'active':'' ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Task History
                </a>

                <a href="/foodbank/impact_metrics.php"
                   class="<?= $currentPage=='impact_metrics.php'?'active':'' ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    Impact Metrics
                </a>
            </div>
        </div>

        <!-- ✅ FIXED ACTIVE RECIPIENT -->
        <a href="http://10.168.125.241/foodbank/active_recipient.php?token=<?= urlencode($token) ?>"
           class="sidebar-link">
            <i class="fa-solid fa-users"></i>
            <span>Active Recipient</span>
        </a>

        <a href="profile_donor.php" class="sidebar-link">
            <i class="fa-solid fa-user"></i>
            <span>My Profile</span>
        </a>

    </nav>
</div>

<!-- ================= TOP BAR ================= -->
<div class="donor-topbar">
    <div class="donor-topbar-actions">

        <div class="donor-top-icon" onclick="location.href='profile_donor.php'">
            <i class="fa-solid fa-user"></i>
        </div>

        <a href="logout.php" class="donor-top-icon logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>

    </div>
</div>

<!-- ================= MAIN ================= -->
<div class="main-content">

    <!-- 🌿 HERO CARD -->
<div class="card hero soft-hero">
   <div class="hero-content">
    <h2><?= $greeting ?>, <?= htmlspecialchars($orgName) ?> ! </h2>

    <div class="hero-meta">
    <span class="hero-location">📍 <?= htmlspecialchars($address) ?></span>
    <span class="hero-type">🏢 <?= htmlspecialchars($businessType) ?></span>
</div>
</div>

    <div class="hero-bubble">
    <i class="fa-solid fa-hand-holding-heart"></i>
</div>

</div>
    <!-- 🌱 HIGHLIGHT -->
    <div class="card highlight-card center-text soft-highlight">
        <h3>Make a difference today</h3>
        <p>Your food donations help families and communities in need.</p>
        <div class="emoji-row">🥕 🍞 🥦</div>
    </div>

    <!-- 📊 DASH GRID -->
    <div class="dashboard-grid">

<div class="card stat-card">
    <div class="stat-icon">
    <i class="fa-solid fa-utensils"></i>
</div>
    <div class="stat-info">
        <h1><?= $totalDonation ?></h1>
        <p>Total Donations Made</p>
    </div>
</div>

        <!-- QUICK ACTION -->
        <div class="card soft-action">
            <h3>Quick Actions</h3>
            <p>Start helping in just one click.</p>

            <div class="center-actions" style="margin-top:18px;">
                <a class="btn btn-primary"
                   href="http://10.168.125.241/foodbank/post_food.php?token=<?= urlencode($token) ?>">
                   Quick Donate
                </a>

                <a class="btn btn-secondary"
                   href="http://10.168.125.241/foodbank/active_recipient.php?token=<?= urlencode($token) ?>">
                   Request Recipient
                </a>
            </div>
        </div>

    </div>

    <div class="footer">
        © 2025 Food Distribution System
    </div>
</div>

<script>
function toggleDonations(el) {
    el.closest('.sidebar-group').classList.toggle('open');
}
</script>
<!-- 🔢 COUNT UP ANIMATION -->
<script>
const target = <?= $totalDonation ?>;
let count = 0;
const el = document.getElementById("donationCount");

const counter = setInterval(() => {
    if(count >= target){
        el.textContent = target;
        clearInterval(counter);
    } else {
        count++;
        el.textContent = count;
    }
}, 40);
</script>
</body>
</html>