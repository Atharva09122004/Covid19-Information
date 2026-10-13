# 🧪 Software Testing & Quality Assurance (STQA) Suite

This directory contains the complete automated test runners and formal test case specifications for the **COVID-19 Information and Awareness Website**.

---

## 📊 Testing Executive Metrics

| Metric | Empirical Result |
| :--- | :---: |
| **Total Master Test Cases** | **134** |
| **Executed Test Cases** | **134** |
| **Passed Test Cases** | **134** |
| **Failed Test Cases** | **0** |
| **Blocked Test Cases** | **0** |
| **Pass Percentage** | **100.0%** |

---

## 🏃 Running Automated Test Suites

Ensure your local Apache server and MySQL instance are running via XAMPP, then execute the automated test suites using the PHP CLI:

```bash
# Execute Full Master Test Runner (Modules 1 - 10)
php tests/master_test_runner.php

# Execute Module 5 (Authentication & Sessions) Runner
php tests/module5_test_runner.php

# Execute Module 6 (Dashboard & Profile Management) Runner
php tests/module6_test_runner.php

# Execute Modules 7 & 8 (Comments & Search) Runner
php tests/modules_7_8_test_runner.php

# Execute Modules 9 & 10 (Admin Portal & Contact Us) Runner
php tests/modules_9_10_test_runner.php
```

---

## 📑 Test Specification Documents Index

| Document | Focus & Scope | Cases Count |
| :--- | :--- | :---: |
| [`master-test-cases.md`](master-test-cases.md) | Comprehensive test case master covering all 10 modules + Security + Regression | **134** |
| [`regex-test-cases.md`](regex-test-cases.md) | Unit-level regex test cases (Name, Email, Mobile, Username, Password) across valid, invalid, boundary, and special character inputs | **50** |
| [`boundary-test-cases.md`](boundary-test-cases.md) | Boundary Value Analysis (BVA) testing minimum, below min, max, and above max boundaries | **30** |
| [`negative-test-cases.md`](negative-test-cases.md) | Negative testing evaluating graceful failures, bad inputs, and error recovery | **25** |
| [`integration-test-cases.md`](integration-test-cases.md) | Cross-module integration workflows (Registration -> Login -> Dashboard -> Database) | **10** |
| [`system-test-cases.md`](system-test-cases.md) | End-to-end full system workflows for both Public Visitors and Administrators | **2** |
| [`regression-test-cases.md`](regression-test-cases.md) | Regression suite ensuring later modules did not disrupt earlier functionality | **10** |
| [`security-test-cases.md`](security-test-cases.md) | Application security audits (SQL Injection, XSS, IDOR, Session Fixation, Hashing) | **15** |
| [`comments-test-cases.md`](comments-test-cases.md) | Focused functional and authorization test cases for community comments | **12** |
| [`search-test-cases.md`](search-test-cases.md) | Focused keyword search engine queries and escaping test cases | **10** |
| [`admin-test-cases.md`](admin-test-cases.md) | Administrative portal CRUD, role management, and moderation test cases | **20** |
| [`contact-test-cases.md`](contact-test-cases.md) | Contact form submission, validation, and inbox reflection test cases | **10** |
