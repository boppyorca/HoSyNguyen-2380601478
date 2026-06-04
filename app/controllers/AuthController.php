<?php
require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/SessionHelper.php';

class AuthController
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    public function login()
    {
        if (SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/Product');
            exit();
        }
        include 'app/views/auth/login.php';
    }

    public function register()
    {
        if (SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/Product');
            exit();
        }
        include 'app/views/auth/register.php';
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($username, $password);

            if ($user) {
                SessionHelper::set('user_id', $user->id);
                SessionHelper::set('user_username', $user->username);
                SessionHelper::set('user_role', $user->role);
                SessionHelper::set('user_name', $user->name);

                // Tải giỏ hàng từ database cho user này vào session
                $query = "SELECT c.product_id, c.quantity, c.selected, p.name, p.price, p.image 
                          FROM cart c 
                          JOIN product p ON c.product_id = p.id 
                          WHERE c.user_id = :user_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $_SESSION['cart'] = [];
                foreach ($rows as $row) {
                    $product_id = $row['product_id'];
                    $_SESSION['cart'][$product_id] = [
                        'name' => $row['name'],
                        'price' => $row['price'],
                        'quantity' => (int)$row['quantity'],
                        'image' => $row['image'],
                        'selected' => (bool)$row['selected']
                    ];
                }

                header('Location: /webbanhang/Product');
                exit();
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không chính xác';
                include 'app/views/auth/login.php';
            }
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';

            $result = $this->userModel->register($username, $password, $name, $email);

            if ($result === true) {
                SessionHelper::flash('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
                header('Location: /webbanhang/Auth/login');
                exit();
            } else {
                $errors = $result;
                include 'app/views/auth/register.php';
            }
        }
    }

    public function logout()
    {
        SessionHelper::remove('user_id');
        SessionHelper::remove('user_username');
        SessionHelper::remove('user_role');
        SessionHelper::remove('user_name');
        SessionHelper::destroy();

        header('Location: /webbanhang/Product');
        exit();
    }
}
