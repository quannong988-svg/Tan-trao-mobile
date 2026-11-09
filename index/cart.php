<?php
$pageTitle = 'Giỏ hàng - Tân Trào Mobile';
require_once __DIR__ . '/includes/header.php';
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $qty = $cart[$p['id']];
        $subtotal = $p['price'] * $qty;
        $total += $subtotal;
        $items[] = ['product' => $p, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $note = trim($_POST['note'] ?? '');
    if ($name && $phone && $items) {
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, note, total) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $phone, $address, $note, $total]);
        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, qty) VALUES (?,?,?,?,?)");
        foreach ($items as $it) {
            $itemStmt->execute([$orderId, $it['product']['id'], $it['product']['name'], $it['product']['price'], $it['qty']]);
        }
        $_SESSION['cart'] = [];
        $items = [];
        $total = 0;
        $message = "✅ Đặt hàng thành công! Mã đơn hàng #$orderId. Chúng tôi sẽ liên hệ bạn qua số $phone để xác nhận.";
    } else {
        $message = "❌ Vui lòng nhập đầy đủ họ tên và số điện thoại.";
    }
}
?>
<div class="container">
  <div class="breadcrumb"><a href="<?= BASE_URL ?>index.php">Trang chủ</a> / Giỏ hàng</div>
  <section class="section">
    <div class="section-title"><h2>🛒 <span>Giỏ hàng của bạn</span></h2></div>
    <?php if ($message): ?>
      <div class="alert <?= strpos($message, '✅') === 0 ? 'alert-success' : 'alert-error' ?>"><?= $message ?></div>
    <?php endif; ?>
    <?php if (!$items): ?>
      <div class="empty-cart">
        <p>Giỏ hàng của bạn đang trống.</p>
        <p style="margin-top:10px;"><a href="<?= BASE_URL ?>index.php" class="btn-primary" style="display:inline-block;">Tiếp tục mua sắm</a></p>
      </div>
    <?php else: ?>
      <table class="cart-table">
        <thead>
          <tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td class="cart-item-name"><?= htmlspecialchars($it['product']['name']) ?></td>
              <td><?= formatPrice($it['product']['price']) ?></td>
              <td>
                <input type="number" class="cart-qty-input" min="1" value="<?= $it['qty'] ?>"
                       onchange="updateCartQty(<?= $it['product']['id'] ?>, this.value)">
              </td>
              <td><?= formatPrice($it['subtotal']) ?></td>
              <td><button class="btn-remove" onclick="removeCartItem(<?= $it['product']['id'] ?>)">Xóa</button></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cart-summary">
        <div class="cart-summary-box">
          <div>Tạm tính: <?= formatPrice($total) ?></div>
          <div class="total">Tổng cộng: <?= formatPrice($total) ?></div>
        </div>
      </div>
      <form method="post" class="form-box">
        <h2>Thông tin đặt hàng</h2>
        <div class="form-group">
          <label>Họ và tên *</label>
          <input type="text" name="customer_name" required>
        </div>
        <div class="form-group">
          <label>Số điện thoại *</label>
          <input type="text" name="phone" required>
        </div>
        <div class="form-group">
          <label>Địa chỉ giao hàng</label>
          <input type="text" name="address">
        </div>
        <div class="form-group">
          <label>Ghi chú</label>
          <textarea name="note" rows="3"></textarea>
        </div>
        <button type="submit" name="checkout" class="btn-primary" style="width:100%;">Đặt hàng ngay</button>
      </form>
    <?php endif; ?>
  </section>
</div>
<script>
function updateCartQty(id, qty) {
  fetch(window.BASE_URL + 'cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=update&id=${id}&qty=${qty}`
  }).then(() => location.reload());
}
function removeCartItem(id) {
  fetch(window.BASE_URL + 'cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=remove&id=${id}`
  }).then(() => location.reload());
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
