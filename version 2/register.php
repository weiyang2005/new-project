<?php
// 连接数据库
$mysqli = new mysqli("localhost", "root", "", "chat_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // 加密密码
    $email = $_POST['email'];

    // 插入用户信息
    $stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);
    if ($stmt->execute()) {
        echo "注册成功！<a href='login.php'>登录</a>";
    } else {
        echo "注册失败：" . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
</head>
<body>
    <h2>用户注册</h2>
    <form method="post" action="register.php">
        用户名: <input type="text" name="username" required><br>
        密码: <input type="password" name="password" required><br>
        邮箱: <input type="email" name="email" required><br>
        <button type="submit">注册</button>
    </form>
</body>
</html>
