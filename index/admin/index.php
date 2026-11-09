<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$revenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tổng quan - Admin Tân Trào Mobile</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <?php include __DIR__ . '/includes_sidebar.php'; ?>
  <div class="admin-content">
    <h2>Xin chào, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?> 👋</h2>
    <div class="promo-strip" style="margin-top:20px;">
      <div class="promo-item"><strong style="font-size:22px;"><?= $productCount ?></strong><span>Sản phẩm</span></div>
      <div class="promo-item"><strong style="font-size:22px;"><?= $categoryCount ?></strong><span>Danh mục</span></div>
      <div class="promo-item"><strong style="font-size:22px;"><?= $orderCount ?></strong><span>Đơn hàng</span></div>
      <div class="promo-item"><strong style="font-size:18px;"><?= formatPrice($revenue) ?></strong><span>Doanh thu</span></div>
    </div>
  </div>
</div>
</body>
</html>
