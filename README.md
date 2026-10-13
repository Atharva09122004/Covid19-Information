# 🛡️ COVID-19 Information & Awareness Portal

<p align="center">
  <strong>A full-stack, secure, and production-ready public health awareness platform demonstrating modern web engineering, OWASP security principles, and comprehensive Software Testing & Quality Assurance (STQA).</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0%2B-777bb4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.0+">
  <img src="https://img.shields.io/badge/MySQL-5.7%2B-4479a1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Architecture-PDO%20%7C%20RBAC-0284c7?style=for-the-badge" alt="PDO RBAC">
  <img src="https://img.shields.io/badge/STQA%20Tests-134%20Passed%20(100%25)-10b981?style=for-the-badge&logo=checkmarx&logoColor=white" alt="Tests Passed">
  <img src="https://img.shields.io/badge/Security-OWASP%20Hardened-ef4444?style=for-the-badge&logo=securityscorecard&logoColor=white" alt="Security Hardened">
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="MIT License">
</p>

---

## 📖 Table of Contents

- [Overview](#-overview)
- [Project Objectives](#-project-objectives)
- [Technology Stack](#-technology-stack)
- [Key Features & Modules](#-key-features--modules)
- [System Architecture](#-system-architecture)
- [Repository Directory Structure](#-repository-directory-structure)
- [Security Architecture](#-security-architecture)
- [Regular Expression Validation](#-regular-expression-validation)
- [Database Schema & Setup](#-database-schema--setup)
- [XAMPP Setup & Local Execution](#-xampp-setup--local-execution)
- [Default Login Credentials](#-default-login-credentials)
- [Software Testing & QA Suite](#-software-testing--qa-suite)
- [Academic Documentation Suite](#-academic-documentation-suite)
- [Known Limitations](#-known-limitations)
- [Future Enhancements](#-future-enhancements)
- [License](#-license)

---

## 🌐 Overview

The **COVID-19 Information and Awareness Website** is an educational, interactive web application engineered to provide verified health guidelines, symptom recognition, prevention protocols, real-time national and global statistics, community discussion boards, and an administrative management portal.

Built using native PHP and MySQL under XAMPP, this project serves both as an academic capstone and as a production-grade software engineering portfolio project. The codebase is organized into a clean, modular architecture with clear separation between application configuration, middleware helpers, public-facing pages, administrative controllers, database migrations, and testing suites.

---

## 🎯 Project Objectives

1. **Disseminate Verified Information**: Deliver reliable, categorized COVID-19 health data (symptoms, prevention, vaccines, safety guidelines).
2. **Interactive Statistical Tracking**: Present national and global COVID-19 metrics with sorting, search, and dynamic aggregation.
3. **Secure User Authentication**: Implement session-based authentication with cryptographic password hashing, session regeneration, and role-based access control.
4. **Community Feedback**: Enable authenticated users to post, edit, and delete their own comments with strict ownership validation (anti-IDOR).
5. **Administrative Management**: Provide a protected administrator portal for managing users, moderating comments, editing educational content, and reviewing visitor messages.
6. **Defensive Software Engineering**: Enforce OWASP Top 10 defenses including SQL injection prevention via PDO prepared statements, XSS escaping, and regex validation.
7. **Empirical Quality Assurance**: Validate system reliability through 134 formal test cases across unit, integration, boundary, security, and regression test suites.

---

## 💻 Technology Stack

| Layer | Technologies & Tools |
| :--- | :--- |
| **Backend** | PHP 8.0+ (PDO, Sessions, BCRYPT password hashing, regex engine) |
| **Frontend** | HTML5 (Semantic), Vanilla CSS3 (Custom Design System, Flexbox, Grid), JavaScript (ES6) |
| **Database** | MySQL 5.7+ / MariaDB 10.4+ (InnoDB engine, utf8mb4 charset, Foreign Key Constraints) |
| **Local Environment** | XAMPP for Windows (Apache 2.4, MySQL 5.7+, phpMyAdmin) |
| **Version Control & CI** | Git, GitHub Actions (Automated syntax linting and integrity checks) |
| **Architecture** | Modular MVC-inspired structure with central helper resolvers (`url()`, `asset()`) |

---

## ✨ Key Features & Modules

### 1. Public Visitor Modules
- **Module 1 — Home Page**: Modern responsive landing page with hero illustration, prevention highlights, database connectivity monitor, and call-to-actions.
- **Module 2 — COVID-19 Information**: Educational knowledge base with category filter tabs (*Overview*, *Symptoms*, *Prevention*, *Safety*, *Vaccination*, *Treatment*).
- **Module 3 — COVID-19 Statistics**: Interactive data table displaying confirmed cases, recoveries, active cases, and mortality rates with country filtering and sort controls.
- **Module 8 — Keyword Search**: Instant keyword search querying titles, descriptions, and categories with SQL LIKE prepared statements.
- **Module 10 — Contact Us**: Visitor inquiry form with server-side regex validation, XSS prevention, and automated pre-fill for logged-in users.

### 2. User Authentication & Profile Modules
- **Module 4 — User Registration**: Form validation enforcing dual-layer regular expressions (Name, Email, 10-digit Indian mobile, Username, Complex Password).
- **Module 5 — Login, Logout & Sessions**: BCRYPT password verification, `session_regenerate_id()`, no-cache HTTP headers, and secure cookie cleanup.
- **Module 6 — User Dashboard & Profile**: User dashboard displaying membership metrics, profile editing (Name, Mobile, Gender), and password updates.
- **Module 7 — Community Comments**: Interactive discussion board allowing authenticated users to post feedback, with strict ownership checks for edit and delete actions.

### 3. Administrator Subsystem
- **Module 9 — Admin Portal**: Role-protected dashboard (`admin` role required) featuring metric summary counters, user role assignment (*user* ↔ *admin*), self-demotion protection, COVID information CRUD, statistics CRUD, comment moderation, and contact inbox review.

---

## 🏛️ System Architecture

```text
                               ┌────────────────────────────────┐
                               │   Client Layer (Web Browser)   │
                               │  HTML5 • Vanilla CSS3 • ES6 JS │
                               └───────────────┬────────────────┘
                                               │ HTTP / HTTPS
                                               ▼
                               ┌────────────────────────────────┐
                               │      Apache HTTP Server        │
                               │        (XAMPP Runtime)         │
                               └───────────────┬────────────────┘
                                               │
                                               ▼
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                                Application Logic Layer                                 │
│                                                                                        │
│   ├── Helper Resolver (app/helpers/helpers.php: url(), asset(), escape())              │
│   ├── Authentication Middleware (app/includes/auth.php, app/includes/admin-auth.php)   │
│   ├── Regular Expression Engine (Preg Match Validation on 5 Core Fields)               │
│   ├── Cryptographic Security (password_hash BCRYPT, password_verify)                   │
│   ├── Output Sanitization (htmlspecialchars for XSS Prevention)                        │
│   └── Database Access Layer (PHP Data Objects - PDO with Parameterized Statements)     │
└──────────────────────────────────────────────┬─────────────────────────────────────────┘
                                               │
                                               ▼
                               ┌────────────────────────────────┐
                               │     Database Layer (MySQL)     │
                               │   Database: covid19_db (InnoDB) │
                               │  users • covid_info • stats    │
                               │  comments • contact_messages   │
                               └────────────────────────────────┘
```

---

## 📂 Repository Directory Structure

```text
Covid19-Information/
├── .github/
│   └── workflows/
│       └── ci.yml                      # GitHub Actions automated PHP syntax linting CI
├── admin/                              # Administrator Control Portal (Role Protected)
│   ├── includes/
│   │   ├── admin-header.php            # Administrative navigation bar
│   │   └── admin-footer.php            # Administrative portal footer
│   ├── comments.php                    # Community comments moderation
│   ├── contact-messages.php            # Visitor contact messages inbox
│   ├── covid-info.php                  # COVID information CRUD manager
│   ├── dashboard.php                   # Summary dashboard & system metrics
│   ├── statistics.php                  # Country statistics CRUD manager
│   ├── users.php                       # User management & role promotion/demotion
│   └── README.md                       # Admin subsystem guide
├── app/                                # Application Core Backend Logic
│   ├── config/
│   │   ├── database.php                # Active PDO database connection
│   │   └── database.example.php        # Environment template (git-safe)
│   ├── helpers/
│   │   └── helpers.php                 # Dynamic URL, asset, and escaping helpers
│   └── includes/
│       ├── admin-auth.php              # Administrator authorization middleware
│       ├── auth.php                    # User authentication middleware
│       ├── footer.php                  # Shared public footer component
│       └── header.php                  # Shared public header & navigation
├── database/
│   ├── database.sql                    # Full SQL schema, constraints & seed data
│   └── README.md                       # Database setup & migration instructions
├── docs/                               # Project Academic Documentation & STQA Reports
│   ├── bug-report.md                   # Formal defect tracking log (0 open defects)
│   ├── project-documentation.md        # Comprehensive 33-section project report
│   ├── project-explanation.md          # 19-question evaluation presentation script
│   ├── testing-summary.md              # Quality assurance metrics & test coverage
│   ├── viva-questions.md               # 50 oral viva voce questions and answers
│   └── README.md                       # Academic documentation guide
├── public/                             # Public Web Assets & Sub-pages
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css               # Complete Vanilla CSS design system
│   │   ├── images/
│   │   │   ├── hero-illustration.jpg   # Medical illustration asset
│   │   │   └── hero-shield.jpg         # Health protection shield graphic
│   │   └── js/
│   │       ├── main.js                 # Mobile menu & interactive UI handlers
│   │       └── validation.js           # Client-side regular expression engine
│   └── pages/
│       ├── auth/
│       │   ├── change-password.php     # Password update controller
│       │   ├── login.php               # User login controller
│       │   ├── logout.php              # Session destruction controller
│       │   └── register.php            # Regex-validated account registration
│       ├── community/
│       │   ├── comments.php            # Discussion board & feedback feed
│       │   ├── delete-comment.php      # IDOR-protected comment deletion
│       │   └── edit-comment.php        # IDOR-protected comment modification
│       ├── covid/
│       │   ├── covid-info.php          # Categorized health guidelines
│       │   ├── search.php              # Keyword search controller
│       │   └── statistics.php          # Regional & national statistics
│       ├── user/
│       │   ├── dashboard.php           # User account dashboard
│       │   └── edit-profile.php        # Self-service profile modification
│       └── contact.php                 # Public contact & inquiry form
├── tests/                              # Software Testing & Quality Assurance Suite
│   ├── admin-test-cases.md             # Admin portal test specifications (20 cases)
│   ├── boundary-test-cases.md          # Boundary Value Analysis matrix (30 cases)
│   ├── comments-test-cases.md          # Comment CRUD test specifications (12 cases)
│   ├── contact-test-cases.md           # Contact form test specifications (10 cases)
│   ├── integration-test-cases.md       # Integration workflow specifications (10 cases)
│   ├── master-test-cases.md            # Comprehensive test matrix (134 cases)
│   ├── negative-test-cases.md          # Negative input specifications (25 cases)
│   ├── regex-test-cases.md             # Regex verification tests (50 cases)
│   ├── regression-test-cases.md        # Regression verification suite (10 cases)
│   ├── search-test-cases.md            # Search engine test specifications (10 cases)
│   ├── security-test-cases.md          # OWASP vulnerability tests (15 cases)
│   ├── system-test-cases.md            # End-to-end full system scenarios (2 cases)
│   ├── master_test_runner.php          # Master automated test runner
│   ├── module5_test_runner.php         # Module 5 authentication runner
│   ├── module6_test_runner.php         # Module 6 dashboard runner
│   ├── modules_7_8_test_runner.php     # Modules 7 & 8 comments/search runner
│   ├── modules_9_10_test_runner.php    # Modules 9 & 10 admin/contact runner
│   └── README.md                       # Test execution manual
├── .gitattributes                      # Git line ending normalization
├── .gitignore                          # Git ignore rules for logs, secrets, and caches
├── composer.json                       # Project metadata & test execution scripts
├── index.php                           # Root portal entry point
├── LICENSE                             # MIT Open Source License
└── README.md                           # Master project documentation
```

---

## 🔒 Security Architecture

| Security Category | Threat Addressed | Implementation Defense |
| :--- | :--- | :--- |
| **SQL Injection (SQLi)** | Unauthorized DB query manipulation | 100% of queries use **PDO Prepared Statements** with parameterized inputs (`:placeholder` or `?`). Raw string concatenation in queries is strictly banned. |
| **Cross-Site Scripting (XSS)** | Malicious script execution in browser | Dynamic output is sanitized using `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` through `escape()` helper prior to rendering. |
| **Password Storage** | Credential breaches | Passwords are cryptographically hashed using PHP's native `password_hash($pass, PASSWORD_BCRYPT)`, producing secure 60-character one-way hashes. |
| **Authentication Verification** | Timing attacks & password forgery | Password authentication uses constant-time `password_verify()`. |
| **Session Fixation** | Stolen pre-login session IDs | `session_regenerate_id(true)` is executed immediately upon successful user login. |
| **IDOR Protection** | Unauthorized editing of records | The active user identity is strictly derived from `$_SESSION['user_id']`. Operations enforce `WHERE id = ? AND user_id = ?`. |
| **Cache Snooping** | Back-button access after logout | Protected pages send `Cache-Control: no-store, no-cache, must-revalidate, max-age=0` and `Pragma: no-cache` HTTP headers. |
| **Role Escalation** | Admin route access by guests/users | Route middleware `app/includes/admin-auth.php` checks `$_SESSION['role'] === 'admin'` before allowing execution. |

---

## 🔍 Regular Expression Validation

Client-side JavaScript (`validation.js`) and server-side PHP (`register.php`, `edit-profile.php`, `contact.php`) validate all user inputs against five strict regular expressions:

| Field | Regular Expression Pattern | Validation Rules Enforced |
| :--- | :--- | :--- |
| **Full Name** | `/^[A-Za-z ]{2,100}$/` | Only letters and spaces. Length: 2 to 100 characters. |
| **Email Address** | `/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/` | Standard RFC-compliant email address structure. |
| **Mobile Number** | `/^[6-9][0-9]{9}$/` | Exact 10-digit Indian mobile number starting with 6, 7, 8, or 9. |
| **Username** | `/^[A-Za-z0-9_]{3,30}$/` | Alphanumeric characters and underscores. Length: 3 to 30 characters. |
| **Password** | `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/` | Minimum 8 characters; at least 1 lowercase letter, 1 uppercase letter, 1 digit, and 1 special symbol. |

---

## 🗄️ Database Schema & Setup

The database (`covid19_db`) consists of 5 relational tables:

```text
┌─────────────────┐       1:N       ┌──────────────────┐
│      users      ├─────────────────┤     comments     │
├─────────────────┤                 ├──────────────────┤
│ id (PK)         │                 │ id (PK)          │
│ name            │                 │ user_id (FK)     │
│ email (UNIQUE)  │                 │ comment          │
│ mobile          │                 │ created_at       │
│ username (UNIQ) │                 │ updated_at       │
│ password        │                 └──────────────────┘
│ gender          │
│ role            │
│ created_at      │
└─────────────────┘

┌─────────────────┐  ┌─────────────────┐  ┌──────────────────┐
│   covid_info    │  │   statistics    │  │ contact_messages │
├─────────────────┤  ├─────────────────┤  ├──────────────────┤
│ id (PK)         │  │ id (PK)         │  │ id (PK)          │
│ title           │  │ country (UNIQUE)│  │ name             │
│ category        │  │ cases           │  │ email            │
│ description     │  │ recovered       │  │ subject          │
│ created_at      │  │ deaths          │  │ message          │
│ updated_at      │  │ updated_at      │  │ created_at       │
└─────────────────┘  └─────────────────┘  └──────────────────┘
```

### Database Setup Instructions

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open your web browser and navigate to phpMyAdmin: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
3. Click the **Import** tab.
4. Click **Choose File** and select [`database/database.sql`](database/database.sql).
5. Click **Go** to create the tables and insert seed data.

---

## 🚀 XAMPP Setup & Local Execution

### Prerequisites
- **Web Server**: Apache 2.4+ (Included with XAMPP)
- **PHP**: PHP 8.0 or newer
- **Database**: MySQL 5.7+ or MariaDB 10.4+
- **Git** (optional)

### Setup Steps

1. **Clone or Copy Repository**:
   Place the project folder inside your XAMPP `htdocs` directory:
   ```text
   C:\xampp\htdocs\Covid19-Information\
   ```

2. **Start Services**:
   Open **XAMPP Control Panel** and start both **Apache** and **MySQL**.

3. **Import Database**:
   Import [`database/database.sql`](database/database.sql) via phpMyAdmin as described above.

4. **Database Configuration**:
   Ensure [`app/config/database.php`](app/config/database.php) matches your local MySQL settings (defaults to `root` with empty password):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'covid19_db');
   ```

5. **Open Application in Browser**:
   - **Main Website**: [http://localhost/Covid19-Information/](http://localhost/Covid19-Information/)
   - **Registration**: [http://localhost/Covid19-Information/public/pages/auth/register.php](http://localhost/Covid19-Information/public/pages/auth/register.php)
   - **Login**: [http://localhost/Covid19-Information/public/pages/auth/login.php](http://localhost/Covid19-Information/public/pages/auth/login.php)
   - **Admin Portal**: [http://localhost/Covid19-Information/admin/dashboard.php](http://localhost/Covid19-Information/admin/dashboard.php)

---

## 🔑 Default Login Credentials

| Role | Username / Identifier | Email | Password | Access Level |
| :--- | :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin@covidshield.org` | `AdminPass123!` | Full Admin Portal (`admin/*`) + Main Website |
| **Test User** | `test_user` | `user@example.com` | `UserPass123!` | Public Features + Dashboard + Comments |

---

## 🧪 Software Testing & QA Suite

The repository contains an exhaustive Software Testing & Quality Assurance (STQA) testing framework comprising **134 formal test case specifications** and **5 automated test runner scripts**.

### Running Automated Test Runners

Execute any of the test suites via PHP CLI from the project root:

```bash
# 1. Master Automated Test Runner (All Modules 1 to 10 + Security)
php tests/master_test_runner.php

# 2. Module 5 (Authentication & Sessions) Runner
php tests/module5_test_runner.php

# 3. Module 6 (Dashboard & Profile Management) Runner
php tests/module6_test_runner.php

# 4. Modules 7 & 8 (Comments & Search) Runner
php tests/modules_7_8_test_runner.php

# 5. Modules 9 & 10 (Admin Portal & Contact Us) Runner
php tests/modules_9_10_test_runner.php
```

### Test Case Documents Index

| Document | Scope | Cases Count |
| :--- | :--- | :---: |
| [`tests/master-test-cases.md`](tests/master-test-cases.md) | Complete master testing matrix covering all modules | **134** |
| [`tests/regex-test-cases.md`](tests/regex-test-cases.md) | Regex unit test matrix (Name, Email, Mobile, Username, Password) | **50** |
| [`tests/boundary-test-cases.md`](tests/boundary-test-cases.md) | Boundary Value Analysis (BVA) test matrix | **30** |
| [`tests/negative-test-cases.md`](tests/negative-test-cases.md) | Negative testing and error-recovery evaluation | **25** |
| [`tests/security-test-cases.md`](tests/security-test-cases.md) | OWASP security audits (SQLi, XSS, IDOR, Session Fixation) | **15** |
| [`tests/integration-test-cases.md`](tests/integration-test-cases.md) | Cross-module integration workflows | **10** |
| [`tests/regression-test-cases.md`](tests/regression-test-cases.md) | Regression validation suite | **10** |
| [`tests/admin-test-cases.md`](tests/admin-test-cases.md) | Administrative portal CRUD and role management | **20** |
| [`tests/comments-test-cases.md`](tests/comments-test-cases.md) | Community feedback and authorization tests | **12** |
| [`tests/search-test-cases.md`](tests/search-test-cases.md) | Keyword search queries and escaping tests | **10** |
| [`tests/contact-test-cases.md`](tests/contact-test-cases.md) | Contact form submission and validation tests | **10** |
| [`tests/system-test-cases.md`](tests/system-test-cases.md) | End-to-end user and admin workflows | **2** |

---

## 🎓 Academic Documentation Suite

The [`docs/`](docs/) directory provides formal college examination documentation:

- 📑 **Comprehensive Project Report**: [`docs/project-documentation.md`](docs/project-documentation.md) — 33-section formal project documentation including Abstract, DFDs, ER diagrams, and architectural analysis.
- 📊 **STQA Testing Summary Report**: [`docs/testing-summary.md`](docs/testing-summary.md) — Executive summary presenting total test cases, execution counts, and 100% pass rates.
- 🐛 **Defect Log**: [`docs/bug-report.md`](docs/bug-report.md) — Defect tracking log signed off with **0 unresolved defects**.
- ❓ **50 Oral Viva Questions & Answers**: [`docs/viva-questions.md`](docs/viva-questions.md) — High-yield viva voce revision covering PHP, PDO, MySQL, STQA, Regex, and OWASP defenses.
- 🎙️ **Project Presentation Guide**: [`docs/project-explanation.md`](docs/project-explanation.md) — Complete presentation speech script addressing the 19 core evaluation questions.

---

## ⚠️ Known Limitations

1. **Local Database Seeding**: COVID-19 statistics are populated from static relational seed records rather than a paid third-party live REST API subscription.
2. **Email Delivery**: The contact form and registration modules record submissions directly into MySQL rather than dispatching outbound SMTP emails (requires local mail server configuration).
3. **Session Storage**: User sessions leverage PHP's native file-based session handler (`session_start()`), which is ideal for single-server XAMPP setups.
4. **Browser Dependency**: Client-side regex verification requires JavaScript to be enabled in the browser (server-side PHP regex verification acts as a fail-safe).

---

## 🚀 Future Enhancements

1. **External API Integration**: Connect to Johns Hopkins / WHO live COVID data feeds for real-time statistical synchronization.
2. **RESTful API Endpoints**: Expose secure JSON endpoints with JWT authentication for mobile application integration.
3. **Two-Factor Authentication (2FA)**: Integrate TOTP-based 2FA (e.g., Google Authenticator) for administrator logins.
4. **Automated Email Notifications**: Configure PHPMailer with SMTP for automated registration confirmation and contact acknowledgment emails.
5. **Interactive Mapping Visualizations**: Embed Leaflet.js or Google Maps to render visual global heatmaps of active COVID-19 cases.

---

## 📄 License

This project is licensed under the terms of the [MIT License](LICENSE).

<p align="center">
  Developed by <strong>Atharva</strong> • COVID-19 Information and Awareness Website
</p>
