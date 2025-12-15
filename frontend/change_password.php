<?php
session_start();

/* Detect role */
if (isset($_SESSION['vetID'])) {
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main">

    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Change Password</h1>
            <p class="text-muted">Change your password below.</p>
        </div>
    </div>


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow border-0 rounded-4 p-4">

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '<?= htmlspecialchars($_SESSION['error_message']); ?>'
                            });
                        </script>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: '<?= htmlspecialchars($_SESSION['success_message']); ?>',
                                timer: 2000,
                                showConfirmButton: false
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
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <div class="text-center">
                            <a href="<?= $cancelUrl ?>" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
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