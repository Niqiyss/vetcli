<?php
//home.php
include "../frontend/header.php";
require_once "../backend/connection.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-teal: #0e5c65;
        --accent-blue: #0095c4;   /* Syringe Blue */
        --light-blue-bg: #e1f5fe;
        --white: #ffffff;
        --text-muted: #6c757d;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: #444;
        background-color: var(--white);
    }

    /* =========================================
       HERO SECTION (YOUR ORIGINAL STYLES)
       ========================================= */
    .hero-content {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        z-index: 2;
    }

    .hero-content .container {
        max-width: 900px;
    }

    .hero-content .row {
        justify-content: center;
    }

    .hero-content .content-box {
        margin: 0 auto;
    }

    .hero-content .cta-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .hero-content h1 {
        font-weight: 700;
        color: #fff;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .hero-content p {
        color: #fff;
        font-size: 1.1rem;
        text-shadow: 0 1px 5px rgba(0,0,0,0.3);
    }

    /* =========================================
       SERVICES SECTION (NEW BLUE UI)
       ========================================= */
    .our-services {
        padding: 80px 0;
        background: #f9fbff;
    }

    .section-title h2 {
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 10px;
    }

    .our-service-box {
        background: #fff;
        border-radius: 18px;
        padding: 32px 24px;
        text-align: center;
        height: 100%;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        transition: 0.35s;
        border-bottom: 4px solid transparent;
    }

    .our-service-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 18px 40px rgba(0, 149, 196, 0.15); /* Blue Shadow */
        border-bottom: 4px solid var(--accent-blue);     /* Blue Border */
    }

    .service-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: var(--light-blue-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.35s;
        color: var(--accent-blue);
        font-size: 30px;
    }

    .our-service-box:hover .service-icon {
        background: var(--accent-blue);
        color: #fff;
    }

    .our-service-box h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--primary-teal);
    }

    .our-service-box p {
        font-size: 14px;
        color: #555;
        margin-bottom: 0;
    }

    /* Emergency Special Style */
    .our-service-box.emergency {
        border: 2px solid #dc3545;
    }
    .our-service-box.emergency:hover {
        border-bottom: 4px solid #dc3545;
        box-shadow: 0 18px 40px rgba(220, 53, 69, 0.15);
    }
    .emergency-icon {
        background: #ffecec !important;
        color: #dc3545 !important;
    }
    .our-service-box.emergency:hover .emergency-icon {
        background: #dc3545 !important;
        color: #fff !important;
    }

    /* =========================================
       ABOUT SECTION (NEW BLUE UI)
       ========================================= */
    .home-about {
        padding: 80px 0;
    }

    .about-content h2 {
        font-weight: 700;
        color: var(--primary-teal);
    }

    .about-content .lead {
        color: var(--accent-blue);
        font-weight: 500;
        border-left: 4px solid var(--accent-blue);
        padding-left: 15px;
    }

    .feature-item .icon {
        width: 45px;
        height: 45px;
        background: var(--light-blue-bg);
        color: var(--accent-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .feature-item h4 {
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 5px;
    }

    /* =========================================
       VETS SECTION (YOUR ORIGINAL STRUCTURE)
       ========================================= */
    .our-vets {
        padding: 60px 0;
        background: #f9fbff;
    }

    .our-vets .section-title h2 {
        font-weight: 700;
        color: var(--primary-teal);
    }

    .vet-carousel-wrapper {
        position: relative;
        overflow: hidden;
        margin-top: 30px;
        padding: 0 20px;
    }

    .vet-carousel {
        display: flex;
        gap: 24px;
        transition: transform 0.45s ease;
    }

    .vet-item {
        flex: 0 0 32%; /* Shows ~3 cards */
        min-width: 300px;
    }

    .vet-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .vet-card:hover {
        border-color: var(--accent-blue);
        transform: translateY(-5px);
    }

    .vet-img-wrapper {
        width: 100%;
        height: 260px;
        text-align: center;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .vet-img-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .vet-card-body {
        padding: 20px 16px 22px;
        text-align: center;
    }

    .vet-card-body h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 8px;
    }

    .vet-card-body span {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        background: var(--light-blue-bg);
        color: var(--accent-blue);
        font-size: 13px;
        font-weight: 600;
    }

    .vet-btn {
        position: absolute;
        top: 45%;
        background: var(--accent-blue);
        color: #fff;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        font-size: 22px;
        cursor: pointer;
        z-index: 10;
        transition: 0.3s;
    }
    
    .vet-btn:hover {
        background: var(--primary-teal);
    }

    .vet-btn.left { left: 0; }
    .vet-btn.right { right: 0; }

    /* Custom Buttons */
    .btn-blue {
        background-color: var(--accent-blue);
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-blue:hover {
        background-color: var(--primary-teal);
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-blue {
        border: 2px solid var(--accent-blue);
        color: var(--accent-blue);
        padding: 10px 30px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        background: transparent;
    }
    .btn-outline-blue:hover {
        background-color: var(--accent-blue);
        color: white;
    }
</style>

<main class="main">

    <section id="hero" class="hero section dark-background">
        <div class="container-fluid p-0 position-relative">
            <div class="hero-wrapper" style="position: relative; height: 600px; overflow: hidden;">
                <div class="hero-image">
                    <img src="../MediTrust/assets/img/health/main.jpg" alt="Advanced Healthcare" class="img-fluid" style="width: 100%; height: 600px; object-fit: cover;">
                </div>

                <div class="hero-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7 col-md-10" data-aos="fade-up" data-aos-delay="150">
                                <div class="content-box">
                                    <h1 data-aos="fade-up" data-aos-delay="150">Where every paw gets attention</h1>
                                    <p data-aos="fade-up" data-aos-delay="200">
                                        From routine checkups to special care, we’re dedicated to every paw that walks in
                                    </p>
                                    <div class="cta-group" data-aos="fade-up" data-aos-delay="200">
                                        <a href="../frontend/userlogin.php" class="btn btn-primary btn-lg rounded-pill px-5">Book Appointment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-services">
        <div class="container section-title">
            <h2>Our Services</h2>
            <p>Quality veterinary services designed to keep your pets healthy and happy</p>
        </div>

        <div class="container">
            <div class="row gy-4 justify-content-center">

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                        <h4>Health Check</h4>
                        <p>Routine examinations to keep pets healthy</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Vaccination</h4>
                        <p>Essential vaccines for disease prevention</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-scissors"></i>
                        </div>
                        <h4>Surgery</h4>
                        <p>Safe surgical procedures by professionals</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box emergency">
                        <div class="service-icon emergency-icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h4 class="text-danger">Emergency Care</h4>
                        <p>Immediate care for urgent situations</p>
                    </div>
                </div>

            </div>

            <div class="text-center mt-5">
                <a href="../frontend/services.php" class="btn-blue">Explore Services</a>
            </div>
        </div>
    </section>

    <section id="home-about" class="home-about section">
        <div class="container" data-aos="fade-up" data-aos-delay="50">
            <div class="row gy-5 align-items-center">

                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="50">
                    <div class="about-image">
                        <img src="../MediTrust/assets/img/health/aboutus.jpeg" alt="Vet Clinic Facility"
                            class="img-fluid rounded-4 shadow mb-4">
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="about-content">
                        <h2>Expert Care, Happy Pets</h2>
                        <p class="lead">We provide compassionate care with trusted veterinarians and modern facilities</p>
                        <p class="text-muted">From regular check-ups to emergency treatment, our clinic ensures your pets receive the best
                            possible medical attention</p>

                        <div class="row g-4 mt-4">
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-heart-fill"></i></div>
                                    <div>
                                        <h4>Gentle & Caring</h4>
                                        <p>Our team prioritizes comfort</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-award-fill"></i></div>
                                    <div>
                                        <h4>Professional Vets</h4>
                                        <p>Experienced & dedicated</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cta-wrapper mt-4 d-flex gap-3">
                            <a href="../frontend/about.php" class="btn-blue">About Us</a>
                            <a href="#our-vets" class="btn-outline-blue">Meet Our Vets</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="our-vets" class="our-vets">
        <div class="container section-title text-center">
            <h2>Meet Our Veterinarians</h2>
            <p>Our Experienced Vets</p>
        </div>

        <div class="container">
            <div class="vet-carousel-wrapper">

                <button class="vet-btn left" onclick="slideVet(-1)">&#10094;</button>

                <div class="vet-carousel" id="vetCarousel">
                    <?php
                    $stmt = $conn->query("
                    SELECT vet_name, specialization, vet_image
                    FROM veterinarian
                    ORDER BY vet_name ASC
                ");
                    $vets = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($vets as $vet):
                        $image = !empty($vet['vet_image'])
                            ? "../uploads/vets/" . htmlspecialchars($vet['vet_image'])
                            : "../uploads/vets/default.png";
                        ?>
                        <div class="vet-item">
                            <div class="vet-card">
                                <div class="vet-img-wrapper">
                                    <img src="<?= $image ?>" alt="Veterinarian">
                                </div>
                                <div class="vet-card-body">
                                    <h4><?= htmlspecialchars($vet['vet_name']); ?></h4>
                                    <span><?= htmlspecialchars($vet['specialization']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="vet-btn right" onclick="slideVet(1)">&#10095;</button>

            </div>
        </div>
    </section>

</main>

<script>
    let vetIndex = 0;

    function slideVet(direction) {
        const carousel = document.getElementById("vetCarousel");
        const items = carousel.children;
        const totalCards = items.length;
        
        // Adjust for responsive card counting
        let visibleCards = 3;
        if (window.innerWidth <= 992) visibleCards = 2;
        if (window.innerWidth <= 768) visibleCards = 1;

        const maxIndex = Math.max(0, totalCards - visibleCards);

        vetIndex += direction;

        if (vetIndex < 0) vetIndex = 0;
        if (vetIndex > maxIndex) vetIndex = maxIndex;

        // Calculate offset percentage based on visible cards
        const offset = vetIndex * (100 / visibleCards);
        carousel.style.transform = `translateX(-${offset}%)`;
    }
    
    // Recalculate on resize
    window.addEventListener('resize', () => slideVet(0));
</script>

<?php
include "../frontend/footer.php";
?>