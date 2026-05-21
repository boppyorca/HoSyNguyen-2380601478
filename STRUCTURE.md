# Cấu Trúc Dự Án Chi Tiết

## Thư Mục Root

```
webbanhang/
├── app/                          # Thư mục ứng dụng chính
├── public/                        # Thư mục public (web root)
├── uploads/                       # Thư mục lưu trữ ảnh sản phẩm
├── database.sql                   # Script tạo cơ sở dữ liệu
├── README.md                      # Hướng dẫn sử dụng
├── INSTALLATION.md                # Hướng dẫn cài đặt
├── STRUCTURE.md                   # File này
└── .gitignore                     # File ignore cho Git
```

## Thư Mục app/

```
app/
├── config/
│   ├── database.php              # Kết nối cơ sở dữ liệu PDO
│   └── config.php                # Cấu hình toàn cục
│
├── controllers/
│   ├── ProductController.php      # Điều khiển sản phẩm
│   │   ├── index()               # Danh sách sản phẩm
│   │   ├── show($id)             # Chi tiết sản phẩm
│   │   ├── add()                 # Form thêm sản phẩm
│   │   ├── save()                # Lưu sản phẩm mới
│   │   ├── edit($id)             # Form sửa sản phẩm
│   │   ├── update()              # Cập nhật sản phẩm
│   │   ├── delete($id)           # Xóa sản phẩm
│   │   └── uploadImage($file)    # Upload hình ảnh
│   │
│   └── CategoryController.php     # Điều khiển danh mục
│       ├── list()                # Danh sách danh mục
│       ├── add()                 # Form thêm danh mục
│       ├── save()                # Lưu danh mục mới
│       ├── edit($id)             # Form sửa danh mục
│       ├── update()              # Cập nhật danh mục
│       └── delete($id)           # Xóa danh mục
│
├── models/
│   ├── ProductModel.php           # Model sản phẩm
│   │   ├── getProducts()         # Lấy danh sách sản phẩm
│   │   ├── getProductById($id)   # Lấy sản phẩm theo ID
│   │   ├── addProduct()          # Thêm sản phẩm mới
│   │   ├── updateProduct()       # Cập nhật sản phẩm
│   │   └── deleteProduct($id)    # Xóa sản phẩm
│   │
│   └── CategoryModel.php          # Model danh mục
│       ├── getCategories()       # Lấy danh sách danh mục
│       ├── getCategoryById($id)  # Lấy danh mục theo ID
│       ├── addCategory()         # Thêm danh mục mới
│       ├── updateCategory()      # Cập nhật danh mục
│       └── deleteCategory($id)   # Xóa danh mục
│
├── views/
│   ├── product/
│   │   ├── list.php              # Danh sách sản phẩm (bảng)
│   │   ├── show.php              # Chi tiết sản phẩm
│   │   ├── add.php               # Form thêm sản phẩm
│   │   └── edit.php              # Form sửa sản phẩm
│   │
│   ├── category/
│   │   ├── list.php              # Danh sách danh mục
│   │   ├── add.php               # Form thêm danh mục
│   │   └── edit.php              # Form sửa danh mục
│   │
│   └── shares/
│       ├── header.php            # Header chung (navbar)
│       └── footer.php            # Footer chung (scripts)
│
└── helpers/
    ├── SessionHelper.php         # Quản lý session
    └── Utility.php               # Hàm tiện ích chung
```

## Thư Mục public/

```
public/
├── index.php                      # Entry point chính (router)
├── .htaccess                      # Cấu hình Apache rewrite
└── uploads/ -> ../uploads/        # Symbolic link (hoặc copy)
```

## Thư Mục uploads/

```
uploads/
└── products/                      # Lưu ảnh sản phẩm
    ├── .gitkeep                   # File giữ thư mục
    └── [image-files].jpg          # Các file ảnh sản phẩm
```

## Quy Trình MVC

### Luồng Dữ Liệu:

```
User Request
    ↓
public/index.php (Router)
    ↓
Controller (app/controllers/)
    ├─ Xử lý logic
    ├─ Gọi Model
    └─ Gọi View
        ↓
    Model (app/models/)
    ├─ Truy vấn Database
    └─ Trả về dữ liệu
        ↓
    View (app/views/)
    ├─ Hiển thị dữ liệu
    └─ Render HTML
        ↓
User Response
```

## Cơ Sở Dữ Liệu

### Bảng category
```sql
id          INT (Primary Key)
name        VARCHAR(100)
description TEXT
```

### Bảng product
```sql
id          INT (Primary Key)
name        VARCHAR(100)
description TEXT
price       DECIMAL(10,2)
image       VARCHAR(255) -- Lưu tên file ảnh
category_id INT (Foreign Key -> category.id)
```

## URL Routes

### Product Routes
```
GET  /webbanhang/Product/              → ProductController::index()
GET  /webbanhang/Product/show/{id}     → ProductController::show($id)
GET  /webbanhang/Product/add           → ProductController::add()
POST /webbanhang/Product/save          → ProductController::save()
GET  /webbanhang/Product/edit/{id}     → ProductController::edit($id)
POST /webbanhang/Product/update        → ProductController::update()
GET  /webbanhang/Product/delete/{id}   → ProductController::delete($id)
```

### Category Routes
```
GET  /webbanhang/Category              → CategoryController::list()
GET  /webbanhang/Category/add          → CategoryController::add()
POST /webbanhang/Category/save         → CategoryController::save()
GET  /webbanhang/Category/edit/{id}    → CategoryController::edit($id)
POST /webbanhang/Category/update       → CategoryController::update()
GET  /webbanhang/Category/delete/{id}  → CategoryController::delete($id)
```

## Tính Năng Bảo Mật

1. **SQL Injection Prevention**
   - Sử dụng Prepared Statements (PDO)
   - Parameterized Queries (:name, :id, etc.)

2. **XSS Prevention**
   - htmlspecialchars() cho output
   - strip_tags() cho input

3. **File Upload Validation**
   - Kiểm tra loại file (MIME type)
   - Kiểm tra kích thước file (max 10MB)
   - Kiểm tra phần mở rộng file
   - Renamed files khi upload (uniqid)

4. **Form Validation**
   - Server-side validation
   - Client-side validation (HTML5)

## Cấu Hình Quan Trọng

```php
// app/config/config.php
MAX_FILE_SIZE      = 10 * 1024 * 1024    // 10 MB
ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif']
ITEMS_PER_PAGE     = 10
```

## Mở Rộng Tương Lai

- [ ] Phân trang (Pagination)
- [ ] Tìm kiếm (Search)
- [ ] Sắp xếp (Sorting)
- [ ] Xác thực người dùng (Authentication)
- [ ] Hàng đợi chờ/Giỏ hàng (Cart)
- [ ] Thanh toán (Payment Gateway)
- [ ] API REST
- [ ] Admin Panel
