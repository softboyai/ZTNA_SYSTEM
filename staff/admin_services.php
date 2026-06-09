<?php
/**
 * Administrative Services
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Staff administrative services with pending requests and service cards.
 * Access: Only users with role 'staff' can view this page.
 */

session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// Log resource access (Zero-Trust monitoring)
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Administrative Services', 'Administrative Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Services - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Staff</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_staff.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="admin_services.php" class="active">
                    <span class="nav-icon">&#127970;</span> Admin Services
                </a>
                <a href="financial_mgmt.php">
                    <span class="nav-icon">&#128176;</span> Finance
                </a>
                <a href="hr_portal.php">
                    <span class="nav-icon">&#128188;</span> HR Portal
                </a>
                <a href="communication.php">
                    <span class="nav-icon">&#128172;</span> Communication
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
                <h1>Administrative Services</h1>
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
                        Segment: <strong>Administrative Network</strong> | 
                        Access: <strong>Allowed</strong> |
                        Time: <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
                    </p>
                </div>

                <!-- Pending Requests Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128203;</div>
                        <h3>Pending Requests</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">18</p>
                        <p>Awaiting processing</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#9989;</div>
                        <h3>Approved Today</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">7</p>
                        <p>Requests approved</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128337;</div>
                        <h3>In Progress</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">5</p>
                        <p>Being processed</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128200;</div>
                        <h3>Completed This Week</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">42</p>
                        <p>Successfully resolved</p>
                    </div>
                </div>

                <!-- Service Cards -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Available Services</h2>
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128196;</div>
                        <h3>Document Processing</h3>
                        <p>Process student transcripts, certificates, and letters</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#127979;</div>
                        <h3>Room Allocation</h3>
                        <p>Manage classroom and office space assignments</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128736;</div>
                        <h3>Facility Maintenance</h3>
                        <p>Submit and track maintenance requests</p>
                    </div>
                </div>

                <!-- Recent Requests Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recent Service Requests</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Request ID</th>
                                <th style="padding: 12px; text-align: left;">Requester</th>
                                <th style="padding: 12px; text-align: left;">Service Type</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Priority</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">REQ-2026-0145</td>
                                <td style="padding: 12px;">Ingabire Gisele</td>
                                <td style="padding: 12px;">Transcript Request</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Medium</span></td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Pending</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">REQ-2026-0144</td>
                                <td style="padding: 12px;">Dr. Mugisha Jean</td>
                                <td style="padding: 12px;">Room Change Request</td>
                                <td style="padding: 12px;">2026-01-27</td>
                                <td style="padding: 12px;"><span style="background: #f8d7da; color: #721c24; padding: 3px 10px; border-radius: 12px; font-size: 12px;">High</span></td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">In Progress</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">REQ-2026-0143</td>
                                <td style="padding: 12px;">Mugabo Patrick</td>
                                <td style="padding: 12px;">Letter of Enrollment</td>
                                <td style="padding: 12px;">2026-01-26</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Low</span></td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">REQ-2026-0142</td>
                                <td style="padding: 12px;">Uwase Diane</td>
                                <td style="padding: 12px;">Exam Deferral</td>
                                <td style="padding: 12px;">2026-01-25</td>
                                <td style="padding: 12px;"><span style="background: #f8d7da; color: #721c24; padding: 3px 10px; border-radius: 12px; font-size: 12px;">High</span></td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">REQ-2026-0141</td>
                                <td style="padding: 12px;">Prof. Uwimana Claire</td>
                                <td style="padding: 12px;">Equipment Request</td>
                                <td style="padding: 12px;">2026-01-24</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Medium</span></td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Pending</span></td>
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
