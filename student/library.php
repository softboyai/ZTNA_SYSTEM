<?php
/**
 * Library Services - Student
 * Mount Kigali University - ZTNA System
 */
session_start();
require_once '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php"); exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Services - MKU ZTNA</title>
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
            <a href="learning_platform.php"><span class="nav-icon">&#128218;</span> Learning Platform</a>
            <a href="academic_records.php"><span class="nav-icon">&#128196;</span> Academic Records</a>
            <a href="library.php" class="active"><span class="nav-icon">&#128214;</span> Library</a>
            <a href="email_comm.php"><span class="nav-icon">&#9993;</span> Email</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Library Services</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128214; Digital Library</h2>
                <p>Access e-books, journals, research papers, and digital resources.</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">&#128214;</div>
                    <h3>E-Books Collection</h3>
                    <p>Access over 5,000 digital textbooks across all disciplines</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128240;</div>
                    <h3>Academic Journals</h3>
                    <p>Browse peer-reviewed journals and research publications</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128196;</div>
                    <h3>Past Exam Papers</h3>
                    <p>Download previous examination papers for revision</p>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">&#128421;</div>
                    <h3>Online Databases</h3>
                    <p>Access IEEE, ACM, and other academic databases</p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>My Borrowed Books</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Book Title</th><th>Author</th><th>Borrowed</th><th>Due Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Computer Networks (6th Ed)</td><td>Andrew Tanenbaum</td><td>2024-11-20</td><td>2024-12-20</td><td><span class="badge badge-granted">Active</span></td></tr>
                        <tr><td>Database Systems Concepts</td><td>Silberschatz et al.</td><td>2024-11-15</td><td>2024-12-15</td><td><span class="badge badge-denied">Overdue</span></td></tr>
                        <tr><td>Introduction to Algorithms</td><td>Cormen et al.</td><td>2024-10-10</td><td>2024-11-10</td><td><span class="badge badge-active">Returned</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
