<!-- home.php -->

<?php
include "../frontend/header.php";
?>


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


<style>
    body {
        font-family: 'Poppins', sans-serif !important;
    }

    /* SECTION TITLE */
    .section-title {
        font-size: 42px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
        color: #1A2A5A;
    }

    .section-title span {
        color: #F6B100;
    }

    /* VET SECTION */
    .vet-section {
        padding: 60px 0;
        background: #fff;
    }

    .vet-carousel-wrapper {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
    }

    .vet-carousel {
        display: flex;
        gap: 30px;
        overflow: hidden;
        transition: transform 0.4s ease-in-out;
    }

    /* CARD DESIGN LIKE KIMVETS */
    .vet-card {
        min-width: 31%;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        opacity: 0;
        transform: translateY(30px);
        transition: 0.6s ease-out;
    }

    .vet-card.visible {
        opacity: 1;
        transform: translateY(0px);
    }

    .vet-img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 15px 15px 0 0;
    }

    .experience-tag {
        background: #1A2A5A;
        color: #fff;
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .vet-card h4 {
        font-size: 20px;
        font-weight: 700;
        margin-top: 18px;
        text-align: center;
        color: #1A2A5A;
    }

    .role {
        font-size: 15px;
        color: #0057B7;
        text-align: center;
        font-weight: 600;
    }

    .vet-list {
        list-style: none;
        padding: 0 25px 25px 25px;
        margin-top: 10px;
    }

    .vet-list li {
        margin-bottom: 6px;
    }

    .vet-list li::before {
        content: "✔ ";
        color: #F6B100;
        font-weight: 700;
    }

    /* BUTTONS */
    .carousel-btn {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        background: #F6B100;
        color: white;
        width: 48px;
        height: 48px;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        z-index: 20;
    }

    .carousel-btn.left {
        left: -20px;
    }

    .carousel-btn.right {
        right: -20px;
    }

    /* OPTIONAL SMOOTH HOVER */
    .vet-card:hover {
        transform: translateY(-6px) !important;
        transition: 0.3s;
    }
</style>


<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
        <div class="container-fluid p-0">
            <div class="hero-wrapper">
                <div class="hero-image">
                    <img src="../MediTrust/assets/img/health/main.jpg" alt="Advanced Healthcare" class="img-fluid">
                </div>

                <div class="hero-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7 col-md-10" data-aos="fade-right" data-aos-delay="100">
                                <div class="content-box">
                                    <h1 data-aos="fade-up" data-aos-delay="200">Where every paws gets attention</h1>
                                    <p data-aos="fade-up" data-aos-delay="250">
                                        From routine checkups to special care, we’re dedicated to every paw that walks
                                        in.
                                    </p>
                                    <div class="cta-group" data-aos="fade-up" data-aos-delay="300">
                                        <a href="appointment.html" class="btn btn-primary">Book Appointment</a>
                                    </div>

                                    <div class="info-badges" data-aos="fade-up" data-aos-delay="350">
                                        <div class="badge-item">
                                            <i class="bi bi-telephone-fill"></i>
                                            <div class="badge-content">
                                                <span>Emergency Line</span>
                                                <strong>+06-1233782</strong>
                                            </div>
                                        </div>
                                        <div class="badge-item">
                                            <i class="bi bi-clock-fill"></i>
                                            <div class="badge-content">
                                                <span>Working Hours</span>
                                                <strong>Mon-Sat: 9AM-5PM</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Features Section -->
                        <div class="features-wrapper">
                            <div class="row gy-4">

                                <!-- Pet Health Check -->
                                <div class="col-lg-4">
                                    <div class="feature-item">
                                        <div class="feature-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                                        <div class="feature-text">
                                            <h3>Pet Health Check</h3>
                                            <p>Routine health check-ups to keep your pets healthy and active.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vaccination & Prevention -->
                                <div class="col-lg-4">
                                    <div class="feature-item">
                                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                        <div class="feature-text">
                                            <h3>Vaccination & Prevention</h3>
                                            <p>Protect your pets from harmful diseases with essential vaccinations.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pet Diagnostics -->
                                <div class="col-lg-4">
                                    <div class="feature-item">
                                        <div class="feature-icon"><i class="bi bi-search-heart"></i></div>
                                        <div class="feature-text">
                                            <h3>Pet Diagnostics</h3>
                                            <p>Accurate diagnosis using modern tools such as X-ray and laboratory tests.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
    </section>

    <!-- About Section -->
    <section id="home-about" class="home-about section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-5 align-items-center">

                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="about-image">
                        <img src="../MediTrust/assets/img/health/showcase-2.jpg" alt="Vet Clinic Facility"
                            class="img-fluid rounded-3 mb-4">
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="about-content">
                        <h2>Dedicated to Your Pets’ Well-being</h2>
                        <p class="lead">We provide compassionate care with trusted veterinarians and modern facilities.
                        </p>
                        <p>From regular check-ups to emergency treatment, our clinic ensures your pets receive the best
                            possible medical attention.</p>

                        <div class="row g-4 mt-4">
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-heart"></i></div>
                                    <h4>Gentle & Caring Staff</h4>
                                    <p>Our team prioritizes comfort and stress-free treatment.</p>
                                </div>
                            </div>
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-award"></i></div>
                                    <h4>Professional Vets</h4>
                                    <p>Experienced veterinarians dedicated to quality care.</p>
                                </div>
                            </div>
                        </div>

                        <div class="cta-wrapper mt-4">
                            <a href="about.php" class="btn btn-primary">Learn More About Us</a>
                            <a href="vets.php" class="btn btn-outline">Meet Our Vets</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Services -->
    <section id="featured-services" class="featured-services section light-background">

        <div class="container section-title" data-aos="fade-up">
            <h2>Featured Services</h2>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <!-- Surgery -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <div class="service-content">
                            <h3>Pet Surgery</h3>
                            <p>Safe and professional surgical procedures including spaying, neutering, and soft tissue
                                surgery.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i>Experienced surgeons</li>
                                <li><i class="fas fa-check-circle"></i>Modern equipment</li>
                                <li><i class="fas fa-check-circle"></i>Post-surgery care</li>
                            </ul>
                            <a href="services.php" class="service-btn">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Grooming -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div class="service-content">
                            <h3>Pet Grooming</h3>
                            <p>Keep your pets clean and comfortable with our grooming services.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i>Bathing & brushing</li>
                                <li><i class="fas fa-check-circle"></i>Nail trimming</li>
                                <li><i class="fas fa-check-circle"></i>Ear cleaning</li>
                            </ul>
                            <a href="services.php" class="service-btn">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dental Care -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-tooth"></i>
                        </div>
                        <div class="service-content">
                            <h3>Pet Dental Care</h3>
                            <p>Professional dental checks, teeth cleaning, and oral health maintenance.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i>Plaque removal</li>
                                <li><i class="fas fa-check-circle"></i>Oral disease screening</li>
                                <li><i class="fas fa-check-circle"></i>Fresh breath treatment</li>
                            </ul>
                            <a href="services.php" class="service-btn">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Emergency Care -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <div class="service-content">
                            <h3>Emergency Vet Care</h3>
                            <p>Immediate treatment for injuries, illness, and urgent pet health issues.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i>24/7 availability</li>
                                <li><i class="fas fa-check-circle"></i>Fast emergency response</li>
                                <li><i class="fas fa-check-circle"></i>Critical care support</li>
                            </ul>
                            <a href="services.php" class="service-btn">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>



    <section id="vets" class="vet-section">
        <div class="container">
            <h2 class="section-title">Meet Our <span>Veterinarians</span></h2>

            <div class="vet-carousel-wrapper">

                <button class="carousel-btn left" id="prevBtn">&#10094;</button>

                <div class="vet-carousel" id="vetCarousel">

                    <!-- PHP doctor array -->
                    <?php
                    $vets = [
                        ["15 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-2.webp", "DR. OOI CHEE HONG", "Principal Veterinary Surgeon", ["Dermatology", "Echocardiography & Ultrasonography", "Herbology (TCM & Acupuncture)"]],
                        ["13 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-3.webp", "DR. SYAHAR AMIR A. GANI", "Senior Veterinarian", ["Exotic Animal / Bird / Wildlife", "Behavior & Nutrition", "Theriogenology & Pathology"]],
                        ["10 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-5.webp", "DR. ZAMRI BIN ZAINIR", "Veterinary Surgeon", ["Exotic Animal Surgery", "Emergency & Critical Care", "Soft Tissue / Orthopedics"]],
                        ["5 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-7.webp", "DR. NUR AMIRA", "Animal Nutrition Specialist", ["Pet Nutrition", "Weight Management", "Digestive Health"]],
                        ["10 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-9.webp", "DR. DANIEL LIM", "Orthopedic Specialist", ["Bone Fractures", "Joint Replacement", "Sports Injuries"]],
                        ["8 YEARS EXPERIENCE", "../MediTrust/assets/img/health/staff-11.webp", "DR. AINA FARHANA", "Feline Specialist", ["Feline Internal Medicine", "Cat Behavior", "Vaccination & Wellness"]],
                    ];

                    foreach ($vets as $vet) {
                        echo '<div class="vet-card">
                        <div class="experience-tag">' . $vet[0] . '</div>
                        <img src="' . $vet[1] . '" class="vet-img">

                        <h4>' . $vet[2] . '</h4>
                        <p class="role">' . $vet[3] . '</p>

                        <ul class="vet-list">';
                        foreach ($vet[4] as $item)
                            echo '<li>' . $item . '</li>';
                        echo '</ul></div>';
                    }
                    ?>

                </div>

                <button class="carousel-btn right" id="nextBtn">&#10095;</button>
            </div>
        </div>
    </section>


</main>

<!-- ===================== JAVASCRIPT ===================== -->
<script>
    let index = 0;
    const carousel = document.getElementById("vetCarousel");
    const cards = document.querySelectorAll(".vet-card");
    const cardsPerView = 3;

    // BUTTON CLICK
    document.getElementById("nextBtn").onclick = () => {
        if (index < cards.length - cardsPerView) index++;
        updateCarousel();
    };

    document.getElementById("prevBtn").onclick = () => {
        if (index > 0) index--;
        updateCarousel();
    };

    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth + 30;
        carousel.style.transform = `translateX(-${index * cardWidth}px)`;
    }

    // FADE-UP ANIMATION
    function animateCards() {
        cards.forEach(card => {
            const rect = card.getBoundingClientRect();
            if (rect.top < window.innerHeight - 80) {
                card.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", animateCards);
    window.addEventListener("load", animateCards);
</script>

<?php
include "../frontend/footer.php";
?>