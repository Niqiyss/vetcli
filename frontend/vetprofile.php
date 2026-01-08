<?php
//vetprofile.php
include "../backend/vetprofile_b.php";
include "../frontend/vetheader.php";
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
            iconColor: '#009d91'
        });
    </script>";
    unset($_SESSION['success_message']);
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

    /* CENTERED HEADER MATCHING OTHER PAGES */
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
        padding: 40px;
        width: 100%;
    }

    /* PROFILE IMAGE SECTION */
    .profile-side {
        border-left: 1px solid #f0f0f0;
        padding-left: 30px;
    }

    .profile-img {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 20px; /* Modern rounded square look */
        border: 5px solid #fff;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--accent-teal);
        margin-top: 25px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
        margin-left: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
        outline: none;
    }

    .form-control[readonly], .form-control:disabled {
        background-color: #f8f9fa;
        color: #999;
    }

    .upload-box {
        border: 2px dashed #d1d9e6;
        border-radius: 12px;
        padding: 15px;
        background: #fafafa;
    }

    .upload-hint {
        font-size: 11px;
        color: var(--text-muted);
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
        background-color: var(--primary-teal);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 157, 145, 0.2);
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

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Veterinarian Profile</h1>
                <p>Manage Your Personal and Account Information</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="custom-card shadow border-0 rounded-4">

                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-4">

                            <div class="col-md-8">
                                
                                <div class="form-section-title">Personal Information</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="vet_name" class="form-control" required
                                            onkeyup="this.value=this.value.toUpperCase();"
                                            value="<?= htmlspecialchars($vet['vet_name']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone_num" class="form-control" required
                                            value="<?= htmlspecialchars($vet['phone_num']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required
                                            value="<?= htmlspecialchars($vet['email']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Specialization</label>
                                        <input type="text" class="form-control" readonly
                                            value="<?= htmlspecialchars($vet['specialization']); ?>">
                                    </div>
                                </div>

                                <div class="form-section-title">Account Information</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required
                                            value="<?= htmlspecialchars($vet['username']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" value="••••••••" disabled>
                                        <div class="mt-2">
                                            <a href="../frontend/change_password.php" class="password-link">
                                                <i class="fas fa-key me-1"></i> Change Password
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-title">Update Profile Image</div>
                                <div class="upload-box mb-3">
                                    <input type="file" name="vet_image" class="form-control" accept="image/*">
                                    <p class="upload-hint mt-2 mb-0"><i class="fas fa-info-circle me-1"></i> JPG / PNG • Max 2MB</p>
                                </div>

                            </div>

                            <div class="col-md-4 profile-side text-center d-flex flex-column align-items-center justify-content-center">
                                <img src="../uploads/vets/<?= htmlspecialchars($vet['vet_image'] ?? 'default.png'); ?>"
                                    class="profile-img mb-3">
                                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($vet['vet_name']); ?></h5>
                                <span class="badge bg-light text-success border px-3 py-2 rounded-pill">
                                    <?= htmlspecialchars($vet['specialization']); ?>
                                </span>
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