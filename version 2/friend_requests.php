<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

// 获取当前用户ID
$user_id = $_SESSION['user_id'];

// 查询好友请求
$stmt = $mysqli->prepare("SELECT f.id, u.username 
                          FROM friends f 
                          JOIN users u ON f.user_id = u.id 
                          WHERE f.friend_id = ? AND f.status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$friend_requests = [];
while ($row = $result->fetch_assoc()) {
    $friend_requests[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>好友请求</title>
</head>
<body>
    <h2>好友请求</h2>
    <ul>
        <?php foreach ($friend_requests as $request): ?>
            <li>
                <?= htmlspecialchars($request['username']) ?>
                <form method="post" action="accept_friend.php" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <button type="submit">同意</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="chat.php">返回聊天</a>
</body>
</html>
