<?php
/**
 * Email & Communication
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Student email and communication services.
 * Access: Only users with role 'student' can view this page.
 */

session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// Log resource access (Zero-Trust monitoring)
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Email & Communication', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email & Communication - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Student</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_student.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="academic_records.php">
                    <span class="nav-icon">&#128196;</span> Academic Records
                </a>
                <a href="library.php">
                    <span class="nav-icon">&#128214;</span> Library
                </a>
                <a href="email_comm.php" class="active">
                    <span class="nav-icon">&#9993;</span> Email
                </a>
                <a href="../my_profile.php">
                    <span class="nav-icon">&#128100;</span> My Security
                </a>
                <div class="nav-divider"></div>
                <a href="../logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>Email & Communication</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- ZTNA Verification Banner -->
                <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px;">
                    <p style="color: #155724; font-size: 13px; margin: 0;">
                        <strong>&#128274; ZTNA Verification Passed:</strong> 
                        Identity verified. Role: <strong><?php echo ucfirst($_SESSION['user_role']); ?></strong> | 
                        IP: <strong><?php echo $_SERVER['REMOTE_ADDR']; ?></strong> | 
                        Segment: <strong>Academic Network</strong> | 
                        Access: <strong>Allowed</strong> |
                        Time: <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
                    </p>
                </div>

                <!-- Email Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128233;</div>
                        <h3>Inbox</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">12</p>
                        <p>Unread messages</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128228;</div>
                        <h3>Sent</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">28</p>
                        <p>Messages sent</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128227;</div>
                        <h3>Announcements</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">5</p>
                        <p>New announcements</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128101;</div>
                        <h3>Groups</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">4</p>
                        <p>Active group chats</p>
                    </div>
                </div>

                <!-- Inbox Messages Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Inbox Messages</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">#</th>
                                <th style="padding: 12px; text-align: left;">From</th>
                                <th style="padding: 12px; text-align: left;">Subject</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">1</td>
                                <td style="padding: 12px;">Dr. Mugisha Jean</td>
                                <td style="padding: 12px;">Assignment 3 Submission Deadline Extended</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">New</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">2</td>
                                <td style="padding: 12px;">Academic Office</td>
                                <td style="padding: 12px;">Semester 2 Registration Open</td>
                                <td style="padding: 12px;">2026-01-25</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">New</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Library Services</td>
                                <td style="padding: 12px;">Book Return Reminder - Due Feb 10</td>
                                <td style="padding: 12px;">2026-01-22</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Unread</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">IT Department</td>
                                <td style="padding: 12px;">ZTNA System Security Update Notice</td>
                                <td style="padding: 12px;">2026-01-20</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Read</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">5</td>
                                <td style="padding: 12px;">Student Affairs</td>
                                <td style="padding: 12px;">Campus Event: Tech Innovation Week 2026</td>
                                <td style="padding: 12px;">2026-01-18</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Read</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">6</td>
                                <td style="padding: 12px;">Prof. Uwimana Claire</td>
                                <td style="padding: 12px;">Research Group Meeting - Friday 2PM</td>
                                <td style="padding: 12px;">2026-01-15</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Read</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
