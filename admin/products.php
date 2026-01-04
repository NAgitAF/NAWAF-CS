<?php
session_start();

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../permissions.php';

/* تحقق الصلاحية */
if (!isset($_SESSION['user_id']) || !hasPermission($conn, $_SESSION['user_id'], 'manage_products')) {
    die('❌ لا تملك صلاحية إدارة المنتجات');
}

/* إضافة منتج */
if (isset($_POST['add_product'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->execute([$name, $price]);

    header("Location: products.php");
    exit;
}

/* حذف منتج */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: products.php");
    exit;
}

/* جلب المنتجات */
$products = $conn->query("SELECT id, name, price FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>إدارة المنتجات</title>
<style>
body { font-family: Tahoma; direction: rtl; }
table { border-collapse: collapse; width: 70%; margin-top: 20px; }
th, td { border: 1px solid #333; padding: 8px; text-align: center; }
th { background: #eee; }
form { margin-top: 20px; }
input, button { padding: 6px; }
a { color: red; text-decoration: none; }
</style>
</head>
<body>

<h2>🛒 إدارة المنتجات</h2>

<!-- إضافة منتج -->
<form method="post">
    <input type="text" name="name" placeholder="اسم المنتج" required>
    <input type="number" step="0.01" name="price" placeholder="السعر" required>
    <button type="submit" name="add_product">➕ إضافة</button>
</form>

<!-- جدول المنتجات -->
<table>
<tr>
    <th>ID</th>
    <th>اسم المنتج</th>
    <th>السعر</th>
    <th>حذف</th>
</tr>

<?php if ($products): ?>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= $p['price'] ?></td>
        <td>
            <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('هل أنت متأكد؟')">🗑 حذف</a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4">لا توجد منتجات</td></tr>
<?php endif; ?>

</table>

</body>
</html>


