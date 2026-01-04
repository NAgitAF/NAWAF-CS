<?php
function hasPermission($conn, $user_id, $permission_name) {
    $sql = "
        SELECT COUNT(*) FROM permissions p
        JOIN role_permissions rp ON p.id = rp.perm_id
        JOIN user_roles ur ON rp.role_id = ur.role_id
        WHERE ur.user_id = ? AND p.perm_name = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $permission_name]);
    return $stmt->fetchColumn() > 0;
}

