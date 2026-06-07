<?php
/**
 * Access Logs Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Displays all access log entries with:
 * - Log ID, Username, Login Time, IP Address, Device, Status, Resource
 * - Filter by date and status
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

// Handle filters
$filter_date = $_GET['date'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Build query with filters
$query = "SELECT al.*, u.username FROM access_logs al LEFT JOIN users u ON al.user_id = u.user_id WHERE 1=1";
$params = [];
$types = '';

if (!empty($filter_date)) {
    $query .= " AND DATE(al.login_time) = ?";
    $params[] = $filter_date;
    $types .= 's';
}

if (!empty($filter_status)) {
    $query .= " AND al.access_status = ?";
    $params[] = $filter_status;
    $types .= 's';
}

$query .= " ORDER BY al.login_time DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$logs = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Logs - MKU ZTNA System</title>
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
                <a href="access_logs.php" class="active">
                    <span class="nav-icon">&#128203;</span> Access Logs
                </a>
                <a href="security_policies.php">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="network_segments.php">
                    <span class="nav-icon">&#127760;</span> Network Segments
                </a>
                <a href="reports.php">
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
                <h1>Access Logs</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <div class="content-area">
                <div class="page-title">
                    <h2>All Access Logs</h2>
                </div>

                <!-- Logs Table with Filter -->
                <div class="table-container">
                    <!-- Filter Bar -->
                    <form class="filter-bar" method="GET" action="access_logs.php">
                        <input type="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>" placeholder="Filter by date">
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="granted" <?php echo $filter_status === 'granted' ? 'selected' : ''; ?>>Granted</option>
                            <option value="denied" <?php echo $filter_status === 'denied' ? 'selected' : ''; ?>>Denied</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="access_logs.php" class="btn btn-danger btn-sm">Clear</a>
                    </form>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Username</th>
                                <th>Login Time</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Resource Accessed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($log = mysqli_fetch_assoc($logs)): ?>
                            <tr>
                                <td><?php echo $log['log_id']; ?></td>
                                <td><?php echo htmlspecialchars($log['username'] ?? 'Unknown'); ?></td>
                                <td><?php echo $log['login_time']; ?></td>
                                <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                <td><?php echo htmlspecialchars(substr($log['device_info'] ?? '', 0, 50)); ?></td>
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

    <script src="../js/main.js"></script>
</body>
</html>
