<?php
/**
 * Financial Management
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Staff financial management with budget stats and transactions.
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
$stmt = mysqli_prepare($conn, "INSERT INTO resource_access (user_id, resource_name, segment_name, ip_address, access_result) VALUES (?, 'Financial Management', 'Administrative Network', ?, 'allowed')");
mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Management - MKU ZTNA System</title>
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
                <a href="financial_mgmt.php" class="active">
                    <span class="nav-icon">&#128176;</span> Finance
                </a>
                <a href="hr_portal.php">
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
                <h1>Financial Management</h1>
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

                <!-- Budget Stats -->
                <div class="resource-grid">
                    <div class="resource-card">
                        <div class="resource-icon">&#128176;</div>
                        <h3>Annual Budget</h3>
                        <p style="font-size: 20px; font-weight: bold; color: #003366;">RWF 850,000,000</p>
                        <p>Fiscal Year 2026</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128200;</div>
                        <h3>Spent to Date</h3>
                        <p style="font-size: 20px; font-weight: bold; color: #003366;">RWF 320,500,000</p>
                        <p>37.7% of budget</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128178;</div>
                        <h3>Remaining</h3>
                        <p style="font-size: 20px; font-weight: bold; color: #003366;">RWF 529,500,000</p>
                        <p>62.3% available</p>
                    </div>
                    <div class="resource-card">
                        <div class="resource-icon">&#128203;</div>
                        <h3>Pending Invoices</h3>
                        <p style="font-size: 20px; font-weight: bold; color: #003366;">RWF 15,200,000</p>
                        <p>8 invoices awaiting</p>
                    </div>
                </div>

                <!-- Recent Transactions Table -->
                <h2 style="color: #003366; margin: 25px 0 15px;">Recent Transactions</h2>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th style="padding: 12px; text-align: left;">Transaction ID</th>
                                <th style="padding: 12px; text-align: left;">Description</th>
                                <th style="padding: 12px; text-align: left;">Department</th>
                                <th style="padding: 12px; text-align: left;">Amount (RWF)</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0089</td>
                                <td style="padding: 12px;">Lab Equipment Purchase - ICT Dept</td>
                                <td style="padding: 12px;">ICT</td>
                                <td style="padding: 12px;">12,500,000</td>
                                <td style="padding: 12px;">2026-01-28</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Approved</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0088</td>
                                <td style="padding: 12px;">Staff Salary Payment - January</td>
                                <td style="padding: 12px;">HR</td>
                                <td style="padding: 12px;">85,000,000</td>
                                <td style="padding: 12px;">2026-01-25</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0087</td>
                                <td style="padding: 12px;">Library Book Acquisition</td>
                                <td style="padding: 12px;">Library</td>
                                <td style="padding: 12px;">4,200,000</td>
                                <td style="padding: 12px;">2026-01-23</td>
                                <td style="padding: 12px;"><span style="background: #cce5ff; color: #004085; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Pending</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0086</td>
                                <td style="padding: 12px;">Internet & Network Infrastructure</td>
                                <td style="padding: 12px;">IT</td>
                                <td style="padding: 12px;">8,750,000</td>
                                <td style="padding: 12px;">2026-01-20</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0085</td>
                                <td style="padding: 12px;">Student Scholarship Disbursement</td>
                                <td style="padding: 12px;">Finance</td>
                                <td style="padding: 12px;">25,000,000</td>
                                <td style="padding: 12px;">2026-01-18</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">TXN-2026-0084</td>
                                <td style="padding: 12px;">Building Maintenance - Block C</td>
                                <td style="padding: 12px;">Facilities</td>
                                <td style="padding: 12px;">3,800,000</td>
                                <td style="padding: 12px;">2026-01-15</td>
                                <td style="padding: 12px;"><span style="background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px;">Completed</span></td>
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
