<?php
// Bật thông báo lỗi để dễ debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nhúng các file cần thiết
require_once 'includes/database.php';
require_once 'classes/User.php';

// Khởi tạo kết nối CSDL
$database = new Database();
$db = $database->getConnection();

// Khởi tạo đối tượng User
$user = new User($db);

$thong_bao = "";

// Kiểm tra xem form có được submit bằng phương thức POST hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form và gán vào các thuộc tính của đối tượng user
    $user->username = $_POST['username'];
    $user->email    = $_POST['email'];
    $user->password = $_POST['password'];

    // Gọi hàm đăng ký
    if ($user->register()) {
        $thong_bao = "<p style='color: green; font-weight: bold;'>Đăng ký thành công! Hãy đăng nhập.</p>";
    } else {
        $thong_bao = "<p style='color: red; font-weight: bold;'>Đăng ký thất bại. Tên đăng nhập hoặc Email có thể đã tồn tại.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-container { max-width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input[type=text], input[type=email], input[type=password] { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Đăng Ký Tài Khoản</h2>
    
    <!-- Hiển thị thông báo thành công hoặc thất bại -->
    <?php echo $thong_bao; ?>

    <!-- Form nhập liệu, gửi dữ liệu đi bằng phương thức POST -->
    <form action="register.php" method="POST">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required>
        <div id="username_msg" style="margin-bottom: 10px;"></div>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Đăng Ký</button>
    </form>
</div>
<script>
document.getElementById("username").addEventListener("keyup", function() {
    var username = this.value;
    var msg_box = document.getElementById("username_msg");
    
    // Chỉ bắt đầu gửi request khi người dùng gõ từ 3 ký tự trở lên
    if (username.length < 3) {
        msg_box.innerHTML = "";
        return;
    }
    
    // Khởi tạo đối tượng XMLHttpRequest để giao tiếp bất đồng bộ
    var xhr = new XMLHttpRequest();
    
    // Cấu hình request gửi đến check_username.php qua phương thức GET
    xhr.open("GET", "check_username.php?username=" + encodeURIComponent(username), true);
    
    // Xử lý dữ liệu khi máy chủ phản hồi
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Chèn thẳng kết quả từ PHP vào thẻ div
            msg_box.innerHTML = xhr.responseText;
        }
    };
    
    // Gửi request
    xhr.send();
});
</script>

</body>
</html>