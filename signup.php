<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "The passwords don't match !";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $error_message = "This email is already in use !";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$full_name, $email, $hashed_password])) {
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Registration error !";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Name</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <i data-lucide="triangle"></i>
        </div>
        
        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">Sign up to get started with ProjectHub.</p>

        <div class="auth-tabs">
            <a href="login.php" class="auth-tab">
                <i data-lucide="log-in" style="width: 18px; height: 18px;"></i>
                Sign in
            </a>
            <a href="#" class="auth-tab active">
                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                Sign up
            </a>
        </div>

        <form method="POST" action="signup.php">
            <?php if (!empty($error_message)): ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem;">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Full Name</div>
                    <input type="text" name="name" class="form-input" placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="form-group">
                <i data-lucide="mail" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Email</div>
                    <input type="email" name="email" class="form-input" placeholder="Enter your email" required>
                </div>
            </div>
            
            <div class="form-group">
                <i data-lucide="lock" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Password</div>
                    <input type="password" name="password" class="form-input" placeholder="Choose a password" required>
                </div>
            </div>

            <div class="form-group">
                <i data-lucide="lock" style="width: 20px; height: 20px;"></i>
                <div style="flex: 1;">
                    <div class="form-label">Confirm Password</div>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Confirm your password" required>
                </div>
            </div>

            <button type="submit" class="btn">
                Create account
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
        lucide.createIcons()
    </script>
</body>
</html>
