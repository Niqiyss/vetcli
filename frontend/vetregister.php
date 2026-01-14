<?php
// vetregister.php

session_start();
include "../frontend/adminheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// success popup
if (isset($_SESSION['success_popup'])) {
    $msg = json_encode($_SESSION['success_popup']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: $msg,
            confirmButtonColor: '#00798C'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

// error popup
if (isset($_SESSION['error_popup']) && is_array($_SESSION['error_popup'])) {
    $msg = "• " . implode("<br>• ", array_map('htmlspecialchars', $_SESSION['error_popup']));
    $msg = json_encode($msg);

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            html: $msg,
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}
?>

<style>
    :root {
        --primary-teal: #00798C;
        --accent-teal: #00798C;
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

    .hero-section {
        background-color: var(--white);
        width: 100%;
        padding: 40px 0;
        border-bottom: 3px solid var(--accent-teal);
        margin-bottom: 40px;
    }

    .header-content-wrapper {
        display: flex;
        flex-direction: column; 
        align-items: center;    
        position: relative;     
    }

    .page-title {
        text-align: center;
        margin-bottom: 20px; 
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 5px;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 15px;
        margin-bottom: 0;
    }


    .admin-badge {
        position: absolute;
        right: 0;
        bottom: 0; 
        
        background-color: white;
        padding: 8px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #e0e0e0;
    }

    .admin-badge-icon {
        width: 32px;
        height: 32px;
        background-color: var(--accent-teal);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .admin-badge-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
        text-align: left;
    }

    .admin-badge-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .admin-badge-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-teal);
    }

    @media (max-width: 768px) {
        .admin-badge {
            position: static;
            transform: none;
            margin-top: 15px;
        }
    }

    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 35px;
    }

    .section-subtitle {
        color: var(--accent-teal);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .section-subtitle i {
        margin-right: 10px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 14px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
        outline: none;
    }

    .upload-box {
        border: 2px dashed #d1d9e6;
        border-radius: 12px;
        padding: 12px;
        background: #fafafa;
        transition: 0.3s;
    }

    .upload-box:hover {
        background: #f1f8e9;
        border-color: var(--accent-teal);
    }

    .btn-register {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: 0.3s;
    }

    .btn-register:hover {
        background-color: var(--primary-teal);
        transform: translateY(-2px);
    }


    .pw-checklist {
        list-style: none;
        padding-left: 0;
        margin-top: 10px;
    }

    .pw-checklist li {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 5px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .pw-checklist li i {
        margin-right: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .pw-checklist li.valid {
        color: var(--accent-teal);
        font-weight: 500;
    }
</style>



<div class="hero-section">
    <div class="container">
        <div class="header-content-wrapper">
            
            <div class="page-title">
                <h1>Veterinarian Registration</h1>
                <p>Create An Account For Veterinary Staff</p>
            </div>

            <div class="admin-badge">
                <div class="admin-badge-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="admin-badge-text">
                    <span class="admin-badge-label">Admin</span>
                    <span class="admin-badge-name"><?= htmlspecialchars($_SESSION['adminname'] ?? 'Admin'); ?></span>
                </div>
            </div>

        </div>
    </div>
</div>

<main class="main pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="custom-card">

                    <form method="POST" action="../backend/vetregister_b.php" enctype="multipart/form-data">
                        <div class="row g-4">

                            <div class="col-md-6 border-end pe-md-4">
                                <h6 class="section-subtitle"><i class="fas fa-user-md"></i> Personal Details</h6>

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="vet_name" class="form-control"
                                        onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Veterinarian Image</label>
                                    <div class="upload-box">
                                        <input type="file" name="vet_image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <h6 class="section-subtitle"><i class="fas fa-stethoscope"></i> Professional Info</h6>

                                <div class="mb-3">
                                    <label class="form-label">Specialization</label>
                                    <select name="specialization" class="form-select" required>
                                        <option disabled selected value="">Select Specialization</option>
                                        <option>General Veterinary Care</option>
                                        <option>Surgery & Orthopedics</option>
                                        <option>Vaccination & Preventive Care</option>
                                        <option>Dermatology & Skin Issues</option>
                                        <option>Emergency & Critical Care</option>
                                        <option>Internal Medicine</option>
                                        <option>Dentistry</option>
                                        <option>Ophthalmology</option>
                                        <option>Neurology</option>
                                        <option>Cardiology</option>
                                        <option>Nutrition & Weight Management</option>
                                        <option>Reproduction & Fertility</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>

                                    <ul class="pw-checklist">
                                        <li id="pw-length">
                                            <i class="bi bi-circle" id="icon-length"></i> At least 6 characters
                                        </li>
                                        <li id="pw-upper">
                                            <i class="bi bi-circle" id="icon-upper"></i> At least 1 uppercase letter
                                        </li>
                                        <li id="pw-symbol">
                                            <i class="bi bi-circle" id="icon-symbol"></i> At least 1 symbol
                                        </li>
                                    </ul>
                                </div>

                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="showPw"
                                        onclick="togglePassword()">
                                    <label class="form-check-label text-muted small" for="showPw"
                                        style="cursor:pointer;">Show Password</label>
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-register">Register Veterinarian</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Toggle Password
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }

    // Password Validation Logic
    const passwordInput = document.getElementById("password");

    const reqLength = document.getElementById("pw-length");
    const iconLength = document.getElementById("icon-length");

    const reqUpper = document.getElementById("pw-upper");
    const iconUpper = document.getElementById("icon-upper");

    const reqSymbol = document.getElementById("pw-symbol");
    const iconSymbol = document.getElementById("icon-symbol");

    function updateStatus(condition, element, icon) {
        if (condition) {
            // Valid State
            element.classList.add("valid");
            icon.classList.remove("bi-circle");
            icon.classList.add("bi-check-circle-fill");
        } else {
            // Invalid State
            element.classList.remove("valid");
            icon.classList.remove("bi-check-circle-fill");
            icon.classList.add("bi-circle");
        }
    }

    passwordInput.addEventListener("input", () => {
        const val = passwordInput.value;

        // 1. Length
        updateStatus(val.length >= 6, reqLength, iconLength);

        // 2. Uppercase
        updateStatus(/[A-Z]/.test(val), reqUpper, iconUpper);

        // 3. Symbol
        updateStatus(/[\W_]/.test(val), reqSymbol, iconSymbol);
    });
</script>

<?php
include "../frontend/footer.php";
?>