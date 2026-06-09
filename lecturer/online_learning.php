<?php
/**
 * Online Learning Platform
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Lecturer online learning management - schedule classes, upload materials.
 * Access: Only users with role 'lecturer' can view this page.
 */

session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'lecturer') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// Log resource access (Zero-Trust monitoring)
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Online Learning Platform', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Platform - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Lecturer</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_lecturer.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="course_management.php">
                    <span class="nav-icon">&#128218;</span> Course Management
                </a>
                <a href="student_records.php">
                    <span class="nav-icon">&#128101;</span> Student Records
                </a>
                <a href="online_learning.php" class="active">
                    <span class="nav-icon">&#128187;</span> Online Learning
                </a>
                <a href="research_portal.php">
                    <span class="nav-icon">&#128300;</span> Research Portal
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
                <h1>Online Learning Platform</h1>
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

                <!-- Action Cards -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128197;</div>
                        <h3>Schedule Class</h3>
                        <p>Create virtual classroom sessions for your courses</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128228;</div>
                        <h3>Upload Materials</h3>
                        <p>Share lecture slides, notes, and resources with students</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#127909;</div>
                        <h3>Record Lecture</h3>
                        <p>Record and share video lectures for asynchronous learning</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128172;</div>
                        <h3>Discussion Forum</h3>
                        <p>Manage course discussion boards and Q&A sessions</p>
                    </div>
                </div>

                <!-- Upcoming Classes Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Upcoming Virtual Classes</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Course</th>
                                <th style="padding: 12px; text-align: left;">Topic</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Time</th>
                                <th style="padding: 12px; text-align: left;">Duration</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Public Key Infrastructure (PKI)</td>
                                <td style="padding: 12px;">2026-02-03</td>
                                <td style="padding: 12px;">10:00 AM</td>
                                <td style="padding: 12px;">2 hours</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Scheduled</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 2105</td>
                                <td style="padding: 12px;">Network Protocols Deep Dive</td>
                                <td style="padding: 12px;">2026-02-04</td>
                                <td style="padding: 12px;">08:00 AM</td>
                                <td style="padding: 12px;">1.5 hours</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Scheduled</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 4301</td>
                                <td style="padding: 12px;">Penetration Testing Lab</td>
                                <td style="padding: 12px;">2026-02-05</td>
                                <td style="padding: 12px;">02:00 PM</td>
                                <td style="padding: 12px;">2 hours</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Scheduled</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 1102</td>
                                <td style="padding: 12px;">Functions and Loops in Python</td>
                                <td style="padding: 12px;">2026-02-06</td>
                                <td style="padding: 12px;">02:00 PM</td>
                                <td style="padding: 12px;">1.5 hours</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Scheduled</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Digital Signatures Workshop</td>
                                <td style="padding: 12px;">2026-02-07</td>
                                <td style="padding: 12px;">10:00 AM</td>
                                <td style="padding: 12px;">2 hours</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Pending</span></td>
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
