<?php
// File này chỉ trả về dữ liệu văn bản, không chứa giao diện HTML
require_once 'includes/database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Bắt tham số username từ phương thức GET
$username = isset($_GET['username']) ? $_GET['username'] : "";

if (strlen($username) > 0) {
    $user->username = $username;
    
    // Trả về thông báo HTML tương ứng để JavaScript in ra màn hình
    if ($user->checkUsernameExists()) {
        echo "<span style='color:red; font-size:0.9em;'>Tên đăng nhập này đã được sử dụng!</span>";
    } else {
        echo "<span style='color:green; font-size:0.9em;'>Tên đăng nhập hợp lệ.</span>";
    }
}
?>