<?php
class OrderReturnModel
{
    private $conn;
    private $table_name = "order_returns";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createReturnRequest($order_id, $user_id, $reason, $evidence_image = null)
    {
        $query = "INSERT INTO " . $this->table_name . " (order_id, user_id, reason, evidence_image, status) 
                  VALUES (:order_id, :user_id, :reason, :evidence_image, 'pending')";
        $stmt = $this->conn->prepare($query);
        $reason = htmlspecialchars(strip_tags($reason));
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':evidence_image', $evidence_image);
        return $stmt->execute();
    }

    public function getReturnById($id)
    {
        $query = "SELECT r.*, o.created_at as order_date, o.name as customer_name, o.phone as customer_phone, o.address as customer_address, o.payment_method
                  FROM " . $this->table_name . " r
                  LEFT JOIN orders o ON r.order_id = o.id
                  WHERE r.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getReturnByOrderId($order_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getReturnsByUserId($user_id)
    {
        $query = "SELECT r.*, o.created_at as order_date 
                  FROM " . $this->table_name . " r
                  LEFT JOIN orders o ON r.order_id = o.id
                  WHERE r.user_id = :user_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllReturns()
    {
        $query = "SELECT r.*, o.name as customer_name, o.phone as customer_phone
                  FROM " . $this->table_name . " r
                  LEFT JOIN orders o ON r.order_id = o.id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateReturnStatus($id, $status, $admin_note = null)
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status";
        if ($admin_note !== null) {
            $query .= ", admin_note = :admin_note";
        }
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        if ($admin_note !== null) {
            $admin_note = htmlspecialchars(strip_tags($admin_note));
            $stmt->bindParam(':admin_note', $admin_note);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
