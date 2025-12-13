<?php
include "../backend/adminprofile_b.php";
include "../frontend/adminheader.php";
?>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main">
    <div class="page-title text-center mt-4">
        <h1>Admin Profile</h1>
        <p class="text-muted">Update your profile details</p>
    </div>

    <!-- ERROR ALERT -->
    <?php if (!empty($formErrors)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                html: `<?= implode("<br>", array_map('htmlspecialchars', $formErrors)); ?>`,
                confirmButtonColor: '#d33'
            });
        </script>
    <?php endif; ?>

    <!-- SUCCESS ALERT -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: '<?= htmlspecialchars($_SESSION['success_message']); ?>',
                confirmButtonColor: '#3085d6'
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <section class="section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4 p-4">

                        <form action="" method="post">

                            <div class="row g-3">

                                <!-- LEFT -->
                                <div class="col-md-6">

                                    <label class="form-label">Admin ID</label>
                                    <input type="text" class="form-control" readonly
                                        value="<?= htmlspecialchars($admin['admin_id']); ?>">

                                    <label class="form-label mt-3">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($admin['username']); ?>">

                                    <label class="form-label mt-3">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" required
                                        value="<?= htmlspecialchars($admin['password']); ?>">
                                    <input type="checkbox" onclick="togglePassword()"> Show Password

                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">

                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="admin_name" class="form-control" required
                                        onkeyup="this.value = this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($admin['admin_name']); ?>">

                                    <label class="form-label mt-3">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($admin['phone_num']); ?>">

                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="btn btn-primary px-5">Update</button>
                                </div>

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
    const x = document.getElementById("password");
    x.type = x.type === "password" ? "text" : "password";
}
</script>

<?php 
include "../frontend/footer.php"; 
?>
