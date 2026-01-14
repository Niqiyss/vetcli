<?php
session_start();

if (!isset($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

require_once "../backend/connection.php";
require_once "../backend/selectmysql.php";

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


$unpaidPayment = getLatestUnpaidPaymentByOwner($_SESSION['ownerID']);
$hasPendingPayment = $unpaidPayment ? true : false;

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Desktop Dropdown Styling */
    .navmenu .dropdown ul li a {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 6px !important;
    }

    .navmenu .dropdown ul li a i.fa-paw {
        width: 16px;
        min-width: 16px;
        text-align: center;
        color: #000 !important;
    }

    /* =========================================
       MOBILE MENU STYLING (Progressive)
    ========================================= */
    @media (max-width: 1199px) {
        
        /* Mobile Nav Icon */
        .mobile-nav-toggle {
            color: #444;
            font-size: 28px;
            cursor: pointer;
            line-height: 0;
            transition: 0.5s;
            z-index: 9999;
            margin-right: 10px;
        }

        /* Hide Desktop Menu by default on Mobile */
        .navmenu ul {
            display: none; 
        }

        /* Mobile Nav Overlay */
        .mobile-nav-active {
            overflow: hidden;
        }

        .mobile-nav-active .mobile-nav-toggle {
            color: #fff;
            position: fixed;
            top: 20px;
            right: 20px;
        }

        /* The Mobile Menu Container */
        .mobile-nav-active .navmenu {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 9998;
            background-color: rgba(0, 0, 0, 0.6);
            overflow-y: auto;
            transition: 0.3s;
        }

        .mobile-nav-active .navmenu ul {
            display: block;
            position: absolute;
            top: 55px;
            right: 15px;
            bottom: 15px;
            left: 15px;
            padding: 10px 0;
            background-color: #fff;
            border-radius: 10px;
            overflow-y: auto;
            transition: 0.3s;
            box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
        }

        /* List Items */
        .mobile-nav-active .navmenu a,
        .mobile-nav-active .navmenu a:focus {
            padding: 10px 20px;
            font-size: 15px;
            color: #333;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            white-space: nowrap;
            transition: 0.3s;
        }

        .mobile-nav-active .navmenu a:hover, 
        .mobile-nav-active .navmenu .active, 
        .mobile-nav-active .navmenu .active:focus {
            color: #009d91; /* Theme Color */
        }

        /* Dropdowns in Mobile */
        .mobile-nav-active .navmenu .dropdown ul {
            position: static;
            display: none;
            z-index: 99;
            padding: 10px 20px;
            margin: 10px 20px;
            background: #f6f9ff;
            border-radius: 6px;
            box-shadow: none;
        }

        .mobile-nav-active .navmenu .dropdown > .dropdown-active {
            display: block !important;
        }

        /* Dropdown Icon Styling */
        .mobile-nav-active .navmenu .dropdown > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .mobile-nav-active .navmenu .dropdown i.toggle-dropdown {
            font-size: 12px;
            line-height: 0;
            margin-left: 5px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: 0.3s;
            background-color: rgba(255, 255, 255, 0.9);
        }
    }
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>VetClinic</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="../MediTrust/assets/img/favicon.jpeg" rel="icon">
    <link href="../MediTrust/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    <link href="../MediTrust/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../MediTrust/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <link href="../MediTrust/assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="../frontend/ownerhome.php" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">VetClinic</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="../frontend/ownerhome.php" class="active">Home</a></li>

                    <li><a href="http://10.48.74.39/Workshop 2/frontend/report_owner.php?token=<?= urlencode($_SESSION['sso_token']) ?>">Dashboard</a></li>
                
                    <li><a href="../frontend/ownerservices.php">Our Services</a></li>
                    <li><a href="../frontend/ownerabout.php">About Us</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle-link">
                            <span>Appointment</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="http://10.48.74.61/vet_clinic/frontend/new_appointment.php?token=<?= urlencode($_SESSION['sso_token']) ?>">
                                    <i class="fas fa-paw"></i>
                                    <span>Book Appointment</span>
                                </a>
                            </li>
                            <li>
                                <a href="http://10.48.74.61/vet_clinic/frontend/appointment_list.php?token=<?= urlencode($_SESSION['sso_token']) ?>">
                                    <i class="fas fa-paw"></i>
                                    <span>Upcoming Appointment</span>
                                </a>
                            </li>
                            <li>
                                <a href="http://10.48.74.61/vet_clinic/frontend/appointment_history.php?token=<?= urlencode($_SESSION['sso_token']) ?>">
                                    <i class="fas fa-paw"></i>
                                    <span>Appointment History</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle-link">
                            <span>MyPet</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="../frontend/newpet.php">
                                    <i class="fas fa-paw"></i>
                                    <span>New Pet</span>
                                </a>
                            </li>
                            <li>
                                <a href="../frontend/ownerpetlist.php">
                                    <i class="fas fa-paw"></i>
                                    <span>View Pet</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li><a href="../frontend/medical_hist.php">Medical History</a></li>

                    <li><a href="http://10.48.74.197/vetclinic/frontend/paymentstatusowner.php?token=<?= urlencode($_SESSION['sso_token']) ?>">MyPayment</a></li>

                    <li><a href="../frontend/ownerprofile.php">MyProfile</a></li>
                </ul>
                
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <a class="btn-getstarted" href="../backend/logout.php">Log out</a>
        </div>
    </header>
    <script src="../MediTrust/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../MediTrust/assets/vendor/aos/aos.js"></script>
    <script src="../MediTrust/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../MediTrust/assets/vendor/glightbox/js/glightbox.min.js"></script>

    <script src="../MediTrust/assets/js/main.js"></script>
    
    <script>
        AOS.init(); 

        document.addEventListener('DOMContentLoaded', function() {
            /**
             * Mobile Nav Toggle Logic
             */
            const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

            function mobileNavToogle() {
                document.querySelector('body').classList.toggle('mobile-nav-active');
                mobileNavToggleBtn.classList.toggle('bi-list');
                mobileNavToggleBtn.classList.toggle('bi-x');
            }

            if (mobileNavToggleBtn) {
                mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
            }

            /**
             * Hide mobile nav on link click
             */
            document.querySelectorAll('#navmenu a').forEach(navmenu => {
                navmenu.addEventListener('click', () => {
                    if (document.querySelector('.mobile-nav-active')) {
                        mobileNavToogle();
                    }
                });
            });

            /**
             * Toggle Mobile Dropdowns
             */
            document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
                navmenu.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.parentNode.classList.toggle('active');
                    this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
                    e.stopImmediatePropagation();
                });
            });
        });
    </script>





<?php if ($hasPendingPayment): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'warning',
        title: 'Pending Payment',
        text: 'You have a pending payment. Please complete it to continue using services.',
        confirmButtonText: 'Pay Now',
        confirmButtonColor: '#009d91',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                "http://10.48.74.197/vetclinic/frontend/paymentstatusowner.php?token=<?= urlencode($_SESSION['sso_token']) ?>";
        }
    });
});
</script>
<?php endif; ?>


</body>
</html>