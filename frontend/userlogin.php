<?php
//userlogin.php
session_start();
include "../frontend/header.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    /* LOGIN CARD STYLING */
    .login-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 40px;
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

    .btn-login {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        background-color: var(--primary-teal);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 157, 145, 0.2);
        color: white;
    }

    .reg-link {
        color: var(--primary-teal);
        text-decoration: none;
        transition: 0.2s;
    }

    .reg-link:hover {
        color: var(--accent-teal);
    }

    .login-icon {
        background-color: #e0f2f1;
        color: var(--accent-teal);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 20px auto;
    }
</style>

<main class="main py-5">
    <div class="page-header-custom">
        <div class="page-title">
            <h1>Login</h1>
            <p>Welcome back! Please Sign In To Continue</p>
        </div>
    </div>

    <section id="login" class="login section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="login-card">

                        <div class="login-icon">
                            <i class="fas fa-lock"></i>
                        </div>

                        <form action="../backend/verifylogin.php" method="post">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label text-muted small" for="showPassword" style="cursor:pointer;">
                                    Show Password
                                </label>
                            </div>

                            <button type="submit" class="btn btn-login w-100">
                                Login
                            </button>

                            <div class="text-center mt-4">
                                <p class="small text-muted mb-0">Don't have an account?
                                    <a href="../frontend/ownerregister.php" class="fw-bold reg-link ms-1">
                                        Register here
                                    </a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }
</script>

<?php
//error
if (isset($_SESSION['error_popup'])):
    $msg = json_encode($_SESSION['error_popup']);
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: <?= $msg ?>,
            confirmButtonColor: '#dc3545'
        });
    </script>
    <?php
    unset($_SESSION['error_popup']);
endif;
?>

<?php
include "../frontend/footer.php";
?>