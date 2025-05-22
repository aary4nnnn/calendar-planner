<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Calendar Planner</title>
    <link rel="stylesheet" href="assets/auth.css" />
</head>
<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");

    if (pwd.type === "password") {
        pwd.type = "text";
        icon.textContent = "🙈";  
    } else {
        pwd.type = "password";
        icon.textContent = "👁️";  
    }
}
</script>



<body>

<div class="background-overlay"></div>  

<div class="content">
<?php
session_start();
require 'db.php';

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$_POST['username'], $_POST['password']]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: login.php");
        exit;
    } else {
        $errorMsg = "Invalid username or password";
    }
}
?>

<div class="login-container">
    <form method="post" autocomplete="off" class="login-form" novalidate>
        <h2>Login</h2>
        
        <?php if ($errorMsg): ?>
            <p class="error-message"><?= htmlspecialchars($errorMsg) ?></p>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required />
       
<div class="password-wrapper">
  <input type="password" name="password" id="password" placeholder="Password" required />
  <span class="toggle-password" onclick="togglePassword()" id="toggleIcon">👁️</span>
</div>


        <button type="submit">Login</button>
        <p class="signup-text">
            New user? <a href="register.php">Register here</a>
        </p>
    </form>
</div>
</div>

</body>
</html>
