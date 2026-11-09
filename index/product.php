<?php
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$pdoTmp = getDB();
$slug = $_GET['slug'] ?? '';
$stmt = $pdoTmp->prepare("SELECT p.*, c.name AS cat_name, c.slug AS cat_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ?");
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) {
    $pageTitle = 'Không tìm thấy sản phẩm';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container"><p style="padding:40px 0">Sản phẩm không tồn tại hoặc đã bị gỡ bỏ.</p></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$pageTitle = $product['name'] . ' - Tân Trào Mobile';
require_once __DIR__ . '/includes/header.php';
$relStmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
$relStmt->execute([$product['category_id'], $product['id']]);
$related = $relStmt->fetchAll();
$discount = discountPercent($product['price'], $product['sale_price']);
function productImg($p) {
    // Ảnh sản phẩm lấy từ thư mục images/, đặt tên file trùng với cột "image" trong DB.
    $src = BASE_URL . 'images/' . $p['image'];
    echo '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($p['name']) . '" '
       . 'onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement(\'span\'),{textContent:\'📱\',style:\'font-size:48px\'}));">';
}
?>
<div class="container">
  <div class="breadcrumb">
    <a href="<?= BASE_URL ?>index.php" class="crumb-home">🏠</a>
    <span class="sep">›</span>
    <a href="<?= BASE_URL ?>category.php?slug=<?= urlencode($product['cat_slug']) ?>"><?= htmlspecialchars($product['cat_name']) ?></a>
    <span class="sep">›</span>
    <span><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <div class="product-detail">
    <div class="detail-thumb">
      <?php productImg($product); ?>
      <div class="thumb-actions">
        <button type="button" title="Yêu thích">♡</button>
        <button type="button" title="So sánh">⇄</button>
      </div>
    </div>
    <div class="detail-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <div class="product-sold">Thương hiệu: <strong><?= htmlspecialchars($product['brand']) ?></strong> · Đã bán <?= (int)$product['sold_count'] ?></div>
      <div class="detail-price">
        <span class="price-now"><?= formatPrice($product['price']) ?></span>
        <?php if ($product['sale_price']): ?>
          <span class="price-old"><?= formatPrice($product['sale_price']) ?></span>
          <span class="badge-sale" style="position:static;display:inline-block;">-<?= $discount ?>%</span>
        <?php endif; ?>
      </div>
      <div class="detail-meta">
        <div>📦 Tình trạng: <?= $product['stock'] > 0 ? 'Còn hàng (' . (int)$product['stock'] . ' sản phẩm)' : 'Tạm hết hàng' ?></div>
        <div>🚚 Giao hàng toàn quốc, nhận hàng trong 1-3 ngày</div>
        <div>🔁 Bảo hành chính hãng, 1 đổi 1 trong 15 tháng</div>
      </div>
      <div class="qty-box">
        <button type="button" class="qty-minus">−</button>
        <input type="number" id="quantity" value="1" min="1">
        <button type="button" class="qty-plus">+</button>
      </div>
      <button class="btn-primary btn-add-cart" data-id="<?= $product['id'] ?>">🛒 Thêm vào giỏ hàng</button>
      <div class="detail-desc">
        <h3>Mô tả sản phẩm</h3>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <?php if ($product['specs']): ?>
          <h3 style="margin-top:20px;">Thông số kỹ thuật</h3>
          <p><?= nl2br(htmlspecialchars($product['specs'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php if ($related): ?>
  <section class="section">
    <div class="section-title"><h2>Sản phẩm liên quan</h2></div>
    <div class="product-grid">
      <?php foreach ($related as $p):
        $d = discountPercent($p['price'], $p['sale_price']); ?>
        <div class="product-card">
          <a href="<?= BASE_URL ?>product.php?slug=<?= urlencode($p['slug']) ?>">
            <div class="product-thumb">
              <?php if ($d > 0): ?><span class="badge-sale">-<?= $d ?>%</span><?php endif; ?>
              <?php productImg($p); ?>
            </div>
            <div class="product-info">
              <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
              <div class="product-price"><span class="price-now"><?= formatPrice($p['price']) ?></span></div>
            </div>
          </a>
          <div style="padding:0 12px 12px;">
            <button class="btn-add-cart" data-id="<?= $p['id'] ?>">🛒 Thêm vào giỏ</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>