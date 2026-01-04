<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>السلة</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>CyberShop</h1>
    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="checkout.php">إتمام الشراء</a>
    </nav>
</header>

<div id="cart-items"></div>

<div class="cart-summary">
    <h3 id="total-price"></h3>
    <a href="checkout.php">
        <button id="checkoutBtn">إتمام الشراء</button>
    </a>
</div>

<script src="js/main.js"></script>
</body>
</html>
