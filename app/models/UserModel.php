<?php
class UserModel
{
    private $conn;
    private $table_name = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $password, $name, $email, $role = 'user')
    {
        $errors = [];
        if (empty($username)) {
            $errors['username'] = 'Tên đăng nhập không được để trống';
        }
        if (empty($password)) {
            $errors['password'] = 'Mật khẩu không được để trống';
        }
        if (empty($name)) {
            $errors['name'] = 'Họ tên không được để trống';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        // Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại';
        }

        // Kiểm tra xem email đã tồn tại chưa
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors['email'] = 'Email đã được sử dụng';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (username, password, role, name, email) VALUES (:username, :password, :role, :name, :email)";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars(strip_tags($role));
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
