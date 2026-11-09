<?php
$pageTitle = 'Tân Trào Mobile - Điện Thoại Chính Hãng Giá Tốt';
require_once __DIR__ . '/includes/header.php';
$featured    = $pdo->query("SELECT * FROM products WHERE is_featured = 1 ORDER BY sold_count DESC LIMIT 8")->fetchAll();
$bestSeller  = $pdo->query("SELECT * FROM products ORDER BY sold_count DESC LIMIT 8")->fetchAll();
$liquidation = $pdo->query("SELECT * FROM products WHERE is_liquidation = 1 LIMIT 8")->fetchAll();
function getProductImageFileName($p) {
    if (!empty($p['image'])) {
        $img = trim($p['image']);
        if (!preg_match('/\.(jpg|jpeg|png|webp)$/i', $img)) {
            $img .= '.jpg';
        }
        return $img;
    }
    $name = mb_strtolower($p['name'] ?? '', 'UTF-8');
    if (!empty($p['is_liquidation']) || strpos($name, 'thanh lý') !== false) {
        return '14thanhli.jpg';
    }
    if (strpos($name, '12 pro max') !== false) {
        return '12promax.jpg';
    }
    if (strpos($name, '14 pro max') !== false) {
        return '14promax.jpg';
    }
    if (strpos($name, '16 pro') !== false) {
        return '16pro.jpg';
    }
    if (strpos($name, '17 ultra') !== false) {
        return '17ultra.jpg';
    }
    if (strpos($name, '11 pro') !== false) {
        return '11pro.jpg';
    }
    if (strpos($name, '16') !== false) {
        return '16.jpg';
    }
    if (strpos($name, '12') !== false || strpos($name, '64gb') !== false) {
        return '1264gb.jpg';
    }
    if (strpos($name, 'a57') !== false) {
        return 'a57.jpg';
    }
    if (strpos($name, 'reno11') !== false || strpos($name, 'reno 11') !== false) {
        return 'reno11.jpg';
    }
    if (strpos($name, 's24') !== false) {
        return 's24.jpg';
    }
    if (strpos($name, 'turbo') !== false || strpos($name, 'redmi') !== false) {
        return 'turbo4.jpg';
    }
    if (strpos($name, 'z11x') !== false || strpos($name, 'vivo') !== false) {
        return 'z11x.jpg';
    }
    return '16pro.jpg';
}
function productImg($p) {
    $imgFile = getProductImageFileName($p);
    $src = BASE_URL . 'images/' . $imgFile;

    echo '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($p['name']) . '" '
       . 'onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement(\'span\'),{textContent:\'📱\',style:\'font-size:48px\'}));">';
}
function renderProductCard($p) {
    $discount = discountPercent($p['price'], $p['sale_price']);
    ?>
    <div class="product-card">
      <a href="<?= BASE_URL ?>product.php?slug=<?= urlencode($p['slug']) ?>">
        <div class="product-thumb">
          <?php if ($discount > 0): ?><span class="badge-sale">-<?= $discount ?>%</span><?php endif; ?>
          <?php if (!empty($p['is_featured'])): ?><span class="badge-featured">Nổi bật</span><?php endif; ?>
          <?php productImg($p); ?>
        </div>
        <div class="product-info">
          <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
          <div class="product-price">
            <span class="price-now"><?= formatPrice($p['price']) ?></span>
            <?php if (!empty($p['sale_price'])): ?><span class="price-old"><?= formatPrice($p['sale_price']) ?></span><?php endif; ?>
          </div>
          <div class="product-sold">Đã bán <?= (int)($p['sold_count'] ?? 0) ?></div>
        </div>
      </a>
      <div style="padding:0 12px 12px;">
        <button class="btn-add-cart" data-id="<?= $p['id'] ?>">🛒 Thêm vào giỏ</button>
      </div>
    </div>
    <?php
}
?>
<div class="banner-wrap">
  <section class="banner">
    <img src="<?= BASE_URL ?>images/banner.jpg.jpg" 
         alt="Tân Trào Mobile" 
         onerror="if(!this.dataset.retry){this.dataset.retry='1';this.src='<?= BASE_URL ?>images/banner.jpg.jpg';}else{this.style.display='none';this.parentElement.innerHTML+='<div class=\'container\'><h1>Tân Trào Mobile - Điện Thoại Chính Hãng Tuyên Quang</h1><p>iPhone • Samsung • OPPO • Xiaomi • Vivo — Giá tốt, bảo hành uy tín, trả góp 0%</p></div>';}">
  </section>
</div>
<div class="container">
  <div class="promo-strip">
    <div class="promo-item"><strong>✅ Hàng chính hãng</strong><span>Đa dạng và chuyên sâu</span></div>
    <div class="promo-item"><strong>🔁 1 đổi 1 15 tháng</strong><span>Kể từ ngày mua hàng</span></div>
    <div class="promo-item"><strong>💯 Cam kết 100%</strong><span>Chất lượng sản phẩm</span></div>
    <div class="promo-item"><strong>🕐 Dùng thử 45 ngày</strong><span>Theo từng sản phẩm</span></div>
  </div>
  <?php if (!empty($featured)): ?>
  <section class="section">
    <div class="section-title">
      <h2>🔥 <span>Sản phẩm nổi bật</span></h2>
      <a href="<?= BASE_URL ?>category.php?slug=all">Xem tất cả</a>
    </div>
    <div class="product-grid">
      <?php foreach ($featured as $p) renderProductCard($p); ?>
    </div>
  </section>
  <?php endif; ?>
  <?php if (!empty($bestSeller)): ?>
  <section class="section">
    <div class="section-title">
      <h2>⭐ <span>Sản phẩm bán chạy</span></h2>
      <a href="<?= BASE_URL ?>category.php?slug=all">Xem tất cả</a>
    </div>
    <div class="product-grid">
      <?php foreach ($bestSeller as $p) renderProductCard($p); ?>
    </div>
  </section>
  <?php endif; ?>
  <?php if (!empty($liquidation)): ?>
  <section class="section">
    <div class="section-title">
      <h2>💥 <span>Máy thanh lý</span></h2>
      <a href="<?= BASE_URL ?>category.php?slug=thanh-ly">Xem tất cả</a>
    </div>
    <div class="product-grid">
      <?php foreach ($liquidation as $p) renderProductCard($p); ?>
    </div>
  </section>
  <?php endif; ?>
  <section class="section" id="about">
    <div class="section-title"><h2>ℹ️ <span>Về Tân Trào Mobile</span></h2></div>
    <p>Tân Trào Mobile là hệ thống bán lẻ điện thoại di động uy tín, chuyên cung cấp các dòng sản phẩm iPhone, Samsung, OPPO, Xiaomi, Vivo chính hãng với mức giá cạnh tranh nhất thị trường. Hỗ trợ trả góp 0%, thu cũ đổi mới và sửa chữa chuyên nghiệp.</p>
  </section>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>