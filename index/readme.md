# Tân Trào Mobile Clone — PHP + MySQL (XAMPP)

Website bán điện thoại clone theo phong cách tantraomobile.com, xây dựng bằng **PHP thuần + MySQL (PDO)**, không dùng framework, dễ chạy trên **XAMPP** và chỉnh sửa bằng **VS Code**.

## 🎯 Tính năng
- Trang chủ: banner, danh mục, sản phẩm nổi bật / bán chạy / thanh lý
- Trang danh mục sản phẩm (lọc theo danh mục)
- Trang chi tiết sản phẩm
- Giỏ hàng (lưu theo session) + đặt hàng (lưu vào DB)
- Tìm kiếm sản phẩm
- Trang quản trị (Admin): đăng nhập, quản lý sản phẩm / danh mục / đơn hàng

## 📁 Cấu trúc thư mục
```
tantraomobile/
├── admin/              # Trang quản trị
├── assets/css/js       # Giao diện
├── config/database.php # Cấu hình kết nối DB
├── includes/           # header.php, footer.php dùng chung
├── index.php           # Trang chủ
├── category.php        # Trang danh mục
├── product.php          # Trang chi tiết sản phẩm
├── cart.php             # Giỏ hàng + đặt hàng
├── cart_action.php      # Xử lý AJAX thêm/sửa/xóa giỏ hàng
├── search.php           # Tìm kiếm
└── database.sql         # File SQL tạo database + dữ liệu mẫu
```

## 🚀 Cài đặt với XAMPP (từng bước)

### Bước 1 — Cài XAMPP
Tải và cài XAMPP tại: https://www.apachefriends.org (chọn bản có PHP 8.x)

### Bước 2 — Copy project vào thư mục htdocs
Copy toàn bộ thư mục `tantraomobile` vào:
- Windows: `C:\xampp\htdocs\tantraomobile`
- macOS: `/Applications/XAMPP/htdocs/tantraomobile`

### Bước 3 — Bật Apache & MySQL
Mở **XAMPP Control Panel** → bấm **Start** ở dòng `Apache` và `MySQL`.

### Bước 4 — Tạo database
1. Mở trình duyệt vào: `http://localhost/phpmyadmin`
2. Bấm tab **Import** (Nhập)
3. Chọn file `database.sql` trong thư mục project → bấm **Go/Thực hiện**
4. Database `tantraomobile` cùng dữ liệu mẫu (danh mục + sản phẩm + tài khoản admin) sẽ được tạo tự động

> Cách khác: mở terminal/cmd, chạy `mysql -u root -p < database.sql`

### Bước 5 — Kiểm tra cấu hình kết nối
Mở file `config/database.php` bằng VS Code, kiểm tra các thông số:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tantraomobile');
define('DB_USER', 'root');
define('DB_PASS', '');        // XAMPP mặc định KHÔNG có mật khẩu
define('BASE_URL', '/tantraomobile/'); // đổi nếu tên thư mục trong htdocs khác
```

### Bước 6 — Truy cập website
Mở trình duyệt vào: **http://localhost/tantraomobile/**

### Bước 7 — Truy cập trang quản trị (Admin)
Vào: **http://localhost/tantraomobile/admin/login.php**
- Tài khoản: `admin`
- Mật khẩu: `admin123`

## 💻 Mở project trong VS Code
1. Mở VS Code → File → Open Folder → chọn thư mục `tantraomobile`
2. Cài extension **PHP Intelephense** để có gợi ý code PHP
3. Cài extension **XAMPP Control** hoặc dùng trực tiếp ứng dụng XAMPP để bật/tắt server
4. Sau khi sửa code, chỉ cần **lưu file (Ctrl+S)** rồi **F5 (refresh trình duyệt)** — không cần build gì thêm vì PHP chạy trực tiếp qua Apache

## 🛠️ Tùy biến thêm
- **Thêm/sửa sản phẩm**: vào trang Admin → Sản phẩm, hoặc chỉnh trực tiếp trong `database.sql`
- **Đổi màu chủ đạo**: sửa biến `--primary` trong `assets/css/style.css`
- **Ảnh sản phẩm thật**: hiện tại dùng icon 📱 thay ảnh để đơn giản hoá. Muốn dùng ảnh thật, upload ảnh vào thư mục `uploads/`, rồi sửa các file hiển thị sản phẩm (`index.php`, `category.php`, `product.php`) thay `<div class="product-thumb">📱</div>` bằng `<img src="<?= BASE_URL ?>uploads/<?= $p['image'] ?>">`

## ⚠️ Lưu ý
- Đây là project demo/học tập, phù hợp để học PHP + MySQL, KHÔNG nên dùng để kinh doanh thật (chưa có thanh toán online, chưa mã hoá đầy đủ theo chuẩn bảo mật production)
- Không sao chép nội dung/hình ảnh gốc từ tantraomobile.com — project này chỉ dùng dữ liệu sản phẩm mẫu tự soạn tương tự để phục vụ mục đích học tập
