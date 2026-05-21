# CLI Tools - Nhập Dữ Liệu Từ Bàn Phím

Thư mục `bin/` chứa các script command-line (CLI) để nhập dữ liệu sản phẩm và danh mục từ terminal.

## 📋 Các Tools Có Sẵn

### 1. **import-category.php** - Nhập Danh Mục

```bash
php bin/import-category.php
```

**Chức năng:**
- ➕ Thêm danh mục mới
- 📋 Xem danh sách danh mục
- 🗑️ Xóa danh mục (qua web interface)

**Ví dụ:**
```
1️⃣ Chọn: Thêm danh mục mới
2️⃣ Nhập tên: Electronics
3️⃣ Nhập mô tả: Thiết bị điện tử
4️⃣ Xác nhận: y
✅ Danh mục đã được lưu
```

### 2. **import-product.php** - Nhập Sản Phẩm

```bash
php bin/import-product.php
```

**Chức năng:**
- ➕ Thêm sản phẩm mới
- 📋 Xem danh sách sản phẩm
- 🖼️ Quản lý hình ảnh (qua web interface)

**Ví dụ:**
```
1️⃣ Chọn: Thêm sản phẩm mới
2️⃣ Nhập tên: Laptop
3️⃣ Nhập mô tả: A high-performance laptop
4️⃣ Nhập giá: 999.99
5️⃣ Chọn danh mục: 1 (Electronics)
6️⃣ Xác nhận: y
✅ Sản phẩm đã được lưu
```

## 🚀 Hướng Dẫn Sử Dụng

### Bước 1: Chuẩn Bị
- ✅ Cài đặt PHP 7.0+
- ✅ Setup MySQL database (chạy `database.sql`)
- ✅ Cấu hình `app/config/config.php`

### Bước 2: Chạy CLI Tools

#### **Nhập danh mục trước**
```bash
php bin/import-category.php
```

Bước này sẽ:
1. Hiển thị menu
2. Bạn chọn "1" để thêm danh mục
3. Nhập thông tin
4. Xác nhận
5. Dữ liệu lưu vào database

#### **Sau đó nhập sản phẩm**
```bash
php bin/import-product.php
```

Bước này sẽ:
1. Hiển thị danh sách danh mục có sẵn
2. Bạn chọn "1" để thêm sản phẩm
3. Nhập thông tin sản phẩm
4. Chọn danh mục
5. Xác nhận
6. Dữ liệu lưu vào database

### Bước 3: Xem Dữ Liệu
- **Qua CLI**: Chọn tùy chọn "2" để xem danh sách
- **Qua Web**: Truy cập `http://localhost/webbanhang/`

## 💡 Các Tính Năng

### ✅ Validation
- ✔️ Kiểm tra dữ liệu không được để trống
- ✔️ Kiểm tra giá phải là số
- ✔️ Kiểm tra danh mục tồn tại
- ✔️ Kiểm tra dữ liệu hợp lệ

### ✅ User Experience
- ✔️ Menu tương tác dễ sử dụng
- ✔️ Hiển thị danh sách trước khi nhập
- ✔️ Xác nhận trước khi lưu
- ✔️ Thông báo rõ ràng (✅ thành công, ❌ lỗi)
- ✔️ Định dạng bảng đẹp mắt

### ✅ Database Integration
- ✔️ Kết nối PDO an toàn
- ✔️ Prepared statements chống SQL injection
- ✔️ Xử lý exception
- ✔️ Validation từ Model

## 🔧 Lập Trình Viên - Mở Rộng

### Thêm CLI Tool Mới

1. **Tạo file mới**: `bin/import-{something}.php`
2. **Kế thừa pattern**: Sao chép cấu trúc từ `import-category.php` hoặc `import-product.php`
3. **Implement methods**:
   - `showHeader()` - Hiển thị tiêu đề
   - `showMenu()` - Hiển thị menu
   - `addItem()` - Thêm dữ liệu
   - `listItems()` - Xem danh sách
   - `readInput()` - Đọc input từ người dùng

### Ví dụ Cấu Trúc

```php
class MyImportCLI
{
    private $db;
    private $model;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->model = new MyModel($this->db);
    }

    public function run()
    {
        while (true) {
            $choice = $this->readInput("Menu: ");
            // Xử lý tùy chọn
        }
    }

    private function readInput($prompt)
    {
        echo $prompt;
        return trim(fgets(STDIN));
    }
}
```

## 📊 Cơ Sở Dữ Liệu

### Bảng category
```sql
- id (INT)
- name (VARCHAR)
- description (TEXT)
```

### Bảng product
```sql
- id (INT)
- name (VARCHAR)
- description (TEXT)
- price (DECIMAL)
- image (VARCHAR) - nullable
- category_id (INT) - FK
```

## ⚠️ Ghi Chú Quan Trọng

1. **Nhập danh mục trước**: Sản phẩm cần danh mục, nên thêm danh mục trước
2. **Xác thực dữ liệu**: CLI tool sẽ xác thực tất cả dữ liệu
3. **Database Connection**: Đảm bảo MySQL running và cấu hình đúng
4. **UTF-8 Encoding**: Hỗ trợ tiếng Việt không dấu

## 🆘 Xử Lý Sự Cố

### Lỗi: "Database connection error"
```
Giải pháp: Kiểm tra app/config/config.php
- DB_HOST, DB_NAME, DB_USER, DB_PASS
```

### Lỗi: "Table doesn't exist"
```
Giải pháp: Import database.sql
mysql -u root -p my_store < database.sql
```

### Lỗi: "Permission denied"
```
Giải pháp: Thêm quyền execute
chmod +x bin/import-category.php
chmod +x bin/import-product.php
```

## 📝 Changelog

- v1.0 - Tạo import-category.php và import-product.php
- v1.1 - Thêm validation và error handling
- v1.2 - Cải thiện UI/UX với emoji và formatting

---

**Hỗ trợ**: Nếu có vấn đề, kiểm tra cấu hình và log database.
