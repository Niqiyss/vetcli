<?php
include "../frontend/header.php";
include "../backend/ownerregister_b.php";
?>

<!-- SWEETALERT2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//SweetAlert2 success popup
if (isset($_SESSION['success_popup'])) {
    $msg = $_SESSION['success_popup'];
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful!',
            text: '$msg',
            confirmButtonColor: '#0d6efd',
        });
    </script>
    ";
    unset($_SESSION['success_popup']);
}
?>

<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Owner Registration</h1>
            <p class="text-muted">Please fill in your details to register as a pet owner.</p>
        </div>
    </div>

    <section id="owner" class="appointment section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4 p-4">

                        <?php if (!empty($formErrors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($formErrors as $error): ?>
                                        <li><?= htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="" method="post">
                            <div class="row g-3">

                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="owner_name" class="form-control"
                                        onkeyup="this.value=this.value.toUpperCase();" required
                                        value="<?= htmlspecialchars($owner_name); ?>">

                                    <label class="form-label mt-3">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($phone_num); ?>">

                                    <label class="form-label mt-3">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?= htmlspecialchars($email); ?>">
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <label class="form-label">Home Address</label>
                                    <input type="text" name="address" class="form-control" required
                                        value="<?= htmlspecialchars($address); ?>">

                                    <label class="form-label mt-3">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($username); ?>">

                                    <label class="form-label mt-3">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>

                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="showPassword"
                                            onclick="togglePassword()">
                                        <label class="form-check-label" for="showPassword">Show Password</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="btn btn-primary px-5">Register</button>
                                </div>

                                <div class="col-12 text-center mt-3">
                                    <p class="mb-0">Already have an account?
                                        <a href="../frontend/userlogin.php" class="text-primary fw-bold">Log in</a>
                                    </p>
                                </div>

                                <script>
                                    function togglePassword() {
                                        var pwField = document.getElementById("password");
                                        pwField.type = pwField.type === "password" ? "text" : "password";
                                    }
                                </script>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include "../frontend/footer.php";
?>
