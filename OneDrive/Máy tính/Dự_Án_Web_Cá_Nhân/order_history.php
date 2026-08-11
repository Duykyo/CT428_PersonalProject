<?php
session_start();

// Buộc phải đăng nhập mới được xem lịch sử
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/database.php';
require_once 'classes/Order.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

// Truyền ID của user hiện tại vào đối tượng Order
$order->user_id = $_SESSION['user_id'];
$stmt = $order->readByUser();
$num_orders = $stmt->rowCount();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử mua hàng</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .history-container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #2196F3; color: white; }
        .status-pending { color: #ff9800; font-weight: bold; }
        .btn-home { display: inline-block; padding: 10px 0; text-decoration: none; color: #2196F3; }
    </style>
</head>
<body>

<div class="history-container">
    <h2>Lịch sử đơn hàng của bạn</h2>

    <?php if ($num_orders > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td><?php echo number_format($row['total_price'], 0, ',', '.'); ?> VNĐ</td>
                        <td class="status-pending"><?php echo strtoupper(htmlspecialchars($row['status'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
    
    <a href="index.php" class="btn-home">&larr; Trở về trang chủ</a>
</div>

</body>
</html>