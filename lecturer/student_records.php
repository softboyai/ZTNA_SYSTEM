<?php
/**
 * Student Records - Lecturer
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
    <title>Student Records - MKU ZTNA</title>
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
            <a href="student_records.php" class="active"><span class="nav-icon">&#128101;</span> Student Records</a>
            <a href="online_learning.php"><span class="nav-icon">&#128187;</span> Online Learning</a>
            <a href="research_portal.php"><span class="nav-icon">&#128300;</span> Research Portal</a>
            <div class="nav-divider"></div>
            <a href="../logout.php"><span class="nav-icon">&#128682;</span> Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
            <h1>Student Records</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($username); ?></span>
                <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            </div>
        </header>
        <div class="content-area">
            <div class="welcome-section">
                <h2>&#128101; Student Grades & Attendance</h2>
                <p>View and manage student performance across your courses.</p>
            </div>

            <div class="table-container">
                <div class="table-header"><h2>ICT 301 - Introduction to Networking (45 Students)</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Student Name</th><th>Reg No.</th><th>Attendance</th><th>Assignment</th><th>Midterm</th><th>Overall</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Uwimana Jean</td><td>BCS/2022/001</td><td>92%</td><td>85</td><td>78</td><td><span class="badge badge-granted">B+</span></td></tr>
                        <tr><td>Mugisha Alice</td><td>BCS/2022/002</td><td>88%</td><td>90</td><td>82</td><td><span class="badge badge-granted">A-</span></td></tr>
                        <tr><td>Habimana Pierre</td><td>BCS/2022/003</td><td>75%</td><td>70</td><td>65</td><td><span class="badge badge-active">C+</span></td></tr>
                        <tr><td>Ingabire Marie</td><td>BCS/2022/004</td><td>95%</td><td>95</td><td>88</td><td><span class="badge badge-granted">A</span></td></tr>
                        <tr><td>Niyonzima Eric</td><td>BCS/2022/005</td><td>60%</td><td>55</td><td>50</td><td><span class="badge badge-denied">D</span></td></tr>
                        <tr><td>Mukamana Grace</td><td>BCS/2022/006</td><td>85%</td><td>80</td><td>75</td><td><span class="badge badge-granted">B</span></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="margin-top: 30px;">
                <div class="table-header"><h2>ICT 305 - Cybersecurity Fundamentals (38 Students)</h2></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Student Name</th><th>Reg No.</th><th>Attendance</th><th>Assignment</th><th>Midterm</th><th>Overall</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Kamali David</td><td>BCS/2022/010</td><td>90%</td><td>88</td><td>80</td><td><span class="badge badge-granted">A-</span></td></tr>
                        <tr><td>Ishimwe Sarah</td><td>BCS/2022/011</td><td>82%</td><td>75</td><td>72</td><td><span class="badge badge-granted">B</span></td></tr>
                        <tr><td>Tuyishime Paul</td><td>BCS/2022/012</td><td>78%</td><td>80</td><td>68</td><td><span class="badge badge-active">B-</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
