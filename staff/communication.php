<?php
/**
 * Communication Tools - Staff
 * Mount Kigali University - ZTNA System
 */
session_start();
require_once '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../login.php"); exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Tools - MKU ZTNA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - Staff</p>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard_staff.php"><span class="nav-icon">&#9733;</span> Dashboard</a>
            <a href="admin_services.php"><span class="nav-icon">&#127970;</span> Admin Services</a>
            <a href="financial_mgmt.php"><span class="nav-icon">&#128176;</span> Finance</a>
            <a href="hr_portal.php"><span class="nav-icon">&#128188;</span> HR Portal</a>
            <a href="communication.php" class="active"><span class="nav-icon">&#128172;</span> Communication</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Communication Tools</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128172; Internal Communication</h2>
                <p>Stay connected with colleagues, departments, and university management.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#9993;</div>
                    <h3>Internal Email</h3>
                    <p>Send and receive messages within the university</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128227;</div>
                    <h3>Announcements</h3>
                    <p>University-wide notices and updates</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128197;</div>
                    <h3>Meeting Scheduler</h3>
                    <p>Schedule and manage meetings with colleagues</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128172;</div>
                    <h3>Team Chat</h3>
                    <p>Real-time messaging with department members</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Recent Announcements</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>From</th><th>Subject</th><th>Date</th><th>Priority</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Vice Chancellor</td><td>End of Year Staff Meeting - December 15</td><td>2024-12-02</td><td><span class="badge badge-denied">High</span></td></tr>
                        <tr><td>HR Department</td><td>Holiday Schedule 2024-2025</td><td>2024-11-30</td><td><span class="badge badge-active">Normal</span></td></tr>
                        <tr><td>ICT Department</td><td>System Maintenance - Saturday Dec 7</td><td>2024-11-28</td><td><span class="badge badge-denied">High</span></td></tr>
                        <tr><td>Finance Office</td><td>Salary Payment Date Change Notice</td><td>2024-11-25</td><td><span class="badge badge-active">Normal</span></td></tr>
                        <tr><td>Administration</td><td>New Parking Arrangements</td><td>2024-11-20</td><td><span class="badge badge-active">Low</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
