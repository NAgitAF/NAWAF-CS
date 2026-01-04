<?php
session_start();
require_once 'db.php';
require_once 'permissions.php';

if (!isset($_SESSION['user_id'])) {
    die('❌ يجب تسجيل الدخول');
}

if (!hasPermission($conn, $_SESSION['user_id'], 'view_admin')) {
    die('❌ ليست لديك صلاحية دخول الغرفة');
}

header("Location: admin/");
exit;
