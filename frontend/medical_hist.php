<?php
include "../backend/medical_hist_b.php";
include "../frontend/ownerheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

    /* PAGE TITLE */
    .page-header-custom {
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .page-title h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-teal);
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 15px;
    }

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


    /* RECORDS LIST BAR */
    .records-bar {
        background: white;
        border-radius: 16px;
        padding: 18px 22px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .records-left {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--primary-teal);
    }

    .records-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #e6f7f6;
        color: var(--accent-teal);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .records-title {
        font-size: 16px;
    }

    .records-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sort-label {
        font-size: 14px;
        color: var(--text-muted);
    }

    .sort-select {
        padding: 10px 14px;
        border-radius: 30px;
        border: 1px solid #ddd;
        font-size: 13px;
        background: white;
        cursor: pointer;
    }

    /* GRID */
    .medical-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
    }

    .medical-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        display: flex;
        flex-direction: column;
    }

    .medical-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 12px;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--primary-teal);
    }

    .medical-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 14px;
    }

    .medical-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .btn-prescription {
        margin-top: auto;
        background: white;
        border: 1px solid var(--accent-teal);
        color: var(--accent-teal);
        padding: 8px;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
    }

    .btn-prescription:hover {
        background: var(--accent-teal);
        color: white;
    }

    .pagination {
    gap: 6px;
    }

    .pagination .page-item .page-link {
        border-radius: 10px;
        padding: 8px 14px;
        color: var(--primary-teal);
        border: 1px solid #ddd;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--accent-teal);
        border-color: var(--accent-teal);
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        color: #bbb;
    }

</style>

<main class="main py-5">
    <div class="container">

        <!-- TITLE -->
        <div class="page-header-custom">
            <div class="page-title">
                <h1>Medical History</h1>
                <p>Your pet’s treatment records</p>
            </div>
        </div>

         <div class="badge-container">
            <div class="owner-badge">
                <div class="owner-badge-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="owner-badge-text">
                    <span class="owner-badge-label">Owner</span>
                    <span class="owner-badge-name"><?= htmlspecialchars($owner) ?></span>
                </div>
            </div>
        </div>

        <!-- RECORDS LIST + SORT -->
        <div class="records-bar">
            <div class="records-left">
                <div class="records-icon">
                    <i class="fas fa-list"></i>
                </div>
                <span class="records-title">Medical History List</span>
            </div>

            <div class="records-right">
                <span class="sort-label">Sort by:</span>
                <select id="sortDate" class="sort-select">
                    <option value="newest">Date: Newest First</option>
                    <option value="oldest">Date: Oldest First</option>
                </select>
            </div>
        </div>

        <!-- CARDS -->
        <section>
            <?php if (!empty($treatments)): ?>
                <div class="medical-grid" id="medicalGrid">
                    <?php foreach ($treatments as $row): ?>
                        <?php $tID = $row['treatment_id']; ?>
                        <div class="medical-card" data-date="<?= $row['treatment_date']; ?>">

                            <!-- DATE -->
                            <div class="medical-header">
                                <?= date("d M Y", strtotime($row['treatment_date'])); ?>
                            </div>

                            <!-- PET NAME -->
                            <div class="medical-row">
                                <span class="medical-label">Pet</span>
                                <span><strong><?= htmlspecialchars($row['pet_name']); ?></strong></span>
                            </div>

                            <!-- APPOINTMENT TIME (SAFE) -->
                            <div class="medical-row">
                                <span class="medical-label">Appointment</span>
                                <span>
                                    <?= date("d M Y", strtotime($row['treatment_date'])); ?>
                                    <?php if (!empty($row['time'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($row['time']); ?>
                                        </small>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <!-- DIAGNOSIS -->
                            <div class="medical-row">
                                <span class="medical-label">Treatment</span>
                                <span><?= htmlspecialchars($row['diagnosis'] ?: 'General Checkup'); ?></span>
                            </div>

                            <!-- VET -->
                            <div class="medical-row">
                                <span class="medical-label">Vet</span>
                                <span><?= htmlspecialchars($row['vet_name']); ?></span>
                            </div>

                            <!-- FEE -->
                            <div class="medical-row">
                                <span class="medical-label">Fee</span>
                                <span><strong>RM <?= number_format($row['treatment_fee'], 2); ?></strong></span>
                            </div>

                            <br>
                            <!-- PRESCRIPTION -->
                            <?php if (isset($instructionsMap[$tID])): ?>
                                <button class="btn-prescription"
                                    onclick='viewPrescription(<?= json_encode($instructionsMap[$tID]); ?>)'>
                                    <i class="fas fa-prescription me-2"></i> View Medicine 
                                </button>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-notes-medical fa-3x mb-3"></i>
                    <h5>No medical records found</h5>
                </div>
            <?php endif; ?>
        </section>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>


    </div>
</main>

<!-- PRESCRIPTION MODAL -->
<div class="modal fade" id="prescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Medicine Instruction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="prescriptionContent"></div>
        </div>
    </div>
</div>


<script>
    const sortDate = document.getElementById("sortDate");
    const grid = document.getElementById("medicalGrid");
    const pagination = document.getElementById("pagination");

    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;
    let cards = Array.from(grid.children);

    function renderPage(page) {
        currentPage = page;
        grid.innerHTML = "";

        const start = (page - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;

        cards.slice(start, end).forEach(card => grid.appendChild(card));
        renderPagination();
    }

    function renderPagination() {
        pagination.innerHTML = "";
        const totalPages = Math.ceil(cards.length / ITEMS_PER_PAGE);

        // Prev
        pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">◀</a>
            </li>
        `;

        // Page numbers (max 3 visible)
        let start = Math.max(1, currentPage - 1);
        let end = Math.min(totalPages, start + 2);

        for (let i = start; i <= end; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? "active" : ""}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        }

        // Next
        pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? "disabled" : ""}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">▶</a>
            </li>
        `;
    }

    function changePage(page) {
        const totalPages = Math.ceil(cards.length / ITEMS_PER_PAGE);
        if (page < 1 || page > totalPages) return;
        renderPage(page);
    }

    // SORTING
    sortDate.addEventListener("change", () => {
        const order = sortDate.value;

        cards.sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return order === "newest" ? dateB - dateA : dateA - dateB;
        });

        renderPage(1);
    });

    // INITIAL LOAD
    renderPage(1);

    // PRESCRIPTION MODAL
    function viewPrescription(data) {
        const container = document.getElementById("prescriptionContent");
        container.innerHTML = "";

        data.forEach(item => {
            container.innerHTML += `
                <div class="mb-2">
                    <strong>${item.medicine_name}</strong>
                    <div class="text-muted">${item.instruction}</div>
                </div>
            `;
        });

        new bootstrap.Modal(document.getElementById("prescriptionModal")).show();
    }
</script>


<?php
include "../frontend/footer.php";
?>