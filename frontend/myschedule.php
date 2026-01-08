<?php
include "../frontend/vetheader.php"; 
include "../backend/myschedule_b.php"; 
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
        color: #333;
    }

    .page-header-custom {
        text-align: center;
        margin-bottom: 10px;
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

    .badge-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 25px;
        padding-right: 10px;
    }

    .vet-badge {
        background-color: white;
        padding: 8px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(14, 92, 101, 0.08);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #eef2f2;
    }

    .vet-badge-icon {
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

    .vet-badge-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
        text-align: left;
    }

    .vet-badge-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .vet-badge-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-teal);
    }

    .week-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .custom-card.is-today {
        border: 2px solid var(--accent-teal);
        background: #f0fdfc;
    }

    .custom-card.is-today::before {
        content: 'TODAY';
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent-teal);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 1px;
    }

    .card-header-custom {
        padding: 20px 25px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .day-icon {
        width: 40px;
        height: 40px;
        background: #e0f2f1;
        color: var(--primary-teal);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .day-name {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .card-body-custom {
        padding: 25px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .shift-badge {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        color: #555;
        font-weight: 500;
    }
    
    .shift-badge i {
        color: var(--accent-teal);
        margin-right: 10px;
    }

    .off-day {
        color: #aaa;
        font-style: italic;
        text-align: center;
    }
</style>

<main class="main py-5">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>My Schedule</h1>
                <p>View your weekly working hours and shifts</p>
            </div>
        </div>

        <div class="badge-container">
            <div class="vet-badge">
                <div class="vet-badge-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="vet-badge-text">
                    <span class="vet-badge-label">Veterinarian</span>
                    <span class="vet-badge-name"><?= htmlspecialchars($vet_name) ?></span>
                </div>
            </div>
        </div>

        <div class="week-grid">
            <?php foreach ($days_of_week as $day): 
                $is_today = ($day === $current_day);
                $shifts = $schedule_map[$day];
                $has_shifts = count($shifts) > 0;
            ?>
            
            <div class="custom-card <?= $is_today ? 'is-today' : '' ?>">
                <div class="card-header-custom">
                    <div class="day-icon <?= $is_today ? 'bg-success text-white' : '' ?>">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3 class="day-name"><?= $day ?></h3>
                </div>

                <div class="card-body-custom">
                    <?php if ($has_shifts): ?>
                        <?php foreach ($shifts as $time): ?>
                            <div class="shift-badge">
                                <i class="far fa-clock"></i> 
                                <?= $time ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="off-day">
                            <i class="fas fa-mug-hot fa-2x mb-2 d-block opacity-25"></i>
                            No shifts
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php endforeach; ?>
        </div>

    </div>
</main>

<?php 
include "../frontend/footer.php"; 
?>