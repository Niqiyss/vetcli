<?php
//adminhome.php

include "../frontend/adminheader.php";
require_once "../backend/connection.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
   
    :root {
        --primary-teal: #0e5c65;
        --accent-blue: #0095c4;
        --light-blue-bg: #e1f5fe;
        --white: #ffffff;
        --text-muted: #6c757d;
        --bg-light: #f4f7f6;
        
        --bg-gradient: linear-gradient(120deg, #f8fcfd 0%, #eef7f9 100%);
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: #444;
        background-color: var(--white);
    }

    
    .hero-modern {
        padding: 120px 0 80px; 
        background: var(--bg-gradient);
        position: relative;
        overflow: hidden;
    }

    
    .hero-modern::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(0, 149, 196, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        z-index: 0;
    }

    .hero-text-col {
        position: relative;
        z-index: 2;
        padding-right: 20px;
    }

    
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(14, 92, 101, 0.1);
        color: var(--primary-teal);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 25px;
        border: 1px solid rgba(14, 92, 101, 0.1);
    }

    .hero-badge i {
        color: var(--accent-blue);
    }

    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--primary-teal);
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .hero-title span {
        color: var(--accent-blue);
    }

    .hero-description {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 35px;
        line-height: 1.7;
        max-width: 500px;
    }


    
    .hero-img-wrapper {
        position: relative;
        z-index: 1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        border: 4px solid #fff;
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hero-img-wrapper:hover {
        transform: translateY(-5px); 
        box-shadow: 0 20px 40px rgba(0, 149, 196, 0.15);
    }

    
    .hero-carousel-img {
        width: 100%;
        height: 350px;
        object-fit: cover; 
        object-position: center;
    }


    .carousel-indicators {
        position: absolute;
        bottom: 15px;
        margin-bottom: 0;
        gap: 8px;
        z-index: 2;
    }

    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        opacity: 0.5;
        border: none;
        margin: 0;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .carousel-indicators .active {
        opacity: 1;
        transform: scale(1.2);
        background-color: #fff;
    }


    .carousel-control-prev, 
    .carousel-control-next {
        width: 45px;
        height: 45px;
        background-color: rgba(0,0,0,0.4); 
        border-radius: 50%; 
        top: 50%; 
        transform: translateY(-50%);
        opacity: 0; 
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.5);
    }

    
    .hero-img-wrapper:hover .carousel-control-prev,
    .hero-img-wrapper:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-control-prev { left: 15px; }
    .carousel-control-next { right: 15px; }

    .carousel-control-prev:hover, 
    .carousel-control-next:hover {
        background-color: var(--accent-blue); 
        border-color: var(--accent-blue);
        opacity: 1;
    }


    
    .hero-info-grid {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .info-card-box {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #fff;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border: 1px solid #edf2f4;
        min-width: 240px;
        transition: 0.3s;
    }

    .info-card-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .icon-box-sm {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-text-sm h6 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }

    .info-text-sm span {
        font-size: 13px;
        color: #777;
    }


    .info-card-box.hours .icon-box-sm {
        background: #e1f5fe;
        color: var(--accent-blue);
    }

    .info-card-box.emergency {
        border-left: 4px solid #dc3545;
    }

    .info-card-box.emergency .icon-box-sm {
        background: #ffecec;
        color: #dc3545;
        animation: pulse 2s infinite;
    }

    .info-card-box.emergency h6 {
        color: #dc3545;
    }

    .emergency-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        width: 100%;
        gap: 15px;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }



    .our-vets {
        padding: 80px 0;
        background-color: var(--white);
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
        flex: 0 0 32%;
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
        border: 1px solid #f0f0f0;
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

    .vet-btn.left {
        left: 0;
    }

    .vet-btn.right {
        right: 0;
    }


    .btn-blue {
        background-color: var(--accent-blue);
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 50px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-blue:hover {
        background-color: var(--primary-teal);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
    
    
    @media (max-width: 991px) {
        .hero-modern {
            padding: 80px 0 40px; 
            text-align: center;
        }

        .hero-text-col {
            padding-right: 0;
            margin-bottom: 40px;
        }

        .hero-description {
            margin: 0 auto 30px;
        }

        .hero-btns {
            justify-content: center;
        }

        .hero-info-grid {
            justify-content: center;
        }

        .hero-img-wrapper {
            transform: none;
            max-width: 500px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-carousel-img {
            height: 280px; 
        }
    }
</style>

<main class="main">

    <section class="hero-modern">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 hero-text-col" data-aos="fade-right">

                    <br><br>
                    <div class="hero-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    </div>

                    <h1 class="hero-title">
                        Where every paw  <br>
                        <span>gets attention</span>
                    </h1>

                    <p class="hero-description">
                        From routine checkups to special care, we’re dedicated to every paw that walks in
                    </p>

                    <div class="hero-info-grid">

                        <div class="info-card-box hours">
                            <div class="icon-box-sm">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="info-text-sm">
                                <h6>Mon - Sat</h6>
                                <span>9:00 AM - 6:00 PM</span>
                            </div>
                        </div>

                        <div class="info-card-box emergency">
                            <a href="tel:+601111244959" class="emergency-link">
                                <div class="icon-box-sm">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="info-text-sm">
                                    <h6>Emergency Line</h6>
                                    <span>011-1124 4959</span>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="hero-img-wrapper">
                        
                        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
                            
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            </div>

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../MediTrust/assets/img/health/aboutus.jpeg" class="hero-carousel-img" alt="Vet examining cat">
                                </div>

                                <div class="carousel-item">
                                    <img src="../MediTrust/assets/img/health/withrabbit.jpeg" class="hero-carousel-img" alt="With Rabbit">
                                </div>

                                <div class="carousel-item">
                                    <img src="../MediTrust/assets/img/health/hero-2.jpg" class="hero-carousel-img" alt="Kitten">
                                </div>

                                <div class="carousel-item">
                                    <img src="../MediTrust/assets/img/health/hamster.webp" class="hero-carousel-img" alt="Golden Retriever">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon visually-hidden" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                                <i class="fas fa-chevron-left text-white fs-5"></i>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon visually-hidden" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                                <i class="fas fa-chevron-right text-white fs-5"></i>
                            </button>

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

                <button class="vet-btn left" onclick="slideVet(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

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

                <button class="vet-btn right" onclick="slideVet(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>
        </div>
    </section>

</main>

<script>
    let vetIndex = 0;

    function slideVet(direction) {
        const carousel = document.getElementById("vetCarousel");
        const card = carousel.querySelector(".vet-item");

        let visibleCards = 3;
        if (window.innerWidth <= 992) visibleCards = 2;
        if (window.innerWidth <= 768) visibleCards = 1;

        const totalCards = carousel.children.length;
        const maxIndex = Math.max(0, totalCards - visibleCards);

        vetIndex += direction;

        if (vetIndex < 0) vetIndex = 0;
        if (vetIndex > maxIndex) vetIndex = maxIndex;

        const offset = vetIndex * (100 / visibleCards);
        carousel.style.transform = `translateX(-${offset}%)`;
    }

    window.addEventListener('resize', () => slideVet(0));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php
include "../frontend/footer.php";
?>