<?php
session_start();

// Kiểm tra trạng thái đăng nhập
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Cửa Hàng B2405493</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .welcome-box { padding: 20px; border: 1px solid #4CAF50; border-radius: 5px; background-color: #f9f9f9; }
        a.btn { display: inline-block; padding: 10px 15px; margin-top: 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-login { background-color: #2196F3; }
        .btn-register { background-color: #4CAF50; }
        .btn-logout { background-color: #f44336; }
    </style>
</head>
<body>

<?php if ($is_logged_in): ?>
    <div class="welcome-box">
        <!-- Sử dụng htmlspecialchars để chống XSS khi in dữ liệu ra màn hình -->
        <h1>Chào mừng trở lại, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Vai trò của bạn: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <p><em>Bạn có quyền truy cập khu vực Quản trị viên để thêm/sửa sản phẩm.</em></p>
        <?php else: ?>
            <p><em>Hãy bắt đầu khám phá các sản phẩm của chúng tôi.</em></p>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-logout">Đăng Xuất</a>
    </div>
<?php else: ?>
    <div class="welcome-box" style="border-color: #ccc;">
        <h1>Chào mừng bạn đến với hệ thống</h1>
        <p>Bạn hiện chưa đăng nhập.</p>
        <a href="login.php" class="btn btn-login">Đăng Nhập</a>
        <a href="register.php" class="btn btn-register">Đăng Ký</a>
    </div>
<?php endif; ?>

</body>
</html>