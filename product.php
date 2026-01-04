

<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ منتج غير صالح');
}

$product_id = (int)$_GET['id'];

/* جلب المنتج */
$stmt = $conn->prepare("
    SELECT p.*, u.name AS seller
    FROM products p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('❌ المنتج غير موجود');
}

/* جلب صور المنتج */
$imgs = $conn->prepare("
    SELECT image FROM product_images WHERE product_id = ?
");
$imgs->execute([$product_id]);
$images = $imgs->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($product['name']) ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>CyberShop</h1>
    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="cart.php">السلة</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">ملفي</a>
            <a href="logout.php">خروج</a>
        <?php else: ?>
            <a href="login.php">دخول</a>
        <?php endif; ?>
    </nav>
</header>

<main class="product-page">

    <!-- معرض الصور -->
    <div class="gallery">
        <img id="mainImg"
             src="images/products/<?= htmlspecialchars($images[0] ?? $product['image']) ?>">
        <div class="thumbs">
            <?php foreach($images as $img): ?>
                <img src="images/products/<?= htmlspecialchars($img) ?>"
                     onclick="document.getElementById('mainImg').src=this.src">
            <?php endforeach; ?>
        </div>
    </div>

    <!-- معلومات المنتج -->
    <div class="product-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p class="price"><?= $product['price'] ?> $</p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p style="margin-top:10px;color:#aaa">
            البائع: <?= htmlspecialchars($product['seller']) ?>
        </p>

        <button onclick="addToCart(
            <?= $product['id'] ?>,
            '<?= addslashes($product['name']) ?>',
            <?= $product['price'] ?>,
            '<?= $product['image'] ?>'
        )">
            أضف إلى السلة
        </button>
    </div>

</main>

<script src="js/main.js"></script>
</body>
</html>


