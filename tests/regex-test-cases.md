# Regular Expression (Regex) Test Cases Documentation

**Project**: COVID-19 Information and Awareness Website  
**Module**: Module 4 — User Registration & Regular Expression Validation  
**Date**: October 2026  

---

## 1. Implemented Regular Expression Patterns

| Field | Regex Pattern | Rules / Constraints |
| :--- | :--- | :--- |
| **Full Name** | `^[A-Za-z ]{2,100}$` | Alphabets and spaces only. Length between 2 and 100 characters. |
| **Email** | `^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$` | Standard email format containing username, `@`, domain name, and valid TLD. |
| **Mobile** | `^[6-9][0-9]{9}$` | Valid 10-digit Indian mobile number starting with digits 6, 7, 8, or 9. |
| **Username** | `^[A-Za-z0-9_]{3,30}$` | Alphanumeric characters and underscores only. Length between 3 and 30 characters. |
| **Password** | `^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$` | Minimum 8 characters; at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (`@$!%*?&`). |

---

## 2. Valid Regex Test Cases (Passing Inputs)

| Test ID | Field | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **REGEX-01** | Full Name | `Atharva Gholap` | Valid | Valid | **PASS** |
| **REGEX-02** | Email Address | `atharva@gmail.com` | Valid | Valid | **PASS** |
| **REGEX-03** | Mobile Number | `9876543210` | Valid | Valid | **PASS** |
| **REGEX-04** | Username | `atharva_99` | Valid | Valid | **PASS** |
| **REGEX-05** | Password | `Pass1234!` | Valid | Valid | **PASS** |

---

## 3. Invalid Regex Test Cases (Failing Inputs)

| Test ID | Field | Test Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **REGEX-06** | Full Name | `Atharva123` | Invalid (Digits not allowed) | Invalid | **PASS** |
| **REGEX-07** | Email Address | `atharva@gmail` | Invalid (Missing TLD domain suffix) | Invalid | **PASS** |
| **REGEX-08** | Mobile Number | `1234567890` | Invalid (Does not start with 6-9) | Invalid | **PASS** |
| **REGEX-09** | Username | `ab` | Invalid (Length < 3 characters) | Invalid | **PASS** |
| **REGEX-10** | Password | `password` | Invalid (Missing uppercase, digit, & special char) | Invalid | **PASS** |

---

## 4. Boundary Value Test Matrix

| Field | Boundary Scenario | Test Input | Expected Validation | Status |
| :--- | :--- | :--- | :--- | :---: |
| **Full Name** | Min Length Boundary (1 char) | `A` | **REJECTED** (Min 2 chars required) | **PASS** |
| **Full Name** | Min Length Valid (2 chars) | `Al` | **ACCEPTED** | **PASS** |
| **Full Name** | Max Length Boundary (100 chars) | 100 character name string | **ACCEPTED** | **PASS** |
| **Full Name** | Max Length Exceeded (101 chars) | 101 character name string | **REJECTED** | **PASS** |
| **Username** | Min Length Boundary (2 chars) | `us` | **REJECTED** (Min 3 chars required) | **PASS** |
| **Username** | Min Length Valid (3 chars) | `usr` | **ACCEPTED** | **PASS** |
| **Username** | Max Length Boundary (30 chars) | 30 character username string | **ACCEPTED** | **PASS** |
| **Username** | Max Length Exceeded (31 chars) | 31 character username string | **REJECTED** | **PASS** |
| **Password** | Min Length Boundary (7 chars) | `Pass12!` | **REJECTED** (Min 8 chars required) | **PASS** |
| **Password** | Min Length Valid (8 chars) | `Pass123!` | **ACCEPTED** | **PASS** |
| **Mobile** | Length Boundary (9 digits) | `987654321` | **REJECTED** (Exactly 10 digits required) | **PASS** |
| **Mobile** | Valid 10 digits | `9876543210` | **ACCEPTED** | **PASS** |
| **Mobile** | Length Exceeded (11 digits) | `98765432101` | **REJECTED** | **PASS** |
