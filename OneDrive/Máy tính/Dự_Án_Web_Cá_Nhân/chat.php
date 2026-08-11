<?php
session_start();

// Buộc người dùng phải đăng nhập mới được vào phòng chat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Chat - Cửa Hàng B2405493</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .chat-container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        ul { list-style-type: none; padding: 0; height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; background: #fafafa; margin-bottom: 15px; }
        ul li { padding: 10px 15px; border-bottom: 1px solid #eee; }
        ul li:nth-child(odd) { background: #fff; }
        form { display: flex; gap: 10px; }
        input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        button { padding: 10px 20px; background-color: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0b7dda; }
        .btn-home { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

<div class="chat-container">
    <h2>Kênh Hỗ Trợ Trực Tuyến</h2>
    
    <!-- Khung hiển thị tin nhắn -->
    <ul id="messages"></ul>
    
    <!-- Form gửi tin nhắn -->
    <form id="chat-form">
        <input id="chat-input" autocomplete="off" placeholder="Nhập tin nhắn..." required />
        <button type="submit">Gửi</button>
    </form>
    
    <a href="index.php" class="btn-home">&larr; Trở về trang chủ</a>
</div>

<!-- Nhúng thư viện Socket.io từ máy chủ Node.js của chúng ta -->
<script src="http://localhost:3000/socket.io/socket.io.js"></script>

<script>
    // Lấy tên đăng nhập từ Session của PHP để làm định danh
    const currentUsername = "<?php echo htmlspecialchars($_SESSION['username']); ?>";

    // Khởi tạo kết nối tới server Chat đang chạy ngầm
    const socket = io('http://localhost:3000');

    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('messages');

    // Sự kiện khi người dùng bấm Gửi tin nhắn
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn trình duyệt tải lại trang
        
        if (input.value) {
            // Đóng gói dữ liệu gồm Tên và Nội dung tin nhắn
            const msgData = {
                username: currentUsername,
                text: input.value
            };
            
            // Phát sự kiện 'chat message' lên máy chủ Node.js
            socket.emit('chat message', msgData);
            
            // Làm trống ô nhập liệu
            input.value = '';
        }
    });

    // Lắng nghe sự kiện 'chat message' trả về từ máy chủ
    socket.on('chat message', function(msg) {
        const item = document.createElement('li');
        
        // Bôi đậm tên người gửi
        item.innerHTML = `<strong>${msg.username}:</strong> ${msg.text}`;
        
        messages.appendChild(item);
        
        // Tự động cuộn khung chat xuống dòng mới nhất
        messages.scrollTop = messages.scrollHeight;
    });
</script>

</body>
</html>