<?php
session_start();
// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

// 获取当前用户ID
$user_id = $_SESSION['user_id'];

// 查询好友列表
$stmt = $mysqli->prepare("SELECT u.id, u.username 
                          FROM friends f 
                          JOIN users u ON f.friend_id = u.id 
                          WHERE f.user_id = ? AND f.status = 'accepted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$friends = [];
while ($row = $result->fetch_assoc()) {
    $friends[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>好友列表</title>
</head>
<body>
    <h2>好友列表</h2>
    <ul>
        <?php foreach ($friends as $friend): ?>
            <li><?= htmlspecialchars($friend['username']) ?> <a href="chat.php?friend_id=<?= $friend['id'] ?>">聊天</a></li>
        <?php endforeach; ?>
    </ul>
    <a href="add_friend.php">添加好友</a>
    <a href="friend_requests.php">好友请求</a>
</body>
</html>
