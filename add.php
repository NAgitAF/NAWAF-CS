<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['add_product'])){
    $stmt = $conn->prepare("
        INSERT INTO products(name,description,price,image,user_id)
        VALUES(?,?,?,?,?)
    ");

    $mainImage = "default.png";
    if(!empty($_FILES['images']['name'][0])){
        $mainImage = time().'_'.$_FILES['images']['name'][0];
        move_uploaded_file(
            $_FILES['images']['tmp_name'][0],
            "images/products/$mainImage"
        );
    }

    $stmt->execute([
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $mainImage,
        $_SESSION['user_id']
    ]);

    $product_id = $conn->lastInsertId();

    foreach($_FILES['images']['name'] as $k=>$name){
        $img = time().'_'.$name;
        move_uploaded_file(
            $_FILES['images']['tmp_name'][$k],
            "images/products/$img"
        );

        $conn->prepare("
            INSERT INTO product_images(product_id,image)
            VALUES(?,?)
        ")->execute([$product_id,$img]);
    }

    header("Location: profile.php");
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>إضافة منتج</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<form method="post" enctype="multipart/form-data">
    <input name="name" placeholder="اسم المنتج" required>
    <textarea name="description" placeholder="الوصف" required></textarea>
    <input type="number" name="price" placeholder="السعر" required>
    <input type="file" name="images[]" multiple required>
    <button name="add_product">حفظ</button>
</form>

</body>
</html>
