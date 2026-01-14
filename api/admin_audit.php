<?php
session_start();

require_once "../backend/connection.php";

/* =========================
   ADMIN AUTH GUARD
========================= */
if (!isset($_SESSION['adminID'])) {
    header("Location: userlogin.php");
    exit();
}

include "../frontend/adminheader.php";

/* =========================
   FETCH AUDIT LOGS
========================= */
$stmt = $conn->query("
    SELECT audit_id,
           user_id,
           user_role,
           action_type,
           table_name,
           record_id,
           source_system,
           action_time
    FROM audit_log
    ORDER BY action_time DESC
");
$auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        /* THEME COLORS */
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

    /* --- HERO HEADER SECTION (White BG + Green Line) --- */
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

    /* --- ADMIN BADGE (Absolute Right) --- */
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

    /* --- CARD STYLE --- */
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
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

    /* --- TABLE STYLING --- */
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
        font-size: 13px;
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

    /* Badges for Action Type */
    .badge-action {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .bg-soft-primary { background-color: #e0f2f1; color: var(--primary-teal); }
    .bg-soft-warning { background-color: #fff3cd; color: #856404; }
    .bg-soft-danger  { background-color: #f8d7da; color: #721c24; }
    .bg-soft-info    { background-color: #d1ecf1; color: #0c5460; }

</style>

<div class="hero-section">
    <div class="container">
        <div class="header-content-wrapper">
            
            <div class="page-title">
                <h1>Audit Trail</h1>
                <p>System Activity Logs & History</p>
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
            
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="card-title-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    System Logs
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Audit ID</th>
                            <th>User ID</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Record ID</th>
                            <th>Source</th>
                            <th>Date / Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($auditLogs)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i><br>
                                    No audit records found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($auditLogs as $log): ?>
                                <?php
                                    // Determine badge style
                                    $action = strtoupper($log['action_type']);
                                    $badgeClass = 'bg-soft-primary'; // Default (Greenish)
                                    if (strpos($action, 'DELETE') !== false) $badgeClass = 'bg-soft-danger';
                                    elseif (strpos($action, 'UPDATE') !== false) $badgeClass = 'bg-soft-warning';
                                    elseif (strpos($action, 'LOGIN') !== false) $badgeClass = 'bg-soft-info';
                                ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($log['audit_id']) ?></td>
                                    <td><?= htmlspecialchars($log['user_id']) ?></td>
                                    <td><?= htmlspecialchars($log['user_role']) ?></td>
                                    <td>
                                        <span class="badge-action <?= $badgeClass ?>">
                                            <?= htmlspecialchars($log['action_type']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($log['table_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($log['record_id'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($log['source_system']) ?></td>
                                    <td><?= htmlspecialchars($log['action_time']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</main>

<?php
include "../frontend/footer.php";
?>