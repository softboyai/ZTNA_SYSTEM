<?php
/**
 * Online Learning Platform - Student
 * Mount Kigali University - ZTNA System
 */
session_start();
require_once '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php"); exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Platform - MKU ZTNA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - Student</p>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard_student.php"><span class="nav-icon">&#9733;</span> Dashboard</a>
            <a href="learning_platform.php" class="active"><span class="nav-icon">&#128218;</span> Learning Platform</a>
            <a href="academic_records.php"><span class="nav-icon">&#128196;</span> Academic Records</a>
            <a href="library.php"><span class="nav-icon">&#128214;</span> Library</a>
            <a href="email_comm.php"><span class="nav-icon">&#9993;</span> Email</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Online Learning Platform</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128218; My Courses</h2>
                <p>Access your enrolled courses, watch lectures, and submit assignments.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#128187;</div>
                    <h3>Introduction to Networking</h3>
                    <p>Instructor: Dr. Mugisha<br>Progress: 75%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #003366; width: 75%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128274;</div>
                    <h3>Cybersecurity Fundamentals</h3>
                    <p>Instructor: Prof. Uwimana<br>Progress: 50%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #003366; width: 50%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128190;</div>
                    <h3>Database Management</h3>
                    <p>Instructor: Mr. Habimana<br>Progress: 90%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #28a745; width: 90%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#127760;</div>
                    <h3>Web Development</h3>
                    <p>Instructor: Ms. Ingabire<br>Progress: 30%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #fd7e14; width: 30%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Upcoming Assignments</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Course</th><th>Assignment</th><th>Due Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Networking</td><td>Lab Report 5 - Subnetting</td><td>2024-12-15</td><td><span class="badge badge-denied">Pending</span></td></tr>
                        <tr><td>Cybersecurity</td><td>Research Paper - Zero Trust</td><td>2024-12-18</td><td><span class="badge badge-denied">Pending</span></td></tr>
                        <tr><td>Database</td><td>Final Project - PHP + MySQL</td><td>2024-12-20</td><td><span class="badge badge-granted">Submitted</span></td></tr>
                        <tr><td>Web Development</td><td>Portfolio Website</td><td>2024-12-22</td><td><span class="badge badge-denied">Pending</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
