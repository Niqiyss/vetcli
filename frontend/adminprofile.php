<?php
//adminprofile.php
include "../backend/adminprofile_b.php";
include "../frontend/adminheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//error popup
if (isset($_SESSION['error_popup'])) {
    $html = '<ul style="text-align:left;margin-left:1rem;">';
    foreach ($_SESSION['error_popup'] as $e) {
        $html .= '<li>' . htmlspecialchars($e) . '</li>';
    }
    $html .= '</ul>';

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            html: " . json_encode($html) . ",
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}

//success popup
if (isset($_SESSION['success_message'])) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            text: " . json_encode($_SESSION['success_message']) . ",
            timer: 1800,
            showConfirmButton: false,
            iconColor: '#00798C'
        });
    </script>";
    unset($_SESSION['success_message']);
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
        text-align: center;
    }

    .page-header-custom {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 15px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }


    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        width: 100%;
    }

    .form-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--accent-teal);
        margin-top: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-section-title i {
        margin-right: 10px;
        font-size: 18px;
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
        box-shadow: 0 0 0 3px rgba(0, 121, 140, 0.1);
        outline: none;
    }

    .form-control:disabled {
        background-color: #f9f9f9;
        color: #bbb;
    }

    .btn-update {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background-color: #006070;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 121, 140, 0.2);
        color: white;
    }

    .password-link {
        color: var(--primary-teal);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
    }

    .password-link:hover {
        color: var(--accent-teal);
        text-decoration: underline;
    }
</style>



<div class="hero-section">
    <div class="container">
        <div class="page-title">
            <h1>Admin Profile</h1>
            <p>Manage Your Account Information</p>
        </div>
    </div>
</div>

<main class="main pb-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="custom-card">

                    <form method="post">
                        <div class="row g-5">

                            <div class="col-md-6 border-end pe-md-5">
                                <div class="form-section-title">
                                    <i class="fas fa-shield-alt"></i> Account Info
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($admin['username']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" value="••••••••" disabled>
                                    <div class="mt-2">
                                        <a href="../frontend/change_password.php" class="password-link">
                                            <i class="fas fa-key me-1"></i> Change Password?
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-5">
                                <div class="form-section-title">
                                    <i class="fas fa-user-circle"></i> Personal Info
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="admin_name" class="form-control" required
                                        onkeyup="this.value=this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($admin['admin_name']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($admin['phone_num']); ?>">
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-update shadow-sm">
                                Update 
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "../frontend/footer.php";
?>