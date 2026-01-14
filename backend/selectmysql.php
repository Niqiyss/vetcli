<?php
require_once "../backend/connection.php";

//select from tya (pending)
function getLatestUnpaidPaymentByOwner($ownerID)
{
    global $connPaymentMYSQL;

    $sql = "
        SELECT payment_id
        FROM payment
        WHERE owner_id = :owner_id
          AND paymentStatus = 'pending'
        LIMIT 1


    ";

    $stmt = $connPaymentMYSQL->prepare($sql);
    $stmt->execute([
        'owner_id' => $ownerID
    ]);

    return $stmt->fetch(); 
}