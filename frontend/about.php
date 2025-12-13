<?php
include "../frontend/header.php";
?>

<style>
.highlight-box {
    background: #f8f9fc;
    border-radius: 12px;
    padding: 18px;
    text-align: center;
    transition: 0.3s ease;
    border: 1px solid #eef1f7;
    height: 100%;
}

.highlight-box i {
    font-size: 30px;
}

.highlight-box:hover {
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.image-frame {
    padding: 10px;
    border-radius: 20px;
    background: #ffffff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
</style>


<main class="main">

    <!-- Page Title -->
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>About Us</h1>
            <p class="text-muted">Caring for Pets, Supporting Families</p>
        </div>
    </div>

    <!-- About Section -->
    <section class="about section py-5">
        <div class="container">

            <div class="row gy-5 align-items-center">

                <!-- Left Content -->
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold mb-3">Your Trusted Veterinary Care Team</h2>

                    <p>
                        At <strong>VetClinic</strong>, pets are more than companions — they are cherished
                        members of the family. Our goal is to deliver exceptional veterinary care supported
                        by modern technology, skilled veterinarians, and heartfelt compassion.
                    </p>

                    <p>
                        Whether it’s routine wellness or advanced medical treatment, our dedicated team is here
                        to support your pet’s health, comfort, and happiness at every stage of life.
                    </p>

                    <div class="row mt-4 g-3">

                        <div class="col-6">
                            <div class="highlight-box">
                                <i class="bi bi-heart-fill text-danger"></i>
                                <h6 class="fw-bold mt-2">Compassionate Care</h6>
                                <p class="small text-muted mb-0">
                                    Loving treatment for every pet.
                                </p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="highlight-box">
                                <i class="bi bi-hospital-fill text-primary"></i>
                                <h6 class="fw-bold mt-2">Modern Facilities</h6>
                                <p class="small text-muted mb-0">
                                    Clean, safe & fully equipped.
                                </p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="highlight-box">
                                <i class="bi bi-activity text-warning"></i>
                                <h6 class="fw-bold mt-2">Advanced Diagnostics</h6>
                                <p class="small text-muted mb-0">
                                    Accurate & reliable test results.
                                </p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="highlight-box">
                                <i class="bi bi-people-fill text-success"></i>
                                <h6 class="fw-bold mt-2">Dedicated Team</h6>
                                <p class="small text-muted mb-0">
                                    Experts who truly care for animals.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right Image -->
                <div class="col-lg-6 d-flex justify-content-center" data-aos="fade-left">
                    <div class="image-frame">
                        <img src="../MediTrust/assets/img/health/aboutus.jpeg" 
                        class="img-fluid rounded-4 shadow" alt="Advanced Healthcare">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Mission, Vision & Values -->
    <section class="mission section py-5 light-background">
        <div class="container">
            <div class="row text-center gy-4">

                <div class="col-lg-4" data-aos="fade-up">
                    <div class="p-4 bg-white shadow rounded-4 h-100">
                        <i class="bi bi-flag-fill text-primary fs-1 mb-3"></i>
                        <h3 class="fw-bold mb-3">Our Mission</h3>
                        <p>
                            To provide high-quality, compassionate veterinary care that enhances
                            the lives of pets and supports their families.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="p-4 bg-white shadow rounded-4 h-100">
                        <i class="bi bi-binoculars-fill text-warning fs-1 mb-3"></i>
                        <h3 class="fw-bold mb-3">Our Vision</h3>
                        <p>
                            To be the most trusted veterinary clinic, known for medical excellence,
                            innovation, and heartfelt service.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 bg-white shadow rounded-4 h-100">
                        <i class="bi bi-stars text-danger fs-1 mb-3"></i>
                        <h3 class="fw-bold mb-3">Our Values</h3>
                        <p>
                            Compassion, trust, professionalism, and a genuine passion for improving
                            the quality of life for all animals.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-us section py-5">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Pet Owners Choose Us</h2>
                <p class="text-muted">More than a clinic — we are your pet’s second family.</p>
            </div>

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="p-4 text-center bg-light shadow-sm rounded-4">
                        <i class="bi bi-person-badge-fill text-primary fs-1 mb-3"></i>
                        <h5>Certified Veterinarians</h5>
                        <p class="small text-muted">
                            Highly skilled experts with years of experience.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="p-4 text-center bg-light shadow-sm rounded-4">
                        <i class="bi bi-heart-pulse-fill text-danger fs-1 mb-3"></i>
                        <h5>Comprehensive Services</h5>
                        <p class="small text-muted">
                            From vaccinations to emergency care.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 text-center bg-light shadow-sm rounded-4">
                        <i class="bi bi-buildings-fill text-warning fs-1 mb-3"></i>
                        <h5>Modern Facilities</h5>
                        <p class="small text-muted">
                            A clean, safe, and fully equipped environment.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="450">
                    <div class="p-4 text-center bg-light shadow-sm rounded-4">
                        <i class="bi bi-chat-heart-fill text-success fs-1 mb-3"></i>
                        <h5>Personalized Care</h5>
                        <p class="small text-muted">
                            Tailored treatment plans for each pet.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

</main>

<?php
include "../frontend/footer.php";
?>
