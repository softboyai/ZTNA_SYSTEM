<?php
/**
 * Security Policies Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Manage security policies:
 * - View all policies
 * - Add new policy
 * - Edit existing policy
 * - Delete policy
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

$username = $_SESSION['username'];
$message = '';
$error = '';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM security_policies WHERE policy_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $message = 'Policy deleted successfully.';
}

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $policy_name = trim($_POST['policy_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_active = intval($_POST['is_active'] ?? 1);
    $edit_id = intval($_POST['policy_id'] ?? 0);
    
    if (empty($policy_name)) {
        $error = 'Policy name is required.';
    } else {
        if ($edit_id > 0) {
            // Update existing policy
            $stmt = mysqli_prepare($conn, "UPDATE security_policies SET policy_name = ?, description = ?, is_active = ? WHERE policy_id = ?");
            mysqli_stmt_bind_param($stmt, "ssii", $policy_name, $description, $is_active, $edit_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $message = 'Policy updated successfully!';
        } else {
            // Add new policy
            $stmt = mysqli_prepare($conn, "INSERT INTO security_policies (policy_name, description, is_active) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssi", $policy_name, $description, $is_active);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $message = 'Policy added successfully!';
        }
    }
}

// Get policy to edit (if editing)
$edit_policy = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM security_policies WHERE policy_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_policy = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Fetch all policies
$policies = mysqli_query($conn, "SELECT * FROM security_policies ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Policies - MKU ZTNA System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/MKUR-logo.png" alt="MKU Logo" style="height: 40px; margin-bottom: 8px;">
                <h2>Mount Kigali University</h2>
                <p>ZTNA System - Admin</p>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard_admin.php">
                    <span class="nav-icon">&#9733;</span> Dashboard
                </a>
                <a href="manage_users.php">
                    <span class="nav-icon">&#128101;</span> User Management
                </a>
                <a href="access_logs.php">
                    <span class="nav-icon">&#128203;</span> Access Logs
                </a>
                <a href="security_policies.php" class="active">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="network_segments.php">
                    <span class="nav-icon">&#127760;</span> Network Segments
                </a>
                <a href="reports.php">
                    <span class="nav-icon">&#128202;</span> Reports
                </a>
                <div class="nav-divider"></div>
                <a href="../logout.php">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">&#9776;</button>
                <h1>Security Policies</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($username); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                </div>
            </header>

            <div class="content-area">
                <!-- Messages -->
                <?php if (!empty($message)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <!-- Add/Edit Policy Form -->
                <div class="form-container" style="margin-bottom: 30px;">
                    <h2><?php echo $edit_policy ? 'Edit Policy' : 'Add New Policy'; ?></h2>
                    <form method="POST" action="security_policies.php">
                        <?php if ($edit_policy): ?>
                            <input type="hidden" name="policy_id" value="<?php echo $edit_policy['policy_id']; ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="policy_name">Policy Name</label>
                            <input type="text" id="policy_name" name="policy_name" value="<?php echo htmlspecialchars($edit_policy['policy_name'] ?? ''); ?>" placeholder="Enter policy name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Enter policy description"><?php echo htmlspecialchars($edit_policy['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select id="is_active" name="is_active">
                                <option value="1" <?php echo (isset($edit_policy) && $edit_policy['is_active'] == 1) || !isset($edit_policy) ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo (isset($edit_policy) && $edit_policy['is_active'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $edit_policy ? 'Update Policy' : 'Add Policy'; ?></button>
                        <?php if ($edit_policy): ?>
                            <a href="security_policies.php" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Policies Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>All Security Policies</h2>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Policy Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($policy = mysqli_fetch_assoc($policies)): ?>
                            <tr>
                                <td><?php echo $policy['policy_id']; ?></td>
                                <td><?php echo htmlspecialchars($policy['policy_name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($policy['description'], 0, 80)); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $policy['is_active'] ? 'active' : 'suspended'; ?>">
                                        <?php echo $policy['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo $policy['created_at']; ?></td>
                                <td class="action-links">
                                    <a href="security_policies.php?action=edit&id=<?php echo $policy['policy_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="security_policies.php?action=delete&id=<?php echo $policy['policy_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this policy?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
