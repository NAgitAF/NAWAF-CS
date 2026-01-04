<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once '../permissions.php';

/* تحقق من تسجيل الدخول */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* تحقق من صلاحية الأدمن */
if (!hasPermission($conn, $_SESSION['user_id'], 'manage_users')) {
    die('❌ لا تملك صلاحية حذف المستخدمين');
}

/* تحقق من وجود ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ طلب غير صالح');
}

$user_id = (int)$_GET['id'];

/* منع حذف نفسك */
if ($user_id === $_SESSION['user_id']) {
    die('❌ لا يمكنك حذف حسابك');
}

/* حذف العلاقات أولًا */
$stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = ?");
$stmt->execute([$user_id]);

/* حذف المستخدم */
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);

/* رجوع */
header("Location: users.php");
exit;

