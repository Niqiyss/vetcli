<?php
//vetregister.php

session_start();
include "../frontend/adminheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//success popup logic
if (isset($_SESSION['success_popup'])) {
    $msg = json_encode($_SESSION['success_popup']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: $msg,
            confirmButtonColor: '#009d91'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

//error popup logic
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
        --primary-teal: #0e5c65;
        --accent-teal: #009d91;
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

    /* MATCHING HEADER CSS FROM VETLIST */
    .page-header-custom {
        margin-bottom: 30px;
        display: flex;
        justify-content: center; /* Centered as requested */
        text-align: center;      /* Centered as requested */
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

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
        outline: none;
    }

    .upload-box {
        border: 2px dashed #d1d9e6;
        border-radius: 12px;
        padding: 12px;
        background: #fafafa;
    }

    .upload-hint {
        font-size: 11px;
        color: var(--text-muted);
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

    .bi-info-circle-fill {
        color: var(--accent-teal) !important;
        cursor: help;
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Veterinarian Registration</h1>
                <p>Create An Account For Veterinary staff</p>
            </div>
        </div>

        <div class="custom-card shadow border-0 rounded-4">

            <form method="POST" action="../backend/vetregister_b.php" enctype="multipart/form-data">

                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="vet_name" class="form-control" placeholder="DR "
                                onkeyup="this.value=this.value.toUpperCase();" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_num" class="form-control" placeholder="01X-XXXXXXX" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Veterinarian Image</label>
                            <div class="upload-box">
                                <input type="file" name="vet_image" class="form-control" accept="image/*">
                                <p class="upload-hint mt-2 mb-0"><i class="fas fa-image me-1"></i>JPG / PNG • Max 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
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
                            <label class="form-label">
                                Username
                                <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip"
                                    title="Must be unique."></i>
                            </label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">
                                Password
                                <i class="bi bi-info-circle-fill ms-1" data-bs-toggle="tooltip"
                                    title="Minimum 6 characters, 1 uppercase letter and 1 symbol."></i>
                            </label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="toggleCheck" onclick="togglePassword()">
                            <label class="form-check-label text-muted small" for="toggleCheck" style="cursor: pointer;">Show Password</label>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-register">
                     Register
                    </button>
                </div>

            </form>

        </div>
    </div>
</main>

<script>
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<?php
include "../frontend/footer.php";
?>