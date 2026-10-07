<?php
/**
 * Module 9 — Admin User Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows administrators to view all registered users, modify user roles (user <-> admin),
 * and safely delete user accounts with self-demotion protection.
 */

require_once '../includes/admin-auth.php';
require_once '../config/database.php';

$currentAdminId = (int)$_SESSION['user_id'];
$errorMessage = "";
$successMessage = "";

$allowedRoles = ['user', 'admin'];

// Handle POST actions (Update Role / Delete User)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $targetUserId = isset($_POST['target_user_id']) ? (int)$_POST['target_user_id'] : 0;

    if ($targetUserId <= 0) {
        $errorMessage = "Invalid target user ID.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                // Action 1: Update Role
                if ($action === 'update_role') {
                    $newRole = trim($_POST['new_role'] ?? '');

                    // Validation: Role whitelist check
                    if (!in_array($newRole, $allowedRoles, true)) {
                        $errorMessage = "Invalid role specified.";
                    }
                    // Self-demotion protection
                    elseif ($targetUserId === $currentAdminId && $newRole === 'user') {
                        $errorMessage = "You cannot remove your own admin privileges.";
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
                        $stmt->execute([$newRole, $targetUserId]);
                        
                        // Update current session if admin changed their own role details
                        if ($targetUserId === $currentAdminId) {
                            $_SESSION['role'] = $newRole;
                        }

                        $successMessage = "User role updated successfully.";
                    }
                }
                // Action 2: Delete User
                elseif ($action === 'delete_user') {
                    // Prevent self-deletion
                    if ($targetUserId === $currentAdminId) {
                        $errorMessage = "You cannot delete your own active admin account.";
                    } else {
                        // Delete user (cascade deletes comments via foreign key)
                        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$targetUserId]);

                        if ($stmt->rowCount() > 0) {
                            $successMessage = "User account deleted successfully.";
                        } else {
                            $errorMessage = "Failed to delete user or user not found.";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            error_log("Admin User Action Error: " . $e->getMessage());
            $errorMessage = "An error occurred while processing the user action.";
        }
    }
}

// Fetch all users
$usersList = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, name, email, mobile, username, gender, role, created_at FROM users ORDER BY id ASC");
        $stmt->execute();
        $usersList = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Fetch Users Error: " . $e->getMessage());
    $errorMessage = "Failed to retrieve user list.";
}

$pageTitle = "User Management";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-page-wrapper">
        
        <div class="table-header-bar" style="margin-bottom: 25px; border-radius: var(--radius-md);">
            <div>
                <h1 style="font-size: 1.6rem; color: var(--secondary-color);">👥 User Management</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">View registered accounts, modify roles, or delete user records.</p>
            </div>
            <span class="count-badge"><?php echo count($usersList); ?> Users Registered</span>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="alert-box alert-success" style="margin-bottom: 25px;">
                ✅ <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" style="margin-bottom: 25px;">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <div class="table-card-container">
            <div class="table-responsive">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usersList)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 30px;">No registered users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usersList as $u): ?>
                                <tr>
                                    <td><strong>#<?php echo $u['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($u['name']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><code>@<?php echo htmlspecialchars($u['username']); ?></code></td>
                                    <td><?php echo htmlspecialchars($u['mobile']); ?></td>
                                    <td><?php echo htmlspecialchars($u['gender']); ?></td>
                                    <td>
                                        <span class="role-badge <?php echo ($u['role'] === 'admin') ? 'role-admin' : ''; ?>">
                                            <?php echo htmlspecialchars($u['role']); ?>
                                        </span>
                                    </td>
                                    <td class="date-col"><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($u['created_at']))); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                            
                                            <!-- Toggle Role Form -->
                                            <form action="users.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="update_role">
                                                <input type="hidden" name="target_user_id" value="<?php echo $u['id']; ?>">
                                                <input type="hidden" name="new_role" value="<?php echo ($u['role'] === 'admin') ? 'user' : 'admin'; ?>">
                                                <button 
                                                    type="submit" 
                                                    class="btn-sm btn-edit" 
                                                    title="Switch role to <?php echo ($u['role'] === 'admin') ? 'user' : 'admin'; ?>"
                                                    <?php echo ($u['id'] === $currentAdminId && $u['role'] === 'admin') ? 'onclick="return confirm(\'Demoting yourself will remove admin access. Proceed?\');"' : ''; ?>
                                                >
                                                    <?php echo ($u['role'] === 'admin') ? '🔻 Make User' : '⭐ Make Admin'; ?>
                                                </button>
                                            </form>

                                            <!-- Delete User Form (POST) -->
                                            <?php if ($u['id'] !== $currentAdminId): ?>
                                                <form action="users.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user @<?php echo htmlspecialchars($u['username']); ?>? All their comments will be removed.');">
                                                    <input type="hidden" name="action" value="delete_user">
                                                    <input type="hidden" name="target_user_id" value="<?php echo $u['id']; ?>">
                                                    <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="warning-tag" style="background: rgba(2, 132, 199, 0.1); color: var(--primary-color);">You</span>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php include 'includes/admin-footer.php'; ?>
