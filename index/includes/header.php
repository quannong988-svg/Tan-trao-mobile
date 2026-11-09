<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();
$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC")->fetchAll();
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$pageTitle = $pageTitle ?? 'Tân Trào Mobile - Điện Thoại Chính Hãng';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="topbar">
  <div class="container topbar-inner">
    <span>Chúc bạn mua sắm vui vẻ, bạn cần thông tin gì hôm nay?</span>
    <div class="topbar-right">
      <span>Email hỗ trợ: <a href="mailto:tantraomobilecskh@gmail.com">tantraomobilecskh@gmail.com</a></span>
      <a href="#">Đăng nhập</a> / <a href="#">Đăng ký</a>
    </div>
  </div>
</div>
<header class="main-header">
  <div class="container header-inner">
    <a href="<?= BASE_URL ?>index.php" class="logo">
      <span class="logo-icon">📱</span>
      Tân Trào <span>Mobile</span>
    </a>
    <form action="<?= BASE_URL ?>search.php" method="get" class="search-box">
      <input type="text" name="q" placeholder="Bạn cần tìm sản phẩm gì?" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      <button type="submit">🔍</button>
    </form>
    <div class="header-actions">
      <a href="tel:0969796661" class="info-box hotline">
        <span class="info-icon">📞</span>
        <span class="info-text">
          <span class="info-label">Gọi mua hàng</span>
          <span class="info-value">0969.796.661</span>
        </span>
      </a>
      <div class="info-box">
        <span class="info-icon">🏬</span>
        <span class="info-text">
          <span class="info-label">Tân Trào Mobile</span>
          <span class="info-value">1 cửa hàng</span>
        </span>
      </div>
      <a href="<?= BASE_URL ?>cart.php" class="info-box cart-link">
        <span class="info-icon">🛒</span>
        <span class="info-text">
          <span class="info-label">Giỏ hàng</span>
          <span class="info-value">(<?= (int)$cartCount ?>) sản phẩm</span>
        </span>
      </a>
    </div>
  </div>
  <nav class="main-nav">
    <div class="container nav-inner">
      <ul>
        <?php foreach ($categories as $cat): ?>
          <li><a href="<?= BASE_URL ?>category.php?slug=<?= urlencode($cat['slug']) ?>"><?= htmlspecialchars($cat['icon'] . ' ' . $cat['name']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="#">Sửa chữa điện thoại</a></li>
        <li><a href="#">Tin tức công nghệ</a></li>
      </ul>
    </div>
  </nav>
</header>
<main>