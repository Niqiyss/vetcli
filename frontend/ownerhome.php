<?php
include "../frontend/ownerheader.php";
include "../backend/connection.php";

?>


<style>
    html {
        scroll-behavior: smooth;
    }

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


    .our-services {
        padding: 70px 0;
        background: #f9fbff;
    }

    .our-service-box {
        background: #fff;
        border-radius: 18px;
        padding: 32px 24px;
        text-align: center;
        height: 100%;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        transition: 0.35s;
    }

    .our-service-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
    }

    .service-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: #eef4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.35s;
    }

    .service-icon i {
        font-size: 30px;
        color: #1A2A5A;
    }

    .our-service-box:hover .service-icon {
        background: #F6B100;
    }

    .our-service-box:hover .service-icon i {
        color: #fff;
    }

    .our-service-box h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1A2A5A;
    }

    .our-service-box p {
        font-size: 14px;
        color: #555;
        margin-bottom: 0;
    }

    .our-service-box.emergency {
        border: 2px solid #dc3545;
    }

    .emergency-icon {
        background: #dc3545 !important;
    }

    .emergency-icon i {
        color: #fff !important;
    }


    .our-vets {
        padding: 45px 0;
        background: #f9fbff;
    }


    .vet-carousel-wrapper {
        position: relative;
        overflow: hidden;
        padding: 10px 0;
    }

    .vet-carousel {
        display: flex;
        gap: 24px;
        transition: transform 0.45s ease;
    }


    .vet-carousel .vet-item {
        flex: 0 0 32%;
    }


    .vet-btn {
        position: absolute;
        top: 38%;
        background: #F6B100;
        color: #fff;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        font-size: 22px;
        cursor: pointer;
        z-index: 20;
    }

    .vet-btn.left {
        left: 12px;
    }

    .vet-btn.right {
        right: 12px;
    }


    .vet-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        transition: 0.35s;
        height: 100%;
        text-align: center;
    }

    .vet-card:hover {
        transform: translateY(-6px);
    }

    .vet-img-wrapper {
        width: 100%;
        height: 100;
        overflow: hidden;
    }

    .vet-img-wrapper img {
        width: 50%;
        height: 50%;
        object-fit: cover;

    }

    .vet-card-body {
        padding: 10px 12px 10px;
    }

    .vet-card-body h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1A2A5A;
        margin-bottom: 4px;
    }

    .vet-card-body span {
        display: inline-block;
        font-size: 12.5px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #eef4ff;
        color: #044242ff;
        font-weight: 500;
    }

</style>


<main class="main">


    <section id="hero" class="hero section dark-background">
        <div class="container-fluid p-0">
            <div class="hero-wrapper">
                <div class="hero-image">
                    <img src="../MediTrust/assets/img/health/main.jpg" alt="Advanced Healthcare" class="img-fluid">
                </div>

                <div class="hero-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7 col-md-10" data-aos="fade-up" data-aos-delay="150">
                                <div class="content-box">
                                    <h1 data-aos="fade-up" data-aos-delay="150">Where every paws gets attention</h1>
                                    <p data-aos="fade-up" data-aos-delay="200">
                                        From routine checkups to special care, we’re dedicated to every paw that walks
                                        in.
                                    </p>
                                    <div class="cta-group" data-aos="fade-up" data-aos-delay="200">
                                        <a href="appointment.html" class="btn btn-primary">Book Appointment</a>

                                        <a href="appointment.html" class="btn btn-warning">Emergency Case</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


    <!-- services -->
    <section class="our-services">
        <div class="container section-title" data-aos="fade-up">
            <h2>Our Services</h2>
            <p>Quality veterinary services designed to keep your pets healthy and happy.</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4 justify-content-center">

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                        <h4>Health Check</h4>
                        <p>Routine examinations to keep pets healthy.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Vaccination</h4>
                        <p>Essential vaccines for disease prevention.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box">
                        <div class="service-icon">
                            <i class="bi bi-scissors"></i>
                        </div>
                        <h4>Surgery</h4>
                        <p>Safe surgical procedures by professionals.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="our-service-box emergency">
                        <div class="service-icon emergency-icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h4>Emergency Care</h4>
                        <p>Immediate care for urgent situations.</p>
                    </div>
                </div>

            </div>


            <div class="text-center mt-5">
                <a href="../frontend/services.php" class="btn btn-primary">
                    Explore Services
                </a>
            </div>
        </div>
    </section>




    <!-- About -->
    <section id="home-about" class="home-about section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-5 align-items-center">

                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="about-image">
                        <img src="../MediTrust/assets/img/health/showcase-2.jpg" alt="Vet Clinic Facility"
                            class="img-fluid rounded-3 mb-4">
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="about-content">
                        <h2>Dedicated to Your Pets’ Well-being</h2>
                        <p class="lead">We provide compassionate care with trusted veterinarians and modern facilities.
                        </p>
                        <p>From regular check-ups to emergency treatment, our clinic ensures your pets receive the best
                            possible medical attention.</p>

                        <div class="row g-4 mt-4">
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-heart"></i></div>
                                    <h4>Gentle & Caring Staff</h4>
                                    <p>Our team prioritizes comfort and stress-free treatment.</p>
                                </div>
                            </div>
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-item">
                                    <div class="icon"><i class="bi bi-award"></i></div>
                                    <h4>Professional Vets</h4>
                                    <p>Experienced veterinarians dedicated to quality care.</p>
                                </div>
                            </div>
                        </div>

                        <div class="cta-wrapper mt-4">
                            <a href="../frontend/ownerabout.php" class="btn btn-primary">Learn More About Us</a>
                            <a href="#our-vets" class="btn btn-outline">Meet Our Vets</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section id="our-vets" class="our-vets">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>Meet Our Veterinarians</h2>
            <p>Experienced professionals dedicated to your pet’s health.</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="vet-carousel-wrapper">

                <!-- LEFT -->
                <button class="vet-btn left" onclick="slideVet(-1)">&#10094;</button>

                <!-- CAROUSEL -->
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
                                </div><br>

                                <div class="vet-card-body">
                                    <h4><?= htmlspecialchars($vet['vet_name']); ?></h4><br>
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

    <script>
        let vetIndex = 0;

        function slideVet(direction) {
            const carousel = document.getElementById("vetCarousel");
            const card = carousel.querySelector(".vet-item");
            const cardWidth = card.offsetWidth + 24;
            const visibleCards = 3;
            const totalCards = carousel.children.length;
            const maxIndex = totalCards - visibleCards;

            vetIndex += direction;

            if (vetIndex < 0) vetIndex = 0;
            if (vetIndex > maxIndex) vetIndex = maxIndex;

            carousel.style.transform = `translateX(-${vetIndex * cardWidth}px)`;
        }
    </script>



</main>

<?php
include "../frontend/footer.php";
?>