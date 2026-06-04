<?php
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/models/OrderReturnModel.php';
require_once 'app/helpers/SessionHelper.php';

class OrderController
{
    private $orderModel;
    private $orderReturnModel;
    private $db;

    public function __construct()
    {
        SessionHelper::requireLogin();
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
        $this->orderReturnModel = new OrderReturnModel($this->db);
    }

    public function index()
    {
        SessionHelper::requireAdmin();
        $orders = $this->orderModel->getAllOrders();
        $stats = $this->orderModel->getOrderStats();
        include 'app/views/order/list.php';
    }

    public function show($id)
    {
        $order = $this->orderModel->getOrderById($id);
        if ($order) {
            // Check authorization: must be admin OR the owner of the order
            $current_user_id = SessionHelper::get('user_id');
            $is_admin = SessionHelper::isAdmin();
            
            if (!$is_admin && $order->user_id != $current_user_id) {
                http_response_code(403);
                die('Access Denied: Bạn không có quyền xem đơn hàng này.');
            }
            
            $details = $this->orderModel->getOrderDetails($id);
            include 'app/views/order/show.php';
        } else {
            echo "Không tìm thấy đơn hàng.";
        }
    }

    public function updateStatus()
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            $order = $this->orderModel->getOrderById($id);
            if (!$order) {
                echo "Không tìm thấy đơn hàng.";
                return;
            }

            // Kiểm tra nghiệp vụ hủy đơn hàng đối với Admin
            if ($status === 'cancelled') {
                if ($order->status === 'shipping' || $order->status === 'completed') {
                    $_SESSION['error'] = 'Không thể hủy đơn hàng đang giao hoặc đã hoàn thành.';
                    header('Location: /webbanhang/Order/show/' . $id);
                    exit();
                }
            }

