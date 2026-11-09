<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$product = ['name'=>'','slug'=>'','brand'=>'','category_id'=>'','price'=>'','sale_price'=>'','description'=>'','specs'=>'','stock'=>10,'is_featured'=>0,'is_liquidation'=>0];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $product = $found;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
    $slug = trim($slug, '-');
    $category_id = (int)$_POST['category_id'];
    $brand = trim($_POST['brand']);
    $price = (int)$_POST['price'];
    $sale_price = $_POST['sale_price'] !== '' ? (int)$_POST['sale_price'] : null;
    $description = trim($_POST['description']);
    $specs = trim($_POST['specs']);
    $stock = (int)$_POST['stock'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_liquidation = isset($_POST['is_liquidation']) ? 1 : 0;

    if ($name && $category_id && $price >= 0) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, slug=?, brand=?, category_id=?, price=?, sale_price=?, description=?, specs=?, stock=?, is_featured=?, is_liquidation=? WHERE id=?");
            $stmt->execute([$name,$slug,$brand,$category_id,$price,$sale_price,$description,$specs,$stock,$is_featured,$is_liquidation,$id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, slug, brand, category_id, price, sale_price, description, specs, stock, is_featured, is_liquidation) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$name,$slug,$brand,$category_id,$price,$sale_price,$description,$specs,$stock,$is_featured,$is_liquidation]);
        }
        header('Location: products.php');
        exit;
    } else {
        $error = 'Vui lòng nhập đầy đủ Tên sản phẩm, Danh mục và Giá hợp lệ.';
        $product = $_POST;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= $id ? 'Sửa' : 'Thêm' ?> sản phẩm - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <?php include __DIR__ . '/includes_sidebar.php'; ?>
  <div class="admin-content">
    <h2><?= $id ? '✏️ Sửa sản phẩm' : '➕ Thêm sản phẩm mới' ?></h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" class="form-box" style="max-width:600px;">
      <div class="form-group">
        <label>Tên sản phẩm *</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Slug (đường dẫn, để trống sẽ tự sinh)</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($product['slug']) ?>">
      </div>
      <div class="form-group">
        <label>Thương hiệu</label>
        <input type="text" name="brand" value="<?= htmlspecialchars($product['brand']) ?>">
      </div>
      <div class="form-group">
        <label>Danh mục *</label>
        <select name="category_id" required>
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Giá bán (VNĐ) *</label>
        <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
      </div>
      <div class="form-group">
        <label>Giá gốc (nếu có khuyến mãi)</label>
        <input type="number" name="sale_price" value="<?= htmlspecialchars($product['sale_price'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Tồn kho</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>">
      </div>
      <div class="form-group">
        <label>Mô tả</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
      </div>
      <div class="form-group">
        <label>Thông số kỹ thuật</label>
        <textarea name="specs" rows="3"><?= htmlspecialchars($product['specs']) ?></textarea>
      </div>
      <div class="form-group">
        <label><input type="checkbox" name="is_featured" <?= !empty($product['is_featured']) ? 'checked' : '' ?> style="width:auto;"> Sản phẩm nổi bật</label>
      </div>
      <div class="form-group">
        <label><input type="checkbox" name="is_liquidation" <?= !empty($product['is_liquidation']) ? 'checked' : '' ?> style="width:auto;"> Máy thanh lý</label>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;"><?= $id ? 'Cập nhật' : 'Thêm sản phẩm' ?></button>
    </form>
  </div>
</div>
</body>
</html>
