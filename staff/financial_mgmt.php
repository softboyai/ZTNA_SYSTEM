<?php
/**
 * Financial Management - Staff
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
    <title>Financial Management - MKU ZTNA</title>
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
            <a href="financial_mgmt.php" class="active"><span class="nav-icon">&#128176;</span> Finance</a>
            <a href="hr_portal.php"><span class="nav-icon">&#128188;</span> HR Portal</a>
            <a href="communication.php"><span class="nav-icon">&#128172;</span> Communication</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Financial Management</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128176; Budget & Finance</h2>
                <p>Monitor budgets, track expenses, and manage financial operations.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">&#128176;</div>
                    <div class="stat-info"><h3>RWF 45M</h3><p>Annual Budget</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">&#128200;</div>
                    <div class="stat-info"><h3>RWF 32M</h3><p>Spent This Year</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128179;</div>
                    <div class="stat-info"><h3>RWF 13M</h3><p>Remaining Budget</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">&#9888;</div>
                    <div class="stat-info"><h3>7</h3><p>Pending Approvals</p></div>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Recent Transactions</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Date</th><th>Description</th><th>Department</th><th>Amount (RWF)</th><th>Type</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>2024-12-01</td><td>Staff Salaries - November</td><td>Human Resources</td><td>12,500,000</td><td><span class="badge badge-denied">Expense</span></td></tr>
                        <tr><td>2024-11-28</td><td>Lab Equipment - Computers</td><td>ICT Department</td><td>3,200,000</td><td><span class="badge badge-denied">Expense</span></td></tr>
                        <tr><td>2024-11-25</td><td>Student Tuition Fees</td><td>Finance</td><td>8,500,000</td><td><span class="badge badge-granted">Income</span></td></tr>
                        <tr><td>2024-11-22</td><td>Internet & Network Services</td><td>ICT Department</td><td>500,000</td><td><span class="badge badge-denied">Expense</span></td></tr>
                        <tr><td>2024-11-20</td><td>Government Grant</td><td>Administration</td><td>15,000,000</td><td><span class="badge badge-granted">Income</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
