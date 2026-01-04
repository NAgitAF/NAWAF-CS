<?php
session_start();
require_once 'db.php';

if(isset($_POST['signup'])){
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // التحقق من عدم تكرار البريد
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $error = "البريد مستخدم مسبقًا";
    }else{
        // إدخال المستخدم
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$name, $email, $password]);

        // ID المستخدم الجديد
        $user_id = $conn->lastInsertId();

        // إعطاء دور user افتراضي (role_id = 3)
        $stmt = $conn->prepare("
            INSERT INTO user_roles (user_id, role_id)
            VALUES (?, 3)
        ");
        $stmt->execute([$user_id]);

        // تسجيل الدخول تلقائيًا
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>تسجيل حساب</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<form method="post">
    <h2>إنشاء حساب</h2>

    <input type="text" name="name" placeholder="الاسم" required>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>

    <button type="submit" name="signup">تسجيل</button>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
</form>

</body>
</html>

