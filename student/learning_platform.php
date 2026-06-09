<?php
/**
 * Online Learning Platform - Student
 * Mount Kigali University - ZTNA System
 * 
 * DYNAMIC PAGE - Demonstrates Zero-Trust in action:
 * - Verifies session and role before access
 * - Logs this resource access in the database
 * - Shows which network segment this belongs to
 * - Displays real user data from database
 */
session_start();
require_once '../db_connect.php';

// ZERO-TRUST: Verify identity and role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    // LOG the unauthorized attempt
    $ip = $_SERVER['REMOTE_ADDR'];
    $device = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $blocked_user = $_SESSION['user_id'] ?? 0;
    $stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (?, ?, ?, 'denied', 'learning_platform - unauthorized role')");
    mysqli_stmt_bind_param($stmt, "iss", $blocked_user, $ip, $device);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// LOG this resource access (proves monitoring is active)
$ip_address = $_SERVER['REMOTE_ADDR'];
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Online Learning Platform', 'Academic Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Get user's access count to this resource
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as visits FROM resource_access WHERE user_id = ? AND resource_name = 'Online Learning Platform'");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$visits = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['visits'];
mysqli_stmt_close($stmt);

// Get last access time
$stmt = mysqli_prepare($conn, "SELECT access_time FROM resource_access WHERE user_id = ? AND resource_name = 'Online Learning Platform' ORDER BY access_time DESC LIMIT 1 OFFSET 1");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$last_visit_row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$last_visit = $last_visit_row ? $last_visit_row['access_time'] : 'First visit';
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Platform - MKU ZTNA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - Student</p>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard_student.php"><span class="nav-icon">&#9733;</span> Dashboard</a>
            <a href="learning_platform.php" class="active"><span class="nav-icon">&#128218;</span> Learning Platform</a>
            <a href="academic_records.php"><span class="nav-icon">&#128196;</span> Academic Records</a>
            <a href="library.php"><span class="nav-icon">&#128214;</span> Library</a>
            <a href="email_comm.php"><span class="nav-icon">&#9993;</span> Email</a>
            <a href="../my_profile.php"><span class="nav-icon">&#128100;</span> My Security</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Online Learning Platform</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">

            <!-- ZTNA Verification Banner - Shows the system is working -->
            <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px;">
                <p style="color: #155724; font-size: 13px; margin: 0;">
                    <strong>&#128274; ZTNA Verification Passed:</strong> 
                    Your identity was verified. Role: <strong>Student</strong> | 
                    IP: <strong><?php echo htmlspecialchars($ip_address); ?></strong> | 
                    Segment: <strong>Academic Network</strong> | 
                    Visit #<?php echo $visits; ?> | 
                    Last visit: <?php echo $last_visit; ?>
                </p>
            </div>

            <div class="welcome-section">
                <h2>&#128218; My Courses</h2>
                <p>Welcome, <?php echo htmlspecialchars($username); ?>. Access your enrolled courses, watch lectures, and submit assignments.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#128187;</div>
                    <h3>Introduction to Networking</h3>
                    <p>Instructor: Dr. Mugisha<br>Progress: 75%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #003366; width: 75%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128274;</div>
                    <h3>Cybersecurity Fundamentals</h3>
                    <p>Instructor: Prof. Uwimana<br>Progress: 50%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #003366; width: 50%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128190;</div>
                    <h3>Database Management</h3>
                    <p>Instructor: Mr. Habimana<br>Progress: 90%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #28a745; width: 90%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#127760;</div>
                    <h3>Web Development</h3>
                    <p>Instructor: Ms. Ingabire<br>Progress: 30%</p>
                    <div style="background: #e0e0e0; border-radius: 10px; height: 8px; margin-top: 10px;">
                        <div style="background: #fd7e14; width: 30%; height: 8px; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 25px; border-left: 4px solid #003366;">
                <p style="font-size: 12px; color: #555; margin: 0;">
                    <strong>&#128274; Zero-Trust Notice:</strong> This access has been logged. 
                    Your activity on this platform is continuously monitored. 
                    You can view your full access history on the <a href="../my_profile.php" style="color: #003366; font-weight: 600;">My Security</a> page.
                </p>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
