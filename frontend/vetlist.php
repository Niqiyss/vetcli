<?php
include "../frontend/adminheader.php";
include "../backend/vetlist_b.php";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .vet-search-wrapper {
        background: #fff;
        border-radius: 40px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 6px 12px;
        max-width: 280px;
    }

    .vet-search-input {
        border: none;
        background: transparent;
        font-size: 0.9rem;
    }

    .vet-search-input:focus {
        outline: none;
        box-shadow: none;
    }

    .vet-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee;
    }

    .spec-badge {
        background: #eef4ff;
        color: #0d6efd;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    table th {
        white-space: nowrap;
    }
</style>

<main class="main">
    <div class="page-title text-center mt-4">
        <h1>Veterinarian List</h1>
        <p class="text-muted">Manage registered veterinarians</p>
    </div>

    <section class="section py-4">
        <div class="container">

            <!-- SEARCH -->
            <div class="d-flex justify-content-end mb-3">
                <form class="vet-search-wrapper d-flex" method="get">
                    <input class="vet-search-input form-control"
                           type="search"
                           name="txtsearch"
                           placeholder="Search ID or Name"
                           value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-sm ms-2">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="card shadow border-0 rounded-4 p-4">

                <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger"><?= $errorMsg ?></div>
                <?php endif; ?>

                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Photo</th>
                            <th>Vet ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Specialization</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($vets)): ?>
                            <?php foreach ($vets as $vet): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/vets/<?= htmlspecialchars($vet['vet_image'] ?? 'default.png') ?>"
                                             class="vet-img">
                                    </td>
                                    <td><?= htmlspecialchars($vet['vet_id']) ?></td>
                                    <td><?= htmlspecialchars($vet['vet_name']) ?></td>
                                    <td><?= htmlspecialchars($vet['phone_num']) ?></td>
                                    <td><?= htmlspecialchars($vet['email']) ?></td>
                                    <td>
                                        <span class="spec-badge">
                                            <?= htmlspecialchars($vet['specialization']) ?>
                                        </span>
                                    </td>
                                    <td>

                                        <button class="btn btn-danger btn-sm"
                                                onclick="confirmDelete('<?= $vet['vet_id'] ?>')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No veterinarians found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</main>

<script>
function confirmDelete(vetID) {
    Swal.fire({
        title: "Delete Veterinarian?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete"
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
