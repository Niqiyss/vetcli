<?php
function logAudit(
    $conn,
    $userId,
    $userRole,
    $actionType,
    $tableName = null,
    $recordId = null,
    $sourceSystem = 'iqin_pg'
) {
    $stmt = $conn->prepare("
        INSERT INTO audit_log
        (user_id, user_role, action_type, table_name, record_id, source_system)
        VALUES
        (:uid, :role, :action, :table, :rid, :source)
    ");

    $stmt->execute([
        ':uid'    => $userId,
        ':role'   => $userRole,
        ':action' => $actionType,
        ':table'  => $tableName,
        ':rid'    => $recordId,
        ':source' => $sourceSystem
    ]);
}
