<?php
// frontend/newpet.php
include "../backend/newpet_b.php";
include "../frontend/ownerheader.php";
?>

<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Register Pet</h1>
            <p class="text-muted">Fill in the details to register your pet.</p>
        </div>
    </div>

    <section class="pet section py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-10">
                    <div class="card shadow-lg rounded-4 p-4">

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

                            <div class="row g-4">

                                <!-- LEFT -->
                                <div class="col-md-6">

                                    <label class="form-label">Pet Name</label>
                                    <input type="text" name="pet_name" class="form-control"
                                        value="<?= htmlspecialchars($pet_name); ?>" required>

                                    <label class="form-label mt-3">Species</label>

                                    <select name="species" id="species" class="form-select" required>
                                        <option value="" disabled selected>Select Species</option>

                                        <?php foreach ($species_options as $sp): ?>
                                            <option value="<?= $sp ?>"
                                                <?= ($species == $sp) ? "selected" : "" ?>>
                                                <?= $sp ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div id="otherSpeciesDiv" style="display:none;" class="mt-2">
                                        <input type="text" name="other_species" class="form-control"
                                            placeholder="Specify species">
                                    </div>

                                    <label class="form-label mt-3">Breed</label>

                                    <select name="breed" id="breed" class="form-select">
                                        <option value="" disabled selected>Select Breed</option>
                                    </select>

                                    <div id="otherBreedDiv" style="display:none;" class="mt-2">
                                        <input type="text" name="other_breed" class="form-control"
                                            placeholder="Specify breed">
                                    </div>

                                    <label class="form-label mt-3">Gender</label>
                                    <div>
                                        <input type="radio" name="gender" value="Male"> Male
                                        <input type="radio" name="gender" value="Female"> Female
                                    </div>

                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">

                                    <label class="form-label">Color</label>
                                    <input type="text" name="color" class="form-control"
                                        value="<?= htmlspecialchars($color); ?>" required>

                                    <label class="form-label mt-3">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        max="<?= date("Y-m-d") ?>" required>

                                    <label class="form-label mt-3">Pet Image</label>
                                    <input type="file" name="pet_image" class="form-control">

                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button class="btn btn-primary px-5">Submit</button>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['success_popup'])): ?>
<script>
    Swal.fire({
        icon: "success",
        title: "Pet Registered!",
        text: "<?= $_SESSION['success_popup']; ?>",
    });
</script>
<?php unset($_SESSION['success_popup']); endif; ?>


<script>
    const speciesSelect = document.getElementById('species');
    const otherSpeciesDiv = document.getElementById('otherSpeciesDiv');
    const breedSelect = document.getElementById('breed');
    const otherBreedDiv = document.getElementById('otherBreedDiv');

    const breeds = {
        "Cat": ["Persian", "Siamese", "Maine Coon", "Ragdoll", "British Shorthair", "Bengal", "Sphynx", "Mixed", "Other"],
        "Dog": ["Husky", "Bulldog", "Golden Retriever", "Chihuahua", "Poodle", "Beagle", "German Shepherd", "Mixed", "Other"],
        // Animals without breed → empty array
        "Rabbit": [],
        "Hamster": [],
        "Guinea Pig": [],
        "Gerbil": [],
        "Ferret": [],
        "Bird": [],
        "Parrot": [],
        "Fish": [],
        "Turtle": [],
        "Iguana": [],
        "Lizard": [],
        "Snake": [],
        "Hedgehog": [],
        "Sugar Glider": [],
        "Chinchilla": [],
        "Frog": [],
        "Goat": [],
        "Sheep": [],
        "Chicken": [],
        "Duck": [],
        "Other": ["Other"]
    };

    speciesSelect.addEventListener('change', function () {

        otherSpeciesDiv.style.display = this.value === "Other" ? "block" : "none";

        breedSelect.innerHTML = `
            <option value="" selected disabled>Select Breed</option>
        `;

        const list = breeds[this.value];

        if (list && list.length > 0) {
            breedSelect.disabled = false;

            list.forEach(b => {
                const opt = document.createElement("option");
                opt.value = b;
                opt.text = b;
                breedSelect.appendChild(opt);
            });

        } else {
            
            breedSelect.disabled = true;
        }

        otherBreedDiv.style.display = "none";
    });

    
    breedSelect.addEventListener('change', function () {
        otherBreedDiv.style.display = (this.value === "Other") ? "block" : "none";
    });
</script>

<?php 
include "../frontend/footer.php"; 
?>
