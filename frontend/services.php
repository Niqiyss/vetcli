<?php
//service.php
include "../frontend/header.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-teal: #0e5c65;  /* Dark Teal for Headings */
        --accent-blue: #0095c4;   /* The Blue Color */
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
        color: #444;
    }

    /* CENTERED HEADER */
    .page-header-custom {
        margin-bottom: 40px;
        display: flex;
        justify-content: center; 
        text-align: center;      
    }

    .page-title h1 {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 15px;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* SERVICE GRID */
    .service-card {
        background: var(--white);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #edf2f4;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    /* Hover Effect */
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 149, 196, 0.1); 
        border-color: rgba(0, 149, 196, 0.3);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        background-color: var(--accent-blue);
        color: var(--white);
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px rgba(0, 149, 196, 0.25);
        transition: 0.3s;
    }

    .service-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .service-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 15px;
    }

    .service-desc {
        font-size: 14px;
        color: #666;
        line-height: 1.7;
        margin-bottom: 0;
    }

    /* ORIGINAL CTA SECTION (White Background + Blue Button) */
    .cta-section {
        background-color: white;
        border-radius: 20px;
        padding: 50px;
        margin-top: 60px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .btn-book {
        background-color: var(--accent-blue);
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        transition: 0.3s;
        margin-top: 20px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-book:hover {
        background-color: #007ba1; /* Darker Blue on Hover */
        box-shadow: 0 5px 15px rgba(0, 149, 196, 0.4);
        color: white;
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="title-wrapper page-title">
                <h1>Our Services</h1>
                <p>We provide a wide range of veterinary services to ensure your pet lives a happy, healthy, and active life.</p>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h5 class="service-title">General Checkup</h5>
                    <p class="service-desc">Routine Health Checkup</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h5 class="service-title">Deworming</h5>
                    <p class="service-desc">Treatment to remove intestinal worms</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <h5 class="service-title">Vaccination</h5>
                    <p class="service-desc">Vaccination services</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <h5 class="service-title">Surgery Consultation</h5>
                    <p class="service-desc">Consultation before surgery</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-bug"></i>
                    </div>
                    <h5 class="service-title">Flea & Tick Treatment</h5>
                    <p class="service-desc">Treatment for flea and tick prevention</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h5 class="service-title">Dental Checkup</h5>
                    <p class="service-desc">Examination of teeth and gums</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h5 class="service-title">Ear & Eye Exam</h5>
                    <p class="service-desc">Check and treatment for eye or ear infections</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-paw"></i>
                    </div>
                    <h5 class="service-title">Skin & Allergy</h5>
                    <p class="service-desc">Diagnosis and treatment for skin infections, itching, or allergies</p>
                </div>
            </div>

        </div>

        <div class="cta-section">
            <h2 class="fw-bold text-dark mb-3">Ready to visit us?</h2>
            <p class="text-muted mb-0">Book an appointment online to skip the wait</p>
            <a href="../frontend/userlogin.php" class="btn btn-book">Book Appointment</a>
        </div>

    </div>
</main>

<?php
include "../frontend/footer.php";
?>