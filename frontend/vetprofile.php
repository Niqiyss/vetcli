<?php
include "../backend/vetprofile_b.php";
include "../frontend/vetheader.php";
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main">
    <div class="page-title text-center mt-4">
        <h1>Veterinarian Profile</h1>
        <p class="text-muted">Update your profile details</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 p-5">

                    <!-- SweetAlert for Errors -->
                    <?php if (!empty($formErrors)): ?>
                        <script>
                            Swal.fire({
                                icon: "error",
                                title: "Update Failed",
                                html: `<?= implode("<br>", array_map('htmlspecialchars', $formErrors)); ?>`
                            });
                        </script>
                    <?php endif; ?>

                    <!-- SweetAlert for Success -->
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

                    <form action="" method="post">
                        <div class="row g-4">

                            <!-- LEFT -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vet ID</label>
                                    <input type="text" class="form-control" readonly
                                        value="<?= htmlspecialchars($vet['vet_id']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="vet_name" class="form-control" required
                                        onkeyup="this.value = this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($vet['vet_name']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($vet['phone_num']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?= htmlspecialchars($vet['email']); ?>">
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Specialization</label>
                                    <select name="specialization" class="form-select" required>
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
                                            $sel = ($vet['specialization'] == $spec) ? "selected" : "";
                                            echo "<option value=\"$spec\" $sel>$spec</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Availability</label>
                                    <select name="availability" class="form-select" required>
                                        <?php
                                        $availabilities = [
                                            "Mon-Fri (9am-5pm)",
                                            "Mon-Wed (9am-5pm)",
                                            "Thu-Fri (9am-5pm), Sat (9am-1pm)",
                                            "Mon-Sun (on-call)",
                                            "Tue-Thu (9am-5pm)",
                                            "Mon-Sat (9am-5pm)"
                                        ];
                                        foreach ($availabilities as $a) {
                                            $sel = ($vet['availability'] == $a) ? "selected" : "";
                                            echo "<option value=\"$a\" $sel>$a</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($vet['username']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required
                                        value="<?= htmlspecialchars($vet['password']); ?>">
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
