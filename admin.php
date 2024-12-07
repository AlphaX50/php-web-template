<?php
require 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer l'utilisateur actuel
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Vérifier si l'utilisateur est administrateur
if ($user['role'] != 1) {
    header('Location: index.php');
    exit;
}

// Récupérer tous les utilisateurs
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();

if (isset($_POST['delete_user_id'])) {
    require 'config.php'; // Assurez-vous que la connexion à la base de données est incluse
    $delete_id = intval($_POST['delete_user_id']);

    // Supprime l'utilisateur de la base de données
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$delete_id])) {
        echo "<script>alert('User deleted successfully!');</script>";
    } else {
        echo "<script>alert('Failed to delete user.');</script>";
    }



    // Modifier le rôle d'un utilisateur
    if (isset($_POST['edit_user'])) {
        $edit_user_id = $_POST['user_id'];
        $new_role = $_POST['role'];
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_role, $edit_user_id]);
    }

    // Bannir un utilisateur
    if (isset($_POST['ban_user'])) {
        $ban_user_ip = $_POST['user_ip'];
        $sql = "INSERT INTO banned_ips (ip_address) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ban_user_ip]);
    }

    header("Location: admin.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ProjectHub</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <i data-lucide="triangle" style="width: 24px; height: 24px;"></i>
                ProjectHub
            </a>
            <div class="profile-dropdown">
                <button class="profile-button" onclick="toggleProfileMenu()">
                    <i data-lucide="user-circle" style="width: 20px; height: 20px;"></i>
                    <span><?= htmlspecialchars($user['full_name']) ?></span>
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="profile.php" class="profile-menu-item">
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
                <h1 class="dashboard-title">Admin Panel</h1>
                <p class="dashboard-subtitle">Manage users, projects, and system settings.</p>
            </div>

            <div class="admin-tabs">
                <button class="admin-tab active">Users</button>
                <button class="admin-tab">Projects</button>
                <button class="admin-tab">Analytics</button>
                <button class="admin-tab">System</button>
            </div>

            <div class="admin-content">
                <div class="admin-actions">
                    <div class="search-bar">
                        <i data-lucide="search" style="width: 20px; height: 20px; color: #A1A1AA;"></i>
                        <input type="text" placeholder="Search users..." class="search-input">
                    </div>
                    <button class="btn-primary">
                        <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                        Add User
                    </button>
                </div>

                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Projects</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <img src="<?= htmlspecialchars($user['profile_picture'] ?: 'default-avatar.png') ?>" alt="User" class="user-avatar">
                                        <div>
                                            <div class="user-name"><?= htmlspecialchars($user['full_name']) ?></div>
                                            <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $user['role'] == 1 ? 'Admin' : 'Member' ?></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>8</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Edit">
                                            <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirmDelete(this);">
                                        <input type="hidden" name="delete_user_id" value="<?= $user['id']; ?>">
                                        <button type="submit" class="btn-icon" title="Delete">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                        </form>
                                        <button class="btn-icon" title="Ban">
                                            <i data-lucide="ban" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <button class="btn-secondary">Previous</button>
                    <div class="pagination-numbers">
                        <button class="page-number active">1</button>
                        <button class="page-number">2</button>
                        <button class="page-number">3</button>
                    </div>
                    <button class="btn-secondary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        function confirmDelete(form) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
    </script>
</body>
</html>