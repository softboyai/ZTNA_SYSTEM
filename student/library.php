<?php
/**
 * Library Services
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Digital library page for students with e-books, journals, past papers.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Library Services', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Services - MKU ZTNA System</title>
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
                <a href="library.php" class="active">
                    <span class="nav-icon">&#128214;</span> Library
                </a>
                <a href="email_comm.php">
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
                <h1>Library Services</h1>
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

                <!-- Library Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>E-Books</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">2,450+</p>
                        <p>Digital books available</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128240;</div>
                        <h3>Journals</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">580+</p>
                        <p>Academic journals</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128196;</div>
                        <h3>Past Papers</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">1,200+</p>
                        <p>Exam papers archived</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128214;</div>
                        <h3>Borrowed</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">3</p>
                        <p>Books currently borrowed</p>
                    </div>
                </div>

                <!-- Borrowed Books Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Currently Borrowed Books</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">#</th>
                                <th style="padding: 12px; text-align: left;">Book Title</th>
                                <th style="padding: 12px; text-align: left;">Author</th>
                                <th style="padding: 12px; text-align: left;">Borrowed Date</th>
                                <th style="padding: 12px; text-align: left;">Due Date</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">1</td>
                                <td style="padding: 12px;">Network Security Fundamentals</td>
                                <td style="padding: 12px;">James Kurose</td>
                                <td style="padding: 12px;">2026-01-10</td>
                                <td style="padding: 12px;">2026-02-10</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Due Soon</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">2</td>
                                <td style="padding: 12px;">Zero Trust Architecture</td>
                                <td style="padding: 12px;">Evan Gilman</td>
                                <td style="padding: 12px;">2026-01-15</td>
                                <td style="padding: 12px;">2026-02-15</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Database Systems Concepts</td>
                                <td style="padding: 12px;">Abraham Silberschatz</td>
                                <td style="padding: 12px;">2026-01-20</td>
                                <td style="padding: 12px;">2026-02-20</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Popular E-Books Section -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Popular E-Books</h2>
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Cybersecurity Essentials</h3>
                        <p>Author: Charles Brooks</p>
                        <p style="color: #28a745; font-size: 12px;">&#10003; Available Online</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Computer Networking</h3>
                        <p>Author: Andrew Tanenbaum</p>
                        <p style="color: #28a745; font-size: 12px;">&#10003; Available Online</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Software Engineering</h3>
                        <p>Author: Ian Sommerville</p>
                        <p style="color: #28a745; font-size: 12px;">&#10003; Available Online</p>
                    </div>
                </div>

                <!-- Recent Journals -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recent Academic Journals</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Journal Title</th>
                                <th style="padding: 12px; text-align: left;">Publisher</th>
                                <th style="padding: 12px; text-align: left;">Year</th>
                                <th style="padding: 12px; text-align: left;">Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">IEEE Transactions on Network Security</td>
                                <td style="padding: 12px;">IEEE</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Open Access</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Journal of Computer Science Education</td>
                                <td style="padding: 12px;">ACM</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Open Access</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">African Journal of Information Systems</td>
                                <td style="padding: 12px;">AJIS</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Subscribed</span></td>
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
