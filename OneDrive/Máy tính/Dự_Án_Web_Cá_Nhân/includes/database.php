<?php
class Database {
    // Thông tin cấu hình kết nối
    private $host = "localhost";
    private $db_name = "ct428_shop";
    private $username = "shop_user";
    private $password = "MatKhauKho@123";
    public $conn;

    // Phương thức tạo kết nối
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", 
                $this->username, 
                $this->password
            );
            
            // Bật chế độ báo lỗi ngoại lệ
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Tắt mô phỏng Prepared Statements để ép MySQL dùng Real Prepared Statements
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            echo "Lỗi kết nối cơ sở dữ liệu: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>