<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tantraomobile');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('BASE_URL', '/TQU/ttmobile/');
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage() .
                '<br>Hãy chắc chắn bạn đã: (1) Bật Apache & MySQL trong XAMPP, (2) Import file database.sql vào phpMyAdmin.');
        }
    }
    return $pdo;
}
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . '₫';
}
function discountPercent($price, $salePrice) {
    if (!$salePrice || $salePrice <= $price) return 0;
    return round((($salePrice - $price) / $salePrice) * 100);
}