<?php
session_start();

// Yêu cầu đăng nhập trước khi thanh toán
if (!isset($_SESSION['user_id'])) {
    die("<h2 style='text-align:center; padding:50px;'>Bạn cần <a href='login.php'>đăng nhập</a> để tiến hành thanh toán.</h2>");
}

// Kiểm tra giỏ hàng có trống không
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

require_once 'includes/database.php';
require_once 'classes/Product.php';
require_once 'classes/Order.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$order = new Order($db);

$total_price = 0;
$order_items = array();

// Duyệt giỏ hàng để tính tổng tiền và chuẩn bị mảng dữ liệu chi tiết
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product->id = $product_id;
    if ($product->readOne()) {
        $total_price += ($product->price * $quantity);
        $order_items[] = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price
        );
    }
}

// Gắn dữ liệu vào đối tượng Order
$order->user_id = $_SESSION['user_id'];
$order->total_price = $total_price;
$order->cart_items = $order_items;

// Thực thi thanh toán
$thong_bao = "";
if ($order->create()) {
    // Làm trống giỏ hàng sau khi đặt thành công
    unset($_SESSION['cart']);
    $thong_bao = "<h2 style='color:green;'>Đặt hàng thành công!</h2><p>Cảm ơn bạn đã mua sắm.</p>";
} else {
    $thong_bao = "<h2 style='color:red;'>Lỗi đặt hàng!</h2><p>Đã xảy ra sự cố trong quá trình thanh toán. Vui lòng thử lại.</p>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f4f4f9; }
        .checkout-box { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn-home { display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<div class="checkout-box">
    <?php echo $thong_bao; ?>
    <a href="index.php" class="btn-home">Trở về trang chủ</a>
</div>

</body>
</html>