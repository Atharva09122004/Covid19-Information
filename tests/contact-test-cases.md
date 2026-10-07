# MODULE 10 — CONTACT US TEST CASES DOCUMENTATION

**Project:** COVID-19 Information and Awareness Website  
**Module:** Module 10 (Contact Us)

---

## 1. Functional & Security Test Cases (TC-CONTACT-01 to TC-CONTACT-11)

| Test Case ID | Test Scenario | Input Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-CONTACT-01** | Valid contact submission | Name: `"John Doe"`, Email: `"john@example.com"`, Subject: `"Inquiry"`, Message: `"Hello, I have a question about guidelines."` | Message submitted, stored in DB, success alert displayed | Message submitted | **PASS** |
| **TC-CONTACT-02** | Empty name | Name: `""` | Display: `"Full Name is required."` | Validation error displayed | **PASS** |
| **TC-CONTACT-03** | Invalid email | Email: `"invalidemail"` | Display: `"Please enter a valid email address."` | Validation error displayed | **PASS** |
| **TC-CONTACT-04** | Empty subject | Subject: `""` | Display: `"Subject is required."` | Validation error displayed | **PASS** |
| **TC-CONTACT-05** | Message < 10 characters | Message: `"Too short"` (9 chars) | Display: `"Message must contain at least 10 characters."` | Validation error displayed | **PASS** |
| **TC-CONTACT-06** | Message > 2000 characters | Message: `A` x 2001 chars | Display: `"Message cannot exceed 2000 characters."` | Validation error displayed | **PASS** |
| **TC-CONTACT-07** | SQL injection input | Subject/Message: `' OR '1'='1; DROP TABLE contact_messages;--` | Input stored literally via PDO prepared statement | Input stored safely | **PASS** |
| **TC-CONTACT-08** | XSS input | Message: `<script>alert('XSS')</script>` | Input escaped via `htmlspecialchars()`; displayed as text | Displayed as plain text | **PASS** |
| **TC-CONTACT-09** | Guest submission | Guest opens `contact.php` & submits | Allowed without authentication | Allowed cleanly | **PASS** |
| **TC-CONTACT-10** | Admin views submitted message | Admin opens `admin/contact-messages.php` | Submitted message rendered in admin table | Message displayed | **PASS** |
| **TC-CONTACT-11** | Admin deletes message | Admin clicks Delete on message | Message removed from DB | Message deleted | **PASS** |

---

## 2. Boundary Testing Matrix

| Field | Tested Value | Length | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Message** | `"123456789"` | 9 characters | Invalid | Error displayed | **PASS** |
| **Message** | `"1234567890"` | 10 characters | Valid | Message submitted | **PASS** |
| **Message** | `A` x 2000 | 2000 characters | Valid | Message submitted | **PASS** |
| **Message** | `A` x 2001 | 2001 characters | Invalid | Error displayed | **PASS** |
| **Name** | `"A"` | 1 character | Invalid | Error displayed | **PASS** |
| **Name** | `"Ab"` | 2 characters | Valid | Message submitted | **PASS** |
| **Email** | `"user@example.com"` | Valid format | Valid | Accepted | **PASS** |
| **Email** | `"user@example"` | Invalid format | Invalid | Error displayed | **PASS** |
