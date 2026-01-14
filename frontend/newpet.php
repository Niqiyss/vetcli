<?php
//newpet.php

session_start();

include "../backend/newpet_b.php";
include "../frontend/ownerheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//success popup
if (isset($_SESSION['success_popup'])) {
    $msg = json_encode($_SESSION['success_popup']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Pet Registered!',
            text: $msg,
            confirmButtonColor: '#00798C'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

//error popup
if (!empty($formErrors)) {
    $html = '<ul style="text-align:left; margin-left:1rem;">';
    foreach ($formErrors as $e) {
        $html .= '<li>' . htmlspecialchars($e) . '</li>';
    }
    $html .= '</ul>';
    $html = json_encode($html);

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            html: $html,
            confirmButtonColor: '#dc3545'
        });
    </script>";
}
?>

<style>
    :root {
        
        --primary-teal: #00798C; 
        --accent-teal: #00798C;  
        
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

 
    .hero-section {
        background-color: var(--white);
        width: 100%;
        padding-top: 40px;
        padding-bottom: 10px;
       
        border-bottom: 3px solid var(--accent-teal); 
        margin-bottom: 40px;
    }

    .page-header-custom {
        text-align: center;
        margin-bottom: 10px;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal); 
        margin-bottom: 15px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }

    /* --- BADGE  --- */

    .badge-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 25px;
        padding-right: 10px;
    }

    .owner-badge {
        background-color: white;
        padding: 8px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(14, 92, 101, 0.08);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #eef2f2;
    }

    .owner-badge-icon {
        width: 32px;
        height: 32px;
        background-color: var(--accent-teal);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .owner-badge-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
        text-align: left;
    }

    .owner-badge-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .owner-badge-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-teal);
    }

    
    @media (max-width: 768px) {
        .badge-container {
            justify-content: center;
            margin-top: 15px;
        }
    }

    
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        width: 100%;
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--accent-teal);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-section-title i {
        margin-right: 8px;
        font-size: 16px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 121, 140, 0.1); 
        outline: none;
    }

    
    .form-check-input:checked {
        background-color: var(--accent-teal);
        border-color: var(--accent-teal);
    }

    
    .upload-box {
        border: 2px dashed #d1d9e6;
        border-radius: 12px;
        padding: 15px;
        background: #fafafa;
        text-align: center;
        transition: 0.3s;
    }

    .upload-box:hover {
        background: #f1f8e9;
        border-color: var(--accent-teal);
    }

    .upload-hint {
        font-size: 11px;
        color: var(--text-muted);
    }

    .img-preview {
        width: 100%;
        max-width: 200px;
        height: auto;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        display: none; 
    }

    .btn-submit {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background-color: #006070; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 121, 140, 0.2);
        color: white;
    }
</style>



<div class="hero-section">
    <div class="container">
        
        <div class="page-header-custom">
            <div class="page-title">
                <h1>Register Pet</h1>
                <p>Fill in the details below to register your new pet</p>
            </div>
        </div>

        <div class="badge-container">
            <div class="owner-badge">
                <div class="owner-badge-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="owner-badge-text">
                    <span class="owner-badge-label">Owner</span>
                    <span class="owner-badge-name"><?= htmlspecialchars($_SESSION['ownername'] ?? 'Owner'); ?></span>
                </div>
            </div>
        </div>

    </div>
</div>

