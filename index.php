<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/style.css">
</head>

<?php
session_start();
require 'db.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$_POST['username'], $_POST['password']]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: login.php");
    } else {
        echo "Invalid login";
    }
}
?>
<form method="post">
Username: <input name="username"><br>
Password: <input type="password" name="password"><br>
<button type="submit">Login</button>
</form>
<a href="register.php">Register</a>
