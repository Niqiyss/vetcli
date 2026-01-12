<?php
//vetheader.php
session_start();

if (!isset($_SESSION['vetID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

require_once "../backend/connection.php";

/* =========================
   SSO CONFIG
========================= */
define('SSO_SECRET', 'VETCLINIC_SSO_2026_SECRET');
define('SSO_EXPIRE', 300);

/* =========================
   TOKEN HELPERS
========================= */
function createSSOToken($id, $name, $type) {
    $payload = [
        'id' => $id,
        'name' => $name,
        'type' => $type,
        'exp' => time() + SSO_EXPIRE
    ];
    $payload_b64 = base64_encode(json_encode($payload));
    $signature = hash_hmac('sha256', $payload_b64, SSO_SECRET);
    return $payload_b64 . '.' . $signature;
}

function decodeSSOToken($token) {
    if (!$token || !str_contains($token, '.')) return false;
    [$payload_b64, $signature] = explode('.', $token, 2);
    $expected = hash_hmac('sha256', $payload_b64, SSO_SECRET);
    if (!hash_equals($expected, $signature)) return false;
    return json_decode(base64_decode($payload_b64), true);
}

/* =========================
   AUTO-REFRESH TOKEN
========================= */
if (isset($_SESSION['sso_token'])) {
    $payload = decodeSSOToken($_SESSION['sso_token']);
    if ($payload && ($payload['exp'] - time()) < 60) {
        $_SESSION['sso_token'] = createSSOToken(
            $payload['id'],
            $payload['name'],
            $payload['type']
        );
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>VetClinic</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="../MediTrust/assets/img/favicon.jpeg" rel="icon">
    <link href="../MediTrust/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../MediTrust/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="../MediTrust/assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <!-- ======= Header ======= -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="../frontend/vethome.php" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">VetClinic</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="../frontend/vethome.php" class="active">Home</a></li>

                    <li><a href="http://10.48.74.39/Workshop 2/frontend/report_vet.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Dashboard</a></li>
                

                    <li><a href="../frontend/myschedule.php">MySchedule</a></li>
                    <li class="dropdown"><a href="#"><span>Patient Records</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="http://10.48.74.61/Vet_clinic/frontend/vet_app_list.php?token=<?= urlencode($_SESSION['sso_token']) ?>">List Appointment</a></li>
                            <li><a href="http://10.48.74.61/Vet_clinic/frontend/vet_app_history.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Appointment History</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Treatment</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="http://10.48.74.38/vet_cli/frontend/treatment_list.php?token=<?= urlencode($_SESSION['sso_token']) ?>">List Treatment</a></li>
                        </ul>
                    </li>
                    <li><a href="../frontend/vetprofile.php">MyProfile</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <a class="btn-getstarted" href="../backend/logout.php">Log out</a>
        </div>
    </header>
    <!-- End Header -->

    <!-- ======= Vendor JS Files ======= -->
    <script src="../MediTrust/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../MediTrust/assets/vendor/aos/aos.js"></script>
    <script src="../MediTrust/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../MediTrust/assets/vendor/glightbox/js/glightbox.min.js"></script>

    <!-- ======= Main JS File ======= -->
    <script src="../MediTrust/assets/js/main.js"></script>
    <script>
        AOS.init(); // initialize animations
    </script>

