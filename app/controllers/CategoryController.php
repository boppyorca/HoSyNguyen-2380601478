<?php
require_once('app/config/database.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        SessionHelper::requireAdmin();
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        include_once 'app/views/category/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $result = $this->categoryModel->addCategory($name, $description);

            if ($result === true) {
                header('Location: ' . SITE_URL . 'Category');
            } else {
                $errors = $result;
                include 'app/views/category/add.php';
            }
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không thấy danh mục.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $result = $this->categoryModel->updateCategory($id, $name, $description);

            if ($result === true) {
                header('Location: ' . SITE_URL . 'Category');
            } else {
                $category = $this->categoryModel->getCategoryById($id);
                $errors = $result;
                include 'app/views/category/edit.php';
            }
        }
    }

    public function delete($id)
    {
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: ' . SITE_URL . 'Category');
        } else {
            echo "Đã xảy ra lỗi khi xóa danh mục.";
        }
    }
}
