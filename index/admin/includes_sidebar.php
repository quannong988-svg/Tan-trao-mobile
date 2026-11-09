<div class="admin-sidebar">
  <h3>📱 Tân Trào Mobile Admin</h3>
  <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">📊 Tổng quan</a>
  <a href="products.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['products.php','product_form.php']) ? 'active' : '' ?>">📦 Sản phẩm</a>
  <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : '' ?>">🗂️ Danh mục</a>
  <a href="orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">🧾 Đơn hàng</a>
  <a href="../index.php" target="_blank">🌐 Xem website</a>
  <a href="logout.php">🚪 Đăng xuất</a>
</div>
