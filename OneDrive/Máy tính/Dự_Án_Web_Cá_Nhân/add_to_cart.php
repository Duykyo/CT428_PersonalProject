<?php
session_start();
require_once 'includes/database.php';
require_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Lấy giỏ hàng từ Session, nếu trống thì gán mảng rỗng
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng Của Bạn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .cart-container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .total-row { font-size: 1.2em; font-weight: bold; text-align: right; }
        .btn-checkout { display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 20px; float: right; }
        .btn-home { display: inline-block; padding: 10px 0; text-decoration: none; color: #2196F3; }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Giỏ Hàng Của Bạn</h2>

    <?php if (empty($cart_items)): ?>
        <p>Giỏ hàng của bạn đang trống.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $id => $quantity): ?>
                    <?php 
                        $product->id = $id;
                        // Chỉ hiển thị nếu sản phẩm thực sự tồn tại trong DB
                        if ($product->readOne()): 
                            $subtotal = $product->price * $quantity;
                            $total_price += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product->name); ?></td>
                        <td><?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <tr>
                    <td colspan="3" class="total-row">Tổng cộng:</td>
                    <td class="total-row" style="color: #e91e63;"><?php echo number_format($total_price, 0, ',', '.'); ?> VNĐ</td>
                </tr>
            </tbody>
        </table>
        
        <a href="checkout.php" class="btn-checkout">Tiến hành thanh toán</a>
    <?php endif; ?>
    
    <div style="clear: both;"></div>
    <a href="index.php" class="btn-home">&larr; Tiếp tục mua sắm</a>
</div>

</body>
</html>