<?php
//adminhome.php
include "../frontend/adminheader.php";
include "../backend/connection.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* --- THEME VARIABLES --- */
    :root {
        --primary-teal: #0e5c65;
        --accent-blue: #0095c4;
        --light-blue-bg: #e1f5fe;
        --white: #ffffff;
        --text-muted: #6c757d;
        --bg-light: #f4f7f6;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: #444;
        background-color: var(--white);
    }

    /* --- HERO SECTION --- */
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

    .hero-content h1 {
        font-weight: 700;
        color: #fff;
        font-size: 3.5rem;
        margin-bottom: 15px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .hero-content p.hero-desc {
        color: #f0f0f0;
        font-size: 1.2rem;
        margin-bottom: 30px;
        text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }

    /* WELCOME BADGE STYLE */
    .welcome-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.15); /* Glass effect */
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 25px;
        border-radius: 50px;
        color: #fff;
        font-size: 14px;
        letter-spacing: 1px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .welcome-username {
        color: #6ee7ff; /* Bright Cyan/Blue */
        font-weight: 800;
        text-transform: uppercase;
        margin-left: 5px;
        text-shadow: 0 0 10px rgba(0, 149, 196, 0.5);
    }

    /* --- VETS SECTION --- */
    .our-vets {
        padding: 80px 0;
        background: var(--bg-light);
    }

    .section-title h2 {
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 10px;
    }
    
    .section-title p {
        color: var(--text-muted);
    }

    .vet-carousel-wrapper {
        position: relative;
        overflow: hidden;
        margin-top: 40px;
        padding: 0 20px;
    }

    .vet-carousel {
        display: flex;
        gap: 24px;
        transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .vet-item {
        flex: 0 0 32%;
        min-width: 300px;
    }

    .vet-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .vet-card:hover {
        border-color: var(--accent-blue);
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 149, 196, 0.1);
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
        padding: 25px 16px;
        text-align: center;
    }

    .vet-card-body h4 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 8px;
    }

    .vet-card-body span {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        background: var(--light-blue-bg);
        color: var(--accent-blue);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* CAROUSEL BUTTONS */
    .vet-btn {
        position: absolute;
        top: 45%;
        background: var(--accent-blue);
        color: #fff;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        z-index: 20;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
    
    /* Responsive tweaks */
    @media (max-width: 991px) {
        .vet-item { flex: 0 0 calc(50% - 12px); }
    }
    @media (max-width: 768px) {
        .vet-item { flex: 0 0 100%; }
        .hero-content h1 { font-size: 2.5rem; }
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
                            <div class="col-lg-8 col-md-10 mx-auto" data-aos="fade-up" data-aos-delay="150">
                                <div class="content-box">
                                    
                                    <div data-aos="fade-up" data-aos-delay="200">
                                        <div class="welcome-badge">
                                            WELCOME BACK, 
                                            <span class="welcome-username">
                                                <?= htmlspecialchars($_SESSION['username'] ?? 'ADMIN'); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <h1 data-aos="fade-up" data-aos-delay="150">Where every paw gets attention</h1>
                                    <p class="hero-desc" data-aos="fade-up" data-aos-delay="200">
                                        From routine checkups to special care, we’re dedicated to every paw that walks in.
                                    </p>
                                </div>
                            </div>
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

        <div class="container" data-aos="fade-up" data-aos-delay="20">
            <div class="vet-carousel-wrapper">

                <button class="vet-btn left" onclick="slideVet(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="vet-carousel" id="vetCarousel">
                    <?php
                    $stmt = $conn->query("SELECT vet_name, specialization, vet_image FROM veterinarian ORDER BY vet_name ASC");
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
        const items = carousel.children;
        const totalCards = items.length;
        
        // Responsive visibility logic
        let visibleCards = 3;
        if (window.innerWidth <= 991) visibleCards = 2;
        if (window.innerWidth <= 768) visibleCards = 1;

        const maxIndex = Math.max(0, totalCards - visibleCards);

        vetIndex += direction;

        if (vetIndex < 0) vetIndex = 0;
        if (vetIndex > maxIndex) vetIndex = maxIndex;

        const offset = vetIndex * (100 / visibleCards);
        carousel.style.transform = `translateX(-${offset}%)`;
    }
    
    // Reset on resize to prevent layout break
    window.addEventListener('resize', () => slideVet(0));
</script>

<?php
include "../frontend/footer.php";
?>