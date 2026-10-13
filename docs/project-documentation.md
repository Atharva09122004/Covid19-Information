# Comprehensive Project Documentation (Module 14)

# COVID-19 Information and Awareness Website

---

## 1. Project Title
**COVID-19 Information and Awareness Website**  
*College Mini Project for Software Testing & Quality Assurance (STQA)*

---

## 2. Abstract
The COVID-19 Information and Awareness Website is a modern, responsive web application designed to educate citizens about coronavirus symptoms, prevention measures, global statistics, community feedback, and direct support inquiries. Developed using HTML5, CSS3, JavaScript, PHP, and MySQL on XAMPP, the application features strict session-based authentication, BCRYPT password hashing, Regular Expression input validation, prepared SQL statements against Injection attacks, HTML entity escaping against XSS, and role-based access control for administrative portal operations.

---

## 3. Introduction
In times of public health crises, accurate dissemination of medical information and data transparency is critical. This project delivers an intuitive, secure, and user-friendly portal providing verified information on COVID-19, real-time statistical metrics, community discussion boards, and direct administrator contact channels while demonstrating software testing, security, and quality assurance principles.

---

## 4. Problem Statement
During pandemic outbreaks, public communication often suffers from fragmented news sources, unverified rumors, lack of localized statistics, and poor user data privacy. Existing portals frequently lack adequate server-side input validation, robust access control, or comprehensive software quality assurance. There is a need for a unified platform that combines reliable public health data with high software security and rigorous quality control.

---

## 5. Objectives
1. Provide a central digital hub for COVID-19 information, symptoms, and prevention guidance.
2. Present accurate aggregate statistical data and country-wise metrics.
3. Enable secure user registration and login using regular expressions and password hashing.
4. Implement a community feedback system with strict authorization and XSS protection.
5. Deliver a keyword search engine for rapid information lookup.
6. Provide a secure administrator portal for content management, user role control, and message handling.
7. Perform software testing, boundary analysis, security audits, and QA documentation.

---

## 6. Scope
The scope encompasses all 10 core web application functional modules (Home, COVID Info, Statistics, Registration, Authentication, User Dashboard, Comments, Search, Admin Portal, Contact Us) alongside comprehensive testing suites (Software Testing & QA, Security Audits, System Integration, and Documentation).

---

## 7. Proposed System
The proposed web application uses a modular multi-tier architecture with PHP PDO data access, server-side validation, session management, BCRYPT security, responsive CSS design, and role-based access control separating public visitors, authenticated users, and administrators.

---

## 8. Existing System
Traditional informative portals often suffer from static unvalidated HTML pages, plain-text password storage, vulnerability to SQL Injection and Cross-Site Scripting (XSS), lack of regular expression input validation, and absent automated testing suites.

---

## 9. Advantages of Proposed System
- **Security First**: BCRYPT password hashing, PDO prepared statements, `htmlspecialchars()` XSS escaping, IDOR defenses, and strict session management.
- **Regular Expression Validation**: Client-side and server-side regex rules enforcing clean data input.
- **Responsive Aesthetics**: CSS custom properties, flexbox grid, modern cards, and glassmorphism navbar.
- **Role-Based Authorization**: Strict boundary enforcing public, authenticated user, and administrator privileges.
- **Comprehensive Quality Assurance**: 100% test pass rate across 134 functional, regex, boundary, security, integration, and system test cases.

---

## 10. Limitations
- System runs locally on Apache/MySQL (XAMPP).
- Live real-time API sync with WHO/Johns Hopkins requires internet connection for live data feeds.
- Two-Factor Authentication (2FA) is planned for future releases.

---

## 11. Functional Requirements
- **FR-01**: The system shall render public informative cards on COVID-19 symptoms, prevention, and guidelines.
- **FR-02**: The system shall calculate aggregate global case totals and support country sorting/filtering.
- **FR-03**: The system shall validate user registration inputs using 5 regular expressions (Name, Email, Mobile, Username, Password).
- **FR-04**: The system shall authenticate users securely and maintain session state across requests.
- **FR-05**: Authenticated users shall be able to edit their profiles, change passwords, and post community comments.
- **FR-06**: The system shall restrict comment editing and deletion strictly to the original author or administrator.
- **FR-07**: The system shall provide a search bar querying COVID information titles and descriptions.
- **FR-08**: The system shall allow guests and users to submit contact inquiry messages.
- **FR-09**: Administrators shall manage users, create/edit/delete info articles, statistics, comments, and messages.

---

