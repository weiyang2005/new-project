<?php
session_start();
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查询用户信息
    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // 验证密码
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id; // 登录成功，设置会话
        header("Location: chat.php"); // 跳转到聊天页面
    } else {
        echo "用户名或密码错误";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
</head>
<body>
    <h2>用户登录</h2>
    <form method="post" action="login.php">
        用户名: <input type="text" name="username" required><br>
        密码: <input type="password" name="password" required><br>
        <button type="submit">登录</button>
    </form>
</body>
</html>
