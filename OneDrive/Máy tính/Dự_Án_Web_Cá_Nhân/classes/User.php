<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức Đăng ký người dùng mới
    public function register() {
        // Câu lệnh SQL (Sử dụng Prepared Statement với các tham số ảo :username, :email, :password_hash)
        $query = "INSERT INTO " . $this->table_name . " 
                  SET username = :username, email = :email, password_hash = :password_hash";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu đầu vào (Sanitize)
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Băm mật khẩu bằng thuật toán BCRYPT mặc định của PHP
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        // Gắn dữ liệu thực tế vào các tham số ảo
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $password_hash);

        // Thực thi truy vấn
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>