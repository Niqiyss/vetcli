<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ownerID'])) {
    header("Location: ../frontend/userlogin.php");
    exit;
}

$ownerID = $_SESSION['ownerID'];

//conn
require_once __DIR__ . "/connection.php";

$connPG    = $conn;                   // PostgreSQL
$connMaria = getMariaDBConnection();  // MariaDB
$connMySQL = getMySQLConnection();    // MySQL


//init
$treatments       = [];
$instructionsMap  = [];
$appointments     = [];
$appointmentMap   = [];
$pets             = [];
$vets             = [];

//complete app
$sqlAppt = "
    SELECT 
        appointment_id,
        pet_id,
        vet_id,
        time
    FROM appointment
    WHERE owner_id = ?
      AND status = 'Completed'
";

$stmtAppt = $connMaria->prepare($sqlAppt);
$stmtAppt->execute([$ownerID]);
$appointments = $stmtAppt->fetchAll(PDO::FETCH_ASSOC);

//app map
$appointmentIDs = [];

foreach ($appointments as $a) {
    $appointmentMap[$a['appointment_id']] = $a;
    $appointmentIDs[] = $a['appointment_id'];
}

//treatment
if (!empty($appointmentIDs)) {

    $appointmentIDs = array_values($appointmentIDs);
    $ph = implode(',', array_fill(0, count($appointmentIDs), '?'));

    $sqlTreatment = "
        SELECT
            treatment_id,
            treatment_date,
            diagnosis,
            treatment_description,
            treatment_fee,
            appointment_id
        FROM TREATMENT
        WHERE appointment_id IN ($ph)
        ORDER BY treatment_date DESC
    ";

    $stmtTreatment = $connMySQL->prepare($sqlTreatment);
    $stmtTreatment->execute($appointmentIDs);
    $treatments = $stmtTreatment->fetchAll(PDO::FETCH_ASSOC);
}

//med instruction
if (!empty($treatments)) {

    $treatmentIDs = array_values(array_column($treatments, 'treatment_id'));
    $ph2 = implode(',', array_fill(0, count($treatmentIDs), '?'));

    $sqlInst = "
        SELECT
            md.treatment_id,
            md.quantity_used,
            md.dosage,
            md.instruction,
            md.medicine_cost,
            m.medicine_name
        FROM MEDICINE_DETAILS md
        JOIN MEDICINE m
            ON md.medicine_id = m.medicine_id
        WHERE md.treatment_id IN ($ph2)
          AND md.instruction IS NOT NULL
          AND md.instruction != ''
    ";

    $stmtInst = $connMySQL->prepare($sqlInst);
    $stmtInst->execute($treatmentIDs);

    while ($row = $stmtInst->fetch(PDO::FETCH_ASSOC)) {
        $instructionsMap[$row['treatment_id']][] = $row;
    }
}

//petname
$petIDs = array_values(array_unique(array_column($appointments, 'pet_id')));

if (!empty($petIDs)) {

    $ph = implode(',', array_fill(0, count($petIDs), '?'));

    $stmtPet = $connPG->prepare(
        "SELECT pet_id, pet_name
         FROM pet
         WHERE pet_id IN ($ph)"
    );
    $stmtPet->execute($petIDs);

    while ($p = $stmtPet->fetch(PDO::FETCH_ASSOC)) {
        $pets[$p['pet_id']] = $p['pet_name'];
    }
}

//vetname
$vetIDs = array_values(array_unique(array_column($appointments, 'vet_id')));

if (!empty($vetIDs)) {

    $ph = implode(',', array_fill(0, count($vetIDs), '?'));

    $stmtVet = $connPG->prepare(
        "SELECT vet_id, vet_name
         FROM veterinarian
         WHERE vet_id IN ($ph)"
    );
    $stmtVet->execute($vetIDs);

    while ($v = $stmtVet->fetch(PDO::FETCH_ASSOC)) {
        $vets[$v['vet_id']] = $v['vet_name'];
    }
}

//merge
foreach ($treatments as &$t) {

    $appt = $appointmentMap[$t['appointment_id']] ?? null;

    /* Pet name */
    $t['pet_name'] = ($appt && isset($pets[$appt['pet_id']]))
        ? $pets[$appt['pet_id']]
        : 'Pet';

    /* Vet name */
    $t['vet_name'] = ($appt && isset($vets[$appt['vet_id']]))
        ? $vets[$appt['vet_id']]
        : 'Vet';

    /* Appointment time (SAFE) */
    $t['time'] = $appt['time'] ?? null;
}
unset($t);


