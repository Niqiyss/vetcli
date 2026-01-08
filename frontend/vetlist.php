<?php
//vetlist.php

include "../frontend/adminheader.php";
include "../backend/vetlist_b.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* VETCLINIC THEME STYLES */
    :root {
        --primary-teal: #0e5c65; /* Dark teal for text */
        --accent-teal: #009d91;  /* Bright teal for buttons/icons */
        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
        color: #333;
    }

    /* Page Header */
    .page-header-custom {
        margin-bottom: 30px;
        display: center;
        justify-content: space-between;
        align-items: flex-end;
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

    /* Main Card */
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .card-title-custom {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
    }

    .card-title-icon {
        background-color: var(--accent-teal);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 14px;
    }

    /* Search Bar Styled to Match Theme */
    .vet-search-wrapper {
        position: relative;
        width: 300px;
    }

    .vet-search-input {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px 10px 40px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .vet-search-input:focus {
        border-color: var(--accent-teal);
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 157, 145, 0.1);
    }

    .search-icon-btn {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        z-index: 5;
    }
    
    .search-icon-btn:hover {
        color: var(--accent-teal);
    }

    /* Table Styling */
    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-custom thead th {
        border: none;
        color: var(--accent-teal);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px;
        background: transparent;
        white-space: nowrap;
    }

    .table-custom tbody tr {
        background-color: white;
        transition: transform 0.2s;
    }

    .table-custom tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .table-custom td {
        padding: 15px 15px;
        border-top: 1px solid #f8f9fa;
        vertical-align: middle;
        font-size: 14px;
        font-weight: 500;
        color: #444;
    }

    .table-custom td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .table-custom td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    /* Avatar & Badges */
    .vet-img {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .spec-badge {
        background: #e0f7fa;
        color: #006064;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    /* Action Buttons */
    .btn-icon-danger {
        color: #dc3545;
        background: #fff5f5;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        cursor: pointer;
    }

    .btn-icon-danger:hover {
        background: #dc3545;
        color: white;
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Veterinarian List</h1>
                <p>Manage Registered Veterinarians Details</p>
            </div>
        </div>

        <div class="custom-card">
            
            <div class="card-header-custom flex-wrap gap-3">
                <div class="card-title-custom">
                    <div class="card-title-icon">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    Vets List
                </div>

                <form class="vet-search-wrapper" method="get" action="vetlist.php">
                    <button type="submit" class="search-icon-btn">
                        <i class="bi bi-search"></i>
                    </button>
                    <input class="vet-search-input" type="search" name="txtsearch" placeholder="Search veterinarians..."
                        value="<?= htmlspecialchars($search) ?>">
                </form>
            </div>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4"><?= $errorMsg ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="80">Photo</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Specialization</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($vets)): ?>
                            <?php foreach ($vets as $vet): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/vets/<?= htmlspecialchars($vet['vet_image'] ?? 'default.png') ?>"
                                            class="vet-img" alt="Vet">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($vet['vet_name']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($vet['phone_num']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($vet['email']) ?></td>
                                    <td>
                                        <span class="spec-badge">
                                            <?= htmlspecialchars($vet['specialization']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn-icon-danger" onclick="confirmDelete('<?= $vet['vet_id'] ?>')" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 text-light"></i><br>
                                    No veterinarians found matching "<?= htmlspecialchars($search) ?>".
                                    <br><a href="vetlist.php" class="small text-decoration-none">Clear search</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>

<script>
    function confirmDelete(vetID) {
        Swal.fire({
            title: "Delete Veterinarian?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#8898aa",
            confirmButtonText: "Yes, delete it"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../backend/vetdelete_b.php?vet_id=" + vetID;
            }
        });
    }
</script>

<?php
include "../frontend/footer.php";
?>