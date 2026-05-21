#!/usr/bin/env php
<?php
require_once(__DIR__ . '/../app/config/database.php');
require_once(__DIR__ . '/../app/models/CategoryModel.php');

class CategoryImportCLI
{
    private $db;
    private $categoryModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
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
                    $this->addCategory();
                    break;
                case 2:
                    $this->listCategories();
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
        echo "║     NHẬP DANH MỤC SẢN PHẨM - CLI TOOL         ║\n";
        echo "╚════════════════════════════════════════════════╝\n";
    }

    private function showMenu()
    {
        echo "\n📋 MENU:\n";
        echo "1️⃣  Thêm danh mục mới\n";
        echo "2️⃣  Xem danh sách danh mục\n";
        echo "3️⃣  Thoát\n";
    }

    private function addCategory()
    {
        echo "\n" . str_repeat("=", 48) . "\n";
        echo "➕ THÊM DANH MỤC MỚI\n";
        echo str_repeat("=", 48) . "\n";

        // Nhập tên danh mục
        $name = $this->readInput("\n📝 Nhập tên danh mục: ");

        if (empty(trim($name))) {
            echo "❌ Tên danh mục không được để trống!\n";
            return;
        }

        // Nhập mô tả
        $description = $this->readInput("📝 Nhập mô tả danh mục: ");

        if (empty(trim($description))) {
            echo "❌ Mô tả không được để trống!\n";
            return;
        }

        // Xác nhận trước khi lưu
        echo "\n📊 Thông tin danh mục:\n";
        echo "   • Tên: $name\n";
        echo "   • Mô tả: $description\n";

        $confirm = $this->readInput("\n✅ Bạn có chắc chắn muốn thêm? (y/n): ");

        if (strtolower($confirm) !== 'y') {
            echo "⏭️  Đã hủy!\n";
            return;
        }

        // Lưu vào database
        try {
            $result = $this->categoryModel->addCategory($name, $description);

            if ($result === true) {
                echo "\n✅ Thêm danh mục thành công!\n";
                echo "🎉 Danh mục '$name' đã được lưu vào database.\n";
            } else {
                echo "\n❌ Lỗi:\n";
                if (is_array($result)) {
                    foreach ($result as $error) {
                        echo "   • $error\n";
                    }
                } else {
                    echo "   • Không thể lưu danh mục\n";
                }
            }
        } catch (Exception $e) {
            echo "\n❌ Lỗi database: " . $e->getMessage() . "\n";
        }
    }

    private function listCategories()
    {
        echo "\n" . str_repeat("=", 48) . "\n";
        echo "📋 DANH SÁCH DANH MỤC\n";
        echo str_repeat("=", 48) . "\n";

        try {
            $categories = $this->categoryModel->getCategories();

            if (empty($categories)) {
                echo "\n⚠️  Không có danh mục nào!\n";
                return;
            }

            echo "\n";
            echo str_pad("ID", 5) . " | " . str_pad("Tên Danh Mục", 25) . " | " . "Mô Tả\n";
            echo str_repeat("-", 90) . "\n";

            foreach ($categories as $cat) {
                $id = str_pad($cat->id, 5);
                $name = str_pad(substr($cat->name, 0, 25), 25);
                $desc = substr($cat->description, 0, 40) . (strlen($cat->description) > 40 ? "..." : "");

                echo "$id | $name | $desc\n";
            }

            echo "\n✅ Tổng cộng: " . count($categories) . " danh mục\n";
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
    $app = new CategoryImportCLI();
    $app->run();
} catch (Exception $e) {
    echo "\n❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
