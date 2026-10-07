# Boundary Value Analysis Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Boundary Test Cases**: 30  
**Pass Rate**: 100% (30 PASS / 0 FAIL)

---

## 1. Full Name Boundary Analysis
**Field Boundary Rule**: Min 2 characters, Max 100 characters.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-NAME-01** | `A` | Below Min (1 char) | Rejection ("Name must be at least 2 characters") | Validation error displayed | PASS |
| **TC-BVA-NAME-02** | `Al` | Min Boundary (2 chars) | Acceptance (Valid short name) | Accepted cleanly | PASS |
| **TC-BVA-NAME-03** | 100-character valid string | Max Boundary (100 chars) | Acceptance (Valid max length name) | Accepted cleanly | PASS |
| **TC-BVA-NAME-04** | 101-character valid string | Above Max (101 chars) | Rejection ("Name cannot exceed 100 characters") | Validation error displayed | PASS |

---

## 2. Username Boundary Analysis
**Field Boundary Rule**: Min 3 characters, Max 30 characters.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-UN-01** | `ab` | Below Min (2 chars) | Rejection ("Username must be at least 3 characters") | Validation error displayed | PASS |
| **TC-BVA-UN-02** | `abc` | Min Boundary (3 chars) | Acceptance (Valid short username) | Accepted cleanly | PASS |
| **TC-BVA-UN-03** | 30-character valid string | Max Boundary (30 chars) | Acceptance (Valid max length username) | Accepted cleanly | PASS |
| **TC-BVA-UN-04** | 31-character valid string | Above Max (31 chars) | Rejection ("Username cannot exceed 30 characters") | Validation error displayed | PASS |

---

## 3. Password Length Boundary Analysis
**Field Boundary Rule**: Min 8 characters required.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-PASS-01** | `Pass12!` (7 chars) | Below Min (7 chars) | Rejection ("Password must be at least 8 characters") | Validation error displayed | PASS |
| **TC-BVA-PASS-02** | `P@sswor1` (8 chars) | Min Boundary (8 chars) | Acceptance (Valid 8-character password) | Accepted cleanly | PASS |

---

## 4. Mobile Number Boundary Analysis
**Field Boundary Rule**: Exact 10 digits starting with 6-9.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-MOB-01** | `987654321` (9 digits) | Below Min (9 digits) | Rejection ("Mobile number must be 10 digits") | Validation error displayed | PASS |
| **TC-BVA-MOB-02** | `9876543210` (10 digits) | Min/Max Boundary (10 digits) | Acceptance (Valid 10-digit mobile) | Accepted cleanly | PASS |
| **TC-BVA-MOB-03** | `98765432101` (11 digits) | Above Max (11 digits) | Rejection ("Mobile number must be 10 digits") | Validation error displayed | PASS |

---

## 5. Comment Text Boundary Analysis
**Field Boundary Rule**: Min 3 characters, Max 1000 characters.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-COMM-01** | `Hi` (2 chars) | Below Min (2 chars) | Rejection ("Comment must be at least 3 characters") | Validation error displayed | PASS |
| **TC-BVA-COMM-02** | `Yes` (3 chars) | Min Boundary (3 chars) | Acceptance (Valid short comment) | Inserted into DB cleanly | PASS |
| **TC-BVA-COMM-03** | 1000-character valid text | Max Boundary (1000 chars) | Acceptance (Valid max comment length) | Inserted into DB cleanly | PASS |
| **TC-BVA-COMM-04** | 1001-character valid text | Above Max (1001 chars) | Rejection ("Comment cannot exceed 1000 characters") | Validation error displayed | PASS |

---

## 6. Contact Message Boundary Analysis
**Field Boundary Rule**: Min 10 characters, Max 2000 characters.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-CONT-01** | `Need help` (9 chars) | Below Min (9 chars) | Rejection ("Message must contain at least 10 characters") | Validation error displayed | PASS |
| **TC-BVA-CONT-02** | `Hello team` (10 chars) | Min Boundary (10 chars) | Acceptance (Valid 10-char message) | Submitted successfully | PASS |
| **TC-BVA-CONT-03** | 2000-character valid text | Max Boundary (2000 chars) | Acceptance (Valid 2000-char message) | Submitted successfully | PASS |
| **TC-BVA-CONT-04** | 2001-character valid text | Above Max (2001 chars) | Rejection ("Message cannot exceed 2000 characters") | Validation error displayed | PASS |

---

## 7. Search Query Length Boundary Analysis
**Field Boundary Rule**: Min 2 characters, Max 100 characters.

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-SRCH-01** | `""` (0 chars) | Empty query | Display search prompt banner | Prompt banner displayed | PASS |
| **TC-BVA-SRCH-02** | `"A"` (1 char) | Below Min (1 char) | Rejection ("Search query must be at least 2 characters") | Validation error displayed | PASS |
| **TC-BVA-SRCH-03** | `"Up"` (2 chars) | Min Boundary (2 chars) | Execution (Executes search query) | Search results loaded | PASS |
| **TC-BVA-SRCH-04** | 100-character search string | Max Boundary (100 chars) | Execution (Executes search query) | Search results loaded | PASS |
| **TC-BVA-SRCH-05** | 101-character search string | Above Max (101 chars) | Rejection ("Search query cannot exceed 100 characters") | Validation error displayed | PASS |

---

## 8. Statistics Metric Boundary Analysis
**Field Boundary Rule**: Non-negative integers (0 to 2,147,483,647).

| Test Case ID | Test Input | Boundary Level | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BVA-STAT-01** | Cases = `-1` | Negative input | Rejection ("Metric values cannot be negative") | Validation error displayed | PASS |
| **TC-BVA-STAT-02** | Cases = `0` | Zero min boundary | Acceptance (Zero count statistical record) | Accepted & stored cleanly | PASS |
| **TC-BVA-STAT-03** | Cases = `50000000` | Typical large integer | Acceptance (Large valid statistics count) | Accepted & stored cleanly | PASS |
| **TC-BVA-STAT-04** | Cases = `2147483647` | INT Max Boundary | Acceptance (Max 32-bit signed integer) | Stored without overflow | PASS |
