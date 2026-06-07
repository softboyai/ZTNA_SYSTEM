<?php
/**
 * Manage Users Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Displays table of all users with:
 * - ID, Username, Email, Role, Status, Date Created, Actions
 * - Actions: Edit, Suspend/Activate, Delete
 * - Button to add new user
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once '../db_connect.php';

// Session check - only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$message = '';
$error = '';

// Handle suspend/activate action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);
    
    if ($action === 'suspend') {
        $stmt = mysqli_prepare($conn, "UPDATE users SET status = 'suspended' WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $target_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $message = 'User has been suspended successfully.';
    } elseif ($action === 'activate') {
        $stmt = mysqli_prepare($conn, "UPDATE users SET status = 'active' WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $target_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $message = 'User has been activated successfully.';
    } elseif ($action === 'delete') {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $target_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $message = 'User has been deleted successfully.';
    }
}

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Admin</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_admin.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="manage_users.php" class="active">
                    <span class="nav-icon">&#128101;</span> User Management
                </a>
                <a href="access_logs.php">
                    <span class="nav-icon">&#128203;</span> Access Logs
                </a>
                <a href="security_policies.php">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="network_segments.php">
                    <span class="nav-icon">&#127760;</span> Network Segments
                </a>
                <a href="reports.php">
                    <span class="nav-icon">&#128202;</span> Reports
                </a>
                <div class="nav-divider"></div>
                <a href="../logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>User Management</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <div class="content-area">
                <!-- Messages -->
                <?php if (!empty($message)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <!-- Page Title with Add Button -->
                <div class="page-title">
                    <h2>All Users</h2>
                    <a href="add_user.php" class="btn btn-primary">+ Add New User</a>
                </div>

                <!-- Users Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo ucfirst($user['user_role']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['status']; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $user['created_at']; ?></td>
                                <td class="action-links">
                                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <a href="manage_users.php?action=suspend&id=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirmAction('Suspend this user?')">Suspend</a>
                                    <?php else: ?>
                                        <a href="manage_users.php?action=activate&id=<?php echo $user['user_id']; ?>" class="btn btn-success btn-sm" onclick="return confirmAction('Activate this user?')">Activate</a>
                                    <?php endif; ?>
                                    <a href="manage_users.php?action=delete&id=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Are you sure you want to delete this user?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
