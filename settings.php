<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        if (password_verify($current_password, $user['password'])) {
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $pdo->prepare($update_sql);
            if ($stmt->execute([$hashed_new_password, $user_id])) {
                $_SESSION['success_message'] = "Password updated successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to update password!";
            }
        } else {
            $_SESSION['error_message'] = "Current password is incorrect!";
        }
    }

    if (isset($_POST['delete_account']) && $_POST['delete_account'] === 'true') {
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($delete_sql);
        if ($stmt->execute([$user_id])) {
            session_destroy();

            echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
            exit;
        }
    }
    
    header("Location: settings.php");
    exit;
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Project Name</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                    <span><?= htmlspecialchars($user['full_name']) ?></span>
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="profil.php" class="profile-menu-item">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#" class="profile-menu-item">
                        <i data-lucide="settings" style="width: 16px; height: 16px;"></i>
                        <span>Settings</span>
                    </a>
                    <div class="profile-menu-divider"></div>
                    <a href="login.php" class="profile-menu-item">
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
                <h1 class="dashboard-title">Settings</h1>
                <p class="dashboard-subtitle">Manage your account preferences and security settings.</p>
            </div>

            <div class="settings-grid">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i data-lucide="bell" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                        <h3 class="settings-card-title">Notifications</h3>
                    </div>
                    <div class="settings-card-content">
                        <div class="settings-option">
                            <div>
                                <h4>Email Notifications</h4>
                                <p>Receive project updates via email</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="settings-option">
                            <div>
                                <h4>Push Notifications</h4>
                                <p>Get instant updates in your browser</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                <div class="settings-card-header">
                    <i data-lucide="shield" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                    <h3 class="settings-card-title">Security</h3>
                </div>
                <div class="settings-card-content">
                    <form method="POST" action="settings.php">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-input" placeholder="Enter current password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-input" placeholder="Enter new password" required>
                        </div>
                        <button type="submit" name="update_password" class="btn-primary" type="button">Update Password</button>
                    </form>
                </div>
            </div>

            <div class="settings-card danger-zone">
                <div class="settings-card-header">
                    <i data-lucide="alert-triangle" style="width: 20px; height: 20px; color: #ef4444;"></i>
                    <h3 class="settings-card-title">Danger Zone</h3>
                </div>
                <div class="settings-card-content">
                    <form method="POST" action="settings.php">
                        <button class="btn-danger" id="deleteAccountButton">Delete Account</button>
                    </form>
                </div>
            </div>

            <form id="deleteAccountForm" method="POST" action="settings.php" style="display: none;">
                <input type="hidden" name="delete_account" value="true">
            </form>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        lucide.createIcons();

        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('active');
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const menu = document.getElementById('profileMenu');

            if (!dropdown.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);

    <?php if (!empty($success_message)) : ?>
        swal("Success", "<?php echo $success_message; ?>", "success");
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
        swal("Error", "<?php echo $error_message; ?>", "error");
    <?php endif; ?>

    if (urlParams.has('account_deleted')) {
        swal("Account Deleted", "Your account has been successfully deleted.", "success");
    }
});

document.getElementById('deleteAccountButton').addEventListener('click', function (event) {
    event.preventDefault();

    swal({
        title: "Are you sure?",
        text: "Once you delete your account, there is no going back.",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: true,
                className: "btn-secondary",
                closeModal: true
            },
            confirm: {
                text: "Yes, delete it!",
                value: true,
                visible: true,
                className: "btn-danger",
                closeModal: true
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById('deleteAccountForm');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    swal("Account Deleted", "Your account has been successfully deleted.", "success")
                        .then(() => {
                            window.location.href = "login.php";
                        });
                } else {
                    swal("Error", "Failed to delete account.", "error");
                }
            }).catch(error => {
                swal("Error", "An error occurred while deleting your account.", "error");
            });
        }
    });
});

    </script>
</body>
</html>