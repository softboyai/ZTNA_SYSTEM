<?php
/**
 * Database Connection File
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * This file establishes connection to the MySQL database
 * using mysqli. Include this file at the top of every page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ztna_system';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8 for proper encoding
mysqli_set_charset($conn, "utf8");
?>
