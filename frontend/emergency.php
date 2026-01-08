<?php
//emergency.php
session_start();

// Smart Header Include
if (isset($_SESSION['ownerID'])) {
    include "ownerheader.php"; // Registered Header
} else {
    include "header.php"; // Guest Header
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Error Handling
if (isset($_SESSION['error_popup']) && is_array($_SESSION['error_popup'])) {
    $msg = implode("<br>", $_SESSION['error_popup']);
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            html: '$msg',
            confirmButtonColor: '#dc3545'
        });
    </script>";
    unset($_SESSION['error_popup']);
}
?>

<style>
    :root {
        --danger-red: #dc3545;
        --danger-dark: #b02a37;
        --bg-color: #fff5f5;
    }

    body {
        background-color: var(--bg-color);
        font-family: 'Poppins', sans-serif;
    }

    .main {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .emergency-card {
        background: white;
        max-width: 650px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(220, 53, 69, 0.2);
        border: 2px solid var(--danger-red);
        overflow: hidden;
    }

    .card-header-emergency {
        background: var(--danger-red);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Pulse animation for urgency */
    .pulse-icon {
        font-size: 45px;
        margin-bottom: 15px;
        animation: pulse 1.5s infinite;
        display: inline-block;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    .card-header-emergency h2 {
        font-weight: 800;
        margin: 0;
        font-size: 26px;
        letter-spacing: 1px;
    }

    .card-header-emergency p {
        margin: 5px 0 0;
        font-size: 15px;
        opacity: 0.9;
    }

    .card-body {
        padding: 35px;
    }

    .form-label {
        font-weight: 700;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #eee;
        padding: 12px 15px;
        font-size: 15px;
        transition: 0.3s;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--danger-red);
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        outline: none;
    }

    /* Highlight Severity Box */
    .severity-select {
        border-color: var(--danger-red);
        background-color: #fffcfc;
        font-weight: 600;
    }

    .btn-emergency {
        width: 100%;
        background: var(--danger-red);
        color: white;
        font-weight: 700;
        padding: 16px;
        border-radius: 50px;
        border: none;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        margin-top: 10px;
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
    }

    .btn-emergency:hover {
        background: var(--danger-dark);
        transform: translateY(-2px);
    }

    .emergency-hotline {
        margin-top: 25px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .emergency-hotline a {
        color: var(--danger-red);
        font-weight: 700;
        text-decoration: none;
        font-size: 18px;
        display: block;
        margin-top: 5px;
    }
    
    .emergency-hotline a:hover {
        text-decoration: underline;
    }
</style>

<main class="main">
    <div class="emergency-card">
        
        <div class="card-header-emergency">
            <i class="fas fa-notes-medical pulse-icon"></i>
            <h2>EMERGENCY FORM</h2>
            <p>Skip the queue. Notify us immediately.</p>
        </div>

        <div class="card-body">
            <form action="../backend/emergency_b.php" method="POST">
                
                <?php if (!isset($_SESSION['ownerID'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="guest_name" class="form-control" placeholder="Your Full Name" required>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border mb-3 text-center text-muted" style="font-size: 14px;">
                        <i class="fas fa-user-check me-2"></i> Logged in as <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
                    </div>
                <?php endif; ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pet Name</label>
                        <input type="text" name="pet_name" class="form-control" placeholder="E.g. Rocky" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Species</label>
                        <select name="species" class="form-select" required>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-danger">Severity Level</label>
                    <select name="severity" class="form-select severity-select" required>
                        <option value="" disabled selected>Select Condition...</option>
                        <option value="Life Threatening">⚠️ Life Threatening (Not breathing, collapse)</option>
                        <option value="Critical">🔴 Critical (Bleeding, seizures)</option>
                        <option value="Severe">🟠 Severe (Broken bones, vomiting)</option>
                        <option value="Moderate">🟡 Moderate (Pain, discomfort)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">What Happened? (Symptoms)</label>
                    <textarea name="symptoms" class="form-control" rows="3" placeholder="Briefly describe the situation..." required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Emergency Contact Number</label>
                    <input type="tel" name="contact_number" class="form-control" placeholder="012-345 6789" required>
                </div>

                <button type="submit" class="btn-emergency">
                    <i class="fas fa-ambulance me-2"></i> Send Alert & I'm Coming
                </button>

                <div class="emergency-hotline">
                    <small class="text-muted">Need to talk to us?</small>
                    <a href="tel:0123456789"><i class="fas fa-phone-alt me-2"></i> +60 12-345 6789</a>
                </div>

            </form>
        </div>

    </div>
</main>

<?php 
include "../frontend/footer.php"; 
?>