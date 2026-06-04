<?php
require_once('app/config/database.php');
require_once('app/helpers/SessionHelper.php');
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
        // Yêu cầu của giảng viên: Khi load lại trang chủ thì giỏ hàng phải trống.
        // Tuy nhiên, không clear giỏ hàng khi người dùng bấm "Tiếp tục mua sắm" từ giỏ hàng.
        if (SessionHelper::isLoggedIn()) {
            // Tải giỏ hàng từ database cho user đã đăng nhập
            $this->loadCartFromDb(SessionHelper::get('user_id'));
        } else {
            if (isset($_SESSION['keep_cart']) && $_SESSION['keep_cart'] === true) {
                unset($_SESSION['keep_cart']);
            } else {
                if (isset($_SESSION['cart'])) {
                    unset($_SESSION['cart']);
                }
            }
        }
        
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function keepCartAndRedirect()
    {
        $_SESSION['keep_cart'] = true;
        header('Location: /webbanhang/Product');
        exit();
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
        SessionHelper::requireAdmin();
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        SessionHelper::requireAdmin();
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
        SessionHelper::requireAdmin();
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
        SessionHelper::requireAdmin();
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
        SessionHelper::requireAdmin();
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . SITE_URL . 'Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = dirname(dirname(__DIR__)) . "/public/uploads/products/";

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

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        // Đồng bộ DB nếu đã đăng nhập
        if (SessionHelper::isLoggedIn()) {
            $this->saveCartToDb(SessionHelper::get('user_id'));
        }

        header('Location: /webbanhang/Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        SessionHelper::requireLogin();
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        SessionHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $payment_method = $_POST['payment_method'] ?? 'cod';
            $transaction_id = $_POST['transaction_id'] ?? null;
            $user_id = SessionHelper::get('user_id');

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            // Bắt đầu giao dịch
            $this->db->beginTransaction();

            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address, payment_method, user_id, transaction_id, payment_status) 
                          VALUES (:name, :phone, :address, :payment_method, :user_id, :transaction_id, 'unpaid')";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':payment_method', $payment_method);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':transaction_id', $transaction_id);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // Lưu chi tiết đơn hàng vào bảng order_details (Chỉ những sản phẩm được tích chọn)
                $cart = $_SESSION['cart'];
                $ordered_items = [];
                $total_amount = 0;
                foreach ($cart as $product_id => $item) {
                    if (!isset($item['selected']) || !$item['selected']) {
                        continue;
                    }
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();

                    $ordered_items[] = [
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ];
                    $total_amount += $item['quantity'] * $item['price'];
                }

                // Chỉ xóa các sản phẩm được chọn mua khỏi giỏ hàng
                foreach ($cart as $product_id => $item) {
                    if (isset($item['selected']) && $item['selected']) {
                        unset($_SESSION['cart'][$product_id]);
                    }
                }

                // Commit giao dịch
                $this->db->commit();

                // Đồng bộ lại DB nếu đã đăng nhập
                if (SessionHelper::isLoggedIn()) {
                    $this->saveCartToDb($user_id);
                }

                // Gửi email đặt hàng thành công
                try {
                    require_once 'app/models/UserModel.php';
                    require_once 'app/helpers/EmailHelper.php';
                    $userModel = new UserModel($this->db);
                    $user = $userModel->getUserById($user_id);
                    if ($user && !empty($user->email)) {
                        EmailHelper::sendOrderSuccessEmail($user->email, $order_id, $user->name ?? $user->username, $total_amount, $ordered_items);
                    }
                } catch (Exception $mailEx) {
                    // Bỏ qua lỗi gửi mail để không làm gián đoạn trải nghiệm người dùng
                }

                // Chuyển hướng đến trang xác nhận đơn hàng kèm theo ID
                header('Location: /webbanhang/Product/orderConfirmation?id=' . $order_id);
                exit();
            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        SessionHelper::requireLogin();
        $id = $_GET['id'] ?? null;
        $order = null;
        $details = [];
        if ($id) {
            require_once 'app/models/OrderModel.php';
            $orderModel = new OrderModel($this->db);
            $order = $orderModel->getOrderById($id);
            if ($order) {
                // Kiểm tra quyền sở hữu đơn hàng
                $current_user_id = SessionHelper::get('user_id');
                $is_admin = SessionHelper::isAdmin();
                if (!$is_admin && $order->user_id != $current_user_id) {
                    http_response_code(403);
                    die('Access Denied: Bạn không có quyền xem trang này.');
                }
                $details = $orderModel->getOrderDetails($id);
            }
        }
        include 'app/views/product/orderConfirmation.php';
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected_ids = $_POST['selected_items'] ?? [];
            $quantities = $_POST['quantities'] ?? [];
            $submit_action = $_POST['submit_action'] ?? 'update';

            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => &$item) {
                    // Cập nhật số lượng
                    if (isset($quantities[$id])) {
                        $qty = intval($quantities[$id]);
                        if ($qty > 0) {
                            $item['quantity'] = $qty;
                        }
                    }
                    // Cập nhật trạng thái được tích chọn
                    $item['selected'] = in_array($id, $selected_ids);
                }
            }

            // Đồng bộ DB nếu đã đăng nhập
            if (SessionHelper::isLoggedIn()) {
                $this->saveCartToDb(SessionHelper::get('user_id'));
            }

            if ($submit_action === 'checkout') {
                // Kiểm tra xem có sản phẩm nào được chọn để thanh toán không
                $has_selected = false;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        if (isset($item['selected']) && $item['selected']) {
                            $has_selected = true;
                            break;
                        }
                    }
                }
                
                if (!$has_selected) {
                    $_SESSION['error'] = 'Vui lòng tích chọn ít nhất một sản phẩm để thanh toán.';
                    header('Location: /webbanhang/Product/cart');
                    exit();
                }
                header('Location: /webbanhang/Product/checkout');
                exit();
            } else {
                $_SESSION['success'] = 'Cập nhật giỏ hàng thành công.';
                header('Location: /webbanhang/Product/cart');
                exit();
            }
        }
    }

    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        // Đồng bộ DB nếu đã đăng nhập
        if (SessionHelper::isLoggedIn()) {
            $this->saveCartToDb(SessionHelper::get('user_id'));
        }

        header('Location: /webbanhang/Product/cart');
        exit();
    }

    private function saveCartToDb($user_id)
    {
        if (!$user_id) return;
        
        $inTransaction = $this->db->inTransaction();
        if (!$inTransaction) {
            $this->db->beginTransaction();
        }
        try {
            // Xóa sạch giỏ hàng cũ của user trong DB
            $query = "DELETE FROM cart WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Nếu giỏ hàng trong session không trống, thêm lại
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $query = "INSERT INTO cart (user_id, product_id, quantity, selected) VALUES (:user_id, :product_id, :quantity, :selected)";
                $stmt = $this->db->prepare($query);
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $selected = isset($item['selected']) && $item['selected'] ? 1 : 0;
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                    $stmt->bindParam(':selected', $selected, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            if (!$inTransaction) {
                $this->db->commit();
            }
        } catch (Exception $e) {
            if (!$inTransaction) {
                $this->db->rollBack();
            } else {
                throw $e;
            }
        }
    }

    private function loadCartFromDb($user_id)
    {
        if (!$user_id) return;

        // Lấy danh sách sản phẩm trong giỏ hàng của user
        $query = "SELECT c.product_id, c.quantity, c.selected, p.name, p.price, p.image 
                  FROM cart c 
                  JOIN product p ON c.product_id = p.id 
                  WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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
    }
}
