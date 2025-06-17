<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'restaurant_website');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $con = new PDO(
        "mysql:host=" . DB_HOST . 
        ";dbname=" . DB_NAME . 
        ";charset=" . DB_CHARSET, 
        DB_USER, 
        DB_PASS
    );
    
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (PDOException $e) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
}
?>