<?php
/**
 * Module 6 — Edit Profile
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows authenticated users to update their Full Name, Mobile Number, and Gender.
 * Strictly uses session user_id to prevent IDOR attacks.
 */

// 1. Authentication middleware
require_once 'includes/auth.php';
require_once 'config/database.php';

// 2. HTTP No-Cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 3. Retrieve user ID strictly from session (IDOR protection: ignore any GET/POST id)
$userId = (int)$_SESSION['user_id'];
$errorMessage = "";
$user = null;

// Regular Expression Patterns (Same as Module 4)
$regexPatterns = [
    'name'   => '/^[A-Za-z ]{2,100}$/',
    'mobile' => '/^[6-9][0-9]{9}$/'
];

$allowedGenders = ['Male', 'Female', 'Other', 'Prefer not to say'];

// Fetch user data from database
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, name, email, mobile, username, gender, role FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
    }
} catch (PDOException $e) {
    error_log("Edit Profile Fetch Error: " . $e->getMessage());
    $errorMessage = "System error loading profile data.";
}

if (!$user) {
    header("Location: dashboard.php");
    exit;
}

// Default input values from DB
$name = $user['name'];
$mobile = $user['mobile'];
$gender = $user['gender'];

// Handle POST request for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    // Server-side validation
    if (empty($name)) {
        $errorMessage = "Full Name is required.";
    } elseif (!preg_match($regexPatterns['name'], $name)) {
        $errorMessage = "Full Name must contain only letters and spaces (2 to 100 characters).";
    } elseif (empty($mobile)) {
        $errorMessage = "Mobile Number is required.";
    } elseif (!preg_match($regexPatterns['mobile'], $mobile)) {
        $errorMessage = "Please enter a valid 10-digit Indian mobile number starting with 6-9.";
    } elseif (empty($gender) || !in_array($gender, $allowedGenders, true)) {
        $errorMessage = "Please select a valid gender option.";
    } else {
        try {
            if ($pdo) {
                // Execute prepared SQL update using SESSION user_id
                $updateStmt = $pdo->prepare("UPDATE users SET name = ?, mobile = ?, gender = ? WHERE id = ?");
                $updateStmt->execute([$name, $mobile, $gender, $userId]);

                // Update session state for instant UI reflection
                $_SESSION['name'] = $name;

                // Redirect to dashboard with success message
                header("Location: dashboard.php?msg=profile_updated");
                exit;
            } else {
                $errorMessage = "Database connection error. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Edit Profile Update Error: " . $e->getMessage());
            $errorMessage = "An error occurred while updating your profile. Please try again.";
        }
    }
}

$pageTitle = "Edit Profile";
include 'includes/header.php';
?>

<main class="container section">
    <div class="form-card-wrapper" style="max-width: 580px;">
        <div class="form-card-header">
            <div class="form-avatar-icon">✏️</div>
            <h1 class="form-card-title">Edit Profile</h1>
            <p class="form-card-subtitle">Update your personal and contact information below.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" id="editProfileAlert">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="edit-profile.php" method="POST" class="auth-form" id="editProfileForm" novalidate>
            
            <!-- Read-only Email Field -->
            <div class="form-group">
                <label class="form-label">Email Address (Read-only)</label>
                <input 
                    type="email" 
                    class="form-control form-control-disabled" 
                    value="<?php echo htmlspecialchars($user['email']); ?>" 
                    disabled 
                    readonly
                >
                <span class="field-hint">Email address cannot be changed.</span>
            </div>

            <!-- Read-only Username Field -->
            <div class="form-group">
                <label class="form-label">Username (Read-only)</label>
                <input 
                    type="text" 
                    class="form-control form-control-disabled" 
                    value="<?php echo htmlspecialchars($user['username']); ?>" 
                    disabled 
                    readonly
                >
                <span class="field-hint">Username cannot be changed.</span>
            </div>

            <!-- Editable Full Name -->
            <div class="form-group">
                <label for="name" class="form-label">Full Name <span class="required-star">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    placeholder="Enter full name" 
                    value="<?php echo htmlspecialchars($name); ?>" 
                    required
                >
                <span class="error-msg" id="nameError">Full Name must contain only letters and spaces (2 to 100 characters).</span>
            </div>

            <!-- Editable Mobile Number -->
            <div class="form-group">
                <label for="mobile" class="form-label">Mobile Number <span class="required-star">*</span></label>
                <input 
                    type="tel" 
                    id="mobile" 
                    name="mobile" 
                    class="form-control" 
                    placeholder="Enter 10-digit mobile number" 
                    value="<?php echo htmlspecialchars($mobile); ?>" 
                    required
                >
                <span class="error-msg" id="mobileError">Please enter a valid 10-digit Indian mobile number starting with 6-9.</span>
            </div>

            <!-- Editable Gender -->
            <div class="form-group">
                <label for="gender" class="form-label">Gender <span class="required-star">*</span></label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">-- Select Gender --</option>
                    <?php foreach ($allowedGenders as $gOpt): ?>
                        <option value="<?php echo htmlspecialchars($gOpt); ?>" <?php echo ($gender === $gOpt) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($gOpt); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-msg" id="genderError">Please select a valid gender option.</span>
            </div>

            <div class="form-actions-row" style="margin-top: 15px;">
                <button type="submit" class="btn btn-primary btn-full">Save Changes</button>
                <a href="dashboard.php" class="btn btn-outline" style="text-align: center; display: flex; align-items: center; justify-content: center;">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
