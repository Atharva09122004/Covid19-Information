<?php
/**
 * Module 6 — User Dashboard & Profile Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Displays user profile, account meta, and security controls for authenticated users.
 */

// 1. Enforce authentication middleware
require_once dirname(__DIR__, 3) . '/app/includes/auth.php';
require_once dirname(__DIR__, 3) . '/app/config/database.php';

// 2. HTTP No-Cache headers to prevent caching sensitive user data
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 3. Retrieve user ID strictly from active session (IDOR protection)
$userId = (int)$_SESSION['user_id'];
$user = null;
$errorMessage = "";
$successMessage = "";

// Handle query parameter feedback messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'profile_updated') {
        $successMessage = "Profile updated successfully.";
    } elseif ($_GET['msg'] === 'password_updated') {
        $successMessage = "Password changed successfully.";
    }
}

try {
    $pdo = getDBConnection();
    if ($pdo) {
        // Query users table using session user_id
        $stmt = $pdo->prepare("SELECT id, name, email, mobile, username, gender, role, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
    }
} catch (PDOException $e) {
    error_log("Dashboard Fetch Error: " . $e->getMessage());
    $errorMessage = "Failed to load user profile. Please try again later.";
}

// Fallback to session values if user fetch failed unexpectedly
if (!$user) {
    $user = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'] ?? 'User',
        'email' => 'N/A',
        'mobile' => 'N/A',
        'username' => $_SESSION['username'] ?? 'User',
        'gender' => 'N/A',
        'role' => $_SESSION['role'] ?? 'user',
        'created_at' => date('Y-m-d H:i:s')
    ];
}

$pageTitle = "User Dashboard";
include dirname(__DIR__, 3) . '/app/includes/header.php';
?>

<main class="container section">
    <div class="dashboard-main-container">
        
        <!-- Welcome Hero Banner -->
        <div class="dashboard-welcome-card">
            <div class="user-avatar-circle">👤</div>
            <div class="welcome-text-wrap">
                <span class="dashboard-badge">USER DASHBOARD</span>
                <h1 class="welcome-heading">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
                <p class="dashboard-subtitle">Manage your personal profile, account settings, and security controls.</p>
            </div>
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

        <!-- Dashboard Grid System -->
        <div class="dashboard-grid-layout">
            
            <!-- Column 1: Personal Profile Details -->
            <div class="dashboard-panel-card">
                <div class="panel-card-header">
                    <div class="panel-header-icon">👤</div>
                    <div class="panel-header-title">
                        <h3>Personal Profile</h3>
                        <p>Your public and contact information</p>
                    </div>
                </div>

                <div class="profile-details-list">
                    <div class="detail-row">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Email Address</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Mobile Number</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['mobile']); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Username</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['gender']); ?></span>
                    </div>
                </div>

                <div class="panel-card-actions">
                    <a href="<?php echo url('public/pages/user/edit-profile.php'); ?>" class="btn btn-primary btn-full">✏️ Edit Profile</a>
                </div>
            </div>

            <!-- Column 2: Account Information & Security Controls -->
            <div class="dashboard-column-right">
                
                <!-- Account Meta Card -->
                <div class="dashboard-panel-card">
                    <div class="panel-card-header">
                        <div class="panel-header-icon">🪪</div>
                        <div class="panel-header-title">
                            <h3>Account Information</h3>
                            <p>System identification and membership details</p>
                        </div>
                        <span class="status-badge status-active">● Active</span>
                    </div>

                    <div class="profile-details-list">
                        <div class="detail-row">
                            <span class="detail-label">User ID</span>
                            <span class="detail-value">#<?php echo htmlspecialchars($user['id']); ?></span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Account Role</span>
                            <span class="detail-value"><span class="role-badge"><?php echo htmlspecialchars($user['role']); ?></span></span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Account Created</span>
                            <span class="detail-value"><?php echo htmlspecialchars(date('d-m-Y (h:i A)', strtotime($user['created_at']))); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Security Actions Card -->
                <div class="dashboard-panel-card">
                    <div class="panel-card-header">
                        <div class="panel-header-icon">🛡️</div>
                        <div class="panel-header-title">
                            <h3>Security & Authentication</h3>
                            <p>Manage account security and session access</p>
                        </div>
                    </div>

                    <div class="security-actions-box">
                        <a href="<?php echo url('public/pages/auth/change-password.php'); ?>" class="btn btn-outline btn-full">🔑 Change Password</a>
                        <a href="<?php echo url('public/pages/auth/logout.php'); ?>" class="btn btn-logout btn-full">🔒 Logout Account</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php include dirname(__DIR__, 3) . '/app/includes/footer.php'; ?>
