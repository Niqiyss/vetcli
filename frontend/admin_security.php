<?php
session_start();
require_once "../backend/connection.php";

/* =========================
   ADMIN AUTH GUARD
========================= */
if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

include "../frontend/adminheader.php";

//fetch locked owner
$owners = $conn->query("
    SELECT owner_id, username, owner_name, failed_attempts
    FROM owner
    WHERE failed_attempts >= 3
")->fetchAll(PDO::FETCH_ASSOC);

//fetch locked vet
$vets = $conn->query("
    SELECT vet_id, username, vet_name, failed_attempts
    FROM veterinarian
    WHERE failed_attempts >= 3
")->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_SESSION['success_popup'])) {
    echo "<script>
        Swal.fire({ 
            icon:'success', 
            title:'Success', 
            text:'{$_SESSION['success_popup']}',
            confirmButtonColor: '#00798C'
        });
    </script>";
    unset($_SESSION['success_popup']);
}

if (isset($_SESSION['error_popup'])) {
    echo "<script>
        Swal.fire({ 
            icon:'error', 
            title:'Error', 
            text:'{$_SESSION['error_popup']}',
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
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
        color: #333;
    }

    
    .hero-section {
        background-color: var(--white);
        width: 100%;
        padding: 40px 0;
        border-bottom: 3px solid var(--accent-teal); 
        margin-bottom: 40px;
    }

    
    .header-content-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .page-title {
        text-align: center;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 5px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }

    
    .admin-badge {
        position: absolute;
        right: 0;
        bottom: 0;
        background-color: white;
        padding: 8px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #e0e0e0;
    }

    .admin-badge-icon {
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

    .admin-badge-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
        text-align: left;
    }

    .admin-badge-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .admin-badge-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-teal);
    }

   
    @media (max-width: 768px) {
        .header-content-wrapper {
            flex-direction: column;
            gap: 20px;
        }
        .admin-badge {
            position: static;
            transform: none;
        }
    }

    
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
    }

    .section-header {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .section-header i {
        background: #e0f7fa;
        color: var(--accent-teal);
        padding: 8px;
        border-radius: 8px;
    }

    
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .table-custom td {
        padding: 15px;
        border-top: 1px solid #f8f9fa;
        vertical-align: middle;
        font-size: 14px;
        font-weight: 500;
        color: #444;
    }

    .table-custom td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        font-weight: 600;
        color: var(--primary-teal);
    }

    .table-custom td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    
    .btn-unlock {
        background-color: #ff9800; 
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-unlock:hover {
        background-color: #e68900;
        color: white;
        box-shadow: 0 3px 10px rgba(255, 152, 0, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
</style>

<div class="hero-section">
    <div class="container">
        <div class="header-content-wrapper">
            
            <div class="page-title">
                <h1>Unlock User Account</h1>
                <p>Manage locked accounts due to failed login attempts</p>
            </div>

            <div class="admin-badge">
                <div class="admin-badge-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="admin-badge-text">
                    <span class="admin-badge-label">Admin</span>
                    <span class="admin-badge-name"><?= htmlspecialchars($_SESSION['adminname'] ?? 'Admin'); ?></span>
                </div>
            </div>

        </div>
    </div>
</div>

<main class="main pb-5">
    <div class="container">

        <div class="custom-card">
            <div class="section-header">
                <i class="fas fa-paw"></i> Locked Owners
            </div>

            <?php if (empty($owners)): ?>
                <div class="empty-state">
                    <i class="bi bi-shield-check fa-3x mb-3 text-success opacity-50"></i>
                    <p>No locked owner accounts found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Failed Attempts</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($owners as $o): ?>
                                <tr>
                                    <td><?= htmlspecialchars($o['username']) ?></td>
                                    <td><?= htmlspecialchars($o['owner_name']) ?></td>
                                    <td><span class="badge bg-danger"><?= $o['failed_attempts'] ?></span></td>
                                    <td class="text-end">
                                        <form method="POST" action="../backend/adminunlock.php">
                                            <input type="hidden" name="user_type" value="owner">
                                            <input type="hidden" name="user_id" value="<?= $o['owner_id'] ?>">
                                            <button class="btn-unlock">
                                                <i class="fas fa-unlock-alt me-1"></i> Unlock
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="custom-card">
            <div class="section-header">
                <i class="fas fa-user-md"></i> Locked Veterinarians
            </div>

            <?php if (empty($vets)): ?>
                <div class="empty-state">
                    <i class="bi bi-shield-check fa-3x mb-3 text-success opacity-50"></i>
                    <p>No locked veterinarian accounts found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Failed Attempts</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vets as $v): ?>
                                <tr>
                                    <td><?= htmlspecialchars($v['username']) ?></td>
                                    <td><?= htmlspecialchars($v['vet_name']) ?></td>
                                    <td><span class="badge bg-danger"><?= $v['failed_attempts'] ?></span></td>
                                    <td class="text-end">
                                        <form method="POST" action="../backend/adminunlock.php">
                                            <input type="hidden" name="user_type" value="vet">
                                            <input type="hidden" name="user_id" value="<?= $v['vet_id'] ?>">
                                            <button class="btn-unlock">
                                                <i class="fas fa-unlock-alt me-1"></i> Unlock
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php 
include "../frontend/footer.php"; 
?>