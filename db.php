<?php
try{
    $conn = new PDO(
        "mysql:host=localhost;dbname=cybershop;charset=utf8",
        "root",
        "1234"
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("DB Error: " . $e->getMessage());
}
