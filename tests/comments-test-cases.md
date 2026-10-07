# MODULE 7 — COMMENTS & FEEDBACK TEST CASES DOCUMENTATION

**Project:** COVID-19 Information and Awareness Website  
**Module:** Module 7 (Comments and Feedback)

---

## 1. Functional & Authorization Test Cases (TC-COM-01 to TC-COM-12)

| Test Case ID | Test Scenario | Input Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-COM-01** | Logged-in user adds valid comment | Comment: `"Very informative website! Helpful resources."` | Comment created, success alert displayed | Comment added & rendered | **PASS** |
| **TC-COM-02** | Empty comment | Comment: `""` | Display: `"Comment must contain at least 3 characters."` | Validation error displayed | **PASS** |
| **TC-COM-03** | Comment with only spaces | Comment: `"   "` | Input trimmed; validation error displayed | Validation error displayed | **PASS** |
| **TC-COM-04** | Comment shorter than 3 characters | Comment: `"Hi"` | Display: `"Comment must contain at least 3 characters."` | Validation error displayed | **PASS** |
| **TC-COM-05** | Comment longer than 1000 characters | Comment: `A` x 1001 characters | Display: `"Comment cannot exceed 1000 characters."` | Validation error displayed | **PASS** |
| **TC-COM-06** | User edits own comment | Update comment text | Comment updated in DB, `(Edited)` badge displayed | Comment updated successfully | **PASS** |
| **TC-COM-07** | User deletes own comment | Click Delete on own comment | Comment removed from DB and feed | Comment deleted successfully | **PASS** |
| **TC-COM-08** | User attempts to edit another user's comment | Access `edit-comment.php?id=VictimCommentID` | Access denied; redirect to `comments.php?error=unauthorized` | Access denied | **PASS** |
| **TC-COM-09** | User attempts to delete another user's comment | POST `id=VictimCommentID` to `delete-comment.php` | Access denied; query fails `rowCount() == 0` | Deletion denied | **PASS** |
| **TC-COM-10** | XSS payload entered | Comment: `<script>alert('XSS')</script>` | Input escaped via `htmlspecialchars()`; displayed as text | Script rendered as plain text | **PASS** |
| **TC-COM-11** | SQL injection-style input | Comment: `' OR '1'='1'; DROP TABLE comments;--` | Input stored literally via PDO prepared statement | Input stored as literal text | **PASS** |
| **TC-COM-12** | Guest attempts to submit comment | Guest visits `comments.php` or submits POST | Submit form hidden for guests; POST redirects to `login.php` | Redirected to login | **PASS** |

---

## 2. Boundary Testing Matrix

| Field | Tested Value | Length | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Comment** | `"Ab"` | 2 characters | Invalid | Error displayed | **PASS** |
| **Comment** | `"Abc"` | 3 characters | Valid | Comment added | **PASS** |
| **Comment** | `A` x 1000 | 1000 characters | Valid | Comment added | **PASS** |
| **Comment** | `A` x 1001 | 1001 characters | Invalid | Error displayed | **PASS** |
