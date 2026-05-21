<?php
echo "<h1>Test Cấu Hình Dự Án</h1>";

// 1. Test PHP Version
echo "<h2>1. Phiên bản PHP</h2>";
echo "Phiên bản: " . phpversion() . "<br>";
echo "Yêu cầu: 7.0 hoặc cao hơn<br>";
echo (phpversion() >= 7.0) ? "✅ OK" : "❌ FAIL";

// 2. Test Required Extensions
echo "<h2>2. Extensions Cần Thiết</h2>";
$extensions = ['pdo', 'pdo_mysql', 'gd'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "$ext: " . ($loaded ? "✅ Đã cài đặt" : "❌ Chưa cài đặt") . "<br>";
}

// 3. Test Database Connection
echo "<h2>3. Kết Nối Cơ Sở Dữ Liệu</h2>";
try {
    require_once('../app/config/database.php');
    $db = new Database();
    $conn = $db->getConnection();

    if ($conn) {
        echo "✅ Kết nối thành công<br>";

        // Test query
        $stmt = $conn->query("SELECT VERSION() as version");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "MySQL Version: " . $result['version'] . "<br>";
    } else {
        echo "❌ Kết nối thất bại<br>";
    }
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "<br>";
}

// 4. Test File Permissions
echo "<h2>4. Quyền Tệp</h2>";
$upload_dir = '../uploads/products';
if (is_dir($upload_dir)) {
    if (is_writable($upload_dir)) {
        echo "✅ Thư mục uploads/products/ có quyền ghi<br>";
    } else {
        echo "❌ Thư mục uploads/products/ không có quyền ghi<br>";
    }
} else {
    echo "⚠️ Thư mục uploads/products/ không tồn tại<br>";
}

// 5. Test File Structure
echo "<h2>5. Cấu Trúc Tệp</h2>";
$files_to_check = [
    '../app/config/database.php',
    '../app/config/config.php',
    '../app/models/ProductModel.php',
    '../app/models/CategoryModel.php',
    '../app/controllers/ProductController.php',
    '../app/controllers/CategoryController.php',
    '../database.sql'
];

foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    echo basename($file) . ": " . ($exists ? "✅" : "❌") . "<br>";
}

echo "<h2>✅ Kiểm Tra Hoàn Tất</h2>";
echo "<p><a href='/webbanhang/Product'>👉 Truy cập ứng dụng</a></p>";
?>
