<?php
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$pdoTmp = getDB();
$slug = $_GET['slug'] ?? '';
$currentCat = null;
if ($slug !== 'all') {
    $stmt = $pdoTmp->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    $currentCat = $stmt->fetch();
    if (!$currentCat) {
        $pageTitle = 'Không tìm thấy danh mục';
        require_once __DIR__ . '/includes/header.php';
        echo '<div class="container"><p style="padding:40px 0">Danh mục không tồn tại.</p></div>';
        require_once __DIR__ . '/includes/footer.php';
        exit;
    }
}
$pageTitle = $currentCat ? $currentCat['name'] . ' - Tân Trào Mobile' : 'Tất cả sản phẩm - Tân Trào Mobile';
require_once __DIR__ . '/includes/header.php';
if ($currentCat) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC");
    $stmt->execute([$currentCat['id']]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
}
$products = $stmt->fetchAll();
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
    <span><?= $currentCat ? htmlspecialchars($currentCat['name']) : 'Tất cả sản phẩm' ?></span>
  </div>
  <div class="cat-chips">
    <a class="cat-chip <?= !$currentCat ? 'active' : '' ?>" href="<?= BASE_URL ?>category.php?slug=all">Tất cả</a>
    <?php foreach ($categories as $cat): ?>
      <a class="cat-chip <?= ($currentCat && $currentCat['id'] == $cat['id']) ? 'active' : '' ?>"
         href="<?= BASE_URL ?>category.php?slug=<?= urlencode($cat['slug']) ?>">
        <?= htmlspecialchars($cat['icon'] . ' ' . $cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>
  <section class="section">
    <div class="section-title">
      <h2><?= $currentCat ? htmlspecialchars($currentCat['name']) : 'Tất cả sản phẩm' ?> <span>(<?= count($products) ?>)</span></h2>
    </div>
    <?php if (!$products): ?>
      <p style="padding:30px 0;color:#666;">Chưa có sản phẩm nào trong danh mục này.</p>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $p):
          $discount = discountPercent($p['price'], $p['sale_price']); ?>
          <div class="product-card">
            <a href="<?= BASE_URL ?>product.php?slug=<?= urlencode($p['slug']) ?>">
              <div class="product-thumb">
                <?php if ($discount > 0): ?><span class="badge-sale">-<?= $discount ?>%</span><?php endif; ?>
                <?php if ($p['is_featured']): ?><span class="badge-featured">Nổi bật</span><?php endif; ?>
                <?php productImg($p); ?>
              </div>
              <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="product-price">
                  <span class="price-now"><?= formatPrice($p['price']) ?></span>
                  <?php if ($p['sale_price']): ?><span class="price-old"><?= formatPrice($p['sale_price']) ?></span><?php endif; ?>
                </div>
                <div class="product-sold">Đã bán <?= (int)$p['sold_count'] ?></div>
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