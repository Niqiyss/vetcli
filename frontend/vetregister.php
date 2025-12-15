<?php
include "../backend/vetregister_b.php";
include "../frontend/adminheader.php";
?>

<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_SESSION['success_popup'])) {
    $msg = $_SESSION['success_popup'];
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: '$msg',
            confirmButtonColor: '#0d6efd'
        });
    </script>
    ";
    unset($_SESSION['success_popup']);
}
?>

<main class="main">

    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Register Veterinarian</h1>
            <p class="text-muted">Fill in the details to register a new veterinarian</p>
        </div>
    </div>

    <section class="vet section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4 p-4">

                        <!-- Errors -->
                        <?php if (!empty($formErrors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($formErrors as $error): ?>
                                        <li><?= htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">

                            <div class="row g-3">

                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="vet_name" class="form-control" required
                                        onkeyup="this.value = this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($vet_name ?? '') ?>">

                                    <label class="form-label mt-3">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($phone_num ?? '') ?>">

                                    <label class="form-label mt-3">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?= htmlspecialchars($email ?? '') ?>">


                                    <label class="form-label mt-3">Veterinarian Image</label>
                                    <input type="file" name="vet_image" class="form-control" accept="image/*">
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <label class="form-label">Specialization</label>
                                    <select name="specialization" class="form-select" required>
                                        <option value="" disabled selected>Select Specialization</option>
                                        <?php
                                        $specializations = [
                                            "General Veterinary Care",
                                            "Surgery & Orthopedics",
                                            "Vaccination & Preventive Care",
                                            "Dermatology & Skin Issues",
                                            "Emergency & Critical Care",
                                            "Internal Medicine",
                                            "Dentistry",
                                            "Ophthalmology",
                                            "Neurology",
                                            "Cardiology",
                                            "Nutrition & Weight Management",
                                            "Reproduction & Fertility"
                                        ];
                                        foreach ($specializations as $spec) {
                                            $sel = (($specialization ?? '') === $spec) ? "selected" : "";
                                            echo "<option value=\"$spec\" $sel>$spec</option>";
                                        }
                                        ?>
                                    </select>

                                    <label class="form-label mt-3">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($username ?? '') ?>">

                                    <label class="form-label mt-3">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>

                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="showPassword"
                                            onclick="togglePassword()">
                                        <label class="form-check-label" for="showPassword">Show Password</label>
                                    </div>

                                    <script>
                                        function togglePassword() {
                                            const pwField = document.getElementById("password");
                                            pwField.type = pwField.type === "password" ? "text" : "password";
                                        }
                                    </script>

                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-5">
                                    Register
                                </button>
                            </div>

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