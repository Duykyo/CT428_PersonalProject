<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $role; // Thêm thuộc tính phân quyền

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức Đăng ký
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET username = :username, email = :email, password_hash = :password_hash";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $password_hash);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Phương thức Đăng nhập
    public function login() {
        // Truy vấn lấy thông tin user dựa trên email
        $query = "SELECT id, username, password_hash, role FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        
        $stmt->execute();

        // Kiểm tra xem email có tồn tại không
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Đối chiếu mật khẩu nhập vào với mã băm trong database
            if(password_verify($this->password, $row['password_hash'])) {
                // Đăng nhập thành công, gán các giá trị vào object
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }
}
?>