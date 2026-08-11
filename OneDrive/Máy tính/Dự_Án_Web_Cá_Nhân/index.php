<?php
session_start();

require_once 'includes/database.php';
require_once 'classes/Product.php';

// Khởi tạo kết nối
$database = new Database();
$db = $database->getConnection();

// Lấy danh sách sản phẩm
$product = new Product($db);
$stmt = $product->readAll();
$num_products = $stmt->rowCount();

// Kiểm tra trạng thái đăng nhập và phân quyền
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Cửa Hàng B2405493</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f9; }
        .header { background-color: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 15px; padding: 8px 12px; border-radius: 4px; }
        .header a.btn-login { background-color: #2196F3; }
        .header a.btn-logout { background-color: #f44336; }
        .header a.btn-admin { background-color: #4CAF50; }
        
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        .product-card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .product-card img { max-width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .product-price { color: #e91e63; font-weight: bold; font-size: 1.2em; margin: 10px 0; }
        .btn-view { display: inline-block; background: #2196F3; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="header">
    <h2>Hệ Thống Cửa Hàng</h2>
    <div>
        <?php if ($is_logged_in): ?>
            <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
            <?php if ($is_admin): ?>
                <a href="add_product.php" class="btn-admin">+ Thêm Sản Phẩm</a>
            <?php endif; ?>
            <a href="logout.php" class="btn-logout">Đăng Xuất</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Đăng Nhập</a>
            <a href="register.php" style="background-color: #555;">Đăng Ký</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h3 style="border-bottom: 2px solid #333; padding-bottom: 10px;">Sản Phẩm Mới Nhất</h3>
    
    <?php if ($num_products > 0): ?>
        <div class="product-grid">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product-card">
                    <!-- Hiển thị ảnh, nếu không có thì dùng ảnh mặc định -->
                    <?php $image_path = !empty($row['image']) ? "assets/images/products/" . htmlspecialchars($row['image']) : "https://via.placeholder.com/200"; ?>
                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    
                    <!-- Làm sạch dữ liệu trước khi in ra chống XSS -->
                    <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                    <div class="product-price"><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</div>
                    
                    <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn-view">Xem chi tiết</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Hiện tại chưa có sản phẩm nào trong hệ thống.</p>
    <?php endif; ?>
</div>

</body>
</html>