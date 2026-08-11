<?php
session_start();

// Xóa tất cả các biến session đang lưu trữ
session_unset();

// Hủy hoàn toàn phiên làm việc
session_destroy();

// Chuyển hướng về trang chủ
header("Location: index.php");
exit();
?>