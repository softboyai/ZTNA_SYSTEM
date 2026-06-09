<?php
/**
 * HR Portal
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Staff HR portal with employee stats, services, and leave history.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'HR Portal', 'Administrative Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Portal - MKU ZTNA System</title>
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
                <a href="hr_portal.php" class="active">
                    <span class="nav-icon">&#128188;</span> HR Portal
                </a>
                <a href="communication.php">
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
                <h1>HR Portal</h1>
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

                <!-- Employee Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128101;</div>
                        <h3>Total Employees</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">156</p>
                        <p>University staff</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128100;</div>
                        <h3>Lecturers</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">78</p>
                        <p>Academic staff</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#127970;</div>
                        <h3>Admin Staff</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">52</p>
                        <p>Support staff</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128197;</div>
                        <h3>On Leave Today</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #003366;">6</p>
                        <p>Employees absent</p>
                    </div>
                </div>

                <!-- HR Service Cards -->
                <h2 style="color: #003366; margin: 25px 0 15px;">HR Services</h2>
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128197;</div>
                        <h3>Leave Management</h3>
                        <p>Apply for leave, track balances, and approve requests</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128176;</div>
                        <h3>Payroll</h3>
                        <p>View pay slips, deductions, and salary history</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128203;</div>
                        <h3>Performance Review</h3>
                        <p>Annual evaluations and goal tracking</p>
                    </div>
                </div>

                <!-- Leave History Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recent Leave History</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Employee</th>
                                <th style="padding: 12px; text-align: left;">Department</th>
                                <th style="padding: 12px; text-align: left;">Leave Type</th>
                                <th style="padding: 12px; text-align: left;">From</th>
                                <th style="padding: 12px; text-align: left;">To</th>
                                <th style="padding: 12px; text-align: left;">Days</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Mugisha Jean</td>
                                <td style="padding: 12px;">ICT Department</td>
                                <td style="padding: 12px;">Annual Leave</td>
                                <td style="padding: 12px;">2026-02-01</td>
                                <td style="padding: 12px;">2026-02-05</td>
                                <td style="padding: 12px;">5</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Approved</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Uwimana Claire</td>
                                <td style="padding: 12px;">ICT Department</td>
                                <td style="padding: 12px;">Conference</td>
                                <td style="padding: 12px;">2026-02-10</td>
                                <td style="padding: 12px;">2026-02-12</td>
                                <td style="padding: 12px;">3</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Approved</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Habimana Eric</td>
                                <td style="padding: 12px;">Administration</td>
                                <td style="padding: 12px;">Sick Leave</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;">2026-01-29</td>
                                <td style="padding: 12px;">2</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Approved</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Niyonzima Claude</td>
                                <td style="padding: 12px;">Finance</td>
                                <td style="padding: 12px;">Annual Leave</td>
                                <td style="padding: 12px;">2026-02-15</td>
                                <td style="padding: 12px;">2026-02-21</td>
                                <td style="padding: 12px;">5</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Pending</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">Mukamana Grace</td>
                                <td style="padding: 12px;">Library</td>
                                <td style="padding: 12px;">Personal Leave</td>
                                <td style="padding: 12px;">2026-01-30</td>
                                <td style="padding: 12px;">2026-01-30</td>
                                <td style="padding: 12px;">1</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Approved</span></td>
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
