<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, email, bio FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    $sql = "UPDATE users SET full_name = ?, email = ?, bio = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$full_name, $email, $bio, $user_id]);

    if ($result) {
        $_SESSION['profile_update_success'] = true;
        header("Location: profil.php");
        exit;
    } else {
        $_SESSION['profile_update_success'] = false;
    }
}

if (isset($_SESSION['profile_update_success'])) {
    $success_message = $_SESSION['profile_update_success'];
    unset($_SESSION['profile_update_success']);
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Project Name</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <i data-lucide="triangle" style="width: 24px; height: 24px;"></i>
                Project Name
            </a>
            <div class="profile-dropdown">
                <button class="profile-button" onclick="toggleProfileMenu()">
                    <i data-lucide="user-circle" style="width: 20px; height: 20px;"></i>
                    <span><?= htmlspecialchars($user['full_name']); ?></span>
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="#" class="profile-menu-item">
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
                <h1 class="dashboard-title">Profile</h1>
                <p class="dashboard-subtitle">Manage your personal information and account settings.</p>
            </div>

            <div class="profile-section">
                <div class="profile-avatar">
                    <img src="https://cdn.discordapp.com/attachments/1202572905947471946/1314910490215841872/user-circle.png?ex=67557d52&is=67542bd2&hm=d72756fefce02616c94205beeb491f047bbc06438fcb17409200baffcd330adb&" alt="Profile picture" class="avatar-image">
                    <button class="btn-secondary">Change Photo</button>
                </div>

                <form class="profile-form" method="POST" action="profil.php">
                    <div class="form-group-row">
                        <div class="form-group1">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-input" value="<?= htmlspecialchars($user['full_name']); ?>" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <div class="form-group1">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($user['email']); ?>" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group1">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-input" rows="4" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        <button type="button" class="btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
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

        <?php if (isset($success_message)) : ?>
            Swal.fire({
                icon: '<?= $success_message ? "success" : "error"; ?>',
                title: '<?= $success_message ? "Profile Updated" : "Error"; ?>',
                text: '<?= $success_message ? "Your profile has been updated successfully." : "There was a problem updating your profile."; ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
</body>
</html>
