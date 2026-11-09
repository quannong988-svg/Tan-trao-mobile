<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
header('Content-Type: application/json');
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // [product_id => qty]
$action = $_POST['action'] ?? '';
$id = (int)($_POST['id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
$pdo = getDB();
switch ($action) {
    case 'add':
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetch()) {
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
        }
        break;
    case 'update':
        if ($qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
        break;
    case 'remove':
        unset($_SESSION['cart'][$id]);
        break;
    case 'clear':
        $_SESSION['cart'] = [];
        break;
}
echo json_encode([
    'success' => true,
    'cartCount' => array_sum($_SESSION['cart']),
]);
