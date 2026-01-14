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
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet">

  <link href="../MediTrust/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../MediTrust/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../MediTrust/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../MediTrust/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../MediTrust/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="../MediTrust/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <link href="../MediTrust/assets/css/main.css" rel="stylesheet">

  <style>
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
        display: block;
      }


      .navmenu ul {
        display: none;
      }


      .mobile-nav-active {
        overflow: hidden;
      }

      .mobile-nav-active .mobile-nav-toggle {
        color: #fff;
        position: fixed;
        top: 20px;
        right: 20px;
      }


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
        color: #009d91;
      }
    }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="../frontend/home.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">VetClinic</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="../frontend/home.php" class="active">Home</a></li>
          <li><a href="../frontend/services.php">Our Services</a></li>
          <li><a href="../frontend/about.php">About Us</a></li>
        </ul>

        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="../frontend/userlogin.php">Sign up / Log in</a>
    </div>
  </header>
  <script src="../MediTrust/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../MediTrust/assets/vendor/aos/aos.js"></script>
  <script src="../MediTrust/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../MediTrust/assets/vendor/glightbox/js/glightbox.min.js"></script>

  <script src="../MediTrust/assets/js/main.js"></script>

  <script>
    AOS.init(); // initialize animations

    document.addEventListener('DOMContentLoaded', function () {

      const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

      function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active');
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
      }

      if (mobileNavToggleBtn) {
        mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
      }


      document.querySelectorAll('#navmenu a').forEach(navmenu => {
        navmenu.addEventListener('click', () => {
          if (document.querySelector('.mobile-nav-active')) {
            mobileNavToogle();
          }
        });
      });
    });
  </script>

</body>

</html>