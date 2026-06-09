<?php
/**
 * Communication Tools
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Staff communication tools with messaging and announcements.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Communication Tools', 'Administrative Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Tools - MKU ZTNA System</title>
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
                <a href="admin_services.php">
                    <span class="nav-icon">&#127970;</span> Admin Services
                </a>
                <a href="financial_mgmt.php">
                    <span class="nav-icon">&#128176;</span> Finance
                </a>
                <a href="hr_portal.php">
                    <span class="nav-icon">&#128188;</span> HR Portal
                </a>
                <a href="communication.php" class="active">
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
                <h1>Communication Tools</h1>
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

                <!-- Communication Cards -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128233;</div>
                        <h3>Internal Email</h3>
                        <p>Send and receive university internal communications</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128227;</div>
                        <h3>Announcements</h3>
                        <p>Post and manage university-wide announcements</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128172;</div>
                        <h3>Team Chat</h3>
                        <p>Real-time messaging with department colleagues</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128197;</div>
                        <h3>Meeting Scheduler</h3>
                        <p>Schedule and manage meetings across departments</p>
                    </div>
                </div>

                <!-- Announcements Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recent Announcements</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">#</th>
                                <th style="padding: 12px; text-align: left;">Title</th>
                                <th style="padding: 12px; text-align: left;">Author</th>
                                <th style="padding: 12px; text-align: left;">Target Audience</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">1</td>
                                <td style="padding: 12px;">Semester 2 Registration Deadline</td>
                                <td style="padding: 12px;">Academic Office</td>
                                <td style="padding: 12px;">All Students</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">2</td>
                                <td style="padding: 12px;">ZTNA Security System Upgrade Notice</td>
                                <td style="padding: 12px;">IT Department</td>
                                <td style="padding: 12px;">All Staff</td>
                                <td style="padding: 12px;">2026-01-26</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Staff Training: Cybersecurity Awareness</td>
                                <td style="padding: 12px;">HR Department</td>
                                <td style="padding: 12px;">All Staff</td>
                                <td style="padding: 12px;">2026-01-24</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">Campus Wi-Fi Network Maintenance</td>
                                <td style="padding: 12px;">IT Department</td>
                                <td style="padding: 12px;">All University</td>
                                <td style="padding: 12px;">2026-01-22</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">5</td>
                                <td style="padding: 12px;">Annual Staff Retreat - Save the Date</td>
                                <td style="padding: 12px;">Vice Chancellor Office</td>
                                <td style="padding: 12px;">All Staff</td>
                                <td style="padding: 12px;">2026-01-20</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Draft</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">6</td>
                                <td style="padding: 12px;">New Research Grant Opportunities</td>
                                <td style="padding: 12px;">Research Office</td>
                                <td style="padding: 12px;">Lecturers</td>
                                <td style="padding: 12px;">2026-01-18</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
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
