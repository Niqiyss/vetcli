<?php
include "../frontend/header.php";
?>


<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Login</h1>
            <p class="text-muted">Welcome back! Please sign in to continue.</p>
        </div>
    </div>

    <section id="login" class="login section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card shadow-lg border-0 rounded-4 p-4">
                        <form action="../backend/verifylogin.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="showPassword"
                                    onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>

                            <div class="text-center mt-3">
                                <p>Don't have an account?
                                    <a href="../frontend/ownerregister.php" class="text-primary fw-bold">Register
                                        here</a>
                                </p>
                            </div>
                        </form>

                        <script>
                            function togglePassword() {
                                var x = document.getElementById("password");
                                x.type = (x.type === "password") ? "text" : "password";
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include "../frontend/footer.php";
?>