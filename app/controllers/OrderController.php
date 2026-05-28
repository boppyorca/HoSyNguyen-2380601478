<?php
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/SessionHelper.php';

class OrderController
{
    private $orderModel;
    private $db;

    public function __construct()
    {
        SessionHelper::requireLogin();
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }

    public function index()
    {
        SessionHelper::requireAdmin();
        $orders = $this->orderModel->getAllOrders();
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
}

