# Student Project Presentation & Viva Speech Guide (Module 14)
**Project Title**: COVID-19 Information and Awareness Website  
**Target Audience**: Teachers, External Examiners, Classmates, Viva Evaluation Panel

---

## Presentation Speech Overview

Hello Respected Teachers and Examiners,

Today I am presenting our mini-project titled **"COVID-19 Information and Awareness Website"**, developed as part of our Software Testing and Quality Assurance (STQA) coursework. Below is a complete step-by-step explanation addressing all 19 key questions about our project:

---

### 1. What is the project?
It is a comprehensive web portal designed to educate the public about COVID-19 symptoms, prevention rules, and real-time global statistics while enabling registered users to participate in community discussions, search for health guidelines, send inquiries, and manage their profiles securely.

---

### 2. Why did we build it?
We built this project to combine web application development with real-world Software Testing & Quality Assurance principles — demonstrating regular expressions, security controls, database integrity, and comprehensive test case execution.

---

### 3. What problem does it solve?
During health emergencies, citizens suffer from fragmented news, unverified rumors, lack of localized statistics, and insecure websites. Our portal provides a single, trusted source of verified information, dynamic statistics, and community interaction backed by enterprise-level web security.

---

### 4. What technologies did we use?
- **Frontend**: HTML5 for semantic structure, Vanilla CSS3 for glassmorphism styling and responsive layouts, Vanilla JavaScript for client-side regex validation and mobile navigation toggling.
- **Backend**: PHP 8.x for session management, authentication middleware, and business logic.
- **Database**: MySQL running on XAMPP with PDO (PHP Data Objects) for database interaction.

---

### 5. How does the system work?
Visitors can freely explore public information cards, review global statistics, search for articles, and send contact messages. Users who register and log in receive protected privileges (editing their profiles, changing passwords, posting community comments). Users with administrative privileges (`role = 'admin'`) can access the Admin Portal (`admin/*`) to manage users, maintain information articles, update statistics, moderate comments, and reply to messages.

---

### 6. What are the modules?
The application is organized into 14 distinct modules:
- **Module 1**: Project Setup & Home Page
- **Module 2**: COVID Information Module
- **Module 3**: COVID Statistics Module
- **Module 4**: User Registration + Regular Expressions
- **Module 5**: Authentication & Session Management
- **Module 6**: User Dashboard & Profile Management
- **Module 7**: Community Comments & Feedback
- **Module 8**: Keyword Search Engine
- **Module 9**: Admin Dashboard & Management
- **Module 10**: Contact Us Module
- **Module 11**: Software Testing & Quality Assurance
- **Module 12**: Application Security Testing
- **Module 13**: Final Integration & Code Optimization
- **Module 14**: Project Documentation & Viva Preparation

---

### 7. How does registration work?
A user enters their Full Name, Email, Indian Mobile Number, Username, Password, Gender, and Confirm Password on `register.php`. Both JavaScript and PHP validate the inputs against 5 strict Regular Expressions. If valid and no duplicate email/username exists in MySQL, the password is encrypted using BCRYPT (`password_hash`) and the user account is created with default role `'user'`.

---

### 8. How does login work?
The user enters their Username or Email along with their Password on `login.php`. PHP queries the database using PDO prepared statements. If a matching record is found, `password_verify()` checks the password against the stored BCRYPT hash. Upon successful match, `session_regenerate_id(true)` issues a fresh session ID and sets `$_SESSION['logged_in'] = true`, `$_SESSION['user_id']`, and `$_SESSION['role']`.

---

### 9. How are passwords secured?
Plaintext passwords are **never** stored in the database. When a user registers or changes their password, PHP's `password_hash($password, PASSWORD_BCRYPT)` converts it into a secure 60-character one-way BCRYPT hash (`$2y$10$...`).

---

### 10. How does the database work?
Our MySQL database (`covid19_db`) contains 5 relational tables:
- `users`: Stores account data, BCRYPT hashes, and roles.
- `covid_info`: Stores informative articles, categories, and descriptions.
- `statistics`: Stores country cases, recovered counts, and death metrics.
- `comments`: Stores user comments linked via foreign key (`user_id -> users.id`).
- `contact_messages`: Stores inquiries submitted by guests and users.

