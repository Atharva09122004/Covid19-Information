/**
 * COVID-19 Information & Awareness Portal
 * Client-side Form Validation & Regular Expression Engine
 * Module 4: User Registration & Regex Testing
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mobile navbar menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    // Registration Form Validation Setup
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        // Form Fields
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const mobileInput = document.getElementById('mobile');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const genderSelect = document.getElementById('gender');
        const resetButton = document.getElementById('resetBtn');

        // Password Strength Elements
        const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
        const passwordStrengthText = document.getElementById('passwordStrengthText');

        // Regular Expression Patterns
        const regexPatterns = {
            name: /^[A-Za-z ]{2,100}$/,
            email: /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,
            mobile: /^[6-9][0-9]{9}$/,
            username: /^[A-Za-z0-9_]{3,30}$/,
            password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
        };

        // Error Message Helper
        function setError(inputElement, errorElementId, message) {
            const errorSpan = document.getElementById(errorElementId);
            if (errorSpan) {
                errorSpan.textContent = message;
                errorSpan.style.display = message ? 'block' : 'none';
            }
            if (inputElement) {
                if (message) {
                    inputElement.classList.add('invalid-field');
                    inputElement.classList.remove('valid-field');
                } else {
                    inputElement.classList.remove('invalid-field');
                    inputElement.classList.add('valid-field');
                }
            }
            return !message;
        }

        // Validation Functions
        function validateName() {
            const val = nameInput.value.trim();
            if (!val) {
                return setError(nameInput, 'nameError', 'Full Name is required.');
            }
            if (!regexPatterns.name.test(val)) {
                return setError(nameInput, 'nameError', 'Full Name must contain only letters and spaces (2 to 100 characters).');
            }
            return setError(nameInput, 'nameError', '');
        }

        function validateEmail() {
            const val = emailInput.value.trim();
            if (!val) {
                return setError(emailInput, 'emailError', 'Email address is required.');
            }
            if (!regexPatterns.email.test(val)) {
                return setError(emailInput, 'emailError', 'Please enter a valid email address (e.g. user@example.com).');
            }
            return setError(emailInput, 'emailError', '');
        }

        function validateMobile() {
            const val = mobileInput.value.trim();
            if (!val) {
                return setError(mobileInput, 'mobileError', 'Mobile number is required.');
            }
            if (!regexPatterns.mobile.test(val)) {
                return setError(mobileInput, 'mobileError', 'Please enter a valid 10-digit Indian mobile number starting with 6-9.');
            }
            return setError(mobileInput, 'mobileError', '');
        }

        function validateUsername() {
            const val = usernameInput.value.trim();
            if (!val) {
                return setError(usernameInput, 'usernameError', 'Username is required.');
            }
            if (!regexPatterns.username.test(val)) {
                return setError(usernameInput, 'usernameError', 'Username must contain only letters, numbers, and underscores (3 to 30 characters).');
            }
            return setError(usernameInput, 'usernameError', '');
        }

        function validatePassword() {
            const val = passwordInput.value;
            if (!val) {
                updatePasswordStrength('');
                return setError(passwordInput, 'passwordError', 'Password is required.');
            }
            updatePasswordStrength(val);
            if (!regexPatterns.password.test(val)) {
                return setError(passwordInput, 'passwordError', 'Password must be at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character (@$!%*?&).');
            }
            return setError(passwordInput, 'passwordError', '');
        }

        function validateConfirmPassword() {
            const passVal = passwordInput.value;
            const confirmVal = confirmPasswordInput.value;
            if (!confirmVal) {
                return setError(confirmPasswordInput, 'confirmPasswordError', 'Please confirm your password.');
            }
            if (confirmVal !== passVal) {
                return setError(confirmPasswordInput, 'confirmPasswordError', 'Passwords do not match.');
            }
            return setError(confirmPasswordInput, 'confirmPasswordError', '');
        }

        function validateGender() {
            const val = genderSelect.value;
            if (!val) {
                return setError(genderSelect, 'genderError', 'Please select a gender option.');
            }
            return setError(genderSelect, 'genderError', '');
        }

        // Password Strength Indicator Calculation
        function updatePasswordStrength(password) {
            if (!passwordStrengthMeter || !passwordStrengthText) return;

            if (!password) {
                passwordStrengthMeter.className = 'strength-bar';
                passwordStrengthText.textContent = '';
                return;
            }

            let score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[@$!%*?&]/.test(password)) score++;

            if (score <= 2) {
                passwordStrengthMeter.className = 'strength-bar strength-weak';
                passwordStrengthText.textContent = 'Password Strength: Weak';
                passwordStrengthText.style.color = '#ef4444';
            } else if (score <= 4) {
                passwordStrengthMeter.className = 'strength-bar strength-medium';
                passwordStrengthText.textContent = 'Password Strength: Medium';
                passwordStrengthText.style.color = '#f59e0b';
            } else {
                passwordStrengthMeter.className = 'strength-bar strength-strong';
                passwordStrengthText.textContent = 'Password Strength: Strong';
                passwordStrengthText.style.color = '#10b981';
            }
        }

        // Event Listeners for Real-time Feedback
        nameInput.addEventListener('blur', validateName);
        emailInput.addEventListener('blur', validateEmail);
        mobileInput.addEventListener('blur', validateMobile);
        usernameInput.addEventListener('blur', validateUsername);
        
        passwordInput.addEventListener('input', () => {
            validatePassword();
            if (confirmPasswordInput.value) validateConfirmPassword();
        });
        
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);
        genderSelect.addEventListener('change', validateGender);

        // Form Submit Event Handler
        registerForm.addEventListener('submit', (e) => {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isMobileValid = validateMobile();
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();
            const isConfirmPasswordValid = validateConfirmPassword();
            const isGenderValid = validateGender();

            const isFormValid = isNameValid && isEmailValid && isMobileValid && 
                                isUsernameValid && isPasswordValid && 
                                isConfirmPasswordValid && isGenderValid;

            if (!isFormValid) {
                e.preventDefault(); // Stop form submission
                
                // Focus first invalid field
                const firstInvalid = registerForm.querySelector('.invalid-field');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });

        // Reset Button Handler
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                document.querySelectorAll('.error-msg').forEach(el => {
                    el.textContent = '';
                    el.style.display = 'none';
                });
                document.querySelectorAll('.invalid-field, .valid-field').forEach(el => {
                    el.classList.remove('invalid-field', 'valid-field');
                });
                if (passwordStrengthMeter && passwordStrengthText) {
                    passwordStrengthMeter.className = 'strength-bar';
                    passwordStrengthText.textContent = '';
                }
            });
        }
    }

    // Login Form Validation Setup
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        const identifierInput = document.getElementById('identifier');
        const passwordInput = document.getElementById('password');

        function setError(inputElement, errorElementId, message) {
            const errorSpan = document.getElementById(errorElementId);
            if (errorSpan) {
                errorSpan.textContent = message;
                errorSpan.style.display = message ? 'block' : 'none';
            }
            if (inputElement) {
                if (message) {
                    inputElement.classList.add('invalid-field');
                    inputElement.classList.remove('valid-field');
                } else {
                    inputElement.classList.remove('invalid-field');
                    inputElement.classList.add('valid-field');
                }
            }
            return !message;
        }

        function validateIdentifier() {
            const val = identifierInput.value.trim();
            if (!val) {
                return setError(identifierInput, 'identifierError', 'Please enter your username or email.');
            }
            return setError(identifierInput, 'identifierError', '');
        }

        function validatePassword() {
            const val = passwordInput.value;
            if (!val) {
                return setError(passwordInput, 'passwordError', 'Please enter your password.');
            }
            return setError(passwordInput, 'passwordError', '');
        }

        if (identifierInput) identifierInput.addEventListener('blur', validateIdentifier);
        if (passwordInput) passwordInput.addEventListener('blur', validatePassword);

        loginForm.addEventListener('submit', (e) => {
            const isIdentifierValid = validateIdentifier();
            const isPasswordValid = validatePassword();

            if (!isIdentifierValid || !isPasswordValid) {
                e.preventDefault();
                const firstInvalid = loginForm.querySelector('.invalid-field');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    }

    // Shared Field Error Helper
    function setFieldError(inputElement, errorElementId, message) {
        const errorSpan = document.getElementById(errorElementId);
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = message ? 'block' : 'none';
        }
        if (inputElement) {
            if (message) {
                inputElement.classList.add('invalid-field');
                inputElement.classList.remove('valid-field');
            } else {
                inputElement.classList.remove('invalid-field');
                inputElement.classList.add('valid-field');
            }
        }
        return !message;
    }

    // Edit Profile Form Validation Setup
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        const nameInput = document.getElementById('name');
        const mobileInput = document.getElementById('mobile');
        const genderSelect = document.getElementById('gender');

        const regexName = /^[A-Za-z ]{2,100}$/;
        const regexMobile = /^[6-9][0-9]{9}$/;

        function validateEditName() {
            const val = nameInput.value.trim();
            if (!val) return setFieldError(nameInput, 'nameError', 'Full Name is required.');
            if (!regexName.test(val)) return setFieldError(nameInput, 'nameError', 'Full Name must contain only letters and spaces (2 to 100 characters).');
            return setFieldError(nameInput, 'nameError', '');
        }

        function validateEditMobile() {
            const val = mobileInput.value.trim();
            if (!val) return setFieldError(mobileInput, 'mobileError', 'Mobile number is required.');
            if (!regexMobile.test(val)) return setFieldError(mobileInput, 'mobileError', 'Please enter a valid 10-digit Indian mobile number starting with 6-9.');
            return setFieldError(mobileInput, 'mobileError', '');
        }

        function validateEditGender() {
            const val = genderSelect.value;
            if (!val) return setFieldError(genderSelect, 'genderError', 'Please select a gender option.');
            return setFieldError(genderSelect, 'genderError', '');
        }

        if (nameInput) nameInput.addEventListener('blur', validateEditName);
        if (mobileInput) mobileInput.addEventListener('blur', validateEditMobile);
        if (genderSelect) genderSelect.addEventListener('change', validateEditGender);

        editProfileForm.addEventListener('submit', (e) => {
            const isNameOk = validateEditName();
            const isMobileOk = validateEditMobile();
            const isGenderOk = validateEditGender();

            if (!isNameOk || !isMobileOk || !isGenderOk) {
                e.preventDefault();
                const firstInvalid = editProfileForm.querySelector('.invalid-field');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    }

    // Change Password Form Validation Setup
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        const currentPassInput = document.getElementById('current_password');
        const newPassInput = document.getElementById('new_password');
        const confirmPassInput = document.getElementById('confirm_password');
        
        const strengthMeter = document.getElementById('passwordStrengthMeter');
        const strengthText = document.getElementById('passwordStrengthText');

        const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        function updatePasswordStrengthBar(password) {
            if (!strengthMeter || !strengthText) return;
            if (!password) {
                strengthMeter.className = 'strength-bar';
                strengthText.textContent = '';
                return;
            }

            let score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[@$!%*?&]/.test(password)) score++;

            if (score <= 2) {
                strengthMeter.className = 'strength-bar strength-weak';
                strengthText.textContent = 'Strength: Weak';
                strengthText.style.color = '#ef4444';
            } else if (score <= 4) {
                strengthMeter.className = 'strength-bar strength-medium';
                strengthText.textContent = 'Strength: Medium';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthMeter.className = 'strength-bar strength-strong';
                strengthText.textContent = 'Strength: Strong';
                strengthText.style.color = '#10b981';
            }
        }

        function validateCurrentPass() {
            const val = currentPassInput.value;
            if (!val) return setFieldError(currentPassInput, 'currentPasswordError', 'Please enter your current password.');
            return setFieldError(currentPassInput, 'currentPasswordError', '');
        }

        function validateNewPass() {
            const val = newPassInput.value;
            updatePasswordStrengthBar(val);
            if (!val) return setFieldError(newPassInput, 'newPasswordError', 'Please enter a new password.');
            if (!regexPassword.test(val)) return setFieldError(newPassInput, 'newPasswordError', 'New password must be at least 8 characters long with uppercase, lowercase, number & special char.');
            return setFieldError(newPassInput, 'newPasswordError', '');
        }

        function validateConfirmPass() {
            const newPass = newPassInput.value;
            const confirmPass = confirmPassInput.value;
            if (!confirmPass) return setFieldError(confirmPassInput, 'confirmPasswordError', 'Please confirm your new password.');
            if (confirmPass !== newPass) return setFieldError(confirmPassInput, 'confirmPasswordError', 'Passwords do not match.');
            return setFieldError(confirmPassInput, 'confirmPasswordError', '');
        }

        if (currentPassInput) currentPassInput.addEventListener('blur', validateCurrentPass);
        if (newPassInput) {
            newPassInput.addEventListener('input', () => {
                validateNewPass();
                if (confirmPassInput.value) validateConfirmPass();
            });
        }
        if (confirmPassInput) confirmPassInput.addEventListener('input', validateConfirmPass);

        changePasswordForm.addEventListener('submit', (e) => {
            const isCurrOk = validateCurrentPass();
            const isNewOk = validateNewPass();
            const isConfOk = validateConfirmPass();

            if (!isCurrOk || !isNewOk || !isConfOk) {
                e.preventDefault();
                const firstInvalid = changePasswordForm.querySelector('.invalid-field');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    }
});
