<?php
/**
 * Registration Page
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Allows new users (students, lecturers, staff) to create
 * an account with STRICT validation:
 * - Username: Only letters and spaces (real name, no numbers)
 * - Email: Must be a valid email format
 * - Password: Must be strong (min 8 chars, uppercase, lowercase, number, special char)
 * 
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
 */

session_start();
require_once 'db_connect.php';

$error = '';
$success = '';

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

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_role = $_POST['user_role'] ?? '';
    
    // ========== VALIDATION RULES ==========
    
    // 1. Check all fields are filled
    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($user_role)) {
        $error = 'All fields are required.';
    }
    // 2. Full Name: Only letters and spaces allowed (real name, no numbers)
    elseif (!preg_match('/^[a-zA-Z\s]+$/', $full_name)) {
        $error = 'Full Name must contain only letters and spaces. No numbers or special characters allowed.';
    }
    // 3. Full Name: Must be at least 3 characters
    elseif (strlen($full_name) < 3) {
        $error = 'Full Name must be at least 3 characters long.';
    }
    // 4. Username: Only letters allowed (no numbers, no spaces, no special chars)
    elseif (!preg_match('/^[a-zA-Z]+$/', $username)) {
        $error = 'Username must contain only letters. No numbers, spaces, or special characters.';
    }
    // 5. Username: Must be at least 3 characters
    elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    }
    // 6. Email: Must be a valid email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address (e.g. name@mku.ac.rw).';
    }
    // 7. Email: Must contain a proper domain (not just random text)
    elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $error = 'Email must be a real email address with a valid domain.';
    }
    // 8. Password: Minimum 8 characters
    elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    }
    // 9. Password: Must contain at least one uppercase letter
    elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter (A-Z).';
    }
    // 10. Password: Must contain at least one lowercase letter
    elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'Password must contain at least one lowercase letter (a-z).';
    }
    // 11. Password: Must contain at least one number
    elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number (0-9).';
    }
    // 12. Password: Must contain at least one special character
    elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/', $password)) {
        $error = 'Password must contain at least one special character (!@#$%^&* etc).';
    }
    // 13. Passwords must match
    elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    }
    // 14. Valid role
    elseif (!in_array($user_role, ['student', 'lecturer', 'staff'])) {
        $error = 'Invalid role selected.';
    } 
    else {
        // All validation passed - check database for duplicates
        
        // Check if email already exists
        $check_stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'An account with this email already exists.';
        } else {
            // Check if username already exists
            $check_user = mysqli_prepare($conn, "SELECT user_id FROM users WHERE username = ?");
            mysqli_stmt_bind_param($check_user, "s", $username);
            mysqli_stmt_execute($check_user);
            $check_user_result = mysqli_stmt_get_result($check_user);
            
            if (mysqli_num_rows($check_user_result) > 0) {
                $error = 'This username is already taken. Please choose another.';
            } else {
                // Hash password using bcrypt
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user using prepared statement
                $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, user_role, status) VALUES (?, ?, ?, ?, 'active')");
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $user_role);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Account created successfully! You can now log in.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_stmt_close($check_user);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mount Kigali University ZTNA System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Validation styling */
        .field-hint {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }
        .field-error {
            color: #cc0000;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .field-success {
            color: #28a745;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .input-valid {
            border-color: #28a745 !important;
        }
        .input-invalid {
            border-color: #cc0000 !important;
        }
        .password-rules {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 8px;
            font-size: 12px;
        }
        .password-rules p {
            margin-bottom: 6px;
            color: #555;
            font-weight: 600;
        }
        .password-rules ul {
            list-style: none;
            padding: 0;
        }
        .password-rules ul li {
            padding: 2px 0;
            color: #999;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .password-rules ul li.valid {
            color: #28a745;
        }
        .password-rules ul li .check-icon::before {
            content: '✗';
            color: #cc0000;
        }
        .password-rules ul li.valid .check-icon::before {
            content: '✓';
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box" style="max-width: 500px;">
            <!-- University Logo and Header -->
            <div class="login-logo">
                <img src="images/MKUR-logo.png" alt="Mount Kigali University Logo" class="login-logo-img">
                <p>Create Your Account</p>
            </div>
            
            <!-- Success Message -->
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="login.php" class="btn btn-primary">Go to Login</a>
                </div>
            <?php else: ?>
            
            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Registration Form -->
            <form class="login-form" method="POST" action="register.php" id="registerForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="e.g. Ingabire Gisele" value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
                    <div class="field-hint">Letters and spaces only. No numbers or symbols.</div>
                    <div class="field-error" id="name-error">Name must contain only letters and spaces</div>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="e.g. ingabire" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    <div class="field-hint">Letters only. This will be your login name.</div>
                    <div class="field-error" id="username-error">Username must contain only letters (no numbers or spaces)</div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="e.g. ingabire@mku.ac.rw" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    <div class="field-hint">Must be a valid email address.</div>
                    <div class="field-error" id="email-error">Please enter a valid email address</div>
                </div>
                <div class="form-group">
                    <label for="user_role">Role</label>
                    <select id="user_role" name="user_role" style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; outline: none;" required>
                        <option value="">-- Select Your Role --</option>
                        <option value="student" <?php echo (isset($user_role) && $user_role === 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="lecturer" <?php echo (isset($user_role) && $user_role === 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                        <option value="staff" <?php echo (isset($user_role) && $user_role === 'staff') ? 'selected' : ''; ?>>Staff Member</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                    <div class="password-rules">
                        <p>Password must contain:</p>
                        <ul>
                            <li id="rule-length"><span class="check-icon"></span> At least 8 characters</li>
                            <li id="rule-upper"><span class="check-icon"></span> One uppercase letter (A-Z)</li>
                            <li id="rule-lower"><span class="check-icon"></span> One lowercase letter (a-z)</li>
                            <li id="rule-number"><span class="check-icon"></span> One number (0-9)</li>
                            <li id="rule-special"><span class="check-icon"></span> One special character (!@#$%^&*)</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                    <div class="field-error" id="confirm-error">Passwords do not match</div>
                    <div class="field-success" id="confirm-success">Passwords match ✓</div>
                </div>
                <button type="submit" class="btn-login" id="submitBtn">Create Account</button>
            </form>
            
            <!-- Link to Login -->
            <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                <p style="font-size: 14px; color: #666;">Already have an account? <a href="login.php" style="color: #003366; font-weight: 600;">Sign In</a></p>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
    
    <script src="js/main.js"></script>
    <script>
        // ============================================================
        // REAL-TIME FORM VALIDATION (JavaScript - client side)
        // This gives instant feedback as the user types.
        // Server-side PHP validation is the final check.
        // ============================================================

        // Full Name validation - letters and spaces only
        document.getElementById('full_name').addEventListener('input', function() {
            var value = this.value;
            var errorEl = document.getElementById('name-error');
            var valid = /^[a-zA-Z\s]*$/.test(value) && value.trim().length >= 3;
            
            if (value.length === 0) {
                this.classList.remove('input-valid', 'input-invalid');
                errorEl.style.display = 'none';
            } else if (!(/^[a-zA-Z\s]*$/.test(value))) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Name must contain only letters and spaces. No numbers allowed!';
            } else if (value.trim().length < 3) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Name must be at least 3 characters long.';
            } else {
                this.classList.remove('input-invalid');
                this.classList.add('input-valid');
                errorEl.style.display = 'none';
            }
        });

        // Username validation - letters only, no numbers, no spaces
        document.getElementById('username').addEventListener('input', function() {
            var value = this.value;
            var errorEl = document.getElementById('username-error');
            
            if (value.length === 0) {
                this.classList.remove('input-valid', 'input-invalid');
                errorEl.style.display = 'none';
            } else if (!(/^[a-zA-Z]*$/.test(value))) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Username must contain only letters. No numbers or spaces!';
            } else if (value.length < 3) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Username must be at least 3 characters.';
            } else {
                this.classList.remove('input-invalid');
                this.classList.add('input-valid');
                errorEl.style.display = 'none';
            }
        });

        // Email validation - must be a real email
        document.getElementById('email').addEventListener('input', function() {
            var value = this.value;
            var errorEl = document.getElementById('email-error');
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            
            if (value.length === 0) {
                this.classList.remove('input-valid', 'input-invalid');
                errorEl.style.display = 'none';
            } else if (!emailRegex.test(value)) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Enter a valid email (e.g. name@mku.ac.rw)';
            } else {
                this.classList.remove('input-invalid');
                this.classList.add('input-valid');
                errorEl.style.display = 'none';
            }
        });

        // Password validation - real-time rule checking
        document.getElementById('password').addEventListener('input', function() {
            var value = this.value;
            
            // Check each rule
            var rules = {
                'rule-length': value.length >= 8,
                'rule-upper': /[A-Z]/.test(value),
                'rule-lower': /[a-z]/.test(value),
                'rule-number': /[0-9]/.test(value),
                'rule-special': /[!@#$%^&*()_+\-=\[\]{};:,.<>?]/.test(value)
            };

            var allValid = true;
            for (var ruleId in rules) {
                var ruleEl = document.getElementById(ruleId);
                if (rules[ruleId]) {
                    ruleEl.classList.add('valid');
                } else {
                    ruleEl.classList.remove('valid');
                    allValid = false;
                }
            }

            if (value.length === 0) {
                this.classList.remove('input-valid', 'input-invalid');
            } else if (allValid) {
                this.classList.remove('input-invalid');
                this.classList.add('input-valid');
            } else {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
            }

            // Also check confirm password if it has value
            var confirmEl = document.getElementById('confirm_password');
            if (confirmEl.value.length > 0) {
                confirmEl.dispatchEvent(new Event('input'));
            }
        });

        // Confirm Password validation - must match password
        document.getElementById('confirm_password').addEventListener('input', function() {
            var value = this.value;
            var password = document.getElementById('password').value;
            var errorEl = document.getElementById('confirm-error');
            var successEl = document.getElementById('confirm-success');
            
            if (value.length === 0) {
                this.classList.remove('input-valid', 'input-invalid');
                errorEl.style.display = 'none';
                successEl.style.display = 'none';
            } else if (value !== password) {
                this.classList.remove('input-valid');
                this.classList.add('input-invalid');
                errorEl.style.display = 'block';
                successEl.style.display = 'none';
            } else {
                this.classList.remove('input-invalid');
                this.classList.add('input-valid');
                errorEl.style.display = 'none';
                successEl.style.display = 'block';
            }
        });

        // Prevent form submission if validation fails
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var fullName = document.getElementById('full_name').value.trim();
            var username = document.getElementById('username').value.trim();
            var email = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var role = document.getElementById('user_role').value;

            var errors = [];

            if (!/^[a-zA-Z\s]+$/.test(fullName)) {
                errors.push('Full Name must contain only letters and spaces.');
            }
            if (!/^[a-zA-Z]+$/.test(username)) {
                errors.push('Username must contain only letters.');
            }
            if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                errors.push('Please enter a valid email address.');
            }
            if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/.test(password)) {
                errors.push('Password does not meet all requirements.');
            }
            if (password !== confirmPassword) {
                errors.push('Passwords do not match.');
            }
            if (role === '') {
                errors.push('Please select your role.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following:\n\n• ' + errors.join('\n• '));
            }
        });
    </script>
</body>
</html>
