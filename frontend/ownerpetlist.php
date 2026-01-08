<?php
//ownerpetlist.php
include "../frontend/ownerheader.php";
include "../backend/ownerpetlist_b.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// SUCCESS POPUP
if (isset($_SESSION['success_popup'])) {
    $msg = json_encode($_SESSION['success_popup']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: $msg,
            confirmButtonColor: '#009d91'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

// ERROR POPUP
if (isset($_SESSION['error_popup'])) {
    $msg = json_encode($_SESSION['error_popup']);
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: $msg,
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}
?>


<style>
    :root {
        --primary-teal: #0e5c65;
        --accent-teal: #009d91;
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }

    /* CENTERED HEADER */
    .page-header-custom {
        margin-bottom: 40px;
        display: flex;
        justify-content: center; 
        text-align: center;      
    }

    .page-title h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 5px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }

    /* PET CARD STYLING */
    .pet-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(14, 92, 101, 0.15);
    }

    .pet-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .pet-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .pet-card:hover .pet-image-wrapper img {
        transform: scale(1.05);
    }

    .pet-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .pet-name {
        color: var(--primary-teal);
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 5px;
    }

    .pet-badge {
        background-color: #e0f2f1;
        color: var(--accent-teal);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: inline-block;
    }

    .pet-details {
        width: 100%;
        margin-bottom: 15px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #f4f7f6;
        font-size: 14px;
        color: #555;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .btn-edit-card {
        margin-top: auto;
        width: 100%;
        background-color: white;
        border: 1px solid var(--accent-teal);
        color: var(--accent-teal);
        padding: 8px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-edit-card:hover {
        background-color: var(--accent-teal);
        color: white;
    }

    /* MODAL STYLING */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 20px 25px;
    }

    .modal-title {
        color: var(--primary-teal);
        font-weight: 700;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #888;
    }

    .btn-save-modal {
        background-color: var(--accent-teal);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-save-modal:hover {
        background-color: var(--primary-teal);
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>My Pets</h1>
                <p>These are All your Registered Pet</p>
            </div>
        </div>

        <section id="petlist">
            <div class="row justify-content-center">

                <?php if (!empty($pets)): ?>
                    <?php foreach ($pets as $pet): ?>

                        <?php
                        $img = !empty($pet['pet_image'])
                            ? "../uploads/pets/" . htmlspecialchars($pet['pet_image'])
                            : "../uploads/pets/default.png";
                        ?>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="pet-card">
                                
                                <div class="pet-image-wrapper">
                                    <img src="<?= $img ?>" alt="Pet Image">
                                </div>

                                <div class="pet-body">
                                    <h5 class="pet-name"><?= htmlspecialchars($pet['pet_name']); ?></h5>
                                    
                                    <span class="pet-badge">
                                        <?= htmlspecialchars($pet['species']); ?> 
                                        <?= !empty($pet['breed']) ? "• " . htmlspecialchars($pet['breed']) : "" ?>
                                    </span>

                                    <div class="pet-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Gender</span>
                                            <span><?= htmlspecialchars($pet['gender']); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Color</span>
                                            <span><?= htmlspecialchars($pet['color']); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Date of Birth</span>
                                            <span><?= date("d M Y", strtotime($pet['dob'])); ?></span>
                                        </div>
                                    </div>

                                    <button class="btn btn-edit-card" data-bs-toggle="modal"
                                        data-bs-target="#editPetModal<?= $pet['pet_id']; ?>">
                                        <i class="fas fa-edit me-2"></i> Edit Details
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editPetModal<?= $pet['pet_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pet Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form method="post" action="../backend/pet_update_b.php" enctype="multipart/form-data">
                                        <div class="modal-body p-4">

                                            <input type="hidden" name="pet_id" value="<?= $pet['pet_id']; ?>">

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label class="form-label">Pet Name</label>
                                                    <input type="text" name="pet_name" class="form-control"
                                                        onkeyup="this.value=this.value.toUpperCase();" required
                                                        value="<?= htmlspecialchars($pet['pet_name']); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Species</label>
                                                    <input type="text" class="form-control" readonly
                                                        value="<?= htmlspecialchars($pet['species']); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Breed</label>
                                                    <input type="text" class="form-control" readonly
                                                        value="<?= htmlspecialchars($pet['breed']); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="form-select">
                                                        <option value="Male" <?= $pet['gender'] == "Male" ? "selected" : "" ?>>Male</option>
                                                        <option value="Female" <?= $pet['gender'] == "Female" ? "selected" : "" ?>>Female</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Color</label>
                                                    <input type="text" name="color" class="form-control"
                                                        value="<?= htmlspecialchars($pet['color']); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" name="dob" class="form-control"
                                                        value="<?= $pet['dob']; ?>">
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Pet Image (Optional)</label>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <img src="<?= $img ?>" class="rounded-3 me-3" 
                                                             style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd;">
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="pet_image" class="form-control mb-1">
                                                            <small class="text-muted">Upload a new image to replace the current one.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-outline-secondary border-0" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button class="btn btn-save-modal px-4">
                                                Update Pet
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-paw fa-3x" style="color: #ddd;"></i>
                        </div>
                        <h5 class="text-muted">No pets found.</h5>
                        <p class="mb-4">You haven't registered any pets yet.</p>
                        <a href="../frontend/newpet.php" class="btn btn-save-modal">
                            <i class="fas fa-plus me-2"></i> Register New Pet
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </div>
</main>

<?php
include "../frontend/footer.php";
?>