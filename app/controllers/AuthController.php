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
