<?php
/**
 * Student Dashboard
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Dashboard for students showing:
 * - Welcome message with username
 * - Last login time and device info
 * - Resource cards for university services
 * 
 * Access: Only users with role 'student' can view this page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Session check - only students can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
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
    <title>Student Dashboard - MKU ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Student</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard_student.php" class="active">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="student/learning_platform.php">
                    <span class="nav-icon">&#128218;</span> Learning Platform
                </a>
                <a href="student/academic_records.php">
                    <span class="nav-icon">&#128196;</span> Academic Records
                </a>
                <a href="student/library.php">
                    <span class="nav-icon">&#128214;</span> Library
                </a>
                <a href="student/email_comm.php">
                    <span class="nav-icon">&#9993;</span> Email
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
                <h1>Student Portal</h1>
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
                    <a href="student/learning_platform.php" class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Online Learning Platform</h3>
                        <p>Access course materials, lectures, and assignments</p>
                    </a>
                    <a href="student/academic_records.php" class="resource-card">
                        <div class="resource-icon">&#128196;</div>
                        <h3>Academic Records</h3>
                        <p>View grades, transcripts, and academic progress</p>
                    </a>
                    <a href="student/library.php" class="resource-card">
                        <div class="resource-icon">&#128214;</div>
                        <h3>Library Services</h3>
                        <p>Search and access digital library resources</p>
                    </a>
                    <a href="student/email_comm.php" class="resource-card">
                        <div class="resource-icon">&#9993;</div>
                        <h3>Email &amp; Communication</h3>
                        <p>University email and messaging services</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
