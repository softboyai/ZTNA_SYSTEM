<?php
/**
 * Academic Records - Student
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
    <title>Academic Records - MKU ZTNA</title>
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
            <a href="academic_records.php" class="active"><span class="nav-icon">&#128196;</span> Academic Records</a>
            <a href="library.php"><span class="nav-icon">&#128214;</span> Library</a>
            <a href="email_comm.php"><span class="nav-icon">&#9993;</span> Email</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Academic Records</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128196; My Academic Transcript</h2>
                <p>View your grades, GPA, and academic progress.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#127891;</div>
                    <div class="stat-info"><h3>3.45</h3><p>Current GPA</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#128203;</div>
                    <div class="stat-info"><h3>96</h3><p>Credits Completed</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128214;</div>
                    <div class="stat-info"><h3>24</h3><p>Credits Remaining</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">&#9733;</div>
                    <div class="stat-info"><h3>Year 3</h3><p>Academic Level</p></div>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Current Semester Grades</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Course Code</th><th>Course Name</th><th>Credits</th><th>Grade</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>ICT 301</td><td>Introduction to Networking</td><td>4</td><td>B+</td><td><span class="badge badge-granted">Pass</span></td></tr>
                        <tr><td>ICT 305</td><td>Cybersecurity Fundamentals</td><td>3</td><td>A-</td><td><span class="badge badge-granted">Pass</span></td></tr>
                        <tr><td>ICT 310</td><td>Database Management</td><td>4</td><td>A</td><td><span class="badge badge-granted">Pass</span></td></tr>
                        <tr><td>ICT 312</td><td>Web Development</td><td>3</td><td>--</td><td><span class="badge badge-denied">In Progress</span></td></tr>
                        <tr><td>GEN 300</td><td>Research Methodology</td><td>2</td><td>B</td><td><span class="badge badge-granted">Pass</span></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Previous Semesters</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Semester</th><th>Credits</th><th>GPA</th><th>Standing</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Year 2, Semester 2</td><td>18</td><td>3.50</td><td><span class="badge badge-granted">Good Standing</span></td></tr>
                        <tr><td>Year 2, Semester 1</td><td>17</td><td>3.40</td><td><span class="badge badge-granted">Good Standing</span></td></tr>
                        <tr><td>Year 1, Semester 2</td><td>16</td><td>3.35</td><td><span class="badge badge-granted">Good Standing</span></td></tr>
                        <tr><td>Year 1, Semester 1</td><td>15</td><td>3.55</td><td><span class="badge badge-granted">Good Standing</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
