<?php
/**
 * Network Segments Page (Admin)
 * Mount Kigali University - Zero-Trust Network Access System
 * 
 * Manage network segments:
 * - View all segments with allowed roles
 * - Add new segment
 * - Edit existing segment
 * - Delete segment
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
    $stmt = mysqli_prepare($conn, "DELETE FROM network_segments WHERE segment_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $message = 'Network segment deleted successfully.';
}

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $segment_name = trim($_POST['segment_name'] ?? '');
    $allowed_roles = isset($_POST['allowed_roles']) ? implode(',', $_POST['allowed_roles']) : '';
    $description = trim($_POST['description'] ?? '');
    $edit_id = intval($_POST['segment_id'] ?? 0);
    
    if (empty($segment_name)) {
        $error = 'Segment name is required.';
    } else {
        if ($edit_id > 0) {
            // Update existing segment
            $stmt = mysqli_prepare($conn, "UPDATE network_segments SET segment_name = ?, allowed_roles = ?, description = ? WHERE segment_id = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $segment_name, $allowed_roles, $description, $edit_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $message = 'Network segment updated successfully!';
        } else {
            // Add new segment
            $stmt = mysqli_prepare($conn, "INSERT INTO network_segments (segment_name, allowed_roles, description) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $segment_name, $allowed_roles, $description);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $message = 'Network segment added successfully!';
        }
    }
}

// Get segment to edit (if editing)
$edit_segment = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM network_segments WHERE segment_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_segment = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Parse allowed roles for the edit form
$edit_roles = [];
if ($edit_segment && !empty($edit_segment['allowed_roles'])) {
    $edit_roles = explode(',', $edit_segment['allowed_roles']);
}

// Fetch all segments
$segments = mysqli_query($conn, "SELECT * FROM network_segments ORDER BY segment_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Segments - MKU ZTNA System</title>
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
                <a href="security_policies.php">
                    <span class="nav-icon">&#128274;</span> Security Policies
                </a>
                <a href="network_segments.php" class="active">
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
                <h1>Network Segments</h1>
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

                <!-- Add/Edit Segment Form -->
                <div class="form-container" style="margin-bottom: 30px;">
                    <h2><?php echo $edit_segment ? 'Edit Network Segment' : 'Add New Network Segment'; ?></h2>
                    <form method="POST" action="network_segments.php">
                        <?php if ($edit_segment): ?>
                            <input type="hidden" name="segment_id" value="<?php echo $edit_segment['segment_id']; ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="segment_name">Segment Name</label>
                            <input type="text" id="segment_name" name="segment_name" value="<?php echo htmlspecialchars($edit_segment['segment_name'] ?? ''); ?>" placeholder="Enter segment name" required>
                        </div>
                        <div class="form-group">
                            <label>Allowed Roles</label>
                            <div style="display: flex; gap: 15px; flex-wrap: wrap; padding: 10px 0;">
                                <label style="font-weight: normal; display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="allowed_roles[]" value="admin" <?php echo in_array('admin', $edit_roles) ? 'checked' : ''; ?>> Admin
                                </label>
                                <label style="font-weight: normal; display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="allowed_roles[]" value="student" <?php echo in_array('student', $edit_roles) ? 'checked' : ''; ?>> Student
                                </label>
                                <label style="font-weight: normal; display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="allowed_roles[]" value="lecturer" <?php echo in_array('lecturer', $edit_roles) ? 'checked' : ''; ?>> Lecturer
                                </label>
                                <label style="font-weight: normal; display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="allowed_roles[]" value="staff" <?php echo in_array('staff', $edit_roles) ? 'checked' : ''; ?>> Staff
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Enter segment description"><?php echo htmlspecialchars($edit_segment['description'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $edit_segment ? 'Update Segment' : 'Add Segment'; ?></button>
                        <?php if ($edit_segment): ?>
                            <a href="network_segments.php" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Segments Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>All Network Segments</h2>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Segment Name</th>
                                <th>Allowed Roles</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($segment = mysqli_fetch_assoc($segments)): ?>
                            <tr>
                                <td><?php echo $segment['segment_id']; ?></td>
                                <td><?php echo htmlspecialchars($segment['segment_name']); ?></td>
                                <td>
                                    <?php
                                    $roles = explode(',', $segment['allowed_roles']);
                                    foreach ($roles as $role) {
                                        echo '<span class="badge badge-active" style="margin: 2px;">' . ucfirst(trim($role)) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars(substr($segment['description'], 0, 60)); ?></td>
                                <td class="action-links">
                                    <a href="network_segments.php?action=edit&id=<?php echo $segment['segment_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="network_segments.php?action=delete&id=<?php echo $segment['segment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this segment?')">Delete</a>
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
