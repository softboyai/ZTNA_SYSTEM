<?php
/**
 * HR Portal - Staff
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
    <title>HR Portal - MKU ZTNA</title>
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
            <a href="hr_portal.php" class="active"><span class="nav-icon">&#128188;</span> HR Portal</a>
            <a href="communication.php"><span class="nav-icon">&#128172;</span> Communication</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>HR Portal</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128188; Human Resources</h2>
                <p>Manage leave requests, employee records, and payroll information.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#128101;</div>
                    <div class="stat-info"><h3>86</h3><p>Total Employees</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#128197;</div>
                    <div class="stat-info"><h3>12</h3><p>On Leave Today</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128203;</div>
                    <div class="stat-info"><h3>5</h3><p>Pending Leave Requests</p></div>
                </div>
            </div>

            <div class="resource-grid" style="margin-top: 20px;">
                <div class="resource-card">
                    <div class="resource-icon">&#128197;</div>
                    <h3>Leave Management</h3>
                    <p>Apply for leave, check balance, view approval status</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128176;</div>
                    <h3>Payroll & Payslips</h3>
                    <p>View monthly payslips and salary details</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128196;</div>
                    <h3>Employee Directory</h3>
                    <p>Search and view employee contact information</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#127891;</div>
                    <h3>Training & Development</h3>
                    <p>Enroll in professional development programs</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>My Leave History</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Leave Type</th><th>From</th><th>To</th><th>Days</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Annual Leave</td><td>2024-12-20</td><td>2024-12-27</td><td>5</td><td><span class="badge badge-denied">Pending</span></td></tr>
                        <tr><td>Sick Leave</td><td>2024-11-10</td><td>2024-11-11</td><td>2</td><td><span class="badge badge-granted">Approved</span></td></tr>
                        <tr><td>Annual Leave</td><td>2024-08-15</td><td>2024-08-25</td><td>8</td><td><span class="badge badge-granted">Approved</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
