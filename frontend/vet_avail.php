<?php

include "../frontend/adminheader.php";
include "../backend/connection.php";

$filterVet = $_GET['vet_id'] ?? '';
?>

<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
/* success popup */
if (isset($_SESSION['success_popup'])) {
    $msg = $_SESSION['success_popup'];
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '$msg',
            confirmButtonColor: '#0d6efd'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

/* error popup */
if (isset($_SESSION['error_popup'])) {
    $msg = $_SESSION['error_popup'];
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '$msg',
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}
?>

<style>
    .modal-header.add {
        background: linear-gradient(135deg, #3398d3ff, #3398d3ff);
        color: #ffffffff;
    }

    .modal-header.edit {
        background: linear-gradient(135deg, #ffc107, #ffca2c);
    }

    .modal-content {
        border-radius: 16px;
    }
</style>

<main class="main">


    <div class="page-title text-center mt-4">
        <h1>Veterinarian Availability</h1>
        <p class="text-muted">Weekly Schedule of Veterinarian</p>
    </div>

    <section class="appointment section py-5">
        <div class="container">

            <!--filter, add avail -->
            <form method="GET" action="vet_avail.php" class="row align-items-end mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Filter by Veterinarian</label>
                    <select name="vet_id" class="form-select">
                        <option value="">All Veterinarians</option>
                        <?php
                        $stmt = $conn->prepare("SELECT vet_id, vet_name FROM veterinarian ORDER BY vet_name");
                        $stmt->execute();
                        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $v):
                            $sel = ($filterVet === $v['vet_id']) ? 'selected' : '';
                            ?>
                            <option value="<?= $v['vet_id'] ?>" <?= $sel ?>>
                                <?= htmlspecialchars($v['vet_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>

                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addAvailModal">
                        + Add Availability
                    </button>
                </div>
            </form>

            <!-- avail list -->
            <div class="card shadow-lg border-0 rounded-4 p-4">
                <div class="table-responsive">

                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Veterinarian</th>
                                <th>Day</th>
                                <th>Start</th>
                                <th>End</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $sql = "
                                    SELECT a.availability_id, a.day_of_week, a.start_time, a.end_time,
                                        v.vet_id, v.vet_name
                                    FROM vet_availability a
                                    JOIN veterinarian v ON a.vet_id = v.vet_id
                                    ";

                            $params = [];
                            if ($filterVet) {
                                $sql .= " WHERE v.vet_id = :vet ";
                                $params[':vet'] = $filterVet;
                            }

                            $sql .= "
                                    ORDER BY v.vet_name,
                                    CASE a.day_of_week
                                        WHEN 'Monday' THEN 1
                                        WHEN 'Tuesday' THEN 2
                                        WHEN 'Wednesday' THEN 3
                                        WHEN 'Thursday' THEN 4
                                        WHEN 'Friday' THEN 5
                                        WHEN 'Saturday' THEN 6
                                    END,
                                    a.start_time
                                    ";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute($params);
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($rows):
                                $i = 1;
                                foreach ($rows as $r):
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($r['vet_name']) ?></td>
                                        <td><?= $r['day_of_week'] ?></td>
                                        <td><?= substr($r['start_time'], 0, 5) ?></td>
                                        <td><?= substr($r['end_time'], 0, 5) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editAvailModal" data-id="<?= $r['availability_id'] ?>"
                                                data-vet="<?= $r['vet_id'] ?>" data-day="<?= $r['day_of_week'] ?>"
                                                data-start="<?= substr($r['start_time'], 0, 5) ?>"
                                                data-end="<?= substr($r['end_time'], 0, 5) ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('<?= $r['availability_id'] ?>','<?= $r['vet_id'] ?>')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-muted">No availability found.</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </section>
</main>

<!-- add avail modal -->
<div class="modal fade" id="addAvailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header add">
                <h5 class="modal-title">Add Availability</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="../backend/vet_avail_b.php" method="POST">
                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label>Veterinarian</label>
                        <select name="vet_id" class="form-select" required>
                            <?php
                            $stmt = $conn->prepare("SELECT vet_id, vet_name FROM veterinarian ORDER BY vet_name");
                            $stmt->execute();
                            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $v):
                                ?>
                                <option value="<?= $v['vet_id'] ?>"><?= htmlspecialchars($v['vet_name']) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label class="mt-3">Day</label>
                        <select name="day_of_week" class="form-select">
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>

                        <label class="mt-3">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!--edit-->
<div class="modal fade" id="editAvailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header edit">
                <h5 class="modal-title">Edit Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="../backend/vet_avail_update_b.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="vet_id" id="edit_vet">

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Day</label>
                        <select name="day_of_week" id="edit_day" class="form-select"></select>
                    </div>

                    <div class="col-md-6">
                        <label>Start Time</label>
                        <input type="time" name="start_time" id="edit_start" class="form-control">
                        <label class="mt-3">End Time</label>
                        <input type="time" name="end_time" id="edit_end" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<script>
    /* EDIT MODAL DATA */
    const editModal = document.getElementById('editAvailModal');
    editModal.addEventListener('show.bs.modal', event => {
        const btn = event.relatedTarget;

        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_vet').value = btn.dataset.vet;
        document.getElementById('edit_start').value = btn.dataset.start;
        document.getElementById('edit_end').value = btn.dataset.end;

        const daySelect = document.getElementById('edit_day');
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        daySelect.innerHTML = '';
        days.forEach(d => {
            const opt = document.createElement('option');
            opt.text = d;
            opt.selected = (d === btn.dataset.day);
            daySelect.add(opt);
        });
    });

    /* SWEETALERT DELETE CONFIRM */
    function confirmDelete(id, vetId) {
        Swal.fire({
            title: 'Delete Availability?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    '../backend/vet_avail_del_b.php?id=' + id + '&vet_id=' + vetId;
            }
        });
    }
</script>

<?php include "../frontend/footer.php"; ?>