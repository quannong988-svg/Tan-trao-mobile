<?php
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$pdoTmp = getDB();
$q = trim($_GET['q'] ?? '');
$pageTitle = 'Kết quả tìm kiếm: ' . $q . ' - Tân Trào Mobile';
require_once __DIR__ . '/includes/header.php';
$products = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR brand LIKE ? ORDER BY created_at DESC");
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like]);
    $products = $stmt->fetchAll();
}
function productImg($p) {
    // Ảnh sản phẩm lấy từ thư mục images/, đặt tên file trùng với cột "image" trong DB.
    $src = BASE_URL . 'images/' . $p['image'];
    echo '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($p['name']) . '" '
       . 'onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement(\'span\'),{textContent:\'📱\',style:\'font-size:48px\'}));">';
}
?>
<div class="container">
  <div class="breadcrumb"><a href="<?= BASE_URL ?>index.php">Trang chủ</a> / Tìm kiếm</div>
  <section class="section">
    <div class="section-title"><h2>🔍 Kết quả tìm kiếm cho "<?= htmlspecialchars($q) ?>" <span>(<?= count($products) ?>)</span></h2></div>
    <?php if (!$products): ?>
      <p style="padding:30px 0;color:#666;">Không tìm thấy sản phẩm phù hợp.</p>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $p):
          $discount = discountPercent($p['price'], $p['sale_price']); ?>
          <div class="product-card">
            <a href="<?= BASE_URL ?>product.php?slug=<?= urlencode($p['slug']) ?>">
              <div class="product-thumb">
                <?php if ($discount > 0): ?><span class="badge-sale">-<?= $discount ?>%</span><?php endif; ?>
                <?php productImg($p); ?>
              </div>
              <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="product-price">
                  <span class="price-now"><?= formatPrice($p['price']) ?></span>
                  <?php if ($p['sale_price']): ?><span class="price-old"><?= formatPrice($p['sale_price']) ?></span><?php endif; ?>
                </div>
              </div>
            </a>
            <div style="padding:0 12px 12px;">
              <button class="btn-add-cart" data-id="<?= $p['id'] ?>">🛒 Thêm vào giỏ</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>