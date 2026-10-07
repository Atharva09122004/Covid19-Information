<?php
/**
 * Module 6 — Change Password
 * Project: COVID-19 Information and Awareness Website
 * 
 * Securely updates user password using password_verify() for current password 
 * verification and password_hash() for new password storage.
 */

// 1. Authentication middleware
require_once 'includes/auth.php';
require_once 'config/database.php';

// 2. HTTP No-Cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 3. Session user ID (IDOR protection: ignore any GET/POST id)
$userId = (int)$_SESSION['user_id'];
$errorMessage = "";

// Module 4 Password Strength Regex
$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation Steps
    if (empty($currentPassword)) {
        $errorMessage = "Please enter your current password.";
    } elseif (empty($newPassword)) {
        $errorMessage = "Please enter a new password.";
    } elseif (empty($confirmPassword)) {
        $errorMessage = "Please confirm your new password.";
    } elseif (!preg_match($passwordRegex, $newPassword)) {
        $errorMessage = "New password must be at least 8 characters long and include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (@$!%*?&).";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = "New password and confirmation password do not match.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                // Fetch current hashed password from DB
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();

                if (!$user) {
                    $errorMessage = "User account not found.";
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    // Password verification failed
                    $errorMessage = "Current password is incorrect.";
                } elseif (password_verify($newPassword, $user['password'])) {
                    // New password is the same as current password
                    $errorMessage = "New password cannot be the same as your current password.";
                } else {
                    // Password validation & verification passed -> Hash and update
                    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$newHash, $userId]);

                    // Redirect to dashboard with success feedback
                    header("Location: dashboard.php?msg=password_updated");
                    exit;
                }
            } else {
                $errorMessage = "Database connection error. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Change Password Error: " . $e->getMessage());
            $errorMessage = "An error occurred while updating your password. Please try again.";
        }
    }
}

$pageTitle = "Change Password";
include 'includes/header.php';
?>

<main class="container section">
    <div class="form-card-wrapper" style="max-width: 540px;">
        <div class="form-card-header">
            <div class="form-avatar-icon">🔑</div>
            <h1 class="form-card-title">Change Password</h1>
            <p class="form-card-subtitle">Update your account password for enhanced security.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" id="changePasswordAlert">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="change-password.php" method="POST" class="auth-form" id="changePasswordForm" novalidate>
            
            <!-- Current Password -->
            <div class="form-group">
                <label for="current_password" class="form-label">Current Password <span class="required-star">*</span></label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password" 
                    class="form-control" 
                    placeholder="Enter current password" 
                    required 
                    autocomplete="current-password"
                >
                <span class="error-msg" id="currentPasswordError">Please enter your current password.</span>
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label for="new_password" class="form-label">New Password <span class="required-star">*</span></label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    class="form-control" 
                    placeholder="Enter new password" 
                    required 
                    autocomplete="new-password"
                >
                <span class="error-msg" id="newPasswordError">New password must be at least 8 characters long with uppercase, lowercase, number & special char.</span>
                
                <!-- Password Strength Meter Bar -->
                <div class="strength-container" style="margin-top: 6px;">
                    <div id="passwordStrengthMeter" class="strength-bar"></div>
                </div>
                <div id="passwordStrengthText" class="strength-text"></div>
            </div>

            <!-- Confirm New Password -->
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm New Password <span class="required-star">*</span></label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    class="form-control" 
                    placeholder="Confirm new password" 
                    required 
                    autocomplete="new-password"
                >
                <span class="error-msg" id="confirmPasswordError">Please confirm your new password.</span>
            </div>

            <div class="form-actions-row" style="margin-top: 15px;">
                <button type="submit" class="btn btn-primary btn-full">Update Password</button>
                <a href="dashboard.php" class="btn btn-outline" style="text-align: center; display: flex; align-items: center; justify-content: center;">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
