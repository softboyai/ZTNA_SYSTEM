<?php
/**
 * Online Learning Platform - Lecturer
 * Mount Kigali University - ZTNA System
 */
session_start();
require_once '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'lecturer') {
    header("Location: ../login.php"); exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning - MKU ZTNA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - Lecturer</p>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard_lecturer.php"><span class="nav-icon">&#9733;</span> Dashboard</a>
            <a href="course_management.php"><span class="nav-icon">&#128218;</span> Course Management</a>
            <a href="student_records.php"><span class="nav-icon">&#128101;</span> Student Records</a>
            <a href="online_learning.php" class="active"><span class="nav-icon">&#128187;</span> Online Learning</a>
            <a href="research_portal.php"><span class="nav-icon">&#128300;</span> Research Portal</a>
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
                <h2>&#128187; Virtual Classroom</h2>
                <p>Manage your online classes, schedule sessions, and interact with students.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#127909;</div>
                    <h3>Schedule Live Class</h3>
                    <p>Create a new virtual classroom session for your students</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128228;</div>
                    <h3>Upload Materials</h3>
                    <p>Share lecture slides, notes, and reading materials</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128203;</div>
                    <h3>Create Assignment</h3>
                    <p>Set new assignments with deadlines and instructions</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128172;</div>
                    <h3>Discussion Forum</h3>
                    <p>Moderate course discussion boards and Q&A</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Upcoming Scheduled Classes</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Course</th><th>Topic</th><th>Date</th><th>Time</th><th>Platform</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>ICT 301</td><td>Network Security Protocols</td><td>2024-12-10</td><td>9:00 AM</td><td>Zoom</td></tr>
                        <tr><td>ICT 305</td><td>Encryption Methods Lab</td><td>2024-12-11</td><td>11:00 AM</td><td>Google Meet</td></tr>
                        <tr><td>ICT 401</td><td>Zero Trust Implementation</td><td>2024-12-12</td><td>2:00 PM</td><td>Zoom</td></tr>
                        <tr><td>ICT 450</td><td>AWS Security Groups</td><td>2024-12-13</td><td>10:00 AM</td><td>Google Meet</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
