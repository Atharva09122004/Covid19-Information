# Security Test Cases Document (Module 12)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Security Test Cases**: 15  
**Pass Rate**: 100% (15 PASS / 0 FAIL)

---

## Security Audit & Vulnerability Testing

| Test ID | Security Category | Attack Scenario / Payload Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SEC-001** | Authentication | Wrong password attempt on valid user | Rejects login with generic error message: "Invalid username/email or password" | Rejection message displayed | PASS |
| **TC-SEC-002** | Authentication | Unauthenticated user accesses protected `dashboard.php` | Access denied, redirected to `login.php?error=auth_required` | Redirected to login page | PASS |
| **TC-SEC-003** | Authorization | Normal user accesses admin route `admin/dashboard.php` | Access denied, redirected to `dashboard.php?error=admin_required` | Redirected to user dashboard | PASS |
| **TC-SEC-004** | Authorization | User A edits comment ID 5 authored by User B (IDOR) | Rejects request: "You are not authorized to modify that comment" | Rejection error displayed | PASS |
| **TC-SEC-005** | Authorization | User A deletes comment ID 5 authored by User B (IDOR) | Rejects request: "You are not authorized to modify that comment" | Rejection error displayed | PASS |
| **TC-SEC-006** | SQL Injection | Login identifier field: `' OR '1'='1` | PDO prepared statement parameter binding treats input as literal string | Authentication fails cleanly | PASS |
| **TC-SEC-007** | SQL Injection | Search input: `Vaccine' UNION SELECT 1,2,3--` | PDO prepared statement parameter binding prevents SQL syntax injection | Executed safely, 0 SQL errors | PASS |
| **TC-SEC-008** | XSS Defense | Comment text payload: `<script>alert('XSS')</script>` | Output escaped via `htmlspecialchars(ENT_QUOTES, 'UTF-8')` | Script tag displayed as text, no alert | PASS |
| **TC-SEC-009** | XSS Defense | Contact message payload: `<img src=x onerror=alert(1)>` | Sanitized prior to rendering in admin panel | Rendered safely as plain text string | PASS |
| **TC-SEC-010** | Password Security | Direct database query on `users` table | Password column stores `$2y$10$...` BCRYPT hash, zero plaintext | All passwords stored as BCRYPT hashes | PASS |
| **TC-SEC-011** | Password Security | Login password comparison via `password_verify()` API | Password verified against hash using native PHP timing-safe API | `password_verify()` executed correctly | PASS |
| **TC-SEC-012** | Session Security | Session creation upon successful user login | `session_regenerate_id(true)` issued to prevent session fixation attacks | New session ID generated on login | PASS |
| **TC-SEC-013** | Session Security | Session destruction upon user logout | `session_unset()`, `session_destroy()`, session cookie cleared | Session state destroyed, cookies cleared | PASS |
| **TC-SEC-014** | Input Validation | Server-side form validation with JavaScript disabled | Server rejects invalid data regardless of client-side validation | Server enforced validation rules | PASS |
| **TC-SEC-015** | Error Handling | Database exception triggered during operation | `try-catch` block catches exception, logs internally, hides stack trace | Generic user message shown, stack trace hidden | PASS |
