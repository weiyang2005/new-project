<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // 获取当前用户ID和接收者ID
    $receiver_id = $_SESSION['user_id'];
    $sender_id = $_GET['receiver_id'];

    // 查询消息记录
    $stmt = $mysqli->prepare("SELECT m.message, u.username, m.sent_at 
                              FROM messages m 
                              JOIN users u ON m.sender_id = u.id 
                              WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                              OR (m.sender_id = ? AND m.receiver_id = ?) 
                              ORDER BY m.sent_at ASC");
    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 获取消息并返回JSON格式
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
    $stmt->close();
}
?>
