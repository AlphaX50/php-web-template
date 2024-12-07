<?php
session_start();
require 'config.php';

function generate_token() {
    return bin2hex(random_bytes(64));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        if ($remember_me) {
            $token = generate_token();
            setcookie('remember_me', $token, time() + (86400 * 30), "/", null, true, true);

            $sql = "UPDATE users SET remember_token = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token, $user['id']]);
        }

        header("Location: index.php");
        exit;
    } else {
        $error_message = "Incorrect login !";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Name</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <i data-lucide="triangle"></i>
        </div>
        
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in with your credentials to continue.</p>

        <div class="auth-tabs">
            <a href="#" class="auth-tab active">
                <i data-lucide="log-in" style="width: 18px; height: 18px;"></i>
                Sign in
            </a>
            <a href="signup.php" class="auth-tab">
                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                Sign up
            </a>
        </div>

        <form method="POST" action="login.php">
            <?php if (!empty($error_message)): ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem;">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Email</div>
                    <input type="text" name="email" class="form-input" placeholder="Enter your email" required>
                </div>
            </div>
            
            <div class="form-group">
                <i data-lucide="lock" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Password</div>
                    <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="checkbox-container" style="display: flex; align-items: center;">
                <input type="checkbox" name="rememberMe" id="rememberMe" style="margin-right: 0.5rem;">
                <label for="rememberMe">
                    <div class="checkbox"></div>
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn">
                Sign in
                <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <div class="help-text">
            Need help? <a href="#">Contact support</a>
        </div>

        <div class="auth-footer">
            <a href="#"><i data-lucide="discord" style="width: 24px; height: 24px;"></i></a>
            <a href="#"><i data-lucide="globe" style="width: 24px; height: 24px;"></i></a>
        </div>
        <footer class="footer">
    <div class="footer-content">
        <p>&copy; 2024 Project Name. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

