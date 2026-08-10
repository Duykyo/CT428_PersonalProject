<?php
// Khởi động Session để lưu trạng thái đăng nhập
session_start();

require_once 'includes/database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    // Nếu hàm login trả về true
    if ($user->login()) {
        // Lưu thông tin vào Session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        
        // Chuyển hướng người dùng về trang chủ (index.php)
        header("Location: index.php");
        exit();
    } else {
        $thong_bao = "<p style='color: red; font-weight: bold;'>Sai email hoặc mật khẩu!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-container { max-width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input[type=email], input[type=password] { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { background-color: #2196F3; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%; }
        button:hover { background-color: #0b7dda; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Đăng Nhập</h2>
    
    <?php echo $thong_bao; ?>

    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Đăng Nhập</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px;">
        Chưa có tài khoản? <a href="register.php">Đăng ký tại đây</a>
    </p>
</div>

</body>
</html>