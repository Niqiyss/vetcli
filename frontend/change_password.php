<?php
session_start();

//role detect
if (isset($_SESSION['adminID'])) {
    include "../frontend/adminheader.php";
    $cancelUrl = "adminprofile.php";
} elseif (isset($_SESSION['vetID'])) {
    include "../frontend/vetheader.php";
    $cancelUrl = "vetprofile.php";
} elseif (isset($_SESSION['ownerID'])) {
    include "../frontend/ownerheader.php";
    $cancelUrl = "ownerprofile.php";
} else {
    header("Location: userlogin.php");
    exit();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        width: 100%;
        position: relative; 
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

    
    .btn-update {
        background-color: var(--accent-teal);
        border: none;
        padding: 10px 30px;
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

    .btn-cancel {
        background-color: transparent;
        border: 2px solid #e0e0e0;
        color: var(--text-muted);
        padding: 10px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        margin-right: 10px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-cancel:hover {
        background-color: #f8f9fa;
        color: #333;
        border-color: #ccc;
    }

    .bi-info-circle-fill {
        color: var(--accent-teal) !important;
        cursor: help;
        display: inline-block; 
    }
    
    .lock-icon {
        font-size: 40px;
        color: var(--accent-teal);
        margin-bottom: 20px;
        background: #e0f7fa; 
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-left: auto;
        margin-right: auto;
    }
</style>



<div class="hero-section">
    <div class="container">
        <div class="page-title">
            <h1>Change Password</h1>
            <p>Change new password</p>
        </div>
    </div>
</div>


<main class="main pb-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="custom-card shadow border-0 rounded-4">
                    
                    <div class="lock-icon">
                        <i class="fas fa-lock"></i>
                    </div>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: <?= json_encode($_SESSION['error_message']); ?>,
                                confirmButtonColor: '#dc3545'
                            });
                        </script>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: <?= json_encode($_SESSION['success_message']); ?>,
                                timer: 2000,
                                showConfirmButton: false,
                                iconColor: '#00798C'
                            });
                        </script>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <form action="../backend/change_password_b.php" method="post">

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                New Password
                                <i class="bi bi-info-circle-fill ms-1" 
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="right"
                                   title="Minimum 6 characters, 1 uppercase letter and 1 symbol.">
                                </i>
                            </label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <div class="text-center">
                            <a href="<?= $cancelUrl ?>" class="btn btn-cancel">
                                Back
                            </a>

                            <button type="submit" class="btn btn-update">
                                Update
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</main>

<script>
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<?php
include "../frontend/footer.php";
?>