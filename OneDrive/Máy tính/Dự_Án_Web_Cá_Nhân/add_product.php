<?php
session_start();

// Cơ chế RBAC: Kiểm tra nghiêm ngặt quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("<h2 style='color:red; text-align:center;'>Truy cập bị từ chối. Bạn không có quyền Quản trị viên!</h2>");
}

require_once 'includes/database.php';
require_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product->name = $_POST['name'];
    $product->price = $_POST['price'];
    $product->description = $_POST['description'];
    
    // Xử lý Upload file an toàn
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_exts = array("jpg", "jpeg", "png", "gif");
        $file_parts = explode(".", $_FILES['image']['name']);
        $file_ext = strtolower(end($file_parts));
        
        // Kiểm tra định dạng file hợp lệ
        if (in_array($file_ext, $allowed_exts)) {
            // Tạo tên file mới duy nhất để tránh trùng lặp
            $image_name = time() . "_" . basename($_FILES['image']['name']);
            $target_path = "assets/images/products/" . $image_name;
            
            // Di chuyển file vào thư mục đích
            move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
        } else {
            $thong_bao = "<p style='color:red;'>Chỉ cho phép tải lên định dạng JPG, JPEG, PNG, GIF.</p>";
        }
    }
    
    $product->image = $image_name;

    // Tiến hành lưu vào CSDL nếu không có lỗi upload
    if (empty($thong_bao)) {
        if ($product->create()) {
            $thong_bao = "<p style='color:green; font-weight:bold;'>Thêm sản phẩm thành công!</p>";
        } else {
            $thong_bao = "<p style='color:red; font-weight:bold;'>Đã xảy ra lỗi khi lưu vào cơ sở dữ liệu.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-container { max-width: 500px; padding: 20px; border: 2px solid #2196F3; border-radius: 5px; }
        input[type=text], input[type=number], textarea { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { background-color: #2196F3; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%; }
        button:hover { background-color: #0b7dda; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Thêm Sản Phẩm (Khu vực Quản trị)</h2>
    
    <?php echo $thong_bao; ?>

    <!-- Form upload bắt buộc phải có enctype="multipart/form-data" -->
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label>
        <input type="text" name="name" required>

        <label>Giá (VNĐ):</label>
        <input type="number" name="price" required>

        <label>Mô tả:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Hình ảnh:</label>
        <input type="file" name="image" accept="image/*" style="margin: 10px 0; display: block;">

        <button type="submit">Lưu Sản Phẩm</button>
    </form>
    <br>
    <a href="index.php">Quay lại trang chủ</a>
</div>

</body>
</html>