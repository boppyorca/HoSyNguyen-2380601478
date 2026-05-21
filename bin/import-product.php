#!/usr/bin/env php
<?php
require_once(__DIR__ . '/../app/config/database.php');
require_once(__DIR__ . '/../app/models/ProductModel.php');
require_once(__DIR__ . '/../app/models/CategoryModel.php');

class ProductImportCLI
{
    private $db;
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function run()
    {
        $this->showHeader();

        while (true) {
            $this->showMenu();
            $choice = $this->readInput("Chọn tùy chọn (1-3): ");

            switch ($choice) {
                case 1:
                    $this->addProduct();
                    break;
                case 2:
                    $this->listProducts();
                    break;
                case 3:
                    echo "\n👋 Tạm biệt!\n";
                    exit(0);
                default:
                    echo "\n❌ Tùy chọn không hợp lệ!\n";
            }
        }
    }

    private function showHeader()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════╗\n";
        echo "║     NHẬP SẢN PHẨM - CLI TOOL                  ║\n";
        echo "╚════════════════════════════════════════════════╝\n";
    }

    private function showMenu()
    {
        echo "\n📋 MENU:\n";
        echo "1️⃣  Thêm sản phẩm mới\n";
        echo "2️⃣  Xem danh sách sản phẩm\n";
        echo "3️⃣  Thoát\n";
    }

    private function addProduct()
    {
        echo "\n" . str_repeat("=", 48) . "\n";
        echo "➕ THÊM SẢN PHẨM MỚI\n";
        echo str_repeat("=", 48) . "\n";

        // Hiển thị danh sách danh mục
        $categories = $this->categoryModel->getCategories();
        if (empty($categories)) {
            echo "\n❌ Không có danh mục nào! Vui lòng thêm danh mục trước.\n";
            return;
        }

        echo "\n📦 Danh sách danh mục:\n";
        foreach ($categories as $cat) {
            echo "   [{$cat->id}] {$cat->name}\n";
        }

        // Nhập tên sản phẩm
        $name = $this->readInput("\n📝 Nhập tên sản phẩm: ");
        if (empty(trim($name))) {
            echo "❌ Tên sản phẩm không được để trống!\n";
            return;
        }

        // Nhập mô tả
        $description = $this->readInput("📝 Nhập mô tả sản phẩm: ");
        if (empty(trim($description))) {
            echo "❌ Mô tả không được để trống!\n";
            return;
        }

        // Nhập giá
        $price = $this->readInput("💰 Nhập giá sản phẩm: ");
        if (!is_numeric($price) || $price < 0) {
            echo "❌ Giá không hợp lệ!\n";
            return;
        }

        // Nhập danh mục
        $category_id = $this->readInput("📦 Chọn ID danh mục: ");
        if (!is_numeric($category_id) || $category_id <= 0) {
            echo "❌ ID danh mục không hợp lệ!\n";
            return;
        }

        // Kiểm tra danh mục tồn tại
        $category = $this->categoryModel->getCategoryById($category_id);
        if (!$category) {
            echo "❌ Danh mục không tồn tại!\n";
            return;
        }

        // Xác nhận
        echo "\n📊 Thông tin sản phẩm:\n";
        echo "   • Tên: $name\n";
        echo "   • Mô tả: $description\n";
        echo "   • Giá: " . number_format($price, 2) . "\n";
        echo "   • Danh mục: {$category->name}\n";

        $confirm = $this->readInput("\n✅ Bạn có chắc chắn muốn thêm? (y/n): ");
        if (strtolower($confirm) !== 'y') {
            echo "⏭️  Đã hủy!\n";
            return;
        }

        // Lưu vào database
        try {
            $result = $this->productModel->addProduct($name, $description, $price, $category_id);

            if ($result === true) {
                echo "\n✅ Thêm sản phẩm thành công!\n";
                echo "🎉 Sản phẩm '$name' đã được lưu vào database.\n";
            } else {
                echo "\n❌ Lỗi:\n";
                if (is_array($result)) {
                    foreach ($result as $error) {
                        echo "   • $error\n";
                    }
                } else {
                    echo "   • Không thể lưu sản phẩm\n";
                }
            }
        } catch (Exception $e) {
            echo "\n❌ Lỗi database: " . $e->getMessage() . "\n";
        }
    }

    private function listProducts()
    {
        echo "\n" . str_repeat("=", 48) . "\n";
        echo "📋 DANH SÁCH SẢN PHẨM\n";
        echo str_repeat("=", 48) . "\n";

        try {
            $products = $this->productModel->getProducts();

            if (empty($products)) {
                echo "\n⚠️  Không có sản phẩm nào!\n";
                return;
            }

            echo "\n";
            echo str_pad("ID", 4) . " | " . str_pad("Tên", 20) . " | " . str_pad("Giá", 12) . " | " . "Danh Mục\n";
            echo str_repeat("-", 80) . "\n";

            foreach ($products as $product) {
                $id = str_pad($product->id, 4);
                $name = str_pad(substr($product->name, 0, 20), 20);
                $price = str_pad(number_format($product->price, 2), 12);
                $category = $product->category_name ?? "N/A";

                echo "$id | $name | $price | $category\n";
            }

            echo "\n✅ Tổng cộng: " . count($products) . " sản phẩm\n";
        } catch (Exception $e) {
            echo "\n❌ Lỗi: " . $e->getMessage() . "\n";
        }
    }

    private function readInput($prompt)
    {
        echo $prompt;
        return trim(fgets(STDIN));
    }
}

// Chạy ứng dụng
try {
    $app = new ProductImportCLI();
    $app->run();
} catch (Exception $e) {
    echo "\n❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
