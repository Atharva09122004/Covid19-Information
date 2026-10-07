# MODULE 8 — SEARCH MODULE TEST CASES DOCUMENTATION

**Project:** COVID-19 Information and Awareness Website  
**Module:** Module 8 (Search)

---

## 1. Functional & Security Test Cases (TC-SEARCH-01 to TC-SEARCH-10)

| Test Case ID | Test Scenario | Input Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SEARCH-01** | Search `"symptoms"` | `q=symptoms` | Displays matching symptoms articles | Symptoms cards displayed | **PASS** |
| **TC-SEARCH-02** | Search `"prevention"` | `q=prevention` | Displays matching prevention articles | Prevention cards displayed | **PASS** |
| **TC-SEARCH-03** | Search non-existent term | `q=xyznonexistent` | Display: `"No matching COVID-19 information found."` | No-results message displayed | **PASS** |
| **TC-SEARCH-04** | Empty search | `q=` | Display: `"Enter a keyword to search."` (No DB query) | Search prompt displayed | **PASS** |
| **TC-SEARCH-05** | Search with surrounding spaces | `q=   symptoms   ` | Input trimmed; matches `"symptoms"` | Matching results displayed | **PASS** |
| **TC-SEARCH-06** | Very long search query (>100 chars) | `q=` x 120 chars | Input capped at 100 characters; processed safely | Truncated & executed safely | **PASS** |
| **TC-SEARCH-07** | Special characters in search | `q=%_$#@!` | Input handled safely via PDO prepared statement | No error; handled safely | **PASS** |
| **TC-SEARCH-08** | SQL Injection-style input | `q=' OR '1'='1` | PDO prepared statement treats input as literal string; no SQL injection | Handled safely | **PASS** |
| **TC-SEARCH-09** | XSS-style search input | `q=<script>alert('XSS')</script>` | Input escaped via `htmlspecialchars()`; displayed as plain text | Displayed as plain text | **PASS** |
| **TC-SEARCH-10** | Case-insensitive search | `q=SYMPTOMS` / `q=symptoms` | Matching results returned regardless of letter case | Matching results displayed | **PASS** |

---

## 2. Boundary Testing Matrix

| Field | Tested Value | Length | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Search Query** | `"a"` | 1 character | Valid search executed | Results displayed | **PASS** |
| **Search Query** | `A` x 100 | 100 characters | Valid search executed | Handled safely | **PASS** |
| **Search Query** | `A` x 101 | 101 characters | Capped at 100 characters | Truncated & executed | **PASS** |
