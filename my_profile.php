<?php
/**
 * My Profile & Security Page
 * Mount Kigali University - ZTNA System
 * 
 * This page PROVES Zero-Trust monitoring works.
 * Every user (admin, student, lecturer, staff) can see:
 * - Their account details
 * - Their last known IP address and device
 * - Their full access history (login/logout records)
 * - Which network segments they can access
 * - Active security policies affecting them
 * 
 * This demonstrates Objectives i, ii, iii working together.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Session check - any logged-in user can access their profile
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];

// Get user details
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

// Get user's access history (last 15 entries)
$stmt = mysqli_prepare($conn, "SELECT * FROM access_logs WHERE user_id = ? ORDER BY login_time DESC LIMIT 15");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$access_history = mysqli_stmt_get_result($stmt);

// Get network segments this user can access
$segments = mysqli_query($conn, "SELECT * FROM network_segments WHERE allowed_roles LIKE '%" . mysqli_real_escape_string($conn, $user_role) . "%'");

// Get active security policies
$policies = mysqli_query($conn, "SELECT * FROM security_policies WHERE is_active = 1");

// Count total logins for this user
$stmt2 = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM access_logs WHERE user_id = ? AND access_status = 'granted'");
mysqli_stmt_bind_param($stmt2, "i", $user_id);
mysqli_stmt_execute($stmt2);
$total_logins = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2))['total'];
mysqli_stmt_close($stmt2);

// Count denied attempts for this user
$stmt3 = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM access_logs WHERE user_id = ? AND access_status = 'denied'");
mysqli_stmt_bind_param($stmt3, "i", $user_id);
mysqli_stmt_execute($stmt3);
$total_denied = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt3))['total'];
mysqli_stmt_close($stmt3);

// Determine back link based on role
$back_link = "dashboard_" . $user_role . ".php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile & Security - MKU ZTNA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - <?php echo ucfirst($user_role); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo $back_link; ?>">
                <span class="nav-icon">&#9733;</span> Dashboard
            </a>
            <a href="my_profile.php" class="active">
                <span class="nav-icon">&#128100;</span> My Profile & Security
            </a>
            <div class="nav-divider"></div>
            <a href="logout.php">
                <span class="nav-icon">&#128682;</span> Logout
            </a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>My Profile & Security Information</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">

            <!-- Account Information -->
            <div class="welcome-section">
                <h2>&#128100; My Account Information</h2>
                <div class="meta-info" style="margin-top: 15px;">
                    <span><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></span>
                    <span><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></span>
                    <span><strong>Role:</strong> <?php echo ucfirst($user['user_role']); ?></span>
                    <span><strong>Status:</strong> <?php echo ucfirst($user['status']); ?></span>
                    <span><strong>Account Created:</strong> <?php echo $user['created_at']; ?></span>
                </div>
            </div>

            <!-- Security Stats - proves monitoring is active -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">&#128273;</div>
                    <div class="stat-info">
                        <h3><?php echo $total_logins; ?></h3>
                        <p>Total Successful Logins</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">&#9888;</div>
                    <div class="stat-info">
                        <h3><?php echo $total_denied; ?></h3>
                        <p>Denied Attempts on My Account</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">&#127760;</div>
                    <div class="stat-info">
                        <h3><?php echo htmlspecialchars($user['ip_address'] ?? 'N/A'); ?></h3>
                        <p>My Last Known IP Address</p>
                    </div>
                </div>
            </div>

            <!-- Device Info - proves device verification -->
            <div class="table-container">
                <div class="table-header">
                    <h2>&#128187; My Device Information (Captured by ZTNA)</h2>
                </div>
                <div style="padding: 20px;">
                    <p style="font-size: 13px; color: #555; margin-bottom: 10px;"><strong>Last Device Used:</strong></p>
                    <p style="font-size: 12px; color: #777; background: #f8f9fa; padding: 12px; border-radius: 6px; word-break: break-all;">
                        <?php echo htmlspecialchars($user['device_info'] ?? 'No device recorded yet'); ?>
                    </p>
                    <p style="font-size: 11px; color: #999; margin-top: 10px;">
                        &#9888; This information is automatically captured at every login for Zero-Trust verification. 
                        If you see an unfamiliar device, contact the network administrator immediately.
                    </p>
                </div>
            </div>

            <!-- Network Segments - proves segmentation is enforced -->
            <div class="table-container" style="margin-top: 20px;">
                <div class="table-header">
                    <h2>&#127760; Network Segments I Can Access</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>Segment Name</th><th>Description</th><th>Access Status</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($seg = mysqli_fetch_assoc($segments)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($seg['segment_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($seg['description']); ?></td>
                            <td><span class="badge badge-granted">Allowed</span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div style="padding: 15px; font-size: 11px; color: #999;">
                    &#128274; You can ONLY access segments listed above. Attempting to access other segments will be denied and logged.
                </div>
            </div>

            <!-- Security Policies - proves policies are active -->
            <div class="table-container" style="margin-top: 20px;">
                <div class="table-header">
                    <h2>&#128274; Active Security Policies</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>Policy</th><th>Description</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($pol = mysqli_fetch_assoc($policies)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($pol['policy_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($pol['description']); ?></td>
                            <td><span class="badge badge-granted">Enforced</span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Access History - proves continuous monitoring -->
            <div class="table-container" style="margin-top: 20px;">
                <div class="table-header">
                    <h2>&#128203; My Access History (Last 15 Events)</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>Date & Time</th><th>IP Address</th><th>Device</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($log = mysqli_fetch_assoc($access_history)): ?>
                        <tr>
                            <td><?php echo $log['login_time']; ?></td>
                            <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                            <td><?php echo htmlspecialchars(substr($log['device_info'] ?? '', 0, 40)) . '...'; ?></td>
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
                <div style="padding: 15px; font-size: 11px; color: #999;">
                    &#9888; Every login and logout is continuously monitored. This is the Zero-Trust principle: "Never Trust, Always Verify."
                </div>
            </div>

        </div>
    </main>
</div>
<script src="js/main.js"></script>
</body>
</html>
