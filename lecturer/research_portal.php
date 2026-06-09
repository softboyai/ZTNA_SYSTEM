<?php
/**
 * Research Portal
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Lecturer research portal with resources and publications.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Research Portal', 'Research Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Portal - MKU ZTNA System</title>
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
                <a href="online_learning.php">
                    <span class="nav-icon">&#128187;</span> Online Learning
                </a>
                <a href="research_portal.php" class="active">
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
                <h1>Research Portal</h1>
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
                        Segment: <strong>Research Network</strong> | 
                        Access: <strong>Allowed</strong> |
                        Time: <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
                    </p>
                </div>

                <!-- Research Resource Cards -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Research Databases</h3>
                        <p>Access IEEE, ACM, Springer, and Scopus databases</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128221;</div>
                        <h3>Submit Paper</h3>
                        <p>Submit research papers for peer review and publication</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128101;</div>
                        <h3>Collaboration</h3>
                        <p>Connect with research partners and co-authors</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128176;</div>
                        <h3>Grants & Funding</h3>
                        <p>Apply for research grants and track funding</p>
                    </div>
                </div>

                <!-- Publications Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">My Publications</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">#</th>
                                <th style="padding: 12px; text-align: left;">Title</th>
                                <th style="padding: 12px; text-align: left;">Journal/Conference</th>
                                <th style="padding: 12px; text-align: left;">Year</th>
                                <th style="padding: 12px; text-align: left;">Citations</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">1</td>
                                <td style="padding: 12px;">Zero-Trust Architecture for African Universities</td>
                                <td style="padding: 12px;">IEEE Africa Conference</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">12</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">2</td>
                                <td style="padding: 12px;">Machine Learning in Network Intrusion Detection</td>
                                <td style="padding: 12px;">Journal of Cybersecurity</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">8</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Published</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Blockchain-Based Identity Management in Education</td>
                                <td style="padding: 12px;">ACM Computing Surveys</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">-</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Under Review</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">IoT Security Challenges in Smart Campus Environments</td>
                                <td style="padding: 12px;">Springer LNCS</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">-</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">In Progress</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">5</td>
                                <td style="padding: 12px;">Cloud Security Best Practices for Developing Nations</td>
                                <td style="padding: 12px;">International Journal of Info Security</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">22</td>
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
