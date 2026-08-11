<?php
class Order {
    private $conn;

    public $user_id;
    public $total_price;
    public $cart_items; // Mảng chứa các sản phẩm trong giỏ

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức tạo đơn hàng an toàn với Transaction
    public function create() {
        try {
            // Bắt đầu giao dịch (Transaction)
            $this->conn->beginTransaction();

            // 1. Lưu vào bảng orders
            $queryOrder = "INSERT INTO orders SET user_id = :user_id, total_price = :total_price";
            $stmtOrder = $this->conn->prepare($queryOrder);
            $stmtOrder->bindParam(":user_id", $this->user_id);
            $stmtOrder->bindParam(":total_price", $this->total_price);
            $stmtOrder->execute();

            // Lấy ID của đơn hàng vừa tạo thành công
            $order_id = $this->conn->lastInsertId();

            // 2. Lưu từng sản phẩm vào bảng order_details
            $queryDetail = "INSERT INTO order_details SET order_id = :order_id, product_id = :product_id, quantity = :quantity, price = :price";
            $stmtDetail = $this->conn->prepare($queryDetail);

            foreach ($this->cart_items as $item) {
                $stmtDetail->bindParam(":order_id", $order_id);
                $stmtDetail->bindParam(":product_id", $item['product_id']);
                $stmtDetail->bindParam(":quantity", $item['quantity']);
                $stmtDetail->bindParam(":price", $item['price']);
                $stmtDetail->execute();
            }

            // Xác nhận hoàn tất và lưu vĩnh viễn toàn bộ dữ liệu (Commit)
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Nếu có bất kỳ lỗi nào xảy ra ở 2 bước trên, hoàn tác toàn bộ thao tác (Rollback)
            $this->conn->rollBack();
            return false;
        }
    }
}
// Phương thức Lấy danh sách đơn hàng của một người dùng cụ thể
    public function readByUser() {
        // Ràng buộc chặt chẽ điều kiện WHERE user_id
        $query = "SELECT id, total_price, status, created_at 
                  FROM orders 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        // Ép kiểu PARAM_INT để đảm bảo an toàn
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
?>