            if ($this->orderModel->updateOrderStatus($id, $status)) {
                $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công.';
                header('Location: /webbanhang/Order/show/' . $id);
                exit();
            } else {
                echo "Đã xảy ra lỗi khi cập nhật trạng thái đơn hàng.";
            }
        }
    }

    public function updatePaymentStatus()
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $payment_status = $_POST['payment_status'] ?? '';
            if ($this->orderModel->updatePaymentStatus($id, $payment_status)) {
                header('Location: /webbanhang/Order/show/' . $id);
                exit();
            } else {
                echo "Đã xảy ra lỗi khi cập nhật trạng thái thanh toán.";
            }
        }
    }

    public function myOrders()
    {
        $user_id = SessionHelper::get('user_id');
        $orders = $this->orderModel->getOrdersByUserId($user_id);
        include 'app/views/order/my_orders.php';
    }

    public function cancel($id)
    {
        $order = $this->orderModel->getOrderById($id);
        if ($order) {
            $current_user_id = SessionHelper::get('user_id');
            if ($order->user_id != $current_user_id) {
                http_response_code(403);
                die('Access Denied: Bạn không có quyền hủy đơn hàng này.');
            }
            
            if ($order->status === 'pending') {
                if ($this->orderModel->updateOrderStatus($id, 'cancelled')) {
                    SessionHelper::flash('success', 'Hủy đơn hàng thành công.');
                    try {
                        $details = $this->orderModel->getOrderDetails($id);
                        $total_amount = 0;
                        foreach ($details as $detail) {
                            $total_amount += $detail->quantity * $detail->price;
                        }
                        require_once 'app/models/UserModel.php';
                        require_once 'app/helpers/EmailHelper.php';
                        $userModel = new UserModel($this->db);
                        $user = $userModel->getUserById($order->user_id);
                        if ($user && !empty($user->email)) {
                            EmailHelper::sendOrderCancelledEmail($user->email, $id, $user->name ?? $user->username, $total_amount);
                        }
                    } catch (Exception $mailEx) {
                        // Bỏ qua lỗi gửi mail để tránh gián đoạn
                    }
                } else {
                    SessionHelper::flash('error', 'Có lỗi xảy ra khi hủy đơn hàng.');
                }
            } else {
                SessionHelper::flash('error', 'Không thể hủy đơn hàng do đơn đang được xử lý hoặc đã hoàn thành.');
            }
            header('Location: /webbanhang/Order/myOrders');
            exit();
        } else {
            echo "Không tìm thấy đơn hàng.";
        }
    }

    public function returnRequest($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            echo "Không tìm thấy đơn hàng.";
            return;
        }

        // Kiểm tra quyền sở hữu đơn hàng
        $current_user_id = SessionHelper::get('user_id');
        if ($order->user_id != $current_user_id) {
            http_response_code(403);
            die('Access Denied: Bạn không có quyền yêu cầu trả hàng cho đơn hàng này.');
        }

        // Kiểm tra trạng thái đơn hàng (phải là completed)
        if ($order->status !== 'completed') {
            $_SESSION['error'] = 'Chỉ có thể trả hàng cho đơn hàng đã hoàn thành.';
            header('Location: /webbanhang/Order/show/' . $order_id);
            exit();
        }

        // Kiểm tra xem đã gửi yêu cầu trả hàng trước đó chưa
        $existingReturn = $this->orderReturnModel->getReturnByOrderId($order_id);
        if ($existingReturn) {
            $_SESSION['error'] = 'Đơn hàng này đã gửi yêu cầu trả hàng trước đó.';
            header('Location: /webbanhang/Order/show/' . $order_id);
            exit();
        }

        // Kiểm tra điều kiện thời gian (không quá 7 ngày kể từ ngày mua)
        $order_time = strtotime($order->created_at);
        $seven_days_ago = time() - (7 * 24 * 60 * 60);
        if ($order_time < $seven_days_ago) {
            $_SESSION['error'] = 'Đã quá hạn 7 ngày để yêu cầu trả hàng cho đơn hàng này.';
            header('Location: /webbanhang/Order/show/' . $order_id);
            exit();
        }

        $details = $this->orderModel->getOrderDetails($order_id);
        include 'app/views/order/return_request.php';
    }

    public function submitReturn()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? '';
            $reason = $_POST['reason'] ?? '';
            $user_id = SessionHelper::get('user_id');

            $order = $this->orderModel->getOrderById($order_id);
            if (!$order || $order->user_id != $user_id) {
                http_response_code(403);
                die('Access Denied');
            }

            if ($order->status !== 'completed') {
                $_SESSION['error'] = 'Đơn hàng chưa hoàn thành không thể trả hàng.';
                header('Location: /webbanhang/Order/show/' . $order_id);
                exit();
            }

            $existingReturn = $this->orderReturnModel->getReturnByOrderId($order_id);
            if ($existingReturn) {
                $_SESSION['error'] = 'Đơn hàng đã được yêu cầu trả trước đó.';
                header('Location: /webbanhang/Order/show/' . $order_id);
                exit();
            }

            $evidence_image = null;
            if (isset($_FILES['evidence_image']) && $_FILES['evidence_image']['error'] == 0) {
                try {
                    $evidence_image = $this->uploadEvidence($_FILES['evidence_image']);
                } catch (Exception $e) {
                    $_SESSION['error'] = $e->getMessage();
                    header('Location: /webbanhang/Order/returnRequest/' . $order_id);
                    exit();
                }
            }

            if ($this->orderReturnModel->createReturnRequest($order_id, $user_id, $reason, $evidence_image)) {
                $_SESSION['success'] = 'Gửi yêu cầu trả hàng thành công! Đang chờ Admin xét duyệt.';
                header('Location: /webbanhang/Order/show/' . $order_id);
                exit();
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi tạo yêu cầu trả hàng.';
                header('Location: /webbanhang/Order/returnRequest/' . $order_id);
                exit();
            }
        }
    }

    private function uploadEvidence($file)
    {
        $target_dir = dirname(dirname(__DIR__)) . "/public/uploads/returns/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($file["tmp_name"]) === false) {
            throw new Exception("File gửi lên không phải hình ảnh hợp lệ.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Kích thước hình ảnh quá lớn (tối đa 10MB).");
        }

        if (!in_array($imageFileType, array("jpg", "jpeg", "png", "gif"))) {
            throw new Exception("Chỉ hỗ trợ ảnh dạng JPG, JPEG, PNG, GIF.");
        }

        $new_filename = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        } else {
            throw new Exception("Lỗi hệ thống khi tải ảnh lên.");
        }
    }

    public function returns()
    {
        SessionHelper::requireAdmin();
        $returns = $this->orderReturnModel->getAllReturns();
        include 'app/views/order/return_list_admin.php';
    }

    public function viewReturn($id)
    {
        SessionHelper::requireAdmin();
        $return = $this->orderReturnModel->getReturnById($id);
        if (!$return) {
            echo "Không tìm thấy yêu cầu trả hàng.";
            return;
        }
        $details = $this->orderModel->getOrderDetails($return->order_id);
        include 'app/views/order/return_show_admin.php';
    }

    public function processReturn()
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';
            $admin_note = $_POST['admin_note'] ?? '';

            $return = $this->orderReturnModel->getReturnById($id);
            if (!$return) {
                echo "Không tìm thấy yêu cầu trả hàng.";
                return;
            }

            $this->db->beginTransaction();
            try {
                // Cập nhật trạng thái yêu cầu trả hàng
                $this->orderReturnModel->updateReturnStatus($id, $status, $admin_note);

                // Nếu Admin bấm hoàn thành việc trả hàng (hoàn tiền)
                if ($status === 'completed') {
                    $this->orderModel->updateOrderStatus($return->order_id, 'cancelled');
                    $this->orderModel->updatePaymentStatus($return->order_id, 'refunded');
                }

                $this->db->commit();

                // Gửi email hoàn tiền thành công
                if ($status === 'completed') {
                    try {
                        $details = $this->orderModel->getOrderDetails($return->order_id);
                        $total_amount = 0;
                        foreach ($details as $detail) {
                            $total_amount += $detail->quantity * $detail->price;
                        }
                        require_once 'app/models/UserModel.php';
                        require_once 'app/helpers/EmailHelper.php';
                        $userModel = new UserModel($this->db);
                        $user = $userModel->getUserById($return->user_id);
                        if ($user && !empty($user->email)) {
                            EmailHelper::sendRefundSuccessEmail($user->email, $return->order_id, $user->name ?? $user->username, $total_amount);
                        }
                    } catch (Exception $mailEx) {
                        // Bỏ qua lỗi gửi mail để tránh gián đoạn
                    }
                }

                $_SESSION['success'] = 'Xử lý yêu cầu trả hàng thành công.';
                header('Location: /webbanhang/Order/viewReturn/' . $id);
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: /webbanhang/Order/viewReturn/' . $id);
                exit();
            }
        }
    }
}

