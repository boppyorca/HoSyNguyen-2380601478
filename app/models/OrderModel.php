<?php
class OrderModel
{
    private $conn;
    private $table_name = "orders";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllOrders()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getOrderDetails($order_id)
    {
        $query = "SELECT od.*, p.name as product_name, p.image as product_image 
                  FROM order_details od 
                  LEFT JOIN product p ON od.product_id = p.id 
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateOrderStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getOrdersByUserId($user_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updatePaymentStatus($id, $payment_status)
    {
        $query = "UPDATE " . $this->table_name . " SET payment_status = :payment_status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

