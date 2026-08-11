<?php
class Product {
    private $conn;
    private $table_name = "products";

    // Các thuộc tính của sản phẩm
    public $id;
    public $name;
    public $price;
    public $description;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức Đọc danh sách tất cả sản phẩm
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Phương thức Thêm sản phẩm mới (Dành cho Admin)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, price=:price, description=:description, image=:image";
        
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu để phòng chống XSS (Cross-Site Scripting)
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));

        // Gắn dữ liệu an toàn vào tham số ảo
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);

        // Thực thi
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>