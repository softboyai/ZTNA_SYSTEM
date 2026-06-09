<?php
/**
 * Course Management
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Lecturer course management page with active courses and materials.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Course Management', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - MKU ZTNA System</title>
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
                <a href="course_management.php" class="active">
                    <span class="nav-icon">&#128218;</span> Course Management
                </a>
                <a href="student_records.php">
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
                <h1>Course Management</h1>
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

                <!-- Course Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128218;</div>
                        <h3>Active Courses</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">4</p>
                        <p>This semester</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128101;</div>
                        <h3>Total Students</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">187</p>
                        <p>Enrolled across courses</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128196;</div>
                        <h3>Materials Uploaded</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">34</p>
                        <p>Lecture notes & slides</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128221;</div>
                        <h3>Pending Assignments</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">12</p>
                        <p>Awaiting grading</p>
                    </div>
                </div>

                <!-- Active Courses Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Active Courses - Semester 2, 2026</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Course Code</th>
                                <th style="padding: 12px; text-align: left;">Course Name</th>
                                <th style="padding: 12px; text-align: left;">Students</th>
                                <th style="padding: 12px; text-align: left;">Credits</th>
                                <th style="padding: 12px; text-align: left;">Schedule</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Network Security & Cryptography</td>
                                <td style="padding: 12px;">52</td>
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">Mon/Wed 10:00-12:00</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 2105</td>
                                <td style="padding: 12px;">Data Communication</td>
                                <td style="padding: 12px;">48</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Tue/Thu 08:00-09:30</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 4301</td>
                                <td style="padding: 12px;">Advanced Cybersecurity</td>
                                <td style="padding: 12px;">38</td>
                                <td style="padding: 12px;">4</td>
                                <td style="padding: 12px;">Wed/Fri 14:00-16:00</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">ICT 1102</td>
                                <td style="padding: 12px;">Introduction to Programming</td>
                                <td style="padding: 12px;">49</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;">Mon/Thu 14:00-15:30</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Uploaded Materials -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recently Uploaded Materials</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">File Name</th>
                                <th style="padding: 12px; text-align: left;">Course</th>
                                <th style="padding: 12px; text-align: left;">Type</th>
                                <th style="padding: 12px; text-align: left;">Upload Date</th>
                                <th style="padding: 12px; text-align: left;">Downloads</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Lecture_8_Encryption.pdf</td>
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Lecture Notes</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;">45</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Assignment_3_Guidelines.pdf</td>
                                <td style="padding: 12px;">ICT 3201</td>
                                <td style="padding: 12px;">Assignment</td>
                                <td style="padding: 12px;">2026-01-26</td>
                                <td style="padding: 12px;">52</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Lab_Exercise_5.pdf</td>
                                <td style="padding: 12px;">ICT 2105</td>
                                <td style="padding: 12px;">Lab Work</td>
                                <td style="padding: 12px;">2026-01-24</td>
                                <td style="padding: 12px;">41</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Lecture_7_TCP_IP.pdf</td>
                                <td style="padding: 12px;">ICT 2105</td>
                                <td style="padding: 12px;">Lecture Notes</td>
                                <td style="padding: 12px;">2026-01-22</td>
                                <td style="padding: 12px;">47</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Midterm_Study_Guide.pdf</td>
                                <td style="padding: 12px;">ICT 4301</td>
                                <td style="padding: 12px;">Study Guide</td>
                                <td style="padding: 12px;">2026-01-20</td>
                                <td style="padding: 12px;">38</td>
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
