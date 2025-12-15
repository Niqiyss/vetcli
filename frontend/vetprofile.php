<?php
include "../backend/vetprofile_b.php";
include "../frontend/vetheader.php";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* profile */
    .profile-side {
        border-left: 1px solid #eee;
        padding-left: 20px;
    }

    .profile-img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #f1f1f1;
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1A2A5A;
        margin-top: 18px;
        margin-bottom: 8px;
        border-bottom: 1px solid #eee;
        padding-bottom: 4px;
    }

    .form-control {
        padding: 8px 12px;
        font-size: 14px;
    }

    .upload-box {
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 10px;
        background: #fafafa;
    }

    .upload-hint {
        font-size: 12px;
        color: #777;
    }

    .card {
        padding: 30px !important;
    }
</style>

<main class="main">

    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Veterinarian Profile</h1>
            <p class="text-muted">Manage your personal information</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                <div class="card shadow-lg border-0 rounded-4">

                    <?php if (!empty($formErrors)): ?>
                        <script>
                            Swal.fire({
                                icon: "error",
                                title: "Update Failed",
                                html: `<?= implode("<br>", array_map('htmlspecialchars', $formErrors)); ?>`
                            });
                        </script>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <script>
                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: "<?= $_SESSION['success_message']; ?>",
                                timer: 1800,
                                showConfirmButton: false
                            });
                        </script>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-4">

                            <!-- LEFT -->
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
                                </div><br>

                                <div class="form-section-title">Account Information</div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required
                                            value="<?= htmlspecialchars($vet['username']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" value="********" disabled>
                                        <small class="text-muted">
                                            <a href="../frontend/change_password.php">
                                                Change Password
                                            </a>
                                        </small>
                                    </div>

                                </div>

                                <div class="form-section-title">Profile Image</div>

                                <div class="upload-box">
                                    <input type="file" name="vet_image" class="form-control" accept="image/*">
                                    <p class="upload-hint mt-1 mb-0">JPG / PNG • Max 2MB</p>
                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-4 profile-side text-center">
                                <br><br>
                                <img src="../uploads/vets/<?= htmlspecialchars($vet['vet_image'] ?? 'default.png'); ?>"
                                    class="profile-img shadow mb-2">
                                <br><br>
                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($vet['vet_name']); ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($vet['specialization']); ?></small>
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary px-4">Update Profile</button>
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