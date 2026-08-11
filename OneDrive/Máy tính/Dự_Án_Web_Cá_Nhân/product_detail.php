<?php
session_start();
require_once 'includes/database.php';
require_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// Bắt và kiểm tra ID từ URL an toàn
$id = isset($_GET['id']) ? intval($_GET['id']) : die("<h2 style='text-align:center;'>Lỗi: Không tìm thấy mã sản phẩm.</h2>");

$product->id = $id;

// Đọc thông tin sản phẩm
if(!$product->readOne()) {
    die("<h2 style='text-align:center;'>Lỗi: Sản phẩm không tồn tại trong hệ thống.</h2>");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product->name); ?> - Chi tiết</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .product-detail-container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; display: flex; gap: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .product-image { flex: 1; }
        .product-image img { width: 100%; border-radius: 5px; object-fit: cover; }
        .product-info { flex: 1; }
        .product-price { color: #e91e63; font-size: 1.5em; font-weight: bold; margin: 15px 0; }
        .btn-cart { background-color: #ff9800; color: white; border: none; padding: 12px 20px; font-size: 1.1em; border-radius: 4px; cursor: pointer; }
        .btn-cart:hover { background-color: #e68a00; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #2196F3; }
    </style>
</head>
<body>

<div class="product-detail-container">
    <div class="product-image">
        <?php $img_src = !empty($product->image) ? "assets/images/products/" . htmlspecialchars($product->image) : "https://via.placeholder.com/400"; ?>
        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
    </div>
    <div class="product-info">
        <h2><?php echo htmlspecialchars($product->name); ?></h2>
        <div class="product-price"><?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ</div>
        <p><strong>Mô tả sản phẩm:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
        
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
            <button type="submit" class="btn-cart">Thêm vào giỏ hàng</button>
        </form>

        <a href="index.php" class="back-link">&larr; Quay lại trang chủ</a>
    </div>
</div>

</body>
</html>