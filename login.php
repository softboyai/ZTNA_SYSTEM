<?php
/**
 * Login Page
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Handles user authentication with:
 * - Credential verification using password_verify()
 * - IP address and device info capture
 * - Access logging for every attempt
 * - Role-based redirect to appropriate dashboard
 * - Blocking of suspended users
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

$error = '';

// If user is already logged in, redirect to their dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    switch ($role) {
        case 'admin': header("Location: dashboard_admin.php"); break;
        case 'student': header("Location: dashboard_student.php"); break;
        case 'lecturer': header("Location: dashboard_lecturer.php"); break;
        case 'staff': header("Location: dashboard_staff.php"); break;
    }
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Capture device and IP information for zero-trust verification
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT user_id, username, email, password, user_role, status FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if user account is suspended
            if ($row['status'] === 'suspended') {
                $error = 'Account suspended. Contact the network administrator.';
                
                // Log the denied access attempt
                $log_stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (?, ?, ?, 'denied', 'login - suspended')");
                mysqli_stmt_bind_param($log_stmt, "iss", $row['user_id'], $ip_address, $device_info);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
            // Verify password using bcrypt
            elseif (password_verify($password, $row['password'])) {
                // Authentication successful - set session variables
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_role'] = $row['user_role'];
                
                // Update user's device and IP info in users table
                $update_stmt = mysqli_prepare($conn, "UPDATE users SET device_info = ?, ip_address = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($update_stmt, "ssi", $device_info, $ip_address, $row['user_id']);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
                
                // Log successful access
                $log_stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (?, ?, ?, 'granted', 'login')");
                mysqli_stmt_bind_param($log_stmt, "iss", $row['user_id'], $ip_address, $device_info);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
                
                // Redirect based on user role
                switch ($row['user_role']) {
                    case 'admin':
                        header("Location: dashboard_admin.php");
                        break;
                    case 'student':
                        header("Location: dashboard_student.php");
                        break;
                    case 'lecturer':
                        header("Location: dashboard_lecturer.php");
                        break;
                    case 'staff':
                        header("Location: dashboard_staff.php");
                        break;
                }
                exit();
            } else {
                // Password incorrect
                $error = 'Invalid username or password.';
                
                // Log denied access
                $log_stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (?, ?, ?, 'denied', 'login - wrong password')");
                mysqli_stmt_bind_param($log_stmt, "iss", $row['user_id'], $ip_address, $device_info);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
        } else {
            // Username not found
            $error = 'Invalid username or password.';
            
            // Log denied access (no user_id since user doesn't exist)
            $log_stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (NULL, ?, ?, 'denied', 'login - user not found')");
            mysqli_stmt_bind_param($log_stmt, "ss", $ip_address, $device_info);
            mysqli_stmt_execute($log_stmt);
            mysqli_stmt_close($log_stmt);
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mount Kigali University ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- University Logo and Header -->
            <div class="login-logo">
                <img src="images/MKUR-logo.png" alt="Mount Kigali University Logo" class="login-logo-img">
                <p>Zero-Trust Network Access System</p>
            </div>
            
            <!-- Error Message Display -->
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form class="login-form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-login">Sign In</button>
            </form>
            
            <!-- Link to Register -->
            <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                <p style="font-size: 14px; color: #666;">Don't have an account? <a href="register.php" style="color: #003366; font-weight: 600;">Create Account</a></p>
            </div>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
