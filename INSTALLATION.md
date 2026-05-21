# Hướng Dẫn Cài Đặt Website Bán Hàng

## 1. Yêu Cầu Hệ Thống

- PHP 7.0 hoặc cao hơn
- MySQL 5.5 hoặc cao hơn
- Apache/Nginx với mod_rewrite được bật
- Trình duyệt web hiện đại

## 2. Các Bước Cài Đặt

### Bước 1: Tải và Giải Nén Dự Án

```bash
# Giải nén tệp dự án vào thư mục web root của máy chủ
# Ví dụ: /var/www/html/webbanhang hoặc C:\xampp\htdocs\webbanhang
```

### Bước 2: Tạo Cơ Sở Dữ Liệu

Có 2 cách để tạo cơ sở dữ liệu:

#### Cách 1: Sử dụng MySQL Command Line
```bash
mysql -u root -p < database.sql
```

#### Cách 2: Sử dụng phpMyAdmin
1. Mở phpMyAdmin (thường là http://localhost/phpmyadmin)
2. Tạo cơ sở dữ liệu mới tên "my_store"
3. Chọn cơ sở dữ liệu vừa tạo
4. Chọn "Import" (Nhập)
5. Tìm file `database.sql` và nhập vào

### Bước 3: Cấu Hình Kết Nối Cơ Sở Dữ Liệu

Chỉnh sửa file `app/config/config.php`:

```php
define('DB_HOST', 'localhost');    // Host MySQL (mặc định: localhost)
define('DB_NAME', 'my_store');     // Tên cơ sở dữ liệu
define('DB_USER', 'root');         // Người dùng MySQL
define('DB_PASS', '');             // Mật khẩu MySQL
```

### Bước 4: Thiết Lập Thư Mục Upload

Đảm bảo thư mục `uploads/products/` có quyền ghi:

```bash
# Linux/Mac
chmod -R 755 uploads/

# Windows (thường mặc định có quyền)
# Không cần chạy lệnh
```

### Bước 5: Cấu Hình Server

#### Nếu Sử Dụng Apache
Đảm bảo `mod_rewrite` được bật trong Apache:

```apache
# Thêm vào file .htaccess hoặc httpd.conf
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /webbanhang/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

#### Nếu Sử Dụng Nginx
Thêm vào server block:

```nginx
location /webbanhang/ {
    if (!-e $request_filename) {
        rewrite ^/webbanhang/(.*)$ /webbanhang/public/index.php?url=$1 last;
    }
}
```

### Bước 6: Kiểm Tra Cài Đặt

1. Mở trình duyệt web
2. Truy cập: `http://localhost/webbanhang/` hoặc `http://your-domain/webbanhang/`
3. Bạn sẽ được chuyển hướng đến danh sách sản phẩm
4. Nếu thấy danh sách sản phẩm, cài đặt thành công! ✅

## 3. Xử Lý Sự Cố

### Lỗi: "Connection error: ..."
**Nguyên nhân**: Cài đặt cơ sở dữ liệu sai
**Giải pháp**: Kiểm tra file `app/config/config.php` và đảm bảo cài đặt đúng

### Lỗi: "404 Not Found"
**Nguyên nhân**: mod_rewrite không hoạt động
**Giải pháp**: 
- Bật mod_rewrite trong Apache
- Hoặc cấu hình lại Nginx
- Hoặc truy cập `public/index.php` trực tiếp

### Lỗi: "Permission denied" trên thư mục upload
**Nguyên nhân**: Quyền thư mục không đúng
**Giải pháp**: 
```bash
chmod -R 755 uploads/
chown -R www-data:www-data uploads/  # Linux
```

### Lỗi: "Không thể tải ảnh lên"
**Nguyên nhân**: 
- Thư mục uploads không tồn tại hoặc không có quyền ghi
- Kích thước file quá lớn
- Loại file không được hỗ trợ

**Giải pháp**:
- Kiểm tra quyền thư mục `uploads/products/`
- Đảm bảo file < 10MB
- Sử dụng định dạng: JPG, JPEG, PNG, GIF

## 4. Sao Lưu và Phục Hồi

### Sao Lưu Cơ Sở Dữ Liệu
```bash
mysqldump -u root -p my_store > backup.sql
```

### Sao Lưu Hình Ảnh
```bash
# Linux/Mac
tar -czf uploads_backup.tar.gz uploads/

# Windows
# Nén thư mục uploads thành ZIP
```

## 5. Tài Liệu Bổ Sung

- Xem `README.md` để biết thêm về tính năng
- Xem `database.sql` để hiểu cấu trúc cơ sở dữ liệu
- Xem `app/config/config.php` để thay đổi cài đặt
