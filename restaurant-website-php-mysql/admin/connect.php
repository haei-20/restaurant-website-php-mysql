<?php
$host = "localhost"; // Địa chỉ máy chủ
$dbname = "restaurant_website"; // Tên cơ sở dữ liệu
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage());
}
?>