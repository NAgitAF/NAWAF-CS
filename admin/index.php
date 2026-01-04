<?php
require_once '../auth.php';
require_once '../db.php';
require_once '../permissions.php';

if (!hasPermission($conn, $_SESSION['user_id'], 'view_admin')) {
    die('🚫 لا تملك صلاحية الدخول');
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>غرفة التحكم</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body class="admin-body">

<header class="admin-header">
    <h1>CyberShop Admin</h1>
    <nav>
        <?php if(hasPermission($conn,$_SESSION['user_id'],'manage_users')): ?>
            <a href="users.php">المستخدمون</a>
        <?php endif; ?>

        <?php if(hasPermission($conn,$_SESSION['user_id'],'manage_products')): ?>
            <a href="products.php">المنتجات</a>
        <?php endif; ?>

        <a href="../index.php">المتجر</a>
        <a href="../logout.php">خروج</a>
    </nav>
</header>

<main class="admin-panel">

    <div class="admin-card">
        <h2>👋 مرحبًا أدمن</h2>
        <p>من هنا تتحكم بكل شيء في CyberShop</p>
    </div>

    <div class="admin-grid">
        <a href="users.php" class="admin-box">👥 إدارة المستخدمين</a>
        <a href="products.php" class="admin-box">📦 إدارة المنتجات</a>
    </div>

</main>

</body>
</html>
