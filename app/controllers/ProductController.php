<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            $image = null;
            $errors = [];

            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                }

                $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

                if ($result === true) {
                    header('Location: ' . SITE_URL . 'Product');
                } else {
                    $errors = $result;
                    $categories = (new CategoryModel($this->db))->getCategories();
                    include 'app/views/product/add.php';
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            }
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            $image = null;
            $errors = [];

            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                }

                $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

                if ($result === true) {
                    header('Location: ' . SITE_URL . 'Product');
                } else {
                    $errors = $result;
                    $product = $this->productModel->getProductById($id);
                    $categories = (new CategoryModel($this->db))->getCategories();
                    include 'app/views/product/edit.php';
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
                $product = $this->productModel->getProductById($id);
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/edit.php';
            }
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . SITE_URL . 'Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "public/uploads/products/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (!isset($file) || $file['error'] != 0) {
            return null;
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($file["tmp_name"]) === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }

        if (!in_array($imageFileType, array("jpg", "jpeg", "png", "gif"))) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }

        $target_file = $target_dir . uniqid() . "." . $imageFileType;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($target_file);
        } else {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
    }
}