## 12. Non-Functional Requirements
- **NFR-01 Performance**: Page load time under 1.5 seconds on local server.
- **NFR-02 Security**: 0 plain-text passwords stored; 0 SQL Injection or XSS vulnerabilities.
- **NFR-03 Usability**: Clean visual hierarchy, mobile-first responsive layout (375px to 1920px).
- **NFR-04 Reliability**: Graceful error handling without raw PHP stack traces or database credential leaks.

---

## 13. Hardware Requirements
- **Processor**: Dual-Core 2.0 GHz or higher.
- **RAM**: 4 GB RAM minimum.
- **Disk Space**: 500 MB available storage.
- **Display**: 1024x768 resolution screen or mobile device.

---

## 14. Software Requirements
- **Operating System**: Windows 10/11, macOS, or Linux.
- **Web Server**: Apache 2.4+ (XAMPP environment).
- **Database Engine**: MySQL 5.7+ / MariaDB 10.4+.
- **PHP Interpreter**: PHP 8.0+.
- **Browser**: Google Chrome, Microsoft Edge, Mozilla Firefox, or Apple Safari.

---

## 15. Technology Stack
- **Frontend**: HTML5, CSS3 (Vanilla Design System), JavaScript (ES6 Client Validation & Nav Toggle).
- **Backend**: PHP 8.x (Session Management, Password Hashing, Regex Engine, PDO Data Access).
- **Database**: MySQL (Relational Schema with Foreign Keys & Constraints).
- **Server**: XAMPP Apache Server.

---

## 16. System Architecture
```
[ Browser Client ]
        │
        ▼ (HTTP / HTTPS Requests)
[ Apache Web Server (XAMPP) ]
        │
        ▼
[ PHP Application Layer (Routing, Auth Middleware, Regex, HTML Escaping) ]
        │
        ▼ (PDO Prepared Statements)
[ MySQL Database (covid19_db) ]
```

---

## 17. Module Description
- **Module 1**: Project Setup & Home Page (Navbar, Hero section, Prevention guidance).
- **Module 2**: COVID Information Module (Category filtering, Article cards).
- **Module 3**: COVID Statistics Module (Aggregate SQL totals, Country data table, Sorting).
- **Module 4**: User Registration + Regex (5 strict regex patterns, duplicate checks).
- **Module 5**: Authentication System (Login, Logout, Session management, BCRYPT hashing).
- **Module 6**: User Dashboard & Profile Management (Edit profile, Change password, Session synchronization).
- **Module 7**: Comments & Feedback (Community feed, Author authorization, IDOR defense).
- **Module 8**: Search Engine (Keyword query against COVID info database).
- **Module 9**: Admin Dashboard & Management (User role control, Info CRUD, Statistics CRUD, Moderation).
- **Module 10**: Contact Us Module (Guest inquiry submission, Server validation, Admin message inbox).
- **Module 11**: Software Testing & QA (Master test cases, Regex, BVA, Negative, Integration, System, Regression).
- **Module 12**: Security Testing (Authentication, Authorization, SQLi, XSS, Session, Password security).

---

## 18. Database Design
The relational database `covid19_db` contains 5 core tables (`users`, `covid_info`, `statistics`, `comments`, `contact_messages`) designed with foreign key constraints (`CASCADE` on delete), indexes, unique constraints, and strict column data types.

---

## 19. Database Tables Schema

### Table 1: `users`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user ID |
| `name` | VARCHAR(100) | NOT NULL | User's full name |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | User's email address |
| `mobile` | VARCHAR(15) | NOT NULL | 10-digit Indian mobile |
| `username` | VARCHAR(30) | NOT NULL, UNIQUE | Unique login username |
| `password` | VARCHAR(255) | NOT NULL | BCRYPT password hash |
| `gender` | VARCHAR(20) | NOT NULL | Selected gender |
| `role` | ENUM('user','admin')| DEFAULT 'user' | Access control role |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Registration timestamp |

### Table 2: `covid_info`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Article ID |
| `title` | VARCHAR(200) | NOT NULL | Article title |
| `category` | VARCHAR(50) | NOT NULL | Category name |
| `description` | TEXT | NOT NULL | Article content |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |

### Table 3: `statistics`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Record ID |
| `country` | VARCHAR(100) | NOT NULL, UNIQUE | Country name |
| `cases` | INT | NOT NULL, DEFAULT 0 | Total confirmed cases |
| `recovered` | INT | NOT NULL, DEFAULT 0 | Total recovered cases |
| `deaths` | INT | NOT NULL, DEFAULT 0 | Total deaths |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Update timestamp |

### Table 4: `comments`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Comment ID |
| `user_id` | INT | NOT NULL, FOREIGN KEY (users.id) | Author user ID |
| `comment_text` | TEXT | NOT NULL | Comment body |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Posting timestamp |

