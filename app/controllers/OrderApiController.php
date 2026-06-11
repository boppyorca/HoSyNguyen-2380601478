<?php
require_once('app/config/database.php');
require_once('app/models/OrderModel.php');

class OrderApiController
{
    private $orderModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }

    // Lấy danh sách tất cả đơn hàng (Admin)
    public function index()
    {
        header('Content-Type: application/json');
        $orders = $this->orderModel->getAllOrders();
        echo json_encode($orders);
    }

    // Lấy chi tiết đơn hàng theo ID (kèm danh sách sản phẩm đã mua)
    public function show($id)
    {
        header('Content-Type: application/json');
        $order = $this->orderModel->getOrderById($id);
        if ($order) {
            $details = $this->orderModel->getOrderDetails($id);
            echo json_encode([
                'order' => $order,
                'details' => $details
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Order not found']);
        }
    }

    // Cập nhật trạng thái đơn hàng / trạng thái thanh toán
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        $status = $data['status'] ?? null;
        $payment_status = $data['payment_status'] ?? null;
        
        $success = false;
        
        if ($status !== null) {
            $this->orderModel->updateOrderStatus($id, $status);
            $success = true;
        }
        
        if ($payment_status !== null) {
            $this->orderModel->updatePaymentStatus($id, $payment_status);
            $success = true;
        }
        
        if ($success) {
            echo json_encode(['message' => 'Order updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Order update failed or no inputs provided']);
        }
    }
}
?>
