<?php
/**
 * Add User Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Form to add a new user with:
 * - Username, Email, Password, Role
 * - Password is hashed with password_hash() before storing
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_password = $_POST['password'] ?? '';
    $new_role = $_POST['user_role'] ?? '';
    
    // Validate inputs
    if (empty($new_username) || empty($new_email) || empty($new_password) || empty($new_role)) {
        $error = 'All fields are required.';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $new_username)) {
        $error = 'Username must contain only letters and spaces. No numbers allowed.';
    } elseif (strlen($new_username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $new_email)) {
        $error = 'Email must be a real email address with a valid domain.';
    } elseif (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $error = 'Password must contain at least one uppercase letter (A-Z).';
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $error = 'Password must contain at least one lowercase letter (a-z).';
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $error = 'Password must contain at least one number (0-9).';
    } elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/', $new_password)) {
        $error = 'Password must contain at least one special character (!@#$%^&* etc).';
    } else {
        // Check if email already exists
        $check_stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $new_email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Email address already exists.';
        } else {
            // Hash password using bcrypt
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Insert new user using prepared statement
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, user_role) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $new_username, $new_email, $hashed_password, $new_role);
            
            if (mysqli_stmt_execute($stmt)) {
                $message = 'User added successfully!';
            } else {
                $error = 'Error adding user. Please try again.';
            }
            mysqli_stmt_close($stmt);
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
    <title>Add User - MKU ZTNA System</title>
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
                <h1>Add New User</h1>
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

                <!-- Add User Form -->
                <div class="form-container">
                    <h2>Create New User Account</h2>
                    <form method="POST" action="add_user.php">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter email address" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <label for="user_role">User Role</label>
                            <select id="user_role" name="user_role" required>
                                <option value="">-- Select Role --</option>
                                <option value="admin">Network Administrator</option>
                                <option value="student">Student</option>
                                <option value="lecturer">Lecturer</option>
                                <option value="staff">Staff Member</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                        <a href="manage_users.php" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
