<?php
/**
 * Academic Records
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Student academic records including GPA, grades, and semester history.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Academic Records', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Records - MKU ZTNA System</title>
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
                <a href="academic_records.php" class="active">
                    <span class="nav-icon">&#128196;</span> Academic Records
                </a>
                <a href="library.php">
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
                <h1>Academic Records</h1>
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

                <!-- GPA Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#127942;</div>
                        <h3>Current GPA</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">3.65</p>
                        <p>Out of 4.00</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128200;</div>
                        <h3>Credits Completed</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">96</p>
                        <p>Out of 140 required</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128214;</div>
                        <h3>Courses Passed</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">32</p>
                        <p>Total courses completed</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128197;</div>
                        <h3>Current Semester</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">Year 3</p>
                        <p>Semester 2, 2026</p>
                    </div>
                </div>

                <!-- Current Semester Grades -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Current Semester Grades (Year 3, Sem 2 - 2026)</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Course Code</th>
                                <th style="padding: 12px; text-align: left;">Course Name</th>
                                <th style="padding: 12px; text-align: left;">Credits</th>
                                <th style="padding: 12px; text-align: left;">Grade</th>
                                <th style="padding: 12px; text-align: left;">Points</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Network Security & Cryptography</td>
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">A</td>
                                <td style="padding: 12px;">4.00</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Passed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3202</td>
                                <td style="padding: 12px;">Cloud Computing</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">B+</td>
                                <td style="padding: 12px;">3.50</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Passed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3203</td>
                                <td style="padding: 12px;">Software Project Management</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">A-</td>
                                <td style="padding: 12px;">3.75</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Passed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3204</td>
                                <td style="padding: 12px;">Artificial Intelligence</td>
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">B</td>
                                <td style="padding: 12px;">3.00</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Passed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3205</td>
                                <td style="padding: 12px;">Database Administration</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">A</td>
                                <td style="padding: 12px;">4.00</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Passed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3206</td>
                                <td style="padding: 12px;">Final Year Project I</td>
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">-</td>
                                <td style="padding: 12px;">-</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">In Progress</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Semester History -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Semester History</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Semester</th>
                                <th style="padding: 12px; text-align: left;">Year</th>
                                <th style="padding: 12px; text-align: left;">Courses</th>
                                <th style="padding: 12px; text-align: left;">Credits</th>
                                <th style="padding: 12px; text-align: left;">GPA</th>
                                <th style="padding: 12px; text-align: left;">Standing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Year 3, Sem 1</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">6</td>
                                <td style="padding: 12px;">20</td>
                                <td style="padding: 12px;">3.72</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Dean's List</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Year 2, Sem 2</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">7</td>
                                <td style="padding: 12px;">21</td>
                                <td style="padding: 12px;">3.58</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Good Standing</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Year 2, Sem 1</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">6</td>
                                <td style="padding: 12px;">19</td>
                                <td style="padding: 12px;">3.45</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Good Standing</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Year 1, Sem 2</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">7</td>
                                <td style="padding: 12px;">21</td>
                                <td style="padding: 12px;">3.62</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Good Standing</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Year 1, Sem 1</td>
                                <td style="padding: 12px;">2026</td>
                                <td style="padding: 12px;">6</td>
                                <td style="padding: 12px;">18</td>
                                <td style="padding: 12px;">3.80</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Dean's List</span></td>
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
