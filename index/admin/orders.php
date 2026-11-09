<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

if (isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], (int)$_POST['order_id']]);
    header('Location: orders.php');
    exit;
}

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
$statuses = ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý đơn hàng - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <?php include __DIR__ . '/includes_sidebar.php'; ?>
  <div class="admin-content">
    <h2>Quản lý đơn hàng</h2>
    <table class="admin-table">
      <thead><tr><th>Mã ĐH</th><th>Khách hàng</th><th>SĐT</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['customer_name']) ?></td>
            <td><?= htmlspecialchars($o['phone']) ?></td>
            <td><?= formatPrice($o['total']) ?></td>
            <td>
              <form method="post" style="display:flex;gap:6px;">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <select name="status" onchange="this.form.submit()" style="padding:4px;">
                  <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                  <?php endforeach; ?>
                </select>
                <input type="hidden" name="update_status" value="1">
              </form>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$orders): ?>
          <tr><td colspan="6" style="text-align:center;padding:20px;color:#888;">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
