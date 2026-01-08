<?php
//service.php
include "../frontend/ownerheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-teal: #0e5c65;  
        --accent-blue: #0095c4;   
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
        padding-top: 2rem;
        margin-bottom: 3rem;
        text-align: center;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal); /* Title stays Dark Teal */
        margin-bottom: 10px;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* SERVICE CARDS */
    .service-card {
        background: var(--white);
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 149, 196, 0.15); /* Blue shadow */
        border-color: rgba(0, 149, 196, 0.2);
    }

    .icon-wrapper {
        width: 80px;
        height: 80px;
        background-color: var(--accent-blue); /* NEW BLUE BACKGROUND */
        color: var(--white);
        border-radius: 18px; /* Slightly rounded square like your image */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(0, 149, 196, 0.3);
    }

    .service-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }

    .service-desc {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* CTA SECTION */
    .cta-section {
        background-color: white;
        border-radius: 20px;
        padding: 50px;
        margin-top: 60px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .btn-book {
        background-color: var(--accent-blue); /* Blue Button */
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        transition: 0.3s;
        margin-top: 20px;
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
            <div class="page-title">
                <h1>Our Services</h1>
                <p>Comprehensive veterinary care tailored to your pet's needs</p>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h5 class="service-title">General Checkup</h5>
                    <p class="service-desc">
                        Routine physical examinations to monitor your pet's health and detect issues early
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-syringe"></i> </div>
                    <h5 class="service-title">Vaccination</h5>
                    <p class="service-desc">
                        Essential vaccines to protect your pets from common and dangerous infectious diseases
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h5 class="service-title">Dental Care</h5>
                    <p class="service-desc">
                        Professional cleaning, polishing, and dental surgeries to keep their smile healthy
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-procedures"></i>
                    </div>
                    <h5 class="service-title">Surgery</h5>
                    <p class="service-desc">
                        Safe surgical procedures including spaying, neutering, and soft tissue surgeries
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h5 class="service-title">Pet Grooming</h5>
                    <p class="service-desc">
                        Bathing, styling, and hygiene care to keep your pet looking and feeling their best
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h5 class="service-title">Emergency Care</h5>
                    <p class="service-desc">
                        Urgent medical attention for critical conditions, available during clinic hours
                    </p>
                </div>
            </div>

        </div>

        <div class="cta-section">
            <h2 class="fw-bold text-dark mb-3">Ready to visit us?</h2>
            <p class="text-muted mb-0">Book an appointment online to skip the wait</p>
            <a
                href="http://10.48.74.61/vet_clinic/frontend/new_appointment.php?owner_id=<?= $_SESSION['ownerID'] ?>&ownername=<?= $_SESSION['ownername'] ?>" class="btn btn-book">Book Appointment</a>
        </div>

    </div>
</main>

<?php
include "../frontend/footer.php";
?>