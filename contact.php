<?php
/**
 * Module 10 — Contact Us
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows visitors (guests, logged-in users, admins) to send messages or questions 
 * to the website administration.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

$pageTitle = "Contact Us";
$errorMessage = "";
$successMessage = "";

// Default pre-fill for logged-in users
$name = $_SESSION['name'] ?? '';
$email = '';
$subject = '';
$message = '';

if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    try {
        $pdo = getDBConnection();
        if ($pdo) {
            $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([(int)$_SESSION['user_id']]);
            $userRow = $stmt->fetch();
            if ($userRow) {
                $email = $userRow['email'];
            }
        }
    } catch (PDOException $e) {
        error_log("Pre-fill Contact Email Error: " . $e->getMessage());
    }
}

// Regex patterns
$regexName = '/^[A-Za-z ]{2,100}$/';
$regexEmail = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Server-side Validation
    if (empty($name)) {
        $errorMessage = "Full Name is required.";
    } elseif (!preg_match($regexName, $name)) {
        $errorMessage = "Full Name must contain only letters and spaces (2 to 100 characters).";
    } elseif (empty($email)) {
        $errorMessage = "Email address is required.";
    } elseif (!preg_match($regexEmail, $email)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif (empty($subject)) {
        $errorMessage = "Subject is required.";
    } elseif (strlen($subject) > 200) {
        $errorMessage = "Subject cannot exceed 200 characters.";
    } elseif (empty($message) || strlen($message) < 10) {
        $errorMessage = "Message must contain at least 10 characters.";
    } elseif (strlen($message) > 2000) {
        $errorMessage = "Message cannot exceed 2000 characters.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$name, $email, $subject, $message]);

                $successMessage = "Your message has been sent successfully.";
                
                // Clear form fields after success
                $subject = '';
                $message = '';
            } else {
                $errorMessage = "Database connection error. Please try again later.";
            }
        } catch (PDOException $e) {
            error_log("Contact Form Insert Error: " . $e->getMessage());
            $errorMessage = "An error occurred while sending your message.";
        }
    }
}

include 'includes/header.php';
?>

<main class="container section">
    <div class="form-card-wrapper" style="max-width: 680px;">
        
        <div class="form-card-header">
            <div class="form-avatar-icon" style="background: linear-gradient(135deg, var(--accent-color), var(--primary-color));">📬</div>
            <h1 class="form-card-title">Contact Us</h1>
            <p class="form-card-subtitle">Have a question or feedback? Send us a message.</p>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="alert-box alert-success" id="contactSuccessAlert">
                ✅ <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" id="contactAlert">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="POST" class="auth-form" id="contactForm" novalidate>
            
            <div class="form-row-2col">
                <div class="form-group">
                    <label for="name" class="form-label">Your Name <span class="required-star">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control" 
                        placeholder="Enter your full name" 
                        value="<?php echo htmlspecialchars($name); ?>" 
                        required
                    >
                    <span class="error-msg" id="nameError">Full Name must contain only letters and spaces (2 to 100 characters).</span>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address <span class="required-star">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="Enter your email address" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        required
                    >
                    <span class="error-msg" id="emailError">Please enter a valid email address.</span>
                </div>
            </div>

            <div class="form-group">
                <label for="subject" class="form-label">Subject <span class="required-star">*</span></label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    class="form-control" 
                    placeholder="Enter message subject" 
                    value="<?php echo htmlspecialchars($subject); ?>" 
                    required 
                    maxlength="200"
                >
                <span class="error-msg" id="subjectError">Subject is required (maximum 200 characters).</span>
            </div>

            <div class="form-group">
                <label for="message" class="form-label">Message <span class="required-star">*</span></label>
                <textarea 
                    id="message" 
                    name="message" 
                    class="form-control" 
                    rows="5" 
                    placeholder="Type your message here (10 to 2000 characters)..." 
                    required
                ><?php echo htmlspecialchars($message); ?></textarea>
                <span class="error-msg" id="messageError">Message must contain between 10 and 2000 characters.</span>
                <div class="char-count-bar"><span id="msgCharCounter">0</span> / 2000 characters</div>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primary btn-full">✉️ Send Message</button>
            </div>
        </form>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
