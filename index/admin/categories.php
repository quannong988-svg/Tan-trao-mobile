<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: categories.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
    $icon = trim($_POST['icon']) ?: '📱';
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, icon) VALUES (?,?,?)");
        $stmt->execute([$name, $slug, $icon]);
        header('Location: categories.php');
        exit;
    } else {
        $error = 'Vui lòng nhập tên danh mục.';
    }
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id=c.id) AS product_count FROM categories c ORDER BY sort_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý danh mục - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <?php include __DIR__ . '/includes_sidebar.php'; ?>
  <div class="admin-content">
    <h2>Quản lý danh mục</h2>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" class="form-box" style="max-width:500px;margin-left:0;">
      <h2>+ Thêm danh mục mới</h2>
      <div class="form-group"><label>Tên danh mục</label><input type="text" name="name" required></div>
      <div class="form-group"><label>Slug (để trống tự sinh)</label><input type="text" name="slug"></div>
      <div class="form-group"><label>Icon (emoji)</label><input type="text" name="icon" placeholder="📱"></div>
      <button type="submit" class="btn-primary" style="width:100%;">Thêm danh mục</button>
    </form>

    <table class="admin-table" style="margin-top:24px;">
      <thead><tr><th>ID</th><th>Icon</th><th>Tên</th><th>Slug</th><th>Số SP</th><th>Hành động</th></tr></thead>
      <tbody>
        <?php foreach ($categories as $c): ?>
          <tr>
            <td>#<?= $c['id'] ?></td>
            <td><?= $c['icon'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['slug']) ?></td>
            <td><?= $c['product_count'] ?></td>
            <td><a href="categories.php?delete=<?= $c['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Xóa danh mục này? Toàn bộ sản phẩm liên quan cũng sẽ bị xóa!')">Xóa</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
