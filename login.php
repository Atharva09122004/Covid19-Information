<?php
/**
 * Module 5 — User Login
 * Project: COVID-19 Information and Awareness Website
 * 
 * Handles user authentication against the database using password_verify()
 * and manages PHP session creation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect directly to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/database.php';

$pageTitle = "Login";
$errorMessage = "";
$successMessage = "";
$identifier = "";

// Check for URL query alert messages (e.g. from logout or auth redirect)
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $successMessage = "You have been logged out successfully.";
} elseif (isset($_GET['error']) && $_GET['error'] === 'auth_required') {
    $errorMessage = "Please log in to access your user dashboard.";
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Validation: Empty inputs
    if (empty($identifier)) {
        $errorMessage = "Please enter your username or email.";
    } elseif (empty($password)) {
        $errorMessage = "Please enter your password.";
    } else {
        try {
            $pdo = getDBConnection();
            if (!$pdo) {
                $errorMessage = "Database connection error. Please try again later.";
            } else {
                // 2. Search users table using prepared statement (username OR email)
                $stmt = $pdo->prepare("SELECT id, name, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1");
                $stmt->execute([$identifier, $identifier]);
                $user = $stmt->fetch();

                // 3. Verify user exists AND check hashed password using password_verify()
                if ($user && password_verify($password, $user['password'])) {
                    // Session Security: Regenerate session ID upon successful login
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id']   = (int)$user['id'];
                    $_SESSION['username']  = $user['username'];
                    $_SESSION['name']      = $user['name'];
                    $_SESSION['role']      = $user['role'];
                    $_SESSION['logged_in'] = true;

                    // Redirect to protected dashboard page
                    header("Location: dashboard.php");
                    exit;
                } else {
                    // Generic security message (prevents username enumeration)
                    $errorMessage = "Invalid username/email or password.";
                }
            }
        } catch (Throwable $e) {
            error_log("Login Exception: " . $e->getMessage());
            $errorMessage = "A system error occurred during login. Please try again later.";
        }
    }
}

include 'includes/header.php';
?>

<main class="container section">
    <div class="form-card-wrapper login-card-wrapper">
        <div class="form-card-header">
            <div class="form-avatar-icon">🔐</div>
            <h1 class="form-card-title">Welcome Back</h1>
            <p class="form-card-subtitle">Login to access your account and dashboard.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" id="loginAlert">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert-box alert-success" id="loginSuccessAlert">
                ✅ <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form" id="loginForm" novalidate>
            <div class="form-group">
                <label for="identifier" class="form-label">Username or Email <span class="required-star">*</span></label>
                <input 
                    type="text" 
                    id="identifier" 
                    name="identifier" 
                    class="form-control" 
                    placeholder="Enter your username or email" 
                    value="<?php echo htmlspecialchars($identifier); ?>" 
                    required
                    autocomplete="username"
                >
                <span class="error-msg" id="identifierError">Please enter your username or email.</span>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password <span class="required-star">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Enter your password" 
                    required
                    autocomplete="current-password"
                >
                <span class="error-msg" id="passwordError">Please enter your password.</span>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primary btn-full">Login</button>
                <button type="reset" class="btn btn-outline" onclick="window.location.href='login.php'">Clear</button>
            </div>
        </form>

        <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--border-color);">
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary-color); font-weight: 700; text-decoration: underline;">Register here</a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
