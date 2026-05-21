# Website Bán Hàng - Quản Lý Sản Phẩm

Đây là một dự án PHP/MySQL để quản lý sản phẩm và danh mục cho một website bán hàng trực tuyến.

## Cấu trúc dự án

```
webbanhang/
├── app/
│   ├── config/
│   │   └── database.php
│   ├── controllers/
│   │   ├── ProductController.php
│   │   └── CategoryController.php
│   ├── models/
│   │   ├── ProductModel.php
│   │   └── CategoryModel.php
│   └── views/
│       ├── product/
│       │   ├── list.php
│       │   ├── show.php
│       │   ├── add.php
│       │   └── edit.php
│       ├── category/
│       │   ├── list.php
│       │   ├── add.php
│       │   └── edit.php
│       └── shares/
│           ├── header.php
│           └── footer.php
├── public/
│   ├── index.php
│   └── .htaccess
├── uploads/
│   └── products/
├── database.sql
└── README.md
```

## Yêu cầu

- PHP 7.0 hoặc cao hơn
- MySQL 5.5 hoặc cao hơn
- Apache/Nginx với mod_rewrite được bật

## Cài đặt

### 1. Tạo cơ sở dữ liệu

Nhập file `database.sql` vào MySQL:

```bash
mysql -u root -p < database.sql
```

hoặc sử dụng phpMyAdmin để import file.

### 2. Cấu hình kết nối cơ sở dữ liệu

Chỉnh sửa file `app/config/database.php`:

```php
private $host = "localhost";
private $db_name = "my_store";
private $username = "root";
private $password = "";
```

### 3. Cấu hình thư mục ảnh

Đảm bảo thư mục `uploads/products/` có quyền ghi:

```bash
chmod 755 uploads/products/
```

## Hướng dẫn sử dụng

### Các trang chính

- **Danh sách sản phẩm**: `/webbanhang/Product/` hoặc `/webbanhang/Product/index`
- **Thêm sản phẩm**: `/webbanhang/Product/add`
- **Xem chi tiết sản phẩm**: `/webbanhang/Product/show/{id}`
- **Sửa sản phẩm**: `/webbanhang/Product/edit/{id}`
- **Xóa sản phẩm**: `/webbanhang/Product/delete/{id}`

- **Danh sách danh mục**: `/webbanhang/Category`
- **Thêm danh mục**: `/webbanhang/Category/add`
- **Sửa danh mục**: `/webbanhang/Category/edit/{id}`
- **Xóa danh mục**: `/webbanhang/Category/delete/{id}`

### Tính năng chính

#### Quản lý sản phẩm
- ✅ Xem danh sách tất cả sản phẩm
- ✅ Xem chi tiết sản phẩm (bao gồm hình ảnh)
- ✅ Thêm sản phẩm mới với hình ảnh
- ✅ Chỉnh sửa sản phẩm (bao gồm cập nhật hình ảnh)
- ✅ Xóa sản phẩm

#### Quản lý danh mục
- ✅ Xem danh sách danh mục
- ✅ Thêm danh mục mới
- ✅ Chỉnh sửa danh mục
- ✅ Xóa danh mục

#### Tính năng nâng cao
- ✅ Upload và hiển thị hình ảnh sản phẩm
- ✅ Xác thực dữ liệu đầu vào
- ✅ Kiểm soát truy cập thông qua URL routing
- ✅ Giao diện Bootstrap 4 responsive

## Mã lỗi cơ bản

- **Kết nối cơ sở dữ liệu thất bại**: Kiểm tra cài đặt trong `app/config/database.php`
- **Lỗi upload ảnh**: Kiểm tra quyền thư mục `uploads/products/`
- **Không tìm thấy dữ liệu**: Kiểm tra cơ sở dữ liệu đã được nhập chưa

## Liên hệ

Nếu có bất kỳ vấn đề nào, vui lòng kiểm tra các file cấu hình và cơ sở dữ liệu.
