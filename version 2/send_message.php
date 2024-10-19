<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // 检查好友关系是否已被接受
    $stmt = $mysqli->prepare("SELECT id FROM friends WHERE user_id = ? AND friend_id = ? AND status = 'accepted'");
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // 插入消息记录
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();
        echo "消息发送成功";
    } else {
        echo "你不能发送消息给未同意的好友";
    }
    $stmt->close();
}
?>
