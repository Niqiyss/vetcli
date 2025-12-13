<?php
include "../frontend/adminheader.php";
include "../backend/vetlist_b.php";
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS for search -->
<style>
    .vet-search-wrapper {
        background-color: white;
        border-radius: 40px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 5px 10px;
        max-width: 260px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .vet-search-input {
        border: none;
        background: transparent;
        font-size: 0.9rem;
        height: 38px;
    }

    .vet-search-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .vet-search-btn {
        background-color: #007bff;
        color: #fff;
        border-radius: 40px;
        padding: 5px 12px;
        font-size: 0.85rem;
        border: none;
    }

    /* Specialization badge only */
    .spec-badge {
        background-color: #e7f1ff;
        color: #0d6efd;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
        white-space: nowrap;
    }
</style>

<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>Veterinarian List</h1>
            <p class="text-muted">All registered veterinarians are listed below.</p>
        </div>
    </div>

    <section id="vetlist" class="section py-4">
        <div class="container">

            <!-- Search -->
            <div class="d-flex justify-content-end mb-3">
                <div class="vet-search-wrapper position-relative">
                    <form class="d-flex align-items-center" method="get">
                        <input class="form-control vet-search-input" type="search" name="txtsearch"
                            placeholder="Search ID or Name" value="<?= htmlspecialchars($search) ?>">
                        <button class="vet-search-btn ms-2" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4 p-4">
                <?php if (!empty($errorMsg)) { ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMsg); ?></div>
                <?php } ?>

                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Vet ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Specialization</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vets)) {
                            foreach ($vets as $vet) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($vet['vet_id']); ?></td>
                                    <td><?= htmlspecialchars($vet['vet_name']); ?></td>
                                    <td><?= htmlspecialchars($vet['phone_num']); ?></td>
                                    <td><?= htmlspecialchars($vet['email']); ?></td>
                                    <td>
                                        <span class="spec-badge">
                                            <?= htmlspecialchars($vet['specialization']); ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($vet['username']); ?></td>
                                    <td><?= htmlspecialchars($vet['password']); ?></td>
                                    <td>
                                        <!-- EDIT -->
                                        <a href="../frontend/vetupdate.php?id=<?= $vet['vet_id']; ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- DELETE -->
                                        <a href="#" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete('<?= $vet['vet_id']; ?>')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="9" class="text-center">No veterinarians found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</main>

<!-- SweetAlert Delete Confirmation -->
<script>
    function confirmDelete(vetID) {
        Swal.fire({
            title: "Delete Veterinarian?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../backend/vetdelete_b.php?vet_id=" + vetID;
            }
        });
    }
</script>

<!-- SweetAlert Success After Redirect -->
<?php if (isset($_GET['msg']) && $_GET['msg'] === "deleted") { ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "Veterinarian record has been deleted successfully.",
            timer: 1800,
            showConfirmButton: false
        });
    </script>
<?php } ?>

<?php
include "../frontend/footer.php";
?>