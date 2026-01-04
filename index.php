<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();
include 'db.php';

/* جلب المنتجات */
$stmt = $conn->query("
    SELECT products.*, users.name AS seller
    FROM products
    JOIN users ON products.user_id = users.id
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* تعريف الأقسام الموسعة */
$categories = [
    ['type'=>'realestate', 'name'=>'🏠 العقارات', 'image'=>'realestate.jpg'],
    ['type'=>'tools', 'name'=>'🛠️ الأدوات', 'image'=>'tools.jpg'],
    ['type'=>'electronics', 'name'=>'💻 الإلكترونيات', 'image'=>'electronics.jpg'],
    ['type'=>'services', 'name'=>'⚙️ الخدمات', 'image'=>'services.jpg'],
    ['type'=>'clothes', 'name'=>'👕 الملابس', 'image'=>'clothes.jpg'],
    ['type'=>'software', 'name'=>'💾 البرامج', 'image'=>'software.jpg'],
    ['type'=>'cars', 'name'=>'🚗 السيارات', 'image'=>'cars.jpg'],
    ['type'=>'home_tools', 'name'=>'🏡 أدوات منزلية', 'image'=>'home_tools.jpg'],
    ['type'=>'accessories', 'name'=>'🎀 إكسسوارات', 'image'=>'accessories.jpg'],
    ['type'=>'furniture', 'name'=>'🛋️ أثاث منزلي', 'image'=>'furniture.jpg'],
    ['type'=>'kitchen_tools', 'name'=>'🍴 أدوات المطبخ', 'image'=>'kitchen_tools.jpg'],
    ['type'=>'car_parts', 'name'=>'🔧 قطع غيار السيارات', 'image'=>'car_parts.jpg'],
    ['type'=>'books', 'name'=>'📚 الكتب', 'image'=>'books.jpg'],
    ['type'=>'toys', 'name'=>'🧸 الألعاب', 'image'=>'toys.jpg'],
    ['type'=>'cosmetics', 'name'=>'💄 مستحضرات تجميل', 'image'=>'cosmetics.jpg'],
    ['type'=>'sports', 'name'=>'🏀 الرياضة', 'image'=>'sports.jpg'],
    ['type'=>'music', 'name'=>'🎵 الموسيقى', 'image'=>'music.jpg'],
    ['type'=>'pets', 'name'=>'🐶 الحيوانات الأليفة', 'image'=>'pets.jpg']
];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>CyberShop</title>
<style>
:root{
    --bg-dark:#141414;
    --bg-card:#1c1c1c;
    --primary:#e50914;
    --text:#ffffff;
    --text-soft:#b3b3b3;
    --radius:15px;
    --transition:.25s;
}

body{
    background: var(--bg-dark);
    color: var(--text);
    font-family: Arial, sans-serif;
    margin:0; padding:0;
}

/* Header مع شعار */
header{
    background: var(--bg-dark);
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #333;
    position: sticky;
    top:0;
    z-index:50;
}

header .logo {
    display:flex;
    align-items:center;
}

header .logo img {
    height:50px;
    margin-right:10px;
}

header h1{
    color: var(--primary);
    font-weight:900;
    font-size:22px;
}

header nav a{
    color:#fff;
    margin:0 10px;
    text-decoration:none;
    font-weight:bold;
    transition: color var(--transition);
}

header nav a:hover{
    color: var(--primary);
}

/* Top Ad */
.top-ad{
    background: var(--bg-card);
    padding: 20px;
    text-align:center;
    margin-bottom: 15px;
    border-radius: var(--radius);
}

.top-ad img {
    width:100%;
    height:250px;
    object-fit:cover;
    border-radius: var(--radius);
    transition: opacity 0.5s;
}

.top-ad a{
    display:inline-block;
    margin-top:10px;
    padding:8px 16px;
    background: var(--primary);
    color:#fff;
    text-decoration:none;
    border-radius: var(--radius);
    transition: background 0.2s;
}

.top-ad a:hover{
    background:#b00610;
}

/* شريط البحث */
.search-bar {
    text-align: center;
    margin: 15px 0 10px 0;
}

.search-bar form {
    display: inline-flex;
    background: #1c1c1c;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 3px 6px rgba(0,0,0,0.4);
}

.search-bar input[type="text"] {
    padding: 8px 15px;
    border: none;
    outline: none;
    background: #141414;
    color: #fff;
    font-size: 14px;
    width: 250px;
}

.search-bar input[type="text"]::placeholder {
    color: #aaa;
}

.search-bar button {
    padding: 8px 15px;
    border: none;
    background: #e50914;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s;
}

.search-bar button:hover {
    background: #b00610;
}

/* شريط متحرك تحت البحث */
.search-marquee {
    background: #1c1c1c;
    color: #e50914;
    padding: 8px 0;
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 20px;
}
.search-marquee marquee img {
    vertical-align: middle;
    margin: 0 5px;
}

/* الأقسام */
.categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    padding: 30px;
}

.category-card img {
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius: var(--radius);
}
.category-card h3 {
    margin-top: 5px;
    font-size: 14px;
}

