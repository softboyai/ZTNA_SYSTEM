<?php
/**
 * PDF Report Download
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Generates a REAL PDF file using FPDF library.
 * When you click the button, the PDF downloads directly to your computer.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once '../db_connect.php';

// Session check - only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include FPDF library
require_once '../lib/fpdf.php';

// ===== GATHER ALL REPORT DATA =====

// Total users
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($result)['total'];

// Users by role
$users_by_role_result = mysqli_query($conn, "SELECT user_role, COUNT(*) as count FROM users GROUP BY user_role ORDER BY count DESC");
$users_by_role = [];
while ($row = mysqli_fetch_assoc($users_by_role_result)) {
    $users_by_role[] = $row;
}

// Total logins granted
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'granted'");
$total_logins = mysqli_fetch_assoc($result)['total'];

// Total denied
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_logs WHERE access_status = 'denied'");
$total_denied = mysqli_fetch_assoc($result)['total'];

// Active policies
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM security_policies WHERE is_active = 1");
$active_policies = mysqli_fetch_assoc($result)['total'];

// Network segments
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM network_segments");
$total_segments = mysqli_fetch_assoc($result)['total'];

// Login activity last 7 days
$login_activity_result = mysqli_query($conn, "SELECT DATE(login_time) as login_date, COUNT(*) as total_logins, SUM(CASE WHEN access_status = 'granted' THEN 1 ELSE 0 END) as granted, SUM(CASE WHEN access_status = 'denied' THEN 1 ELSE 0 END) as denied FROM access_logs WHERE login_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(login_time) ORDER BY login_date DESC");
$login_activity = [];
while ($row = mysqli_fetch_assoc($login_activity_result)) {
    $login_activity[] = $row;
}

// All users
$all_users_result = mysqli_query($conn, "SELECT user_id, username, email, user_role, status, created_at FROM users ORDER BY created_at DESC");
$all_users = [];
while ($row = mysqli_fetch_assoc($all_users_result)) {
    $all_users[] = $row;
}

// Recent access logs (last 20)
$recent_logs_result = mysqli_query($conn, "SELECT al.*, u.username FROM access_logs al LEFT JOIN users u ON al.user_id = u.user_id ORDER BY al.login_time DESC LIMIT 20");
$recent_logs = [];
while ($row = mysqli_fetch_assoc($recent_logs_result)) {
    $recent_logs[] = $row;
}

// ===== GENERATE PDF =====

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        if (file_exists('../images/MKUR-logo.png')) {
            $this->Image('../images/MKUR-logo.png', 10, 8, 30);
        }
        // University name
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(30); // move right past logo
        $this->SetTextColor(0, 51, 102);
        $this->Cell(0, 8, 'Mount Kigali University', 0, 1, 'C');
        // Subtitle
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, 'Zero-Trust Network Access System - Report', 0, 1, 'C');
        // Line
        $this->SetDrawColor(0, 51, 102);
        $this->SetLineWidth(0.5);
        $this->Line(10, 28, 200, 28);
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Mount Kigali University - ZTNA System Report', 0, 1, 'C');
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb} | Generated: ' . date('Y-m-d H:i'), 0, 0, 'C');
    }

    // Section title
    function SectionTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(0, 51, 102);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 8, '  ' . $title, 0, 1, 'L', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(3);
    }

    // Table header
    function TableHeader($headers, $widths)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(240, 244, 248);
        $this->SetTextColor(0, 51, 102);
        for ($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 7, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetTextColor(0, 0, 0);
    }

    // Table row
    function TableRow($data, $widths)
    {
        $this->SetFont('Arial', '', 9);
        for ($i = 0; $i < count($data); $i++) {
            $this->Cell($widths[$i], 6, $data[$i], 1, 0, 'C');
        }
        $this->Ln();
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 25);

// Report Info
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Report ID: RPT-' . date('Ymd-His') . '    |    Generated by: ' . $_SESSION['username'] . '    |    Date: ' . date('F d, Y - h:i A'), 0, 1, 'C');
$pdf->Ln(5);

// ===== SECTION 1: System Overview =====
$pdf->SectionTitle('1. System Overview');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(63, 8, 'Total Users: ' . $total_users, 1, 0, 'C');
$pdf->Cell(63, 8, 'Successful Logins: ' . $total_logins, 1, 0, 'C');
$pdf->Cell(64, 8, 'Denied Attempts: ' . $total_denied, 1, 1, 'C');
$pdf->Cell(63, 8, 'Active Policies: ' . $active_policies, 1, 0, 'C');
$pdf->Cell(63, 8, 'Network Segments: ' . $total_segments, 1, 0, 'C');
$pdf->Cell(64, 8, 'Today: ' . date('Y-m-d'), 1, 1, 'C');
$pdf->Ln(5);

// ===== SECTION 2: Users by Role =====
$pdf->SectionTitle('2. Users by Role');
$headers = ['Role', 'Number of Users', 'Percentage'];
$widths = [60, 65, 65];
$pdf->TableHeader($headers, $widths);
foreach ($users_by_role as $row) {
    $percentage = ($total_users > 0) ? round(($row['count'] / $total_users) * 100, 1) . '%' : '0%';
    $pdf->TableRow([ucfirst($row['user_role']), $row['count'], $percentage], $widths);
}
$pdf->Ln(5);

// ===== SECTION 3: Login Activity (Last 7 Days) =====
$pdf->SectionTitle('3. Login Activity (Last 7 Days)');
$headers = ['Date', 'Total Attempts', 'Granted', 'Denied', 'Success Rate'];
$widths = [38, 38, 38, 38, 38];
$pdf->TableHeader($headers, $widths);
foreach ($login_activity as $row) {
    $success_rate = ($row['total_logins'] > 0) ? round(($row['granted'] / $row['total_logins']) * 100, 1) . '%' : '0%';
    $pdf->TableRow([$row['login_date'], $row['total_logins'], $row['granted'], $row['denied'], $success_rate], $widths);
}
if (empty($login_activity)) {
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 7, 'No login activity in the last 7 days.', 0, 1, 'C');
}
$pdf->Ln(5);

// ===== SECTION 4: All Registered Users =====
$pdf->SectionTitle('4. Registered Users');
$headers = ['ID', 'Username', 'Email', 'Role', 'Status'];
$widths = [15, 35, 60, 35, 45];
$pdf->TableHeader($headers, $widths);
foreach ($all_users as $user) {
    $pdf->TableRow([
        $user['user_id'],
        $user['username'],
        $user['email'],
        ucfirst($user['user_role']),
        ucfirst($user['status'])
    ], $widths);
}
$pdf->Ln(5);

// ===== SECTION 5: Recent Access Logs =====
$pdf->AddPage();
$pdf->SectionTitle('5. Recent Access Logs (Last 20 Entries)');
$headers = ['ID', 'User', 'Time', 'IP Address', 'Status', 'Resource'];
$widths = [12, 25, 38, 30, 25, 60];
$pdf->TableHeader($headers, $widths);
foreach ($recent_logs as $log) {
    $pdf->TableRow([
        $log['log_id'],
        $log['username'] ?? 'Unknown',
        $log['login_time'],
        $log['ip_address'],
        ucfirst($log['access_status']),
        substr($log['resource_accessed'] ?? '-', 0, 25)
    ], $widths);
}
$pdf->Ln(10);

// ===== CONFIDENTIAL NOTICE =====
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(200, 0, 0);
$pdf->Cell(0, 8, 'CONFIDENTIAL - FOR AUTHORIZED PERSONNEL ONLY', 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, 'This report was generated from live system data. Unauthorized distribution is prohibited.', 0, 1, 'C');
$pdf->Cell(0, 6, 'Developed by: INGABIRE GISELE | Student ID: BBICTR/2024/36790', 0, 1, 'C');

// ===== OUTPUT PDF - Direct Download =====
$filename = 'ZTNA_Report_' . date('Y-m-d_H-i-s') . '.pdf';
$pdf->Output('D', $filename);
exit();
?>
