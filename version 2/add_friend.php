<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $user_id = $_SESSION['user_id'];
    $friend_username = $_POST['friend_username'];

    // 查询好友ID
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $friend_username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($friend_id);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // 插入好友关系
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $user_id, $friend_id);
        if ($stmt->execute()) {
            echo "好友请求已发送！";
        } else {
            echo "好友请求发送失败：" . $stmt->error;
        }
    } else {
        echo "用户不存在";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加好友</title>
</head>
<body>
    <h2>发送好友请求</h2>
    <form method="post" action="add_friend.php">
        好友用户名: <input type="text" name="friend_username" required><br>
        <button type="submit">发送请求</button>
    </form>
    <a href="chat.php">返回聊天</a>
</body>
</html>
