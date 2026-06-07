<?php
/**
 * Course Management - Lecturer
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
    <title>Course Management - MKU ZTNA</title>
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
            <a href="course_management.php" class="active"><span class="nav-icon">&#128218;</span> Course Management</a>
            <a href="student_records.php"><span class="nav-icon">&#128101;</span> Student Records</a>
            <a href="online_learning.php"><span class="nav-icon">&#128187;</span> Online Learning</a>
            <a href="research_portal.php"><span class="nav-icon">&#128300;</span> Research Portal</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Course Management</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128218; My Courses</h2>
                <p>Manage your courses, upload materials, and set assignments.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#128218;</div>
                    <div class="stat-info"><h3>4</h3><p>Active Courses</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#128101;</div>
                    <div class="stat-info"><h3>156</h3><p>Total Students</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128203;</div>
                    <div class="stat-info"><h3>8</h3><p>Pending Assignments</p></div>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>My Courses This Semester</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Code</th><th>Course Name</th><th>Students</th><th>Schedule</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>ICT 301</td><td>Introduction to Networking</td><td>45</td><td>Mon/Wed 9:00 AM</td><td><span class="badge badge-granted">Active</span></td></tr>
                        <tr><td>ICT 305</td><td>Cybersecurity Fundamentals</td><td>38</td><td>Tue/Thu 11:00 AM</td><td><span class="badge badge-granted">Active</span></td></tr>
                        <tr><td>ICT 401</td><td>Advanced Network Security</td><td>32</td><td>Mon/Fri 2:00 PM</td><td><span class="badge badge-granted">Active</span></td></tr>
                        <tr><td>ICT 450</td><td>Cloud Computing</td><td>41</td><td>Wed/Fri 10:00 AM</td><td><span class="badge badge-granted">Active</span></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Recent Uploads</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Course</th><th>Material</th><th>Type</th><th>Uploaded</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>ICT 301</td><td>Chapter 7 - TCP/IP Protocol</td><td>Lecture Notes</td><td>2024-12-01</td></tr>
                        <tr><td>ICT 305</td><td>Lab 5 - Firewall Configuration</td><td>Lab Manual</td><td>2024-11-28</td></tr>
                        <tr><td>ICT 401</td><td>Zero Trust Architecture Paper</td><td>Reading</td><td>2024-11-25</td></tr>
                        <tr><td>ICT 450</td><td>AWS Deployment Guide</td><td>Tutorial</td><td>2024-11-22</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
