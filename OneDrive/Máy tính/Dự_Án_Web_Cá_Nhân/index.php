<?php
// Bật hiển thị lỗi để dễ dàng debug trong quá trình phát triển
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nhúng file kết nối
require_once 'includes/database.php';

// Khởi tạo đối tượng Database và gọi kết nối
$database = new Database();
$db = $database->getConnection();

if($db) {
    echo "<h1>Tuyệt vời! Kết nối cơ sở dữ liệu đã thành công.</h1>";
    echo "<p>Hệ thống phòng thủ PDO đã được kích hoạt sẵn sàng.</p>";
}
?>