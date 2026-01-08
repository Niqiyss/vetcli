<?php
//ownerregister.php

session_start();
include "../frontend/header.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//success logic
if (isset($_SESSION['success_popup'])) {
    $successMsg = json_encode($_SESSION['success_popup']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: $successMsg,
            confirmButtonColor: '#009d91'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

//error logic
if (isset($_SESSION['error_popup'])) {
    $errors = explode("\n", $_SESSION['error_popup']);
    $html = '<ul style="text-align:left; margin-left:1rem;">';
    foreach ($errors as $e) {
        $html .= '<li>' . htmlspecialchars($e) . '</li>';
    }
    $html .= '</ul>';
    $html = json_encode($html);

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            html: $html,
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}
?>

<style>
    :root {
        --primary-teal: #0e5c65;
        --accent-teal: #009d91;
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

    /* MATCHING HEADER CSS */
    .page-header-custom {
        margin-bottom: 30px;
        display: flex;
        justify-content: center; 
        text-align: center;      
    }

    .page-title h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 5px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }

    /* CARD STYLING */
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 35px;
        width: 100%;
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
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
        outline: none;
    }

    .btn-register {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }

    .btn-register:hover {
        background-color: var(--primary-teal);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 157, 145, 0.3);
        color: white;
    }

    #addressDetails {
        overflow: hidden;
        transition: all 0.4s ease;
        max-height: 0;
        opacity: 0;
    }

    .bi-info-circle-fill {
        color: var(--accent-teal) !important;
        cursor: help;
    }

    .login-link {
        color: var(--primary-teal);
        text-decoration: none;
        transition: 0.2s;
    }

    .login-link:hover {
        color: var(--accent-teal);
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Owner Registration</h1>
                <p>Create Your Account by Fill In All Details Below</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12"> <div class="custom-card">

                    <form method="post" action="../backend/ownerregister_b.php">
                        <div class="row g-4">

                            <div class="col-md-6 border-end pe-md-4">
                                <h6 class="section-subtitle"><i class="fas fa-user-circle"></i> Account Information</h6>

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="owner_name" class="form-control" 
                                        onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Username
                                        <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip" title="Must be unique."></i>
                                    </label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">
                                        Password
                                        <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip" 
                                           title="Min 6 chars, 1 uppercase and 1 symbol."></i>
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="showPw" onclick="togglePassword()">
                                    <label class="form-check-label text-muted small" for="showPw" style="cursor:pointer;">Show Password</label>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <h6 class="section-subtitle"><i class="fas fa-map-marker-alt"></i> Home Address (Optional)</h6>

                                <div class="mb-3">
                                    <label class="form-label">House No, Building, Street Name</label>
                                    <input type="text" id="street" name="street" class="form-control"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>

                                <div id="addressDetails">
                                    <div class="mb-3">
                                        <label class="form-label">Postal Code
                                            <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip" title="Example: 75150"></i> 
                                        </label>
                                        <input type="text" name="postcode" class="form-control" maxlength="5" placeholder="75150">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">City
                                            <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip" title="Example: BUKIT BARU"></i> 
                                        </label>
                                        <input type="text" name="city" class="form-control" placeholder="CITY NAME"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">State
                                            <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip" title="Example: MELAKA"></i> 
                                        </label>
                                        <input type="text" name="state" class="form-control" placeholder="STATE NAME"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-register">
                                    Register
                                </button>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <span class="text-muted">Already have an account?</span>
                                <a href="../frontend/userlogin.php" class="fw-bold login-link ms-1">Log in here</a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }

    const street = document.getElementById("street");
    const details = document.getElementById("addressDetails");

    street.addEventListener("input", () => {
        const show = street.value.trim() !== "";
        details.style.maxHeight = show ? "500px" : "0";
        details.style.opacity = show ? "1" : "0";
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>

<?php
include "../frontend/footer.php";
?>