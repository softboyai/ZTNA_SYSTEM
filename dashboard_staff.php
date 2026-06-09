<?php
/**
 * Staff Dashboard
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Dashboard for staff members showing:
 * - Welcome message with username
 * - Last login time and device info
 * - Resource cards for administrative services
 * 
 * Access: Only users with role 'staff' can view this page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Session check - only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Get last login info
$stmt = mysqli_prepare($conn, "SELECT login_time, device_info FROM access_logs WHERE user_id = ? AND access_status = 'granted' ORDER BY login_time DESC LIMIT 1 OFFSET 1");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$last_login = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - MKU ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Staff</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard_staff.php" class="active">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="staff/admin_services.php">
                    <span class="nav-icon">&#127970;</span> Admin Services
                </a>
                <a href="staff/financial_mgmt.php">
                    <span class="nav-icon">&#128176;</span> Finance
                </a>
                <a href="staff/hr_portal.php">
                    <span class="nav-icon">&#128188;</span> HR Portal
                </a>
                <a href="staff/communication.php">
                    <span class="nav-icon">&#128172;</span> Communication
                </a>
                <a href="my_profile.php">
                    <span class="nav-icon">&#128100;</span> My Security
                </a>
                <div class="nav-divider"></div>
                <a href="logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>Staff Portal</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="content-area">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                    <div class="meta-info">
                        <span><strong>Last Login:</strong> <?php echo $last_login ? $last_login['login_time'] : 'First login'; ?></span>
                        <span><strong>Device:</strong> <?php echo $last_login ? htmlspecialchars(substr($last_login['device_info'], 0, 60)) . '...' : 'N/A'; ?></span>
                    </div>
                </div>

                <!-- Resource Cards -->
                <h2 style="color: #003366; margin-bottom: 15px;">University Resources</h2>
                <div class="resource-grid">
                    <a href="staff/admin_services.php" class="resource-card">
                        <div class="resource-icon">&#127970;</div>
                        <h3>Administrative Services</h3>
                        <p>Access administrative tools and university management</p>
                    </a>
                    <a href="staff/financial_mgmt.php" class="resource-card">
                        <div class="resource-icon">&#128176;</div>
                        <h3>Financial Management</h3>
                        <p>Budget tracking, expenses, and financial reports</p>
                    </a>
                    <a href="staff/hr_portal.php" class="resource-card">
                        <div class="resource-icon">&#128188;</div>
                        <h3>HR Portal</h3>
                        <p>Leave management, payroll, and employee services</p>
                    </a>
                    <a href="staff/communication.php" class="resource-card">
                        <div class="resource-icon">&#128172;</div>
                        <h3>Communication Tools</h3>
                        <p>Internal messaging, announcements, and collaboration</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