### Table 5: `contact_messages`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Message ID |
| `name` | VARCHAR(100) | NOT NULL | Sender name |
| `email` | VARCHAR(100) | NOT NULL | Sender email |
| `subject` | VARCHAR(200) | NOT NULL | Inquiry subject |
| `message` | TEXT | NOT NULL | Message text |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Submission timestamp |

---

## 20. ER Diagram Description
- Entity `users` has a 1-to-N relationship with `comments` (`users.id` -> `comments.user_id`).
- Entities `covid_info`, `statistics`, and `contact_messages` act as independent operational entities managed by users with `role = 'admin'`.

---

## 21. Data Flow Diagram (DFD) Description
- **Level 0 (Context DFD)**: User/Visitor inputs data -> COVID Portal Engine -> Interacts with MySQL DB -> Displays web pages & reports.
- **Level 1 DFD**: 
  - Subprocess 1.0: Public Browsing (Reads `covid_info` & `statistics`).
  - Subprocess 2.0: Authentication & Account Setup (Writes to `users`).
  - Subprocess 3.0: User Feedback (Writes to `comments`).
  - Subprocess 4.0: Admin Moderation & Management (CRUD on all tables).

---

## 22. Use Case Description
- **Actors**: Public Visitor, Registered User, Administrator.
- **Visitor Use Cases**: View Home, Browse COVID Info, View Statistics, Search, Submit Contact Message, Register Account, Login.
- **Registered User Use Cases**: Manage Profile, Change Password, Post Comment, Edit Own Comment, Delete Own Comment, Logout.
- **Administrator Use Cases**: All User capabilities + Admin Portal Access, Manage Users (Role Promote/Demote, Delete), COVID Info CRUD, Statistics CRUD, Moderate Comments, Manage Contact Messages.

---

## 23. Authentication Flow
```
User enters username/email & password 
         │
         ▼
[ login.php ] ──> PDO query finds user record by identifier
         │
         ▼
`password_verify(password, hash)` ──> Match?
   │                                  │
   ├─ NO ──> Error message displayed   └─ YES ──> `session_regenerate_id(true)`
                                                   Sets `$_SESSION['user_id']`, `$_SESSION['role']`
                                                   Redirects to `dashboard.php` / `admin/dashboard.php`
```

---

## 24. Admin Flow
1. Admin authenticates via `login.php`.
2. Middleware `includes/admin-auth.php` verifies `$_SESSION['logged_in'] === true` AND `$_SESSION['role'] === 'admin'`.
3. Non-admins or unauthenticated users attempting access are redirected cleanly with error parameters.
4. Admin accesses administrative controls for Users, Info, Statistics, Comments, and Messages.

---

## 25. Testing Methodology
The project employs a structured Testing Lifecycle combining Black-Box Functional Testing, Unit-Level Regex Verification, Boundary Value Analysis (BVA), Negative Testing, Integration Testing, System End-to-End Testing, Regression Testing, and Application Security Auditing.

---

## 26. Test Case Summary
- Total Executed Test Cases: 134
- Passed Test Cases: 134
- Failed Test Cases: 0
- Blocked Test Cases: 0
- Overall Pass Rate: 100.0%

---

## 27. Regular Expression Testing
Implemented and executed 50 test cases covering Name, Email, Mobile, Username, and Password patterns across valid, invalid, boundary, and special character inputs with 100% pass rate.

---

## 28. Boundary Testing
Implemented and executed 30 boundary value test cases examining min-1, min, max, and max+1 string lengths and integer bounds across all system forms with 100% pass rate.

---

## 29. Security Testing
Executed 15 safe application-level security test cases covering SQL Injection defense (prepared statements), XSS defense (`htmlspecialchars`), IDOR defenses (session user ID checks), Password Security (BCRYPT hashing), Session Security (`session_regenerate_id`), and Error Handling with 100% pass rate.

---

## 30. Results
All 10 application development modules and 4 testing/documentation modules were implemented, integrated, and verified with 0 defects remaining.

---

## 31. System Advantages
- Exceptional user interface design and responsive CSS layout.
- High security standards preventing common web vulnerabilities (OWASP Top 10 defenses).
- Complete automated and manual test documentation suitable for academic evaluation.

---

## 32. Future Scope
- Add Two-Factor Authentication (2FA) via SMS/Email OTP.
- Integrate real-time REST API updates from global health agencies.
- Implement downloadable PDF reports for country statistics.

---

## 33. Conclusion
The COVID-19 Information and Awareness Website successfully achieves all academic and functional requirements. It presents a robust, secure, beautiful, and fully tested web application demonstrating software development best practices and quality assurance standards.
