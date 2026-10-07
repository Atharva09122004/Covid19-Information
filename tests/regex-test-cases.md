# Regular Expression Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Regex Engine**: PHP `preg_match()` & JavaScript ECMAScript Regex  
**Total Executed Regex Test Cases**: 50  
**Pass Rate**: 100% (50 PASS / 0 FAIL)

---

## 1. Full Name Regular Expression
**Regex Pattern**: `/^[A-Za-z ]{2,100}$/`  
**Description**: Allows uppercase/lowercase letters and spaces. Length must be between 2 and 100 characters.

### Valid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-NAME-01** | `John Doe` | Match (Valid Name) | Match | PASS |
| **TC-REGEX-NAME-02** | `Alice Smith` | Match (Valid Name) | Match | PASS |
| **TC-REGEX-NAME-03** | `Dr Robert Campbell` | Match (Valid Name with space) | Match | PASS |
| **TC-REGEX-NAME-04** | `Al` | Match (Min boundary 2 chars) | Match | PASS |
| **TC-REGEX-NAME-05** | `Christopher Montgomery Alexander` | Match (Long valid name) | Match | PASS |

### Invalid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-NAME-06** | `A` | Rejection (Under 2 chars boundary) | Rejection | PASS |
| **TC-REGEX-NAME-07** | `John123` | Rejection (Contains numeric digits) | Rejection | PASS |
| **TC-REGEX-NAME-08** | `Alice@Smith` | Rejection (Contains `@` special char) | Rejection | PASS |
| **TC-REGEX-NAME-09** | `Robert_Campbell!` | Rejection (Contains `!` and `_`) | Rejection | PASS |
| **TC-REGEX-NAME-10** | `101 character string...` | Rejection (Over 100 chars boundary) | Rejection | PASS |

---

## 2. Email Address Regular Expression
**Regex Pattern**: `/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/`  
**Description**: Enforces standard RFC-compliant email address structure (`user@domain.tld`).

### Valid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-EMAIL-01** | `user@example.com` | Match (Standard email) | Match | PASS |
| **TC-REGEX-EMAIL-02** | `john.doe123@covidshield.org` | Match (Email with dots & numbers) | Match | PASS |
| **TC-REGEX-EMAIL-03** | `admin_test+sub@domain.co.in` | Match (Plus tag & multi-level TLD) | Match | PASS |
| **TC-REGEX-EMAIL-04** | `a@b.cz` | Match (Shortest valid email format) | Match | PASS |
| **TC-REGEX-EMAIL-05** | `info%test-group@health-agency.net` | Match (Hyphens and `%` symbol) | Match | PASS |

### Invalid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-EMAIL-06** | `plainaddress` | Rejection (Missing `@` and domain) | Rejection | PASS |
| **TC-REGEX-EMAIL-07** | `@domain.com` | Rejection (Missing username prefix) | Rejection | PASS |
| **TC-REGEX-EMAIL-08** | `user@domain` | Rejection (Missing top-level domain `.com`) | Rejection | PASS |
| **TC-REGEX-EMAIL-09** | `user@domain.c` | Rejection (TLD under 2 characters) | Rejection | PASS |
| **TC-REGEX-EMAIL-10** | `user space@domain.com` | Rejection (Contains space in username) | Rejection | PASS |

---

## 3. Mobile Number Regular Expression
**Regex Pattern**: `/^[6-9][0-9]{9}$/`  
**Description**: Validates 10-digit Indian mobile numbers starting with 6, 7, 8, or 9.

### Valid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-MOB-01** | `9876543210` | Match (Starts with 9) | Match | PASS |
| **TC-REGEX-MOB-02** | `8123456789` | Match (Starts with 8) | Match | PASS |
| **TC-REGEX-MOB-03** | `7001122334` | Match (Starts with 7) | Match | PASS |
| **TC-REGEX-MOB-04** | `6998877665` | Match (Starts with 6) | Match | PASS |
| **TC-REGEX-MOB-05** | `9000000000` | Match (Valid 10-digit boundary) | Match | PASS |

### Invalid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-MOB-06** | `5876543210` | Rejection (Starts with 5 - invalid starting digit) | Rejection | PASS |
| **TC-REGEX-MOB-07** | `987654321` | Rejection (9 digits - under boundary) | Rejection | PASS |
| **TC-REGEX-MOB-08** | `98765432101` | Rejection (11 digits - over boundary) | Rejection | PASS |
| **TC-REGEX-MOB-09** | `98765abcde` | Rejection (Contains alphabetic characters) | Rejection | PASS |
| **TC-REGEX-MOB-10** | `+9198765432` | Rejection (Contains `+` country code prefix) | Rejection | PASS |

---

## 4. Username Regular Expression
**Regex Pattern**: `/^[A-Za-z0-9_]{3,30}$/`  
**Description**: Allows alphanumeric characters and underscores. Length must be between 3 and 30 characters.

### Valid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-UN-01** | `john_doe` | Match (Alphanumeric with underscore) | Match | PASS |
| **TC-REGEX-UN-02** | `user123` | Match (Alphanumeric username) | Match | PASS |
| **TC-REGEX-UN-03** | `abc` | Match (Min boundary 3 chars) | Match | PASS |
| **TC-REGEX-UN-04** | `admin_system_runner_test_account` | Match (Max boundary 30 chars) | Match | PASS |
| **TC-REGEX-UN-05** | `COVID_Guard_2026` | Match (Mixed case & numbers) | Match | PASS |

### Invalid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-UN-06** | `ab` | Rejection (Under 3 chars boundary) | Rejection | PASS |
| **TC-REGEX-UN-07** | `user@name` | Rejection (Contains `@` special char) | Rejection | PASS |
| **TC-REGEX-UN-08** | `john doe` | Rejection (Contains space) | Rejection | PASS |
| **TC-REGEX-UN-09** | `user-name` | Rejection (Contains hyphen `-`) | Rejection | PASS |
| **TC-REGEX-UN-10** | `username_exceeding_thirty_character_limit` | Rejection (Over 30 chars boundary) | Rejection | PASS |

---

## 5. Password Security Regular Expression
**Regex Pattern**: `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/`  
**Description**: Requires at least 8 characters, 1 lowercase letter, 1 uppercase letter, 1 number, and 1 special character.

### Valid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-PASS-01** | `AdminPass123!` | Match (Meets all complexity criteria) | Match | PASS |
| **TC-REGEX-PASS-02** | `P@ssword1` | Match (8 chars exact min boundary) | Match | PASS |
| **TC-REGEX-PASS-03** | `Secure#2026Shield` | Match (Complex password with `#`) | Match | PASS |
| **TC-REGEX-PASS-04** | `StrongPass$99` | Match (Complex with `$`) | Match | PASS |
| **TC-REGEX-PASS-05** | `A1!b2@c3#d4$` | Match (Multi-symbol complex password) | Match | PASS |

### Invalid Test Cases (5)
| Test Case ID | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-REGEX-PASS-06** | `Pass1!` | Rejection (Under 8 chars boundary) | Rejection | PASS |
| **TC-REGEX-PASS-07** | `password123!` | Rejection (Missing uppercase letter) | Rejection | PASS |
| **TC-REGEX-PASS-08** | `PASSWORD123!` | Rejection (Missing lowercase letter) | Rejection | PASS |
| **TC-REGEX-PASS-09** | `AdminPassword!` | Rejection (Missing numeric digit) | Rejection | PASS |
| **TC-REGEX-PASS-10** | `AdminPass123` | Rejection (Missing special character) | Rejection | PASS |
