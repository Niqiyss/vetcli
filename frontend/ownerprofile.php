<?php
include "../backend/ownerprofile_b.php";
include "../frontend/ownerheader.php";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1A2A5A;
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 6px;
    }

    .card {
        padding: 35px !important;
    }

    .form-control {
        font-size: 14px;
        padding: 8px 12px;
    }
</style>

<main class="main">

    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
        <h1>Owner Profile</h1>
        <p class="text-muted">Manage your personal information</p>
        </div>
    </div>

    <?php if (!empty($formErrors)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                html: `<?= implode("<br>", array_map('htmlspecialchars', $formErrors)); ?>`
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: '<?= htmlspecialchars($_SESSION['success_message']); ?>',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card shadow-lg border-0 rounded-4">

                    <form method="post">
                        <div class="row g-4">

                            <!-- LEFT -->
                            <div class="col-md-6">

                                <div class="form-section-title">Personal Information</div>

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="owner_name" class="form-control" required
                                        onkeyup="this.value=this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($owner['owner_name']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($owner['phone_num']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?= htmlspecialchars($owner['email']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" required
                                        value="<?= htmlspecialchars($owner['address']); ?>">
                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <div class="form-section-title">Account Information</div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($owner['username']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" value="********" disabled>
                                    <small class="text-muted">
                                        <a href="../frontend/change_password.php">
                                            Change Password
                                        </a>
                                    </small>
                                </div>

                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Update Profile
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
