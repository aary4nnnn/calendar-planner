<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Calendar Planner</title>
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
require 'db.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format.";
    }
    // Validate password
    elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{7,14}$/', $password)) {
    $errorMsg = "Password must be 7–14 characters long and include both letters and numbers. Special characters are allowed.";
    }



    else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errorMsg = "Username or Email already exists.";
        } else {
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            $successMsg = "Registration successful! <a href='index.php'>Login now</a>";
        }
    }
}
?>


<div class="login-container">
    <form method="post" autocomplete="off" class="login-form" novalidate>
        <h2>Register</h2>

        <?php if ($errorMsg): ?>
         <p class="error-message"><?= htmlspecialchars($errorMsg) ?></p>
            <?php elseif ($successMsg): ?>
             <p class="success-message"><?= $successMsg ?></p>
            <?php endif; ?>


        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        
<div class="password-wrapper">
  <input type="password" name="password" id="password" placeholder="Password" required />
  <span class="toggle-password" onclick="togglePassword()" id="toggleIcon">👁️</span>
</div>

        <button type="submit">Register</button>

        <p class="signup-text">
            Already a user? <a href="index.php">Login here</a>
        </p>
    </form>
</div>
</div>

</body>
</html>
