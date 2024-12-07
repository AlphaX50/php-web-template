<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $sql = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("User not found !");
    }

    $fullName = htmlspecialchars($user['full_name']);
} catch (Exception $e) {
    $fullName = "User";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Name</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">
                <i data-lucide="triangle" style="width: 24px; height: 24px;"></i>
                Project Name
            </a>
            <div class="profile-dropdown">
                <button class="profile-button" onclick="toggleProfileMenu()">
                    <i data-lucide="user-circle" style="width: 20px; height: 20px;"></i>
                    <span><?php echo $fullName; ?></span>
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="profil.php" class="profile-menu-item">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                        <span>Profile</span>
                    </a>
                    <a href="settings.php" class="profile-menu-item">
                        <i data-lucide="settings" style="width: 16px; height: 16px;"></i>
                        <span>Settings</span>
                    </a>
                    <div class="profile-menu-divider"></div>
                    <a href="logout.php" class="profile-menu-item">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Welcome back, <?php echo $fullName; ?> !</h1>
                <p class="dashboard-subtitle">Here's what's happening with your projects.</p>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <i data-lucide="folder" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                        <h3 class="dashboard-card-title">Active Projects</h3>
                    </div>
                    <p class="dashboard-card-content">You have 5 active projects and 2 pending tasks.</p>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <i data-lucide="activity" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                        <h3 class="dashboard-card-title">Recent Activity</h3>
                    </div>
                    <p class="dashboard-card-content">Latest update: Project X milestone completed.</p>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <i data-lucide="users" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                        <h3 class="dashboard-card-title">Team Updates</h3>
                    </div>
                    <p class="dashboard-card-content">3 new team members joined your projects this week.</p>
                </div>
            </div>
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
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('active');
        }
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const menu = document.getElementById('profileMenu');
            
            if (!dropdown.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>