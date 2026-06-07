<?php
/**
 * Admin Dashboard
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Main dashboard for Network Administrators showing:
 * - Total Users count
 * - Total Logins Today
 * - Failed Login Attempts Today
 * - Active Network Segments
 * 
 * Access: Only users with role 'admin' can view this page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Session check - only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get total users count
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($result)['total'];

// Get total logins today
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE DATE(login_time) = CURDATE() AND access_status = 'granted'");
$logins_today = mysqli_fetch_assoc($result)['total'];

// Get failed login attempts today
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE DATE(login_time) = CURDATE() AND access_status = 'denied'");
$failed_today = mysqli_fetch_assoc($result)['total'];

// Get active network segments
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM network_segments");
$active_segments = mysqli_fetch_assoc($result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MKU ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Admin</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard_admin.php" class="active">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="admin/manage_users.php">
                    <span class="nav-icon">&#128101;</span> User Management
                </a>
                <a href="admin/access_logs.php">
                    <span class="nav-icon">&#128203;</span> Access Logs
                </a>
                <a href="admin/security_policies.php">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="admin/network_segments.php">
                    <span class="nav-icon">&#127760;</span> Network Segments
                </a>
                <a href="admin/reports.php">
                    <span class="nav-icon">&#128202;</span> Reports
                </a>
                <div class="nav-divider"></div>
                <a href="logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>Dashboard Overview</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="content-area">
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">&#128101;</div>
                        <div class="stat-info">
                            <h3><?php echo $total_users; ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">&#128273;</div>
                        <div class="stat-info">
                            <h3><?php echo $logins_today; ?></h3>
                            <p>Logins Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">&#9888;</div>
                        <div class="stat-info">
                            <h3><?php echo $failed_today; ?></h3>
                            <p>Failed Attempts Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">&#127760;</div>
                        <div class="stat-info">
                            <h3><?php echo $active_segments; ?></h3>
                            <p>Network Segments</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Access Logs -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Recent Access Activity</h2>
                        <a href="admin/access_logs.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Time</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Resource</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get recent 10 access logs
                            $logs = mysqli_query($conn, "SELECT al.*, u.username FROM access_logs al LEFT JOIN users u ON al.user_id = u.user_id ORDER BY al.login_time DESC LIMIT 10");
                            while ($log = mysqli_fetch_assoc($logs)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log['username'] ?? 'Unknown'); ?></td>
                                <td><?php echo $log['login_time']; ?></td>
                                <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $log['access_status']; ?>">
                                        <?php echo ucfirst($log['access_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($log['resource_accessed'] ?? '-'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
