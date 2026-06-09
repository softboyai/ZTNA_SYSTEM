-- =====================================================
-- Mount Kigali University - Zero-Trust Network Access System
-- Database: ztna_system
-- 
-- Student: INGABIRE GISELE
-- Student ID: BBICTR/2024/36790
--
-- This SQL file creates the database, tables, and
-- inserts sample data with bcrypt hashed passwords.
-- Run this file in phpMyAdmin or MySQL CLI.
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS ztna_system;
USE ztna_system;

-- =====================================================
-- Table: users
-- Stores all system users with their roles and status
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_role ENUM('admin', 'student', 'lecturer', 'staff') NOT NULL,
    device_info VARCHAR(255) DEFAULT NULL,
    ip_address VARCHAR(50) DEFAULT NULL,
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- Table: access_logs
-- Records every login attempt (granted or denied)
-- =====================================================
CREATE TABLE IF NOT EXISTS access_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    device_info VARCHAR(255),
    access_status ENUM('granted', 'denied') NOT NULL,
    resource_accessed VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- Table: security_policies
-- Defines network security policies managed by admin
-- =====================================================
CREATE TABLE IF NOT EXISTS security_policies (
    policy_id INT AUTO_INCREMENT PRIMARY KEY,
    policy_name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- Table: network_segments
-- Defines network zones and which roles can access them
-- =====================================================
CREATE TABLE IF NOT EXISTS network_segments (
    segment_id INT AUTO_INCREMENT PRIMARY KEY,
    segment_name VARCHAR(100) NOT NULL,
    allowed_roles VARCHAR(255),
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- Table: resource_access
-- Logs when users access specific resources (proves ZTNA)
-- =====================================================
CREATE TABLE IF NOT EXISTS resource_access (
    access_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resource_name VARCHAR(255) NOT NULL,
    segment_name VARCHAR(100),
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    access_result ENUM('allowed', 'blocked') DEFAULT 'allowed',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- Sample Users (passwords are bcrypt hashed)
-- Admin@1234 / Student@1234 / Lecturer@1234 / Staff@1234
-- =====================================================
INSERT INTO users (username, email, password, user_role, status) VALUES
('admin', 'admin@mku.ac.rw', '$2y$10$x7VJpRbxB12d58m12GZWA.MlpqMSBe4iAIpxtjL/mNNfJydL93yjS', 'admin', 'active'),
('student1', 'student1@mku.ac.rw', '$2y$10$cj/SnQrHL2qvjYahw//ztuwOULCMqpOD.usquEZbm4RyXJ5fP2rsW', 'student', 'active'),
('lecturer1', 'lecturer1@mku.ac.rw', '$2y$10$xn7WZTY2XQ95SCtBYA3bAuGHYYlnpkLGcahMkaBHQi27s82Q0ND0m', 'lecturer', 'active'),
('staff1', 'staff1@mku.ac.rw', '$2y$10$jvPIIQ6VbSYhx4RFA5S7iuqXqKDlmG4knbTZE1HH9Q6y9wtg2u/86', 'staff', 'active');

-- =====================================================
-- Sample Security Policies
-- =====================================================
INSERT INTO security_policies (policy_name, description, is_active) VALUES
('Multi-Factor Authentication', 'All users must complete MFA verification before accessing sensitive resources.', 1),
('Device Compliance Check', 'Only devices meeting security standards (updated OS, antivirus) are allowed access.', 1),
('Session Timeout Policy', 'User sessions expire after 30 minutes of inactivity.', 1),
('IP Restriction Policy', 'Access is restricted to university-approved IP ranges for critical systems.', 1);

-- =====================================================
-- Sample Network Segments
-- =====================================================
INSERT INTO network_segments (segment_name, allowed_roles, description) VALUES
('Academic Network', 'student,lecturer', 'Access to learning management systems, course materials, and academic databases.'),
('Administrative Network', 'admin,staff', 'Access to HR systems, financial management, and administrative tools.'),
('Research Network', 'lecturer', 'Access to research portals, journals, and collaboration tools.'),
('General Network', 'admin,student,lecturer,staff', 'Basic internet access, email, and communication tools.');
