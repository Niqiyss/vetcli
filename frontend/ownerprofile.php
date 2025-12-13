<?php
include "../backend/ownerprofile_b.php";
include "../frontend/ownerheader.php";

?>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main">
    <div class="page-title text-center mt-4">
        <h1>Owner Profile</h1>
        <p class="text-muted">Update your profile details</p>
    </div>

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

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 p-5">

                    <form action="" method="post">
                        <div class="row g-4">

                            <!-- LEFT -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="owner_name" class="form-control" required
                                        onkeyup="this.value = this.value.toUpperCase();"
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
                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" required
                                        value="<?= htmlspecialchars($owner['address']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($owner['username']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required
                                        value="<?= htmlspecialchars($owner['password']); ?>">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" onclick="togglePassword()">
                                        <label class="form-check-label">Show Password</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5">Update</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword() {
        var x = document.getElementById("password");
        x.type = x.type === "password" ? "text" : "password";
    }
</script>

<?php 
include "../frontend/footer.php"; 
?>
