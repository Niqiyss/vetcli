<?php
include "../frontend/adminheader.php";
include "../backend/vetupdate_b.php";
?>

<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Update Veterinarian</h1>
            <p class="text-muted">Modify the selected veterinarian</p>
        </div>
    </div>

    <section id="vet" class="vet section py-5">
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
                            <input type="hidden" name="update" value="<?= htmlspecialchars($vet_id); ?>">

                            <div class="row g-3">

                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="vet_name" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" required value="<?= htmlspecialchars($vet_name); ?>">

                                    <label class="form-label mt-3">Phone Number</label>
                                    <input type="text" name="phone_num" class="form-control" required
                                        value="<?= htmlspecialchars($phone_num); ?>">

                                    <label class="form-label mt-3">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?= htmlspecialchars($email); ?>">
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <label class="form-label">Specialization</label>
                                    <select name="specialization" class="form-select" required>
                                        <option value="" disabled>Select Specialization</option>
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
                                            $sel = ($specialization == $spec) ? "selected" : "";
                                            echo "<option value=\"$spec\" $sel>$spec</option>";
                                        }
                                        ?>
                                    </select>

                                    <label class="form-label mt-3">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($username); ?>">

                                    <label class="form-label mt-3">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required
                                        value="<?= htmlspecialchars($password); ?>">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" onclick="togglePassword()">
                                        <label class="form-check-label">Show Password</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <a href="../frontend/vetlist.php" class="btn btn-secondary px-5 me-2">Back</a>
                                <button type="submit" class="btn btn-primary px-5">Update</button>
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
        var x = document.getElementById("password");
        x.type = x.type === "password" ? "text" : "password";
    }
</script>

<?php
include "../frontend/footer.php";
?>
