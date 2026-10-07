# Negative Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Negative Test Cases**: 25  
**Pass Rate**: 100% (25 PASS / 0 FAIL)

---

## Negative Test Case Suite

| Test Case ID | Feature / Module | Negative Input Condition | Expected Behavior | Actual Behavior | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-NEG-001** | Registration | All form fields submitted empty | Graceful rejection with "All fields are required" error | Form error banner displayed cleanly | PASS |
| **TC-NEG-002** | Registration | Invalid email string (`john.doe@com`) | Rejects invalid TLD format, preserves form input | Validation error displayed, form preserved | PASS |
| **TC-NEG-003** | Registration | Invalid Indian mobile starting digit (`4876543210`) | Rejects invalid mobile prefix starting digit | Validation error displayed | PASS |
| **TC-NEG-004** | Registration | Weak password without uppercase (`weakpass123!`) | Rejects password missing uppercase letter | Validation error displayed | PASS |
| **TC-REG-005** | Registration | Password & confirm password mismatch | Rejects mismatch: "Passwords do not match" | Mismatch error displayed | PASS |
| **TC-NEG-006** | Registration | Duplicate email registration attempt | Rejects duplicate email address registration | "Email already registered" error shown | PASS |
| **TC-NEG-007** | Registration | Duplicate username registration attempt | Rejects duplicate username registration | "Username already taken" error shown | PASS |
| **TC-NEG-008** | Authentication | Wrong password for valid user | Rejects authentication cleanly without revealing user existence details | "Invalid username/email or password" error | PASS |
| **TC-NEG-009** | Authentication | Non-existent user login attempt | Rejects authentication with generic error message | "Invalid username/email or password" error | PASS |
| **TC-NEG-010** | COVID Info | Invalid category string in GET request (`?category=<script>`) | Sanitizes GET parameter, falls back to "All" category view | Safe fallback executed, no error thrown | PASS |
| **TC-NEG-011** | COVID Statistics | Invalid country string in GET request (`?country=InvalidCountry`) | Displays notice banner, falls back to All countries | Notice banner rendered cleanly | PASS |
| **TC-NEG-012** | COVID Statistics | Negative case numbers in Admin form (`cases = -100`) | Rejects negative case count: "Metrics cannot be negative" | Validation error displayed | PASS |
| **TC-NEG-013** | Dashboard | Unauthenticated direct access to `dashboard.php` | Denies access, redirects to `login.php?error=auth_required` | Redirected to login page cleanly | PASS |
| **TC-NEG-014** | Admin Portal | Normal user direct access to `admin/dashboard.php` | Denies access, redirects to `dashboard.php?error=admin_required` | Redirected to user dashboard cleanly | PASS |
| **TC-NEG-015** | Admin Management| Admin attempts self-demotion to normal user | Blocks self-demotion: "You cannot remove your own admin privileges" | Self-demotion blocked | PASS |
| **TC-NEG-016** | Comments (IDOR)| User A submits POST request to edit User B's comment | Denies authorization: "You are not authorized to modify that comment" | Rejection message displayed | PASS |
| **TC-NEG-017** | Comments (IDOR)| User A submits POST request to delete User B's comment | Denies authorization: "You are not authorized to modify that comment" | Rejection message displayed | PASS |
| **TC-NEG-018** | Comments | Empty comment text submission | Rejects empty comment: "Comment cannot be empty" | Validation error displayed | PASS |
| **TC-NEG-019** | Comments | Guest submits comment POST request directly | Denies submission, redirects to `login.php?error=auth_required` | Redirected to login page | PASS |
| **TC-NEG-020** | Search | Search query containing SQL syntax (`' OR '1'='1`) | Parameterized query executes literal string match without SQL syntax error | Executed safely, 0 SQL errors | PASS |
| **TC-NEG-021** | Search | Search query containing HTML/JS script | Escapes output string via `htmlspecialchars()` | Escaped string rendered, JS prevented | PASS |
| **TC-NEG-022** | Contact Us | Empty message text in Contact Us form | Rejects submission: "Message is required" | Validation error displayed | PASS |
| **TC-NEG-023** | Contact Us | Message length under 10 characters (`"Hello"`) | Rejects message: "Message must contain at least 10 characters" | Validation error displayed | PASS |
| **TC-NEG-024** | Profile Edit | User submits numeric string in Full Name (`"User123"`) | Rejects name: "Name must contain only letters and spaces" | Validation error displayed | PASS |
| **TC-NEG-025** | Profile Edit | User submits illegal gender option (`"Alien"`) | Rejects invalid option: "Please select a valid gender option" | Validation error displayed | PASS |
