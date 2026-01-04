<?php
session_start();
include 'db.php';

if(isset($_GET['id'])){
    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id=? AND user_id=?"
    );
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
}
header("Location: profile.php");
exit;
