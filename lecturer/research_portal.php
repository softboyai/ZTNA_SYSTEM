<?php
/**
 * Research Portal - Lecturer
 * Mount Kigali University - ZTNA System
 */
session_start();
require_once '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'lecturer') {
    header("Location: ../login.php"); exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Portal - MKU ZTNA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
            <h2>Mount Kigali University</h2>
            <p>ZTNA System - Lecturer</p>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard_lecturer.php"><span class="nav-icon">&#9733;</span> Dashboard</a>
            <a href="course_management.php"><span class="nav-icon">&#128218;</span> Course Management</a>
            <a href="student_records.php"><span class="nav-icon">&#128101;</span> Student Records</a>
            <a href="online_learning.php"><span class="nav-icon">&#128187;</span> Online Learning</a>
            <a href="research_portal.php" class="active"><span class="nav-icon">&#128300;</span> Research Portal</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Research Portal</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128300; Research & Publications</h2>
                <p>Access research resources, manage publications, and collaborate with peers.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#128214;</div>
                    <h3>IEEE Digital Library</h3>
                    <p>Access IEEE journals and conference papers</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128240;</div>
                    <h3>ACM Digital Library</h3>
                    <p>Computing research and publications</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#127891;</div>
                    <h3>Google Scholar</h3>
                    <p>Search academic papers and citations</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128101;</div>
                    <h3>Collaboration Hub</h3>
                    <p>Connect with researchers across departments</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>My Publications</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Title</th><th>Journal/Conference</th><th>Year</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Zero-Trust Architecture in Higher Education</td><td>IEEE Access</td><td>2024</td><td><span class="badge badge-granted">Published</span></td></tr>
                        <tr><td>Machine Learning for Network Intrusion Detection</td><td>ACM Conference</td><td>2024</td><td><span class="badge badge-granted">Published</span></td></tr>
                        <tr><td>Blockchain-Based Identity Management</td><td>Journal of Cybersecurity</td><td>2024</td><td><span class="badge badge-denied">Under Review</span></td></tr>
                        <tr><td>IoT Security in Smart Campus Networks</td><td>Rwanda ICT Conference</td><td>2023</td><td><span class="badge badge-granted">Published</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
