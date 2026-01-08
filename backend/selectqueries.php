<?php

function getMedicines(PDO $connMySQL) {
    $sql = "SELECT medicineID, medicineName, price FROM medicine";
    $stmt = $connMySQL->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMedicineDetails(PDO $connMySQL) {
    $sql = "
        SELECT md.detailID, m.medicineName,
               md.dosage, md.instructions
        FROM medicine_details md
        JOIN medicine m ON md.medicineID = m.medicineID
    ";
    $stmt = $connMySQL->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTreatments(PDO $connMySQL, $ownerID) {
    $sql = "
        SELECT treatmentID, treatmentDate, diagnosis, totalAmount
        FROM treatment
        WHERE ownerID = :ownerID
    ";
    $stmt = $connMySQL->prepare($sql);
    $stmt->execute([':ownerID' => $ownerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAppointments(PDO $connMaria, $vetID) {
    $sql = "
        SELECT appointment_id, appointment_date, appointment_time, status
        FROM appointment
        WHERE vet_id = :vetID
        ORDER BY appointment_date DESC
    ";
    $stmt = $connMaria->prepare($sql);
    $stmt->execute([':vetID' => $vetID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
