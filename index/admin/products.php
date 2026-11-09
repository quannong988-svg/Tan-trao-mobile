<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: products.php');
    exit;
}

$products = $pdo->query("SELECT p.*, c.name AS cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý sản phẩm - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <?php include __DIR__ . '/includes_sidebar.php'; ?>
  <div class="admin-content">
    <h2>Quản lý sản phẩm</h2>
    <a href="product_form.php" class="btn-add">+ Thêm sản phẩm</a>
    <table class="admin-table">
      <thead>
        <tr><th>ID</th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th><th>Nổi bật</th><th>Hành động</th></tr>
      </thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td>#<?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['cat_name']) ?></td>
            <td><?= formatPrice($p['price']) ?></td>
            <td><?= (int)$p['stock'] ?></td>
            <td><?= $p['is_featured'] ? '✅' : '' ?></td>
            <td>
              <a href="product_form.php?id=<?= $p['id'] ?>" class="btn-sm btn-edit">Sửa</a>
              <a href="products.php?delete=<?= $p['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
