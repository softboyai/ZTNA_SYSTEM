<?php
/**
 * Student Records
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Lecturer view of student grades and records by course.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Student Records', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - MKU ZTNA System</title>
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
                <a href="student_records.php" class="active">
                    <span class="nav-icon">&#128101;</span> Student Records
                </a>
                <a href="online_learning.php">
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
                <h1>Student Records</h1>
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

                <!-- ICT 3201 - Network Security & Cryptography -->
                <h2 style="color: #003366; margin: 25px 0 15px;">ICT 3201 - Network Security & Cryptography</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Student ID</th>
                                <th style="padding: 12px; text-align: left;">Student Name</th>
                                <th style="padding: 12px; text-align: left;">Assignment 1</th>
                                <th style="padding: 12px; text-align: left;">Assignment 2</th>
                                <th style="padding: 12px; text-align: left;">Midterm</th>
                                <th style="padding: 12px; text-align: left;">Total</th>
                                <th style="padding: 12px; text-align: left;">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2024/36790</td>
                                <td style="padding: 12px;">Ingabire Gisele</td>
                                <td style="padding: 12px;">18/20</td>
                                <td style="padding: 12px;">17/20</td>
                                <td style="padding: 12px;">42/50</td>
                                <td style="padding: 12px;">77/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">A</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2024/36801</td>
                                <td style="padding: 12px;">Mugabo Patrick</td>
                                <td style="padding: 12px;">16/20</td>
                                <td style="padding: 12px;">15/20</td>
                                <td style="padding: 12px;">38/50</td>
                                <td style="padding: 12px;">69/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">B+</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2024/36815</td>
                                <td style="padding: 12px;">Uwase Diane</td>
                                <td style="padding: 12px;">19/20</td>
                                <td style="padding: 12px;">18/20</td>
                                <td style="padding: 12px;">45/50</td>
                                <td style="padding: 12px;">82/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">A</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2024/36823</td>
                                <td style="padding: 12px;">Habimana Eric</td>
                                <td style="padding: 12px;">14/20</td>
                                <td style="padding: 12px;">13/20</td>
                                <td style="padding: 12px;">30/50</td>
                                <td style="padding: 12px;">57/90</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">B-</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2024/36840</td>
                                <td style="padding: 12px;">Mukamana Grace</td>
                                <td style="padding: 12px;">17/20</td>
                                <td style="padding: 12px;">16/20</td>
                                <td style="padding: 12px;">40/50</td>
                                <td style="padding: 12px;">73/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">A-</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ICT 2105 - Data Communication -->
                <h2 style="color: #003366; margin: 25px 0 15px;">ICT 2105 - Data Communication</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Student ID</th>
                                <th style="padding: 12px; text-align: left;">Student Name</th>
                                <th style="padding: 12px; text-align: left;">Assignment 1</th>
                                <th style="padding: 12px; text-align: left;">Assignment 2</th>
                                <th style="padding: 12px; text-align: left;">Midterm</th>
                                <th style="padding: 12px; text-align: left;">Total</th>
                                <th style="padding: 12px; text-align: left;">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2025/37001</td>
                                <td style="padding: 12px;">Niyonzima Claude</td>
                                <td style="padding: 12px;">15/20</td>
                                <td style="padding: 12px;">16/20</td>
                                <td style="padding: 12px;">36/50</td>
                                <td style="padding: 12px;">67/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">B</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2025/37015</td>
                                <td style="padding: 12px;">Ishimwe Alice</td>
                                <td style="padding: 12px;">18/20</td>
                                <td style="padding: 12px;">19/20</td>
                                <td style="padding: 12px;">44/50</td>
                                <td style="padding: 12px;">81/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">A</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2025/37028</td>
                                <td style="padding: 12px;">Tuyisenge Jean</td>
                                <td style="padding: 12px;">12/20</td>
                                <td style="padding: 12px;">14/20</td>
                                <td style="padding: 12px;">28/50</td>
                                <td style="padding: 12px;">54/90</td>
                                <td style="padding: 12px;"><span style="background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 12px; font-size: 12px;">C+</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2025/37042</td>
                                <td style="padding: 12px;">Umutoni Sandrine</td>
                                <td style="padding: 12px;">17/20</td>
                                <td style="padding: 12px;">16/20</td>
                                <td style="padding: 12px;">39/50</td>
                                <td style="padding: 12px;">72/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">A-</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">BBICTR/2025/37056</td>
                                <td style="padding: 12px;">Bizimana Felix</td>
                                <td style="padding: 12px;">16/20</td>
                                <td style="padding: 12px;">15/20</td>
                                <td style="padding: 12px;">35/50</td>
                                <td style="padding: 12px;">66/90</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">B</span></td>
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
