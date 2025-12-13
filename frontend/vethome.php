<?php
//vethome.php

include "../frontend/vetheader.php";
include "../backend/connection.php";

?>


<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
        <div class="container-fluid p-0">
            <div class="hero-wrapper">
                <div class="hero-image">
                    <img src="../MediTrust/assets/img/health/main.jpg" alt="Advanced Healthcare"
                        class="img-fluid">
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
            <p>Quality veterinary services designed to keep your pets healthy and happy.</p>
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



    <section id="find-a-doctor" class="find-a-doctor section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Find A Doctor</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
                <div class="col-lg-12">
                    <div class="search-container">
                        <form class="search-form" action="forms/doctor-search.php" method="get">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="doctor_name"
                                        placeholder="Doctor name or keyword">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="specialty" id="specialty-select">
                                        <option value="">Select Specialty</option>
                                        <option value="cardiology">Cardiology</option>
                                        <option value="neurology">Neurology</option>
                                        <option value="orthopedics">Orthopedics</option>
                                        <option value="pediatrics">Pediatrics</option>
                                        <option value="dermatology">Dermatology</option>
                                        <option value="oncology">Oncology</option>
                                        <option value="surgery">Surgery</option>
                                        <option value="emergency">Emergency Medicine</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search me-2"></i>Search Doctor
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row" data-aos="fade-up" data-aos-delay="400">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-3.webp" alt="Dr. Sarah Mitchell"
                                class="img-fluid">
                            <div class="availability-badge online">Available</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. Sarah Mitchell</h5>
                            <p class="specialty">Cardiology</p>
                            <p class="experience">15+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="rating-text">(4.9)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-7.webp" alt="Dr. Michael Rodriguez"
                                class="img-fluid">
                            <div class="availability-badge busy">In Surgery</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. Michael Rodriguez</h5>
                            <p class="specialty">Neurology</p>
                            <p class="experience">12+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                                <span class="rating-text">(4.7)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-1.webp" alt="Dr. Emily Chen"
                                class="img-fluid">
                            <div class="availability-badge online">Available</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. Emily Chen</h5>
                            <p class="specialty">Pediatrics</p>
                            <p class="experience">8+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="rating-text">(5.0)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-9.webp" alt="Dr. James Thompson"
                                class="img-fluid">
                            <div class="availability-badge offline">Next: Tomorrow 9AM</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. James Thompson</h5>
                            <p class="specialty">Orthopedics</p>
                            <p class="experience">20+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                                <span class="rating-text">(4.8)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-5.webp" alt="Dr. Lisa Anderson"
                                class="img-fluid">
                            <div class="availability-badge online">Available</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. Lisa Anderson</h5>
                            <p class="specialty">Dermatology</p>
                            <p class="experience">10+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                                <span class="rating-text">(4.6)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="../MediTrust/assets/img/health/staff-12.webp" alt="Dr. Robert Kim"
                                class="img-fluid">
                            <div class="availability-badge online">Available</div>
                        </div>
                        <div class="doctor-info">
                            <h5>Dr. Robert Kim</h5>
                            <p class="specialty">Oncology</p>
                            <p class="experience">18+ years experience</p>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="rating-text">(4.9)</span>
                            </div>
                            <div class="appointment-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <a href="#" class="btn btn-primary btn-sm">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>

</main>

</body>

</html>


<?php
include "../frontend/footer.php";
?>