<?php
/**
 * Email & Communication - Student
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
    <title>Email & Communication - MKU ZTNA</title>
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
            <a href="learning_platform.php"><span class="nav-icon">&#128218;</span> Learning Platform</a>
            <a href="academic_records.php"><span class="nav-icon">&#128196;</span> Academic Records</a>
            <a href="library.php"><span class="nav-icon">&#128214;</span> Library</a>
            <a href="email_comm.php" class="active"><span class="nav-icon">&#9993;</span> Email</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Email & Communication</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#9993; Inbox</h2>
                <p>University email and internal messaging system.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#9993;</div>
                    <div class="stat-info"><h3>12</h3><p>Unread Messages</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#128228;</div>
                    <div class="stat-info"><h3>5</h3><p>Sent Today</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128227;</div>
                    <div class="stat-info"><h3>3</h3><p>Announcements</p></div>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Recent Messages</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>From</th><th>Subject</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Academic Office</td><td>Exam Timetable - December 2024</td><td>2024-12-01</td><td><span class="badge badge-denied">Unread</span></td></tr>
                        <tr><td>Dr. Mugisha</td><td>Lab Report Feedback - Networking</td><td>2024-11-30</td><td><span class="badge badge-denied">Unread</span></td></tr>
                        <tr><td>Library</td><td>Book Return Reminder</td><td>2024-11-28</td><td><span class="badge badge-granted">Read</span></td></tr>
                        <tr><td>ICT Department</td><td>Password Reset Notification</td><td>2024-11-25</td><td><span class="badge badge-granted">Read</span></td></tr>
                        <tr><td>Student Affairs</td><td>Holiday Schedule Announcement</td><td>2024-11-22</td><td><span class="badge badge-granted">Read</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
