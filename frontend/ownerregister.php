<?php
// ownerregister.php
session_start();
include "../frontend/header.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// success popup
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

// error popup
if (isset($_SESSION['error_popup'])) {
    $errors = explode('\n', $_SESSION['error_popup']);
    $html = '<ul style=\"text-align:left; margin-left:1rem;\">';
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

        --primary-teal: #137c85; 
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
        margin-bottom: 5px;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 15px;
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

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 14px;
        border: 1px solid #e0e0e0;
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
        transition: 0.3s;
    }

    .btn-register:hover {
        background-color: var(--primary-teal);
        transform: translateY(-2px);
    }

    #addressDetails {
        transition: all 0.4s ease;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
    }

    .login-link {
        color: var(--primary-teal);
        text-decoration: none;
        font-weight: 600;
    }

    .login-link:hover {
        color: var(--accent-teal);
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




<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Owner Registration</h1>
                <p>Create Your Account by Fill In All Details Below</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="custom-card">

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
                                    <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>

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

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="showPw" onclick="togglePassword()">
                                    <label class="form-check-label text-muted small" for="showPw" style="cursor:pointer;">
                                        Show Password
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <h6 class="section-subtitle"><i class="fas fa-map-marker-alt"></i> Home Address
                                    (Optional)</h6>

                                <div class="mb-3">
                                    <label class="form-label">House No, Building, Street Name</label>
                                    <input type="text" id="street" name="street" class="form-control"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>

                                <div id="addressDetails">
                                    <div class="mb-3">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="postcode" class="form-control" maxlength="5">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state" class="form-control"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-register">Register</button>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <span class="text-muted">Already have an account?</span>
                                <a href="../frontend/userlogin.php" class="login-link ms-1">Log in here</a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>


<script>
    // Toggle Password Visibility
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }

    // Address Slide Down Logic
    const street = document.getElementById("street");
    const details = document.getElementById("addressDetails");

    street.addEventListener("input", () => {
        const show = street.value.trim() !== "";
        details.style.maxHeight = show ? "500px" : "0";
        details.style.opacity = show ? "1" : "0";
    });


    // LIVE PASSWORD CHECK
    const passwordInput = document.getElementById("password");

    // Elements
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
            // Invalid/Default State
            element.classList.remove("valid");
            icon.classList.remove("bi-check-circle-fill");
            icon.classList.add("bi-circle");
        }
    }

    passwordInput.addEventListener("input", () => {
        const val = passwordInput.value;

        // 1. Length Check
        updateStatus(val.length >= 6, reqLength, iconLength);

        // 2. Uppercase Check
        updateStatus(/[A-Z]/.test(val), reqUpper, iconUpper);

        // 3. Symbol Check
        updateStatus(/[\W_]/.test(val), reqSymbol, iconSymbol);
    });
</script>

<?php
include "../frontend/footer.php";
?>