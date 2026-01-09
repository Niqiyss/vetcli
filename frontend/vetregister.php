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
            confirmButtonColor: '#009d91'
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
        --primary-teal: #0e5c65;
        --accent-teal: #009d91;
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

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
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 15px;
    }

    .custom-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 35px;
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
    }

    .upload-box {
        border: 2px dashed #d1d9e6;
        border-radius: 12px;
        padding: 12px;
        background: #fafafa;
    }

    .btn-register {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
    }

    .bi-info-circle-fill {
        color: var(--accent-teal) !important;
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Veterinarian Registration</h1>
                <p>Create An Account For Veterinary Staff</p>
            </div>
        </div>

        <div class="custom-card">

            <form method="POST" action="../backend/vetregister_b.php" enctype="multipart/form-data">
                <div class="row g-4">

                    <div class="col-md-6">
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
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>

                            <!-- ✅ PASSWORD RULES -->
                            <ul class="small mt-2" style="list-style:none;padding-left:0;">
                                <li id="pw-length" style="color:red;">✗ At least 6 characters</li>
                                <li id="pw-upper" style="color:red;">✗ At least 1 uppercase letter</li>
                                <li id="pw-symbol" style="color:red;">✗ At least 1 symbol</li>
                            </ul>
                        </div>

                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" onclick="togglePassword()">
                            <label class="form-check-label text-muted small">Show Password</label>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-register">Register</button>
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

    /* ✅ LIVE PASSWORD CHECK */
    const pw = document.getElementById("password");
    const pwLen = document.getElementById("pw-length");
    const pwUp = document.getElementById("pw-upper");
    const pwSym = document.getElementById("pw-symbol");

    pw.addEventListener("input", () => {
        pwLen.style.color = pw.value.length >= 6 ? "green" : "red";
        pwLen.innerHTML = (pw.value.length >= 6 ? "✓" : "✗") + " At least 6 characters";

        pwUp.style.color = /[A-Z]/.test(pw.value) ? "green" : "red";
        pwUp.innerHTML = (/[A-Z]/.test(pw.value) ? "✓" : "✗") + " At least 1 uppercase letter";

        pwSym.style.color = /[\W_]/.test(pw.value) ? "green" : "red";
        pwSym.innerHTML = (/[\W_]/.test(pw.value) ? "✓" : "✗") + " At least 1 symbol";
    });
</script>

<?php
include "../frontend/footer.php";
?>