/* المنتجات */
.product-card{
    display:inline-block;
    width:220px;
    margin:10px;
    padding:10px;
    background: var(--bg-card);
    border-radius: var(--radius);
    text-align:center;
    box-shadow:0 3px 6px rgba(0,0,0,0.5);
    transition: transform 0.2s, box-shadow 0.2s;
    vertical-align: top;
    color:#fff;
}
.product-card:hover{
    transform:scale(1.05);
    box-shadow: 0 6px 12px rgba(229,9,20,0.5);
}
.product-card img{
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius: var(--radius);
}
.product-card h3{
    font-size:16px;
    margin:10px 0 5px 0;
}
.product-card .price{
    font-size:14px;
    font-weight:bold;
    color: var(--primary);
    margin-bottom:5px;
}
.product-card button{
    padding:7px 12px;
    border:none;
    background-color: var(--primary);
    color:#fff;
    border-radius:8px;
    cursor:pointer;
    transition: background-color 0.2s;
    margin-top:5px;
}
.product-card button:hover{
    background-color:#b00610;
}

/* Grid containers */
#product-container{
    text-align:center;
    padding:20px;
}

/* Responsive */
@media(max-width:768px){
    .product-card, .category-card{ width:45%; margin:5px; }
}
@media(max-width:480px){
    .product-card, .category-card{ width:90%; margin:10px auto; }
}

/* شريط متحرك أسفل الصفحة */
.footer-marquee {
    background: #1c1c1c;
    color: #e50914;
    padding: 10px 0;
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 100;
}
.footer-marquee marquee img {
    vertical-align: middle;
    margin: 0 5px;
}
</style>
</head>
<body>

<header>
    <div class="logo">
        <img src="images/logo.png" alt="CyberShop Logo">
        <h1>CyberShop</h1>
    </div>
    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="cart.php">السلة <span id="cart-counter">0</span></a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">ملفي</a>
            <a href="add.php">إضافة منتج</a>
            <a href="check_admin.php">غرفة التحكم</a>
            <a href="logout.php">خروج</a>
        <?php else: ?>
            <a href="login.php">دخول</a>
            <a href="signup.php">تسجيل</a>
        <?php endif; ?>
    </nav>
</header>

<section class="top-ad">
    <div class="ad-content">
        <img id="ad-img" src="images/ads/ad1.jpg" alt="إعلان CyberShop">
        <h2>🔥 إعلان CyberShop</h2>
        <p>تم إطلاق أقسام جديدة داخل الموقع – تصفّح الآن</p>
        <a href="#">اكتشف الجديد</a>
    </div>
</section>

<section class="search-bar">
    <form action="category.php" method="get">
        <input type="text" name="q" placeholder="ابحث عن قسم أو منتج..." />
        <button type="submit">بحث</button>
    </form>
</section>

<div class="search-marquee">
    <marquee behavior="scroll" direction="left" scrollamount="4">
        <img src="images/logo.png" alt="Logo" style="height:25px;">
        CyberShop - اكتشف أحدث المنتجات والخدمات الآن! 🌟 كل يوم عروض جديدة! 🛒
        <img src="images/logo.png" alt="Logo" style="height:25px;">
    </marquee>
</div>

<section class="categories">
<?php foreach($categories as $cat): ?>
<div class="category-card">
    <a href="category.php?type=<?= $cat['type'] ?>">
        <img src="images/products/cats/<?= $cat['image'] ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
    </a>
    <h3><?= htmlspecialchars($cat['name']) ?></h3>
</div>
<?php endforeach; ?>
</section>

<main id="product-container">
<?php if(count($products) === 0): ?>
    <p style="text-align:center;width:100%">لا توجد منتجات حاليًا</p>
<?php endif; ?>

<?php foreach($products as $prod): ?>
<div class="product-card">
    <a href="product.php?id=<?= $prod['id'] ?>">
        <img src="images/products/<?= htmlspecialchars($prod['image']) ?>" alt="">
    </a>
    <h3><?= htmlspecialchars($prod['name']) ?></h3>
    <p class="price"><?= $prod['price'] ?> $</p>
    <button onclick="addToCart(
        <?= $prod['id'] ?>,
        '<?= htmlspecialchars($prod['name'], ENT_QUOTES) ?>',
        <?= $prod['price'] ?>,
        '<?= $prod['image'] ?>'
    )">أضف إلى السلة</button>
</div>
<?php endforeach; ?>
</main>

<div class="footer-marquee">
    <marquee behavior="scroll" direction="left" scrollamount="4">
        <img src="images/logo.png" alt="Logo" style="height:25px;">
        CyberShop - أحدث المنتجات والخدمات متوفرة الآن! 🌟 CyberShop - كل جديد في عالم التسوق! 🛒
        <img src="images/logo.png" alt="Logo" style="height:25px;">
    </marquee>
</div>

<script>
// تغيير صور الإعلان تلقائياً كل 3 ثواني
const adImages = [
    'images/ads/ad1.jpg','images/ads/ad2.jpg','images/ads/ad3.jpg','images/ads/ad4.jpg','images/ads/ad5.jpg',
    'images/ads/ad6.jpg','images/ads/ad7.jpg','images/ads/ad8.jpg','images/ads/ad9.jpg','images/ads/ad10.jpg'
];

let currentAd = 0;
const adElement = document.getElementById('ad-img');
setInterval(() => {
    currentAd = (currentAd + 1) % adImages.length;
    adElement.src = adImages[currentAd];
}, 3000);
</script>
<script src="js/main.js"></script>
</body>
</html>



