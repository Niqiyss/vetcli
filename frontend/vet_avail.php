<?php
//vet_avail.php

session_start();
include "../frontend/adminheader.php";
include "../backend/connection.php";

$filterVet = $_GET['vet_id'] ?? '';
$filterDay = $_GET['day'] ?? '';
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_SESSION['success_popup'])) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{$_SESSION['success_popup']}',
            confirmButtonColor: '#009d91'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

if (isset($_SESSION['error_popup'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{$_SESSION['error_popup']}',
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

    /* Filters */
    .filter-wrapper {
        background-color: #fff;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .form-select, .btn {
        border-radius: 8px;
        font-size: 14px;
        height: 45px;
    }
    
    .form-select:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 0.2rem rgba(0, 157, 145, 0.25);
    }

    .btn-primary {
        background-color: var(--accent-teal);
        border: none;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #007d73;
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
        padding: 20px 15px;
        border-top: 1px solid #f8f9fa;
        vertical-align: middle;
        font-size: 14px;
        font-weight: 500;
        color: #444;
    }

    .table-custom td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: var(--accent-teal);
        font-weight: 700;
    }

    .table-custom td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    /* Time Badges */
    .time-badge {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .day-text {
        color: var(--primary-teal);
        font-weight: 600;
    }
    
    /* Modal Styling */
    .modal-content {
        border-radius: 16px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 20px 25px;
    }
    .modal-title {
        font-weight: 700;
        color: var(--primary-teal);
    }
    .modal-body {
        padding: 25px;
    }
    .modal-footer {
        border-top: none;
        padding: 20px 25px;
    }

</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Veterinarian Availability</h1>
                <p>Schedule Availability of Vet Staff</p>
            </div>
        </div>

        <div class="custom-card">
            
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="card-title-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    Vets Schedule List
                </div>
                
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailModal">
                    <i class="fas fa-plus me-2"></i> Add Availability
                </button>
            </div>

            <div class="filter-wrapper">
                <form method="GET" action="vet_avail.php" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user-md"></i></span>
                            <select name="vet_id" class="form-select border-start-0 ps-0" onchange="this.form.submit()">
                                <option value="">Filter by Veterinarian (All)</option>
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
                    </div>

                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-calendar-day"></i></span>
                            <select name="day" class="form-select border-start-0 ps-0" onchange="this.form.submit()">
                                <option value="">Filter by Day (All)</option>
                                <?php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                foreach ($days as $d):
                                    $sel = ($filterDay === $d) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $d ?>" <?= $sel ?>><?= $d ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                         <?php if ($filterVet || $filterDay): ?>
                            <a href="vet_avail.php" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        <?php else: ?>
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>VETERINARIAN</th>
                            <th>DAY</th>
                            <th>START TIME</th>
                            <th>END TIME</th>
                            <th class="text-end">ACTION</th>
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
                        $where = [];

                        if ($filterVet) {
                            $where[] = "v.vet_id = :vet";
                            $params[':vet'] = $filterVet;
                        }
                        if ($filterDay) {
                            $where[] = "a.day_of_week = :day";
                            $params[':day'] = $filterDay;
                        }
                        if ($where) {
                            $sql .= " WHERE " . implode(" AND ", $where);
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
                                    <td>#<?= str_pad($i++, 3, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2 text-success">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                            <?= htmlspecialchars($r['vet_name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="day-text"><?= $r['day_of_week'] ?></span>
                                    </td>
                                    <td>
                                        <span class="time-badge"><?= date("g:i A", strtotime($r['start_time'])) ?></span>
                                    </td>
                                    <td>
                                        <span class="time-badge"><?= date("g:i A", strtotime($r['end_time'])) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light text-warning mx-1" data-bs-toggle="modal"
                                            data-bs-target="#editAvailModal" data-id="<?= $r['availability_id'] ?>"
                                            data-vet="<?= $r['vet_id'] ?>" data-day="<?= $r['day_of_week'] ?>"
                                            data-start="<?= substr($r['start_time'], 0, 5) ?>"
                                            data-end="<?= substr($r['end_time'], 0, 5) ?>" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light text-danger mx-1"
                                            onclick="confirmDelete('<?= $r['availability_id'] ?>','<?= $r['vet_id'] ?>')" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-light"></i><br>
                                    No availability records found for this selection.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div> </div>
</main>

<div class="modal fade" id="addAvailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../backend/vet_avail_b.php" method="POST">
                <div class="modal-body row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">VETERINARIAN</label>
                        <select name="vet_id" class="form-select" required>
                            <?php
                            $stmt = $conn->prepare("SELECT vet_id, vet_name FROM veterinarian ORDER BY vet_name");
                            $stmt->execute();
                            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $v):
                                ?>
                                <option value="<?= $v['vet_id'] ?>">
                                    <?= htmlspecialchars($v['vet_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="form-label fw-bold text-muted small mt-4">DAY OF WEEK</label>
                        <select name="day_of_week" class="form-select" required>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">START TIME</label>
                        <input type="time" name="start_time" class="form-control" min="09:00" max="18:00" step="900" required>

                        <label class="form-label fw-bold text-muted small mt-4">END TIME</label>
                        <input type="time" name="end_time" class="form-control" min="09:00" max="18:00" step="900" required>

                        <div class="alert alert-light mt-3 mb-0 small text-center">
                            <i class="fas fa-info-circle me-1"></i> Clinic Hours: 9:00 AM – 6:00 PM
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAvailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../backend/vet_avail_update_b.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="vet_id" id="edit_vet">

                <div class="modal-body row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">DAY OF WEEK</label>
                        <select name="day_of_week" id="edit_day" class="form-select" required></select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">START TIME</label>
                        <input type="time" name="start_time" id="edit_start" class="form-control" min="09:00" max="18:00" step="900" required>

                        <label class="form-label fw-bold text-muted small mt-4">END TIME</label>
                        <input type="time" name="end_time" id="edit_end" class="form-control" min="09:00" max="18:00" step="900" required>
                        
                        <div class="alert alert-light mt-3 mb-0 small text-center">
                            <i class="fas fa-info-circle me-1"></i> Clinic Hours: 9:00 AM – 6:00 PM
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-warning px-4 text-white">Update Schedule</button>
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
            opt.value = d; // Ensure value is set
            opt.selected = (d === btn.dataset.day);
            daySelect.add(opt);
        });
    });

    //delete confirmation
    function confirmDelete(id, vetId) {
        Swal.fire({
            title: 'Delete Schedule?',
            text: 'This slot will be removed permanently',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#8898aa',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../backend/vet_avail_del_b.php?id=' + id + '&vet_id=' + vetId;
            }
        });
    }
</script>

<?php 
include "../frontend/footer.php"; 
?>