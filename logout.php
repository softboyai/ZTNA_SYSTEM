<?php
/**
 * Logout Page
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Destroys the user session completely and logs the logout event.
 * Redirects to login page after logout.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

// Log the logout event before destroying session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    // Record logout in access_logs
    $stmt = mysqli_prepare($conn, "INSERT INTO access_logs (user_id, ip_address, device_info, access_status, resource_accessed) VALUES (?, ?, ?, 'granted', 'logout')");
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $ip_address, $device_info);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Destroy session completely
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
