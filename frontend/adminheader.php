<?php
//adminheader.php

session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

require_once "../backend/connection.php";

//ssoverify
define('SSO_SECRET', 'VETCLINIC_SSO_2026_SECRET');
define('SSO_EXPIRE', 300);

//token
function createSSOToken($id, $name, $type)
{
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

function decodeSSOToken($token)
{
    if (!$token || !str_contains($token, '.'))
        return false;
    [$payload_b64, $signature] = explode('.', $token, 2);
    $expected = hash_hmac('sha256', $payload_b64, SSO_SECRET);
    if (!hash_equals($expected, $signature))
        return false;
    return json_decode(base64_decode($payload_b64), true);
}

//autorefresh
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
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Lato:wght@100;300;400;700;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap"
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

    <!--Header and Navmenu-->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="../frontend/adminhome.php" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">VetClinic</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>

                    <li><a href="../frontend/adminhome.php" class="active">Home</a></li>

                    <li><a
                            href="http://10.48.74.39/Workshop 2/frontend/report.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Dashboard</a>
                    </li>

                    <li class="dropdown"><a href="#"><span>Security</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="../frontend/admin_security.php">Unlocked Account</a></li>
                        </ul>
                    </li>

                    <li class="dropdown"><a href="#"><span>Veterinarian</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="../frontend/vetregister.php">Register Vet</a></li>
                            <li><a href="../frontend/vet_avail.php">Add Availability Vet</a></li>
                            <li><a href="../frontend/vetlist.php">List Vet</a></li>
                        </ul>
                    </li>

                    <li class="dropdown"><a href="#"><span>Medicine</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a
                                    href="http://10.48.74.38/vet_cli/frontend/medicinedetails.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Manage
                                    Medicine</a></li>
                            <li><a
                                    href="http://10.48.74.38/vet_cli/frontend/admin_medicine_list.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Stock
                                    Medicine</a></li>
                        </ul>
                    </li>


                    <li><a
                            href="http://10.48.74.61/Vet_clinic/frontend/services.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Services</a>
                    </li>

                    <li class="dropdown"><a href="#"><span>Payments</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="http://10.48.74.197/vetclinic/frontend/paymenthistory.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Payment History</a></li>
                            <li><a href="http://10.48.74.197/vetclinic/frontend/paymentaudit.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Payment Audit</a></li>
                        </ul>
                    </li>


                    <li><a href="../frontend/adminprofile.php">MyProfile</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="../backend/logout.php">Log out</a>
        </div>
    </header>