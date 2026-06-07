<?php
/**
 * Edit User Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Pre-filled form to update user details:
 * - Username, Email, Role, Status
 * - Password update is optional
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

// Get user ID from URL
$edit_id = intval($_GET['id'] ?? 0);

if ($edit_id === 0) {
    header("Location: manage_users.php");
    exit();
}

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $edit_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_role = $_POST['user_role'] ?? '';
    $new_status = $_POST['status'] ?? '';
    $new_password = $_POST['password'] ?? '';
    
    if (empty($new_username) || empty($new_email) || empty($new_role) || empty($new_status)) {
        $error = 'Username, email, role, and status are required.';
    } else {
        // Check if email is already used by another user
        $check_stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        mysqli_stmt_bind_param($check_stmt, "si", $new_email, $edit_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Email address is already used by another user.';
        } else {
            // Update user - with or without password change
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ?, password = ?, user_role = ?, status = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($update_stmt, "sssssi", $new_username, $new_email, $hashed_password, $new_role, $new_status, $edit_id);
            } else {
                $update_stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ?, user_role = ?, status = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($update_stmt, "ssssi", $new_username, $new_email, $new_role, $new_status, $edit_id);
            }
            
            if (mysqli_stmt_execute($update_stmt)) {
                $message = 'User updated successfully!';
                // Refresh user data
                $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $edit_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Error updating user. Please try again.';
            }
            mysqli_stmt_close($update_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - MKU ZTNA System</title>
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
                <h1>Edit User</h1>
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

                <!-- Edit User Form -->
                <div class="form-container">
                    <h2>Edit User: <?php echo htmlspecialchars($user['username']); ?></h2>
                    <form method="POST" action="edit_user.php?id=<?php echo $edit_id; ?>">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password (leave blank to keep current)</label>
                            <input type="password" id="password" name="password" placeholder="Enter new password (optional)">
                        </div>
                        <div class="form-group">
                            <label for="user_role">User Role</label>
                            <select id="user_role" name="user_role" required>
                                <option value="admin" <?php echo $user['user_role'] === 'admin' ? 'selected' : ''; ?>>Network Administrator</option>
                                <option value="student" <?php echo $user['user_role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                                <option value="lecturer" <?php echo $user['user_role'] === 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                                <option value="staff" <?php echo $user['user_role'] === 'staff' ? 'selected' : ''; ?>>Staff Member</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="suspended" <?php echo $user['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="manage_users.php" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
