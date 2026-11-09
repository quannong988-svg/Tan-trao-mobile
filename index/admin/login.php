<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';
$pdo = getDB();

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập quản trị - Tân Trào Mobile</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background:#f5f5f5;">
  <form method="post" class="form-box">
    <h2>🔐 Đăng nhập quản trị</h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <div class="form-group">
      <label>Tên đăng nhập</label>
      <input type="text" name="username" required autofocus>
    </div>
    <div class="form-group">
      <label>Mật khẩu</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn-primary" style="width:100%;">Đăng nhập</button>
    <p style="margin-top:14px;font-size:13px;color:#888;text-align:center;">Mặc định: admin / admin123</p>
  </form>
</body>
</html>