---

### 11. How does search work?
On `search.php`, the user types a search query (e.g., *"Vaccine"*). PHP sanitizes the input and executes a parameterized SQL `LIKE` query (`SELECT * FROM covid_info WHERE title LIKE :q OR description LIKE :q`). Results are rendered with matching keywords highlighted, while output text is escaped using `htmlspecialchars()` to prevent XSS.

---

### 12. How do comments work?
Authenticated users can post comments on `comments.php`. SQL JOIN queries merge `comments` with `users` to display author names and timestamps. Users can edit or delete their own comments. To prevent IDOR (Insecure Direct Object Reference) vulnerabilities, PHP strictly verifies that `comments.user_id` matches `$_SESSION['user_id']` before permitting any update or deletion.

---

### 13. How does admin work?
When an administrator logs in (`role = 'admin'`), an **⚙️ Admin** link appears in the navbar leading to `admin/dashboard.php`. Middleware `includes/admin-auth.php` enforces that non-admin users attempting to access `/admin/*` are immediately blocked and redirected. Admins can promote/demote users, manage COVID articles, update statistics, moderate comments, and view contact inbox messages.

---

### 14. How does Contact Us work?
Guests and logged-in users can submit messages on `contact.php`. For logged-in users, Name and Email are automatically pre-filled. Submitted messages undergo server validation and are stored in `contact_messages`. Administrators can view, review, and delete messages in `admin/contact-messages.php`.

---

### 15. Where did we use Regular Expressions?
We implemented 5 Regular Expressions in Module 4 for both client-side JS and server-side PHP validation:
1. **Full Name**: `/^[A-Za-z ]{2,100}$/` (Letters and spaces, 2-100 characters).
2. **Email Address**: `/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/` (Standard RFC email).
3. **Mobile Number**: `/^[6-9][0-9]{9}$/` (10-digit Indian mobile starting with 6-9).
4. **Username**: `/^[A-Za-z0-9_]{3,30}$/` (Alphanumeric with underscores, 3-30 characters).
5. **Password**: `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/` (Min 8 chars, uppercase, lowercase, number, special character).

---

### 16. How did we perform testing?
We created and executed a comprehensive testing suite consisting of:
- **134 Master Test Cases** across all 12 modules.
- **50 Regular Expression Test Cases** covering valid, invalid, boundary, and special character inputs.
- **30 Boundary Value Analysis (BVA) Test Cases** evaluating min, below-min, max, and above-max inputs.
- **25 Negative Test Cases** ensuring graceful failure on invalid inputs.
- **10 Integration Test Cases** verifying cross-module data flow.
- **2 System Test Cases** verifying end-to-end visitor and admin workflows.
- **10 Regression Test Cases** verifying existing functionality remained intact.
- **100.0% Pass Rate** was achieved across all executed tests.

---

### 17. What security testing did we perform?
We performed non-destructive application security testing verifying:
- **SQL Injection Defense**: Verified PDO prepared statements block payload attacks (`' OR '1'='1`).
- **XSS Defense**: Verified `htmlspecialchars()` escapes script tags (`<script>alert(1)</script>`).
- **IDOR Defense**: Verified users cannot edit/delete another user's profile or comments.
- **Session Security**: Verified `session_regenerate_id(true)` on login and `session_destroy()` on logout.
- **Password Security**: Verified zero plaintext passwords exist in MySQL.
- **Access Control**: Verified protected user routes and admin endpoints block unauthorized requests.

---

### 18. What are the limitations?
- The project runs on a local server environment (XAMPP).
- Live real-time API syncing with international WHO databases requires an active internet connection for live feeds.

---

### 19. What is future scope?
- Adding Two-Factor Authentication (2FA) via SMS/Email OTP.
- Implementing an automated REST API integration with global health organizations.
- Adding downloadable PDF statistical summary reports for health authorities.

---

Thank you, teachers and examiners! I am ready for any questions.
