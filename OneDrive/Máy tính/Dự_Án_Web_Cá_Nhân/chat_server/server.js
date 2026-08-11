const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");

// Cấu hình CORS để cho phép frontend (từ localhost của PHP) kết nối tới an toàn
const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});

// Lắng nghe sự kiện khi có một client (người dùng) kết nối
io.on("connection", (socket) => {
  console.log("(+) Một người dùng đã kết nối với ID: " + socket.id);

  // Lắng nghe tín hiệu 'chat message' từ client gửi lên
  socket.on("chat message", (msg) => {
    console.log(`[Tin nhắn] ${msg.username}: ${msg.text}`);

    // Phát (broadcast) tin nhắn đó tới TẤT CẢ các client đang kết nối để họ đều thấy
    io.emit("chat message", msg);
  });

  // Lắng nghe sự kiện ngắt kết nối
  socket.on("disconnect", () => {
    console.log("(-) Người dùng đã ngắt kết nối: " + socket.id);
  });
});

// Chạy server trên cổng 3000
const PORT = 3000;
server.listen(PORT, () => {
  console.log(`🚀 Máy chủ Socket.io đang chạy tại http://localhost:${PORT}`);
});
