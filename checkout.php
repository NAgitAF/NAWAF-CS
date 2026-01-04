<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>إتمام الشراء</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>إتمام الشراء</h1>
    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="cart.php">السلة</a>
    </nav>
</header>

<form onsubmit="sendOrder(event)">
    <h2>بيانات العميل</h2>

    <input type="text" id="name" placeholder="الاسم الكامل" required>
    <input type="text" id="phone" placeholder="رقم الهاتف" required>
    <textarea id="address" placeholder="العنوان" required></textarea>

    <button type="submit">تأكيد الطلب</button>
</form>

<script src="js/main.js"></script>
</body>
</html>
