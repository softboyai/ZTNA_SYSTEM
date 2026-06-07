<?php
/**
 * PDF Report Generator
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Generates a printable/downloadable PDF report with:
 * - System overview stats
 * - Users by role
 * - Login activity
 * - Failed attempts
 * - Access logs summary
 * 
 * Uses browser print-to-PDF (no external libraries needed).
 * User can view on screen, print, or save as PDF.
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

// Get report type from URL
$report_type = $_GET['type'] ?? 'full';

// ===== GATHER ALL REPORT DATA =====

// Total users
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($result)['total'];

// Users by role
$users_by_role = mysqli_query($conn, "SELECT user_role, COUNT(*) as count FROM users GROUP BY user_role ORDER BY count DESC");

// Users by status
$result = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM users GROUP BY status");
$users_by_status = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users_by_status[$row['status']] = $row['count'];
}

// Total logins
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'granted'");
$total_logins = mysqli_fetch_assoc($result)['total'];

// Total failed attempts
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'denied'");
$total_denied = mysqli_fetch_assoc($result)['total'];

// Today's activity
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE DATE(login_time) = CURDATE()");
$today_total = mysqli_fetch_assoc($result)['total'];

// Login activity last 7 days
$login_activity = mysqli_query($conn, "SELECT DATE(login_time) as login_date, COUNT(*) as total_logins, SUM(CASE WHEN access_status = 'granted' THEN 1 ELSE 0 END) as granted, SUM(CASE WHEN access_status = 'denied' THEN 1 ELSE 0 END) as denied FROM access_logs WHERE login_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(login_time) ORDER BY login_date DESC");

// Recent access logs (last 20)
$recent_logs = mysqli_query($conn, "SELECT al.*, u.username FROM access_logs al LEFT JOIN users u ON al.user_id = u.user_id ORDER BY al.login_time DESC LIMIT 20");

// Active security policies
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM security_policies WHERE is_active = 1");
$active_policies = mysqli_fetch_assoc($result)['total'];

// Network segments
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM network_segments");
$total_segments = mysqli_fetch_assoc($result)['total'];

// All users list
$all_users = mysqli_query($conn, "SELECT user_id, username, email, user_role, status, created_at FROM users ORDER BY created_at DESC");

// Current date/time for report header
$report_date = date('F d, Y - h:i A');
$report_id = 'RPT-' . date('Ymd-His');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Report - MKU ZTNA System</title>
    <style>
        /* ===== PDF REPORT STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            background: #fff;
            padding: 0;
        }

        /* Print button bar - hidden when printing */
        .action-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #003366;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .action-bar h3 {
            color: #fff;
            font-size: 14px;
        }

        .action-bar .btn-group {
            display: flex;
            gap: 10px;
        }

        .action-bar .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-bar .btn-print {
            background: #fff;
            color: #003366;
        }

        .action-bar .btn-back {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        .action-bar .btn:hover {
            opacity: 0.9;
        }

        /* Report Container */
        .report-container {
            max-width: 900px;
            margin: 80px auto 40px;
            padding: 40px;
            background: #fff;
        }

        /* Report Header */
        .report-header {
            text-align: center;
            border-bottom: 3px solid #003366;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }

        .report-header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .report-header h1 {
            color: #003366;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .report-header h2 {
            color: #003366;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .report-header .report-meta {
            font-size: 12px;
            color: #666;
        }

        .report-header .report-meta span {
            margin: 0 10px;
        }

        /* Section Titles */
        .section-title {
            background: #003366;
            color: #fff;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 600;
            margin: 25px 0 15px;
            border-radius: 4px;
        }

        /* Stats Row */
        .stats-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            min-width: 150px;
            border: 2px solid #003366;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .stat-box .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #003366;
        }

        .stat-box .stat-label {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }

        /* Tables */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .report-table th {
            background: #f0f4f8;
            color: #003366;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #003366;
        }

        .report-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }

        .report-table tr:nth-child(even) {
            background: #fafafa;
        }

        .report-table .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-granted, .badge-active { background: #d4edda; color: #155724; }
        .badge-denied, .badge-suspended { background: #f8d7da; color: #721c24; }

        /* Report Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #003366;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .report-footer p {
            margin-bottom: 3px;
        }

        /* Confidential watermark */
        .confidential {
            text-align: center;
            color: #cc0000;
            font-size: 11px;
            font-weight: 600;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .action-bar {
                display: none !important;
            }

            .report-container {
                margin: 0;
                padding: 20px;
                max-width: 100%;
            }

            body {
                background: #fff;
            }

            .section-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .stat-box {
                border: 2px solid #003366 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (hidden when printing) -->
    <div class="action-bar">
        <h3>&#128196; ZTNA System Report Preview</h3>
        <div class="btn-group">
            <a href="reports.php" class="btn btn-back">&#8592; Back to Reports</a>
            <button onclick="window.print()" class="btn btn-print">&#128424; Print / Save as PDF</button>
        </div>
    </div>

    <!-- Report Content -->
    <div class="report-container">

        <!-- Report Header -->
        <div class="report-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo">
            <h1>Mount Kigali University</h1>
            <h2>Zero-Trust Network Access System &mdash; System Report</h2>
            <div class="report-meta">
                <span><strong>Report ID:</strong> <?php echo $report_id; ?></span>
                <span><strong>Generated:</strong> <?php echo $report_date; ?></span>
                <span><strong>Generated By:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </div>

        <!-- SECTION: System Overview -->
        <div class="section-title">1. System Overview</div>
        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_logins; ?></div>
                <div class="stat-label">Successful Logins</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_denied; ?></div>
                <div class="stat-label">Denied Attempts</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $active_policies; ?></div>
                <div class="stat-label">Active Policies</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_segments; ?></div>
                <div class="stat-label">Network Segments</div>
            </div>
        </div>

        <!-- SECTION: Users by Role -->
        <div class="section-title">2. Users by Role</div>
        <table class="report-table">
            <thead>
                <tr><th>Role</th><th>Number of Users</th><th>Percentage</th></tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($users_by_role, 0);
                while ($row = mysqli_fetch_assoc($users_by_role)): 
                    $percentage = ($total_users > 0) ? round(($row['count'] / $total_users) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?php echo ucfirst($row['user_role']); ?></td>
                    <td><?php echo $row['count']; ?></td>
                    <td><?php echo $percentage; ?>%</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- SECTION: User Status -->
        <div class="section-title">3. User Account Status</div>
        <table class="report-table">
            <thead>
                <tr><th>Status</th><th>Count</th></tr>
            </thead>
            <tbody>
                <tr><td><span class="badge badge-active">Active</span></td><td><?php echo $users_by_status['active'] ?? 0; ?></td></tr>
                <tr><td><span class="badge badge-suspended">Suspended</span></td><td><?php echo $users_by_status['suspended'] ?? 0; ?></td></tr>
            </tbody>
        </table>

        <!-- SECTION: Login Activity (Last 7 Days) -->
        <div class="section-title">4. Login Activity (Last 7 Days)</div>
        <table class="report-table">
            <thead>
                <tr><th>Date</th><th>Total Attempts</th><th>Granted</th><th>Denied</th><th>Success Rate</th></tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($login_activity, 0);
                while ($row = mysqli_fetch_assoc($login_activity)): 
                    $success_rate = ($row['total_logins'] > 0) ? round(($row['granted'] / $row['total_logins']) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?php echo $row['login_date']; ?></td>
                    <td><?php echo $row['total_logins']; ?></td>
                    <td><?php echo $row['granted']; ?></td>
                    <td><?php echo $row['denied']; ?></td>
                    <td><?php echo $success_rate; ?>%</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- SECTION: All Registered Users -->
        <div class="section-title">5. Registered Users List</div>
        <table class="report-table">
            <thead>
                <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Registered</th></tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($all_users)): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo ucfirst($user['user_role']); ?></td>
                    <td><span class="badge badge-<?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span></td>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- SECTION: Recent Access Logs -->
        <div class="section-title">6. Recent Access Logs (Last 20 Entries)</div>
        <table class="report-table">
            <thead>
                <tr><th>Log ID</th><th>User</th><th>Time</th><th>IP Address</th><th>Status</th><th>Resource</th></tr>
            </thead>
            <tbody>
                <?php while ($log = mysqli_fetch_assoc($recent_logs)): ?>
                <tr>
                    <td><?php echo $log['log_id']; ?></td>
                    <td><?php echo htmlspecialchars($log['username'] ?? 'Unknown'); ?></td>
                    <td><?php echo $log['login_time']; ?></td>
                    <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                    <td><span class="badge badge-<?php echo $log['access_status']; ?>"><?php echo ucfirst($log['access_status']); ?></span></td>
                    <td><?php echo htmlspecialchars($log['resource_accessed'] ?? '-'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Report Footer -->
        <div class="report-footer">
            <p><strong>Mount Kigali University &mdash; Zero-Trust Network Access System</strong></p>
            <p>Report generated on <?php echo $report_date; ?> by <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p>This report is auto-generated from live system data.</p>
            <div class="confidential">&#9888; CONFIDENTIAL &mdash; FOR AUTHORIZED PERSONNEL ONLY</div>
        </div>

    </div>

</body>
</html>
