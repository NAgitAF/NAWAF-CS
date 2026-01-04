<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$ids = array_column($data, 'id');

if(empty($ids)){
    exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $conn->prepare("
    SELECT * FROM products
    WHERE id IN ($placeholders)
");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

foreach($products as $i => $p):
$total += $p['price'];
?>
<div class="product-card">
    <img src="images/products/<?= $p['image'] ?>">
    <h3><?= $p['name'] ?></h3>
    <p>$<?= $p['price'] ?></p>
    <button onclick="removeFromCart(<?= $i ?>)">حذف</button>
</div>
<?php endforeach; ?>

<h2 style="text-align:center">الإجمالي: $<?= $total ?></h2>
