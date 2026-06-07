<?php
/**
 * Reports Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Displays system reports:
 * - Total users by role (table)
 * - Login activity summary
 * - Failed attempts summary
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once '../db_connect.php';

// Session check - only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

// Get users by role
$users_by_role = mysqli_query($conn, "SELECT user_role, COUNT(*) as count FROM users GROUP BY user_role");

// Get login activity for last 7 days
$login_activity = mysqli_query($conn, "SELECT DATE(login_time) as login_date, COUNT(*) as total_logins, SUM(CASE WHEN access_status = 'granted' THEN 1 ELSE 0 END) as granted, SUM(CASE WHEN access_status = 'denied' THEN 1 ELSE 0 END) as denied FROM access_logs WHERE login_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(login_time) ORDER BY login_date DESC");

// Get top failed attempt users
$failed_users = mysqli_query($conn, "SELECT u.username, COUNT(*) as failed_count FROM access_logs al LEFT JOIN users u ON al.user_id = u.user_id WHERE al.access_status = 'denied' GROUP BY al.user_id ORDER BY failed_count DESC LIMIT 10");

// Get total statistics
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'granted'");
$total_granted = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'denied'");
$total_denied = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs");
$total_logs = mysqli_fetch_assoc($result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Admin</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_admin.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="manage_users.php">
                    <span class="nav-icon">&#128101;</span> User Management
                </a>
                <a href="access_logs.php">
                    <span class="nav-icon">&#128203;</span> Access Logs
                </a>
                <a href="security_policies.php">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="network_segments.php">
                    <span class="nav-icon">&#127760;</span> Network Segments
                </a>
                <a href="reports.php" class="active">
                    <span class="nav-icon">&#128202;</span> Reports
                </a>
                <div class="nav-divider"></div>
                <a href="../logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>System Reports</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <div class="content-area">
                <div class="page-title">
                    <h2>Reports &amp; Analytics</h2>
                    <div>
                        <a href="download_report.php" class="btn btn-primary" style="margin-right: 8px;">&#128196; Download PDF Report</a>
                        <a href="generate_report.php" target="_blank" class="btn btn-success">&#128065; View Report</a>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">&#128101;</div>
                        <div class="stat-info">
                            <h3><?php echo $total_users; ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">&#128203;</div>
                        <div class="stat-info">
                            <h3><?php echo $total_logs; ?></h3>
                            <p>Total Log Entries</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">&#128273;</div>
                        <div class="stat-info">
                            <h3><?php echo $total_granted; ?></h3>
                            <p>Access Granted</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">&#9888;</div>
                        <div class="stat-info">
                            <h3><?php echo $total_denied; ?></h3>
                            <p>Access Denied</p>
                        </div>
                    </div>
                </div>

                <!-- Report Cards -->
                <div class="report-grid">
                    <!-- Users by Role -->
                    <div class="report-card">
                        <h3>&#128101; Users by Role</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($users_by_role)): ?>
                                <tr>
                                    <td><?php echo ucfirst($row['user_role']); ?></td>
                                    <td><?php echo $row['count']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Login Activity (Last 7 Days) -->
                    <div class="report-card">
                        <h3>&#128202; Login Activity (Last 7 Days)</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Granted</th>
                                    <th>Denied</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($login_activity)): ?>
                                <tr>
                                    <td><?php echo $row['login_date']; ?></td>
                                    <td><?php echo $row['total_logins']; ?></td>
                                    <td><?php echo $row['granted']; ?></td>
                                    <td><?php echo $row['denied']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Top Failed Attempts -->
                    <div class="report-card">
                        <h3>&#9888; Top Failed Attempt Users</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Failed Attempts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($failed_users)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?></td>
                                    <td><?php echo $row['failed_count']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($failed_users) === 0): ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #999;">No failed attempts recorded</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
