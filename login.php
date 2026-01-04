<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])){

        // 🔐 SECURITY ADD (START)
        session_regenerate_id(true); // حماية الجلسة
        // 🔐 SECURITY ADD (END)

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // 🔐 SECURITY ADD (START)
        // جلب دور المستخدم من الجداول الجديدة بدون تعديل users
        $roleStmt = $conn->prepare("
            SELECT r.role_name
            FROM roles r
            JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = ?
            LIMIT 1
        ");
        $roleStmt->execute([$user['id']]);
        $_SESSION['role'] = $roleStmt->fetchColumn() ?: 'user';
        // 🔐 SECURITY ADD (END)

        header("Location: index.php");
        exit;
    } else {
        $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<form class="login-form" method="post">
    <h2>تسجيل الدخول</h2>

    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>

    <button type="submit" name="login">دخول</button>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <p>ليس لديك حساب؟ <a href="signup.php">إنشاء حساب</a></p>
</form>

</body>
</html>
