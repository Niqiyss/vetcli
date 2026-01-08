<?php
//about.php
include "../frontend/header.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-teal: #0e5c65;  /* Main Titles (Kept Dark) */
        --accent-blue: #0095c4;   /* NEW BLUE (Matches Service Page) */
        --light-blue-bg: #e1f5fe; /* Light Blue for backgrounds */
        --bg-light: #f4f7f6;
        --text-muted: #6c757d;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: #444;
        background-color: var(--white);
    }

    /* --- PAGE HEADER --- */
    .page-header-custom {
        padding-top: 2rem;
        margin-bottom: 3rem;
        text-align: center;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal); /* Title remains Dark Teal */
        margin-bottom: 10px;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* --- BADGES & HEADINGS --- */
    .section-title-badge {
        font-size: 13px;
        font-weight: 700;
        color: var(--accent-blue); /* Blue Text */
        text-transform: uppercase;
        letter-spacing: 1.5px;
        display: inline-block;
        margin-bottom: 10px;
        background: var(--light-blue-bg); /* Light Blue BG */
        padding: 5px 15px;
        border-radius: 20px;
    }

    .section-heading {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal); /* Heading remains Dark Teal */
        margin-bottom: 25px;
        line-height: 1.3;
    }

    /* --- ABOUT SECTION --- */
    .about-section {
        padding: 40px 0 80px;
    }

    .lead-text {
        font-size: 18px;
        font-weight: 500;
        color: #333;
        margin-bottom: 20px;
        border-left: 4px solid var(--accent-blue); /* Blue Border */
        padding-left: 15px;
    }

    .about-text p {
        line-height: 1.8;
        color: #666;
        margin-bottom: 25px;
    }

    .stat-box h3 {
        font-weight: 700;
        color: var(--accent-blue); /* Blue Numbers */
        margin-bottom: 0;
        font-size: 28px;
    }
    .stat-box small {
        font-size: 13px;
        text-transform: uppercase;
        color: #888;
        font-weight: 600;
    }

    .about-img-wrapper {
        position: relative;
        padding: 15px;
    }

    .about-img-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: repeating-radial-gradient(var(--light-blue-bg) 0, var(--light-blue-bg) 3px, transparent 4px, transparent 10px);
        z-index: -1;
        border-radius: 50%;
    }

    .about-img-wrapper img {
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        width: 100%;
        object-fit: cover;
    }

    /* --- WHY US SECTION --- */
    .why-us {
        padding: 90px 0;
        background-color: var(--bg-light);
    }

    .feature-card {
        background: var(--white);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .feature-card:hover {
        transform: translateX(10px);
        box-shadow: 0 15px 30px rgba(0, 149, 196, 0.15); /* Blue Shadow */
        border-left: 4px solid var(--accent-blue); /* Blue Border */
    }

    .feature-icon {
        background-color: var(--light-blue-bg); /* Light Blue BG */
        color: var(--accent-blue); /* Blue Icon */
        min-width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .feature-content h6 {
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 8px;
        font-size: 18px;
    }

    .feature-content p {
        font-size: 14px;
        color: #666;
        margin: 0;
        line-height: 1.6;
    }

    .why-img {
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        object-fit: cover;
        min-height: 450px;
    }

    /* --- MISSION VISION CARDS --- */
    .mv-section {
        padding: 90px 0;
    }

    .mv-card {
        text-align: center;
        padding: 40px 30px;
        border-radius: 20px;
        background: var(--white);
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        height: 100%;
        transition: 0.3s;
        border-top: 4px solid var(--bg-light); 
    }

    .mv-card:hover {
        transform: translateY(-10px);
        border-top: 4px solid var(--accent-blue); /* Blue Top Border */
        box-shadow: 0 15px 40px rgba(0, 149, 196, 0.15);
    }

    .mv-icon {
        width: 80px;
        height: 80px;
        background: var(--bg-light);
        color: var(--primary-teal); /* Icon starts dark */
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 25px;
        transition: 0.3s;
    }

    .mv-card:hover .mv-icon {
        background: var(--accent-blue); /* Icon BG becomes Blue */
        color: var(--white);
    }

    .mv-card h4 {
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .mv-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.7;
    }
</style>

<main class="main">

    <div class="page-header-custom">
        <div class="title-wrapper page-title">
            <h1>About Us</h1>
            <p>From routine checkups to special care, we are dedicated to every paw that walks in</p>
        </div>
    </div>

    <section class="about-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="about-text pe-lg-4">
                        <span class="section-title-badge">Who We Are</span>
                        <h2 class="section-heading">Dedicated to Your Pet's Health & Happiness</h2>
                        
                        <p class="lead-text">
                            At <strong>VetClinic</strong>, your pet’s health and well-being are our top priority. 
                            We are committed to providing professional, ethical, and compassionate veterinary care
                        </p>

                        <p>
                            Our experienced veterinarians and support staff work closely together to ensure
                            every pet receives safe, effective, and personalized treatment. We believe in treating
                            every animal as if they were our own family member.
                        </p>
                        
                        <div class="row mt-5">
                            <div class="col-4 text-center stat-box">
                                <h3>10+</h3>
                                <small>Years Experience</small>
                            </div>
                            <div class="col-4 text-center stat-box border-start border-end">
                                <h3>5k+</h3>
                                <small>Happy Pets</small>
                            </div>
                            <div class="col-4 text-center stat-box">
                                <h3>24/7</h3>
                                <small>Support</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="about-img-wrapper">
                        <img src="../MediTrust/assets/img/health/aboutus.jpeg" alt="Veterinary Team">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="why-us">
        <div class="container">
            <div class="row align-items-center gy-5">
                
                <div class="col-lg-5">
                    <img src="../MediTrust/assets/img/health/vett.jpeg" class="why-img" alt="Why Choose Us">
                </div>

                <div class="col-lg-7 ps-lg-5">
                    <span class="section-title-badge">Why Choose Us</span>
                    <h2 class="section-heading">Care You Can Trust</h2>
                    <p class="mb-5 text-muted">
                        Choosing the right veterinary clinic matters. We focus on responsible care,
                        clear communication, and long-term health support for every pet
                    </p>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <div class="feature-content">
                            <h6>Compassionate Care</h6>
                            <p>We treat every pet with patience and empathy, ensuring a calm and comfortable experience during every visit</p>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="feature-content">
                            <h6>Professional & Ethical</h6>
                            <p>Our veterinarians follow professional standards and proven medical practices to deliver safe, accurate, and reliable treatment</p>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <div class="feature-content">
                            <h6>Clear Communication</h6>
                            <p>We explain conditions and treatments clearly, helping owners make confident decisions about their pet’s care</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section class="mv-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-title-badge">Our Philosophy</span>
                <h2 class="section-heading">Guided by Purpose</h2>
            </div>

            <div class="row gy-4">
                
                <div class="col-md-4">
                    <div class="mv-card">
                        <div class="mv-icon">
                            <i class="bi bi-flag-fill"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p>To provide compassionate, ethical, and high-quality veterinary care that improves the health and quality of life of pets in our community</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mv-card">
                        <div class="mv-icon">
                            <i class="bi bi-binoculars-fill"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <p>To become a trusted veterinary clinic recognized for medical excellence, professionalism, and innovation in animal healthcare</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mv-card">
                        <div class="mv-icon">
                            <i class="bi bi-stars"></i>
                        </div>
                        <h4>Our Values</h4>
                        <p>Compassion, responsibility, professionalism, integrity, and respect for animals and their owners define every action we take</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php
include "../frontend/footer.php";
?>