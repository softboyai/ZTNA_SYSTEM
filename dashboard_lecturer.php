<?php
/**
 * Lecturer Dashboard
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Dashboard for lecturers showing:
 * - Welcome message with username
 * - Last login time and device info
 * - Resource cards for teaching and research services
 * 
 * Access: Only users with role 'lecturer' can view this page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Session check - only lecturers can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'lecturer') {
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
    <title>Lecturer Dashboard - MKU ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Lecturer</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard_lecturer.php" class="active">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="lecturer/course_management.php">
                    <span class="nav-icon">&#128218;</span> Course Management
                </a>
                <a href="lecturer/student_records.php">
                    <span class="nav-icon">&#128101;</span> Student Records
                </a>
                <a href="lecturer/online_learning.php">
                    <span class="nav-icon">&#128187;</span> Online Learning
                </a>
                <a href="lecturer/research_portal.php">
                    <span class="nav-icon">&#128300;</span> Research Portal
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
                <h1>Lecturer Portal</h1>
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
                    <a href="lecturer/course_management.php" class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Course Management</h3>
                        <p>Manage courses, upload materials, and set assignments</p>
                    </a>
                    <a href="lecturer/student_records.php" class="resource-card">
                        <div class="resource-icon">&#128101;</div>
                        <h3>Student Records</h3>
                        <p>View and manage student grades and attendance</p>
                    </a>
                    <a href="lecturer/online_learning.php" class="resource-card">
                        <div class="resource-icon">&#128187;</div>
                        <h3>Online Learning Platform</h3>
                        <p>Virtual classroom and e-learning tools</p>
                    </a>
                    <a href="lecturer/research_portal.php" class="resource-card">
                        <div class="resource-icon">&#128300;</div>
                        <h3>Research Portal</h3>
                        <p>Access research databases, journals, and collaboration tools</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
