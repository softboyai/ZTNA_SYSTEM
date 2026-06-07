<?php
/**
 * Homepage / Landing Page
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * This is the main frontend page that visitors see first.
 * It provides information about the ZTNA system and links
 * to the login page.
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();

// If user is already logged in, redirect to their dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    switch ($role) {
        case 'admin': header("Location: dashboard_admin.php"); break;
        case 'student': header("Location: dashboard_student.php"); break;
        case 'lecturer': header("Location: dashboard_lecturer.php"); break;
        case 'staff': header("Location: dashboard_staff.php"); break;
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mount Kigali University - Zero-Trust Network Access System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- =====================================================
         NAVIGATION BAR
         ===================================================== -->
    <nav class="homepage-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="images/MKUR-logo.png" alt="Mount Kigali University Logo" class="nav-logo">
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#features">Features</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
                <a href="register.php" class="nav-btn" style="background: transparent; border: 2px solid #fff; color: #fff !important;">Register</a>
                <a href="login.php" class="nav-btn">Login</a>
            </div>
            <button class="nav-mobile-btn" onclick="toggleMobileNav()">&#9776;</button>
        </div>
    </nav>

    <!-- =====================================================
         HERO SECTION
         ===================================================== -->
    <section class="hero-section" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <img src="images/MKUR-logo.png" alt="MKU Logo" class="hero-logo">
            <h1>Zero-Trust Network Access System</h1>
            <p>Securing university resources with continuous verification. Never trust, always verify.</p>
            <div class="hero-buttons">
                <a href="login.php" class="hero-btn hero-btn-primary">Access Portal</a>
                <a href="register.php" class="hero-btn hero-btn-secondary">Create Account</a>
                <a href="#about" class="hero-btn hero-btn-secondary">Learn More</a>
            </div>
        </div>
    </section>

    <!-- =====================================================
         ABOUT SECTION
         ===================================================== -->
    <section class="homepage-section" id="about">
        <div class="section-container">
            <h2 class="section-title">About Our ZTNA System</h2>
            <p class="section-subtitle">Protecting Mount Kigali University's digital infrastructure</p>
            
            <div class="about-grid">
                <div class="about-card">
                    <div class="about-icon">&#128274;</div>
                    <h3>Zero-Trust Architecture</h3>
                    <p>Our system operates on the principle of "never trust, always verify." Every user and device is continuously authenticated before accessing university resources.</p>
                </div>
                <div class="about-card">
                    <div class="about-icon">&#128737;</div>
                    <h3>Continuous Verification</h3>
                    <p>Unlike traditional security models, we verify identity at every access point. Your credentials, device, and location are checked with each request.</p>
                </div>
                <div class="about-card">
                    <div class="about-icon">&#127760;</div>
                    <h3>Network Segmentation</h3>
                    <p>Resources are divided into secure segments. Users only access what their role permits, minimizing the attack surface across the university network.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
         FEATURES SECTION
         ===================================================== -->
    <section class="homepage-section section-alt" id="features">
        <div class="section-container">
            <h2 class="section-title">Key Features</h2>
            <p class="section-subtitle">Advanced security features protecting our academic community</p>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-number">01</div>
                    <h3>Role-Based Access Control</h3>
                    <p>Four distinct roles — Administrator, Student, Lecturer, and Staff — each with tailored access permissions to university resources.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-number">02</div>
                    <h3>Device &amp; IP Tracking</h3>
                    <p>Every login captures device information and IP address for security auditing and anomaly detection.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-number">03</div>
                    <h3>Comprehensive Access Logs</h3>
                    <p>All access attempts — both successful and failed — are logged for real-time monitoring and forensic analysis.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-number">04</div>
                    <h3>Security Policy Enforcement</h3>
                    <p>Configurable security policies ensure compliance with university standards and best practices.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-number">05</div>
                    <h3>Account Suspension</h3>
                    <p>Compromised or violating accounts can be instantly suspended, blocking all further access to university systems.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-number">06</div>
                    <h3>Encrypted Credentials</h3>
                    <p>All passwords are hashed using bcrypt encryption, ensuring user credentials are never stored in plain text.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
         SERVICES / USER PORTALS SECTION
         ===================================================== -->
    <section class="homepage-section" id="services">
        <div class="section-container">
            <h2 class="section-title">University Services</h2>
            <p class="section-subtitle">Secure access to all university digital resources</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">&#128187;</div>
                    <h3>Student Portal</h3>
                    <ul>
                        <li>Online Learning Platform</li>
                        <li>Academic Records</li>
                        <li>Library Services</li>
                        <li>Email &amp; Communication</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">&#128218;</div>
                    <h3>Lecturer Portal</h3>
                    <ul>
                        <li>Course Management</li>
                        <li>Student Records</li>
                        <li>Online Learning Platform</li>
                        <li>Research Portal</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">&#127970;</div>
                    <h3>Staff Portal</h3>
                    <ul>
                        <li>Administrative Services</li>
                        <li>Financial Management</li>
                        <li>HR Portal</li>
                        <li>Communication Tools</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">&#128274;</div>
                    <h3>Admin Panel</h3>
                    <ul>
                        <li>User Management</li>
                        <li>Access Monitoring</li>
                        <li>Security Policies</li>
                        <li>Network Segments</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
         CONTACT SECTION
         ===================================================== -->
    <section class="homepage-section section-alt" id="contact">
        <div class="section-container">
            <h2 class="section-title">Contact Us</h2>
            <p class="section-subtitle">Get in touch with the IT Department</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">&#128205;</div>
                    <h3>Location</h3>
                    <p>Mount Kigali University<br>Kigali, Rwanda</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">&#9993;</div>
                    <h3>Email</h3>
                    <p>it-support@mku.ac.rw<br>admin@mku.ac.rw</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">&#128222;</div>
                    <h3>Phone</h3>
                    <p>+250 788 000 000<br>+250 722 000 000</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">&#128340;</div>
                    <h3>Working Hours</h3>
                    <p>Mon - Fri: 8:00 AM - 5:00 PM<br>Sat: 9:00 AM - 1:00 PM</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
         FOOTER
         ===================================================== -->
    <footer class="homepage-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="images/MKUR-logo.png" alt="MKU Logo" class="footer-logo">
                <h3>Mount Kigali University</h3>
                <p>Empowering Generations Through Education</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#features">Features</a>
                <a href="login.php">Login</a>
            </div>
            <div class="footer-links">
                <h4>Resources</h4>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">IT Support</a>
                <a href="#">Help Center</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Mount Kigali University. All Rights Reserved. | ZTNA System</p>
            <p class="footer-credit">Developed by: INGABIRE GISELE | Student ID: BBICTR/2024/36790</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script>
        // Mobile navigation toggle
        function toggleMobileNav() {
            var navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // Close mobile nav if open
                document.querySelector('.nav-links').classList.remove('active');
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('.homepage-nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
