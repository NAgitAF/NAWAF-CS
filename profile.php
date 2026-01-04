<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT * FROM products
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>ملفي الشخصي</title>
<link rel="stylesheet" href="css/style.css">
<style>
.product-card{
    background:#111;
    border-radius:12px;
    padding:15px;
    color:#fff;
    text-align:center;
}
.product-card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
}
.delete-btn{
    margin-top:10px;
    background:#c0392b;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}
</style>
</head>
<body>

<header>
    <h1>ملفي الشخصي</h1>
    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="add.php">إضافة منتج</a>
        <a href="logout.php">خروج</a>
    </nav>
</header>

<main id="product-container">

<?php if(empty($products)): ?>
    <p style="text-align:center;">لا يوجد لديك منتجات</p>
<?php endif; ?>

<?php foreach($products as $prod): ?>

    <?php
    $image = !empty($prod['image'])
        ? "/cybershop/images/products/".$prod['image']
        : "/cybershop/images/no-image.png";
    ?>

    <div class="product-card">

        <img src="<?= $image ?>" alt="<?= htmlspecialchars($prod['name']) ?>">

        <h3><?= htmlspecialchars($prod['name']) ?></h3>
        <p><?= htmlspecialchars($prod['description']) ?></p>
        <p><strong>$<?= $prod['price'] ?></strong></p>

        <button class="delete-btn"
                onclick="confirmDelete(<?= $prod['id'] ?>)">
            حذف المنتج
        </button>

    </div>

<?php endforeach; ?>

</main>

<script>
function confirmDelete(id){
    if(confirm("هل أنت متأكد من حذف هذا المنتج؟")){
        window.location.href = "delete.php?id=" + id;
    }
}
</script>

</body>
</html>


