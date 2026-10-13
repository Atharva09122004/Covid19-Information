<?php
/**
 * COVID-19 User Registration Module (Module 4)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Handles user registration, server-side Regex validation, duplicate checks, and password hashing.
 */

$pageTitle = "Create Your Account";

require_once dirname(__DIR__, 3) . '/app/config/database.php';

$errors = [];
$successMessage = '';
$formValues = [
    'name'     => '',
    'email'    => '',
    'mobile'   => '',
    'username' => '',
    'gender'   => ''
];

// Regular Expression Patterns for Server-Side Validation
$regexPatterns = [
    'name'     => '/^[A-Za-z ]{2,100}$/',
    'email'    => '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
    'mobile'   => '/^[6-9][0-9]{9}$/',
    'username' => '/^[A-Za-z0-9_]{3,30}$/',
    'password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
];

$allowedGenders = ['Male', 'Female', 'Other', 'Prefer not to say'];

// Handle POST Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize and Trim Inputs
    $name            = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email           = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mobile          = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    $username        = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password        = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $gender          = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    // Retain safe form values for repopulation
    $formValues['name']     = $name;
    $formValues['email']    = $email;
    $formValues['mobile']   = $mobile;
    $formValues['username'] = $username;
    $formValues['gender']   = $gender;

    // 2. Server-side Regex & Input Validations
    if (empty($name)) {
        $errors['name'] = 'Full Name is required.';
    } elseif (!preg_match($regexPatterns['name'], $name)) {
        $errors['name'] = 'Full Name must contain only letters and spaces (2 to 100 characters).';
    }

    if (empty($email)) {
        $errors['email'] = 'Email address is required.';
    } elseif (!preg_match($regexPatterns['email'], $email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($mobile)) {
        $errors['mobile'] = 'Mobile number is required.';
    } elseif (!preg_match($regexPatterns['mobile'], $mobile)) {
        $errors['mobile'] = 'Please enter a valid 10-digit Indian mobile number starting with 6-9.';
    }

    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match($regexPatterns['username'], $username)) {
        $errors['username'] = 'Username must contain only letters, numbers, and underscores (3 to 30 characters).';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (!preg_match($regexPatterns['password'], $password)) {
        $errors['password'] = 'Password must contain at least 8 characters, uppercase, lowercase, number, and special character (@$!%*?&).';
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($gender) || !in_array($gender, $allowedGenders, true)) {
        $errors['gender'] = 'Please select a valid gender option.';
    }

    // 3. Database Operations (Duplicate Check & Insertion)
    if (empty($errors)) {
        $db = getDBConnection();
        
        if ($db !== null) {
            try {
                // Check if Email already exists
                $stmtCheckEmail = $db->prepare("SELECT id FROM users WHERE email = :email");
                $stmtCheckEmail->execute([':email' => $email]);
                
                if ($stmtCheckEmail->fetch()) {
                    $errors['email'] = 'An account with this email address already exists.';
                }

                // Check if Username already exists
                $stmtCheckUsername = $db->prepare("SELECT id FROM users WHERE username = :username");
                $stmtCheckUsername->execute([':username' => $username]);
                
                if ($stmtCheckUsername->fetch()) {
                    $errors['username'] = 'This username is already taken.';
                }

                // If no duplicate errors, hash password and insert record
                if (empty($errors)) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $stmtInsert = $db->prepare("
                        INSERT INTO users (name, email, mobile, username, password, gender, role) 
                        VALUES (:name, :email, :mobile, :username, :password, :gender, 'user')
                    ");

                    $stmtInsert->execute([
                        ':name'     => $name,
                        ':email'    => $email,
                        ':mobile'   => $mobile,
                        ':username' => $username,
                        ':password' => $passwordHash,
                        ':gender'   => $gender
                    ]);

                    $successMessage = "Registration successful! Your account has been created.";

                    // Clear form values on success
                    $formValues = [
                        'name' => '', 'email' => '', 'mobile' => '', 'username' => '', 'gender' => ''
                    ];
                }
            } catch (PDOException $e) {
                error_log("Registration DB Error: " . $e->getMessage());
                $errors['general'] = "Registration failed due to a system error. Please try again later.";
            }
        } else {
            $errors['general'] = "Database connection failed. Please ensure MySQL is running in XAMPP.";
        }
    }
}
?>

<?php include dirname(__DIR__, 3) . '/app/includes/header.php'; ?>