<main class="main pb-5">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <div class="custom-card shadow border-0 rounded-4">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="row g-5">

                            <div class="col-md-6 border-end pe-md-5">
                                
                                <div class="form-section-title">
                                    <i class="fas fa-paw"></i> Basic Info
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pet Name</label>
                                    <input type="text" name="pet_name" class="form-control" placeholder="E.g. Oyen"
                                           onkeyup="this.value=this.value.toUpperCase();"
                                           value="<?= htmlspecialchars($pet_name ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Species</label>
                                    <select name="species" id="species" class="form-select" required>
                                        <option value="" disabled selected>Select Species</option>
                                        <optgroup label="Cats & Dogs">
                                            <option value="Cat">Cat</option>
                                            <option value="Dog">Dog</option>
                                        </optgroup>
                                        <optgroup label="Small Pets">
                                            <option value="Rabbit">Rabbit</option>
                                            <option value="Hamster">Hamster</option>
                                            <option value="Guinea Pig">Guinea Pig</option>
                                            <option value="Ferret">Ferret</option>
                                            <option value="Hedgehog">Hedgehog</option>
                                            <option value="Sugar Glider">Sugar Glider</option>
                                            <option value="Chinchilla">Chinchilla</option>
                                        </optgroup>
                                        <optgroup label="Birds">
                                            <option value="Parrot">Parrot</option>
                                            <option value="Owl">Owl</option>
                                        </optgroup>
                                        <optgroup label="Reptiles (Exotic Pets)">
                                            <option value="Turtle">Turtle</option>
                                            <option value="Iguana">Iguana</option>
                                            <option value="Lizard">Lizard</option>
                                            <option value="Snake">Snake</option>
                                        </optgroup>
                                        <optgroup label="Aquatic (Fish)">
                                            <option value="Goldfish">Goldfish</option>
                                            <option value="Koi">Koi</option>
                                            <option value="Guppy">Guppy</option>
                                            <option value="Arowana">Arowana</option>
                                        </optgroup>
                                        <optgroup label="Farm Animals">
                                            <option value="Goat">Goat</option>
                                            <option value="Sheep">Sheep</option>
                                            <option value="Chicken">Chicken</option>
                                            <option value="Duck">Duck</option>
                                        </optgroup>
                                        <optgroup label="Others">
                                            <option value="Other">Other</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div id="otherSpeciesDiv" style="display:none;" class="mb-3">
                                    <label class="form-label">Specify Species</label>
                                    <input type="text" name="other_species" class="form-control" placeholder="Enter species">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Breed</label>
                                    <select name="breed" id="breed" class="form-select">
                                        <option value="" disabled selected>Select Breed</option>
                                    </select>
                                </div>

                                <div id="otherBreedDiv" style="display:none;" class="mb-3">
                                    <label class="form-label">Specify Breed</label>
                                    <input type="text" name="other_breed" class="form-control" placeholder="Enter breed">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">Gender</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderM" value="Male">
                                        <label class="form-check-label" for="genderM">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderF" value="Female">
                                        <label class="form-check-label" for="genderF">Female</label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 ps-md-5">
                                
                                <div class="form-section-title">
                                    <i class="fas fa-info-circle"></i> Physical Details
                                </div>

                                    <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                            max="<?= date("Y-m-d") ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pet Image</label>
                                    <div class="upload-box">
                                        <input type="file" name="pet_image" id="petImage" class="form-control mb-2" accept="image/*">
                                        <p class="upload-hint mb-0"><i class="fas fa-image me-1"></i> JPG / PNG • Max 2MB</p>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <img id="previewImage" src="#" alt="Preview" class="img-preview">
                                </div>
                                <br>         

                                
                            </div>

                            <div class="col-12 text-center mt-5">
                                <button class="btn btn-submit">
                                Register Pet
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<script>
const speciesSelect = document.getElementById('species');
const otherSpeciesDiv = document.getElementById('otherSpeciesDiv');
const breedSelect = document.getElementById('breed');
const otherBreedDiv = document.getElementById('otherBreedDiv');

const breeds = {
    "Cat": ["Persian","Siamese","Maine Coon","Ragdoll","British Shorthair","Bengal","Sphynx","Mixed","Other"],
    "Dog": ["Husky","Bulldog","Golden Retriever","Chihuahua","Poodle","Beagle","German Shepherd","Mixed","Other"],
    "Rabbit": ["None"],
    "Hamster": ["None"],
    "Guinea Pig": ["None"],
    "Ferret": ["None"],
    "Hedgehog": ["None"],
    "Sugar Glider": ["None"],
    "Chinchilla": ["None"],
    "Parrot": ["None"],
    "Owl": ["None"],
    "Turtle": ["None"],
    "Iguana": ["None"],
    "Lizard": ["None"],
    "Snake": ["None"],
    "Goldfish": ["None"],
    "Koi": ["None"],
    "Guppy": ["None"],
    "Arowana": ["None"],
    "Goat": ["None"],
    "Sheep": ["None"],
    "Chicken": ["None"],
    "Duck": ["None"],
    "Other": ["None"]
};

speciesSelect.addEventListener('change', function () {

    otherSpeciesDiv.style.display = this.value === "Other" ? "block" : "none";

    breedSelect.innerHTML = `<option value="" disabled selected>Select Breed</option>`;

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

const petImageInput = document.getElementById("petImage");
const previewImage = document.getElementById("previewImage");

petImageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    if (!file.type.startsWith("image/")) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid File',
            text: 'Please select a valid image file.'
        });
        this.value = "";
        previewImage.style.display = "none";
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewImage.style.display = "block"; 
    };
    reader.readAsDataURL(file);
});
</script>

<?php 
include "../frontend/footer.php"; 
?>