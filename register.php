<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['username'], $_POST['email'], $_POST['password']]);
    header("Location: login.php");
}
?>
<form method="post">
Username: <input name="username"><br>
Email: <input name="email"><br>
Password: <input type="password" name="password"><br>
<button type="submit">Register</button>
</form>
