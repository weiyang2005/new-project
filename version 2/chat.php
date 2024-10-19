<?php
session_start();
// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>聊天</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 发送消息
        function sendMessage() {
            const receiver_id = $('#receiver_id').val();
            const message = $('#message').val();

            $.post('send_message.php', { receiver_id: receiver_id, message: message }, function(response) {
                $('#message').val(''); // 清空消息输入框
                fetchMessages(); // 重新获取消息
            });
        }

        // 获取消息
        function fetchMessages() {
            const receiver_id = $('#receiver_id').val();

            $.get('fetch_messages.php', { receiver_id: receiver_id }, function(data) {
                const messages = JSON.parse(data);
                $('#messages').empty(); // 清空消息容器
                messages.reverse(); // 反转消息顺序，使最新消息在最下方
                messages.forEach(function(message) {
                    $('#messages').append(`<div><strong>${message.username}</strong>: ${message.message}</div>`);
                });
            });
        }

        // 选择好友后立即获取消息
        $(document).ready(function() {
            $('#receiver_id').change(fetchMessages);
        });

        // 每5秒获取一次消息
        setInterval(fetchMessages, 1000);
    </script>
</head>
<body>
    <h2>聊天</h2>
    <div id="messages"></div>
    <select id="receiver_id">
        <?php
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

        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['username']}</option>";
        }
        $stmt->close();
        ?>
    </select>
    <input type="text" id="message" placeholder="消息"><br>
    <button onclick="sendMessage()">发送</button>
    <a href="friends_list.php">好友列表</a>
    <a href="friend_requests.php">好友请求</a>
</body>
</html>