<!-- Main Registration Section -->
<section class="section">
    <div class="container">
        
        <div class="form-card-wrapper">
            <div class="form-card-header">
                <div class="form-avatar-icon">👤</div>
                <h1 class="form-card-title">Create Your Account</h1>
                <p class="form-card-subtitle">Register to access the COVID-19 information platform.</p>
            </div>

            <!-- General Error Alert -->
            <?php if (isset($errors['general'])): ?>
                <div class="alert-box alert-danger">
                    <span>⚠️ <?php echo htmlspecialchars($errors['general']); ?></span>
                </div>
            <?php endif; ?>

            <!-- Success Alert -->
            <?php if (!empty($successMessage)): ?>
                <div class="alert-box alert-success">
                    <span>🎉 <strong>Success:</strong> <?php echo htmlspecialchars($successMessage); ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form action="<?php echo url('public/pages/auth/register.php'); ?>" method="POST" id="registerForm" class="auth-form" novalidate>
                
                <!-- Row 1: Full Name & Email -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name <span class="required-star">*</span></label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control <?php echo isset($errors['name']) ? 'invalid-field' : ''; ?>" 
                               placeholder="e.g. Rahul Sharma" 
                               value="<?php echo htmlspecialchars($formValues['name']); ?>" 
                               required>
                        <span class="error-msg" id="nameError" style="<?php echo isset($errors['name']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['name']) ? htmlspecialchars($errors['name']) : ''; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address <span class="required-star">*</span></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control <?php echo isset($errors['email']) ? 'invalid-field' : ''; ?>" 
                               placeholder="e.g. rahul@example.com" 
                               value="<?php echo htmlspecialchars($formValues['email']); ?>" 
                               required>
                        <span class="error-msg" id="emailError" style="<?php echo isset($errors['email']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['email']) ? htmlspecialchars($errors['email']) : ''; ?>
                        </span>
                    </div>
                </div>

                <!-- Row 2: Mobile & Username -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="mobile" class="form-label">Mobile Number <span class="required-star">*</span></label>
                        <input type="tel" 
                               id="mobile" 
                               name="mobile" 
                               class="form-control <?php echo isset($errors['mobile']) ? 'invalid-field' : ''; ?>" 
                               placeholder="10-digit mobile (e.g. 9876543210)" 
                               value="<?php echo htmlspecialchars($formValues['mobile']); ?>" 
                               required>
                        <span class="error-msg" id="mobileError" style="<?php echo isset($errors['mobile']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['mobile']) ? htmlspecialchars($errors['mobile']) : ''; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username <span class="required-star">*</span></label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-control <?php echo isset($errors['username']) ? 'invalid-field' : ''; ?>" 
                               placeholder="e.g. rahul_99" 
                               value="<?php echo htmlspecialchars($formValues['username']); ?>" 
                               required>
                        <span class="error-msg" id="usernameError" style="<?php echo isset($errors['username']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['username']) ? htmlspecialchars($errors['username']) : ''; ?>
                        </span>
                    </div>
                </div>

                <!-- Row 3: Password & Confirm Password -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="required-star">*</span></label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control <?php echo isset($errors['password']) ? 'invalid-field' : ''; ?>" 
                               placeholder="Min 8 chars (Upper, Lower, Num, Special)" 
                               required>
                        
                        <!-- Password Strength Meter Bar -->
                        <div class="strength-container">
                            <div class="strength-bar" id="passwordStrengthMeter"></div>
                        </div>
                        <span class="strength-text" id="passwordStrengthText"></span>

                        <span class="error-msg" id="passwordError" style="<?php echo isset($errors['password']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['password']) ? htmlspecialchars($errors['password']) : ''; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password <span class="required-star">*</span></label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="form-control <?php echo isset($errors['confirm_password']) ? 'invalid-field' : ''; ?>" 
                               placeholder="Re-enter password" 
                               required>
                        <span class="error-msg" id="confirmPasswordError" style="<?php echo isset($errors['confirm_password']) ? 'display:block;' : ''; ?>">
                            <?php echo isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password']) : ''; ?>
                        </span>
                    </div>
                </div>

                <!-- Row 4: Gender -->
                <div class="form-group">
                    <label for="gender" class="form-label">Gender <span class="required-star">*</span></label>
                    <select id="gender" 
                            name="gender" 
                            class="form-control <?php echo isset($errors['gender']) ? 'invalid-field' : ''; ?>" 
                            required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male" <?php echo ($formValues['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($formValues['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($formValues['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                        <option value="Prefer not to say" <?php echo ($formValues['gender'] === 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                    <span class="error-msg" id="genderError" style="<?php echo isset($errors['gender']) ? 'display:block;' : ''; ?>">
                        <?php echo isset($errors['gender']) ? htmlspecialchars($errors['gender']) : ''; ?>
                    </span>
                </div>

                <!-- Form Action Buttons -->
                <div class="form-actions-row">
                    <button type="submit" class="btn btn-primary btn-full">Register Account</button>
                    <button type="reset" id="resetBtn" class="btn btn-outline btn-full">Clear Form</button>
                </div>
            </form>
        </div>

    </div>
</section>

<script src="<?php echo asset('js/validation.js'); ?>"></script>
<?php include dirname(__DIR__, 3) . '/app/includes/footer.php'; ?>
