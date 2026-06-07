<?php
/**
 * Administrative Services - Staff
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
    <title>Administrative Services - MKU ZTNA</title>
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
            <a href="admin_services.php" class="active"><span class="nav-icon">&#127970;</span> Admin Services</a>
            <a href="financial_mgmt.php"><span class="nav-icon">&#128176;</span> Finance</a>
            <a href="hr_portal.php"><span class="nav-icon">&#128188;</span> HR Portal</a>
            <a href="communication.php"><span class="nav-icon">&#128172;</span> Communication</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Administrative Services</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#127970; University Administration</h2>
                <p>Manage daily administrative operations and university services.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#128203;</div>
                    <div class="stat-info"><h3>23</h3><p>Pending Requests</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#128203;</div>
                    <div class="stat-info"><h3>145</h3><p>Processed This Month</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">&#128197;</div>
                    <div class="stat-info"><h3>5</h3><p>Upcoming Events</p></div>
                </div>
            </div>

            <div class="resource-grid" style="margin-top: 20px;">
                <div class="resource-card">
                    <div class="resource-icon">&#128196;</div>
                    <h3>Document Management</h3>
                    <p>Process student applications, transcripts, and certificates</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128197;</div>
                    <h3>Event Scheduling</h3>
                    <p>Manage university calendar and room bookings</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128230;</div>
                    <h3>Inventory Management</h3>
                    <p>Track university assets, supplies, and equipment</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128236;</div>
                    <h3>Official Correspondence</h3>
                    <p>Handle official letters, memos, and notices</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>Recent Requests</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Request ID</th><th>From</th><th>Type</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>REQ-001</td><td>Uwimana Jean (Student)</td><td>Transcript Request</td><td>2024-12-02</td><td><span class="badge badge-denied">Pending</span></td></tr>
                        <tr><td>REQ-002</td><td>Dr. Mugisha (Lecturer)</td><td>Room Booking</td><td>2024-12-01</td><td><span class="badge badge-granted">Approved</span></td></tr>
                        <tr><td>REQ-003</td><td>ICT Department</td><td>Equipment Purchase</td><td>2024-11-30</td><td><span class="badge badge-denied">Pending</span></td></tr>
                        <tr><td>REQ-004</td><td>Mugisha Alice (Student)</td><td>Letter of Recommendation</td><td>2024-11-28</td><td><span class="badge badge-granted">Completed</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
