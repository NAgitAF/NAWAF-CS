<?php
session_start();
require_once '../db.php';
require_once '../permissions.php';

/* تحقق من تسجيل الدخول */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* تحقق من الصلاحية */
if (!hasPermission($conn, $_SESSION['user_id'], 'delete_product')) {
    die('❌ لا تملك صلاحية حذف المنتجات');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ طلب غير صالح');
}

$product_id = (int)$_GET['id'];

/* حذف صور المنتج أولًا (إن وجدت) */
$stmt = $conn->prepare("DELETE FROM product_images WHERE product_id = ?");
$stmt->execute([$product_id]);

/* حذف المنتج */
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$product_id]);

header("Location: products.php");
exit;
