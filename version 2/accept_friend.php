<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取好友请求ID
    $request_id = $_POST['request_id'];

    // 获取当前用户ID
    $user_id = $_SESSION['user_id'];

    // 查询好友关系
    $stmt = $mysqli->prepare("SELECT user_id, friend_id FROM friends WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($user_id, $friend_id);
    $stmt->fetch();
    $stmt->close();

    // 更新好友关系状态为accepted
    $stmt = $mysqli->prepare("UPDATE friends SET status = 'accepted' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    if ($stmt->execute()) {
        // 插入另一方向的好友关系
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'accepted')");
        $stmt->bind_param("ii", $friend_id, $user_id);
        $stmt->execute();
        echo "好友请求已同意！<a href='friend_requests.php'>返回</a>";
    } else {
        echo "操作失败：" . $stmt->error;
    }
    $stmt->close();
}
?>