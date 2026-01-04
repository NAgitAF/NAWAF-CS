<pre><?php print_r($users); ?></pre>

<?php


session_start();
require_once '../db.php';
require_once '../auth.php';
require_once '../permissions.php';

// تحقق من الصلاحية
if (!hasPermission($conn, $_SESSION['user_id'], 'manage_users')) {
    die('❌ لا تملك صلاحية إدارة المستخدمين');
}

// =====================
// حذف مستخدم (آمن)
// =====================
if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];

    // منع حذف نفسك
    if ($user_id == $_SESSION['user_id']) {
        $error = "❌ لا يمكنك حذف حسابك";
    } else {
        // تحقق هل المستخدم أدمن
        $stmt = $conn->prepare("
            SELECT r.role_name
            FROM user_roles ur
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $role = $stmt->fetchColumn();

        // عدد الأدمن
        $adminsCount = $conn->query("
            SELECT COUNT(*) FROM user_roles ur
            JOIN roles r ON ur.role_id = r.id
            WHERE r.role_name = 'admin'
        ")->fetchColumn();

        if ($role === 'admin' && $adminsCount <= 1) {
            $error = "❌ لا يمكن حذف آخر أدمن في النظام";
        } else {
            // حذف الأدوار
            $conn->prepare("DELETE FROM user_roles WHERE user_id=?")->execute([$user_id]);
            // حذف المستخدم
            $conn->prepare("DELETE FROM users WHERE id=?")->execute([$user_id]);
            $success = "✅ تم حذف المستخدم بنجاح";
        }
    }
}

// =====================
// تحديث الدور
// =====================
if (isset($_POST['change_role'])) {
    $user_id = (int)$_POST['user_id'];
    $role_id = (int)$_POST['role_id'];

    if ($user_id !== $_SESSION['user_id']) {
        $conn->prepare("DELETE FROM user_roles WHERE user_id=?")->execute([$user_id]);
        $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$user_id, $role_id]);
    }
}

// جلب المستخدمين
$users = $conn->query("
    SELECT 
        u.id, u.name, u.email,
        IFNULL(ur.role_id, 3) AS role_id,
        IFNULL(r.role_name, 'user') AS role_name
    FROM users u
    LEFT JOIN user_roles ur ON u.id = ur.user_id
    LEFT JOIN roles r ON ur.role_id = r.id
    ORDER BY u.id ASC
")->fetchAll(PDO::FETCH_ASSOC);

// جلب الأدوار
$roles = $conn->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة المستخدمين</title>
<link rel="stylesheet" href="../css/style.css">
<style>
table{width:95%;margin:auto;border-collapse:collapse}
th,td{padding:10px;text-align:center}
th{background:#111;color:#fff}
td{background:#fff;border-bottom:1px solid #eee}
select,button{padding:6px 10px}
.danger{background:#c62828;color:#fff;border:none;border-radius:6px}
.success{color:green;text-align:center}
.error{color:red;text-align:center}
</style>
</head>
<body>

<div class="admin-container">

    <div class="admin-title">👥 إدارة المستخدمين</div>

    <?php if(isset($error)) echo "<div class='admin-error'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='admin-success'>$success</div>"; ?>

    <div class="admin-card">
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الدور</th>
                <th>حفظ</th>
                <th>حذف</th>
            </tr>

            <?php foreach($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>

                <td>
                    <?php if($u['id']==$_SESSION['user_id']): ?>
                        <strong><?= $u['role_name'] ?></strong>
                    <?php else: ?>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <select name="role_id" class="admin-select">
                                <?php foreach($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= $r['id']==$u['role_id']?'selected':'' ?>>
                                        <?= $r['role_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if($u['id']!=$_SESSION['user_id']): ?>
                        <button class="btn btn-dark" name="change_role">حفظ</button>
                        </form>
                    <?php else: ?> 🔒 <?php endif; ?>
                </td>

                <td>
                    <?php if($u['id']!=$_SESSION['user_id']): ?>
                        <form method="post" onsubmit="return confirm('هل أنت متأكد؟');">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button class="btn btn-danger" name="delete_user">حذف</button>
                        </form>
                    <?php else: ?> — <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</body>

</html>

