# Viva Voce Questions & Answers Guide (Module 14)
**Project**: COVID-19 Information and Awareness Website  
**Subject**: Software Testing, Quality Assurance, Web Technologies & Database Management Systems

---

### Q1: What is the main objective of this COVID-19 Information Portal?
**A**: To provide an accurate, secure public health portal displaying COVID-19 symptoms, prevention guidance, and statistics while demonstrating Software Testing and Quality Assurance principles.

### Q2: What technology stack is used in this project?
**A**: Frontend: HTML5, CSS3, JavaScript. Backend: PHP 8.x. Database: MySQL (XAMPP environment).

### Q3: What is PDO in PHP and why did you use it?
**A**: PDO (PHP Data Objects) is a secure database access abstraction layer that supports prepared statements to prevent SQL Injection attacks.

### Q4: How does the application protect against SQL Injection?
**A**: By using PDO Prepared Statements with parameterized placeholders (`?` or `:name`) for all SQL queries instead of concatenating user input directly into queries.

### Q5: What is Cross-Site Scripting (XSS) and how is it prevented here?
**A**: XSS occurs when malicious scripts are injected into web pages. We prevent it by sanitizing all dynamic content using `htmlspecialchars(ENT_QUOTES, 'UTF-8')` before outputting HTML.

### Q6: How are user passwords secured in the database?
**A**: Passwords are encrypted using PHP's native `password_hash($password, PASSWORD_BCRYPT)` function, producing a secure 60-character one-way hash.

### Q7: How is user password verification performed during login?
**A**: We use `password_verify($inputPassword, $storedHash)` which safely compares the entered plain-text password against the stored BCRYPT hash without exposing plaintext data.

### Q8: What is a PHP Session and how is it used here?
**A**: A PHP Session stores user authentication state (`user_id`, `username`, `role`, `logged_in`) on the server side using a unique session cookie (`PHPSESSID`).

### Q9: Why do you call `session_regenerate_id(true)` upon successful login?
**A**: To prevent Session Fixation attacks by replacing the old pre-login session ID with a newly generated session ID while destroying the previous one.

### Q10: How does role-based access control (RBAC) work in this portal?
**A**: Middleware scripts (`includes/auth.php` and `includes/admin-auth.php`) check `$_SESSION['logged_in']` and `$_SESSION['role']` to restrict protected user routes and admin endpoints (`admin/*`).

### Q11: What is IDOR (Insecure Direct Object Reference) and how is it prevented?
**A**: IDOR occurs when a user accesses or modifies another user's data by altering a request parameter (e.g., `id=5`). We prevent IDOR by obtaining the target user ID strictly from `$_SESSION['user_id']`.

### Q12: Where did you use Regular Expressions (Regex) in this project?
**A**: On both client side (JavaScript) and server side (PHP) for validating Full Name, Email, Mobile Number, Username, and Password fields during registration and profile update.

### Q13: What regex pattern was used for the Full Name field?
**A**: `/^[A-Za-z ]{2,100}$/` which permits uppercase/lowercase letters and spaces, requiring length between 2 and 100 characters.

### Q14: What regex pattern was used for Indian mobile numbers?
**A**: `/^[6-9][0-9]{9}$/` which verifies a 10-digit number starting with 6, 7, 8, or 9.

### Q15: What regex pattern was used for password complexity?
**A**: `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/` which enforces min 8 chars with uppercase, lowercase, digit, and special character.

### Q16: What tables exist in the `covid19_db` database?
**A**: 5 tables: `users`, `covid_info`, `statistics`, `comments`, `contact_messages`.

### Q17: What is a Primary Key?
**A**: A unique identifier for a table record, such as `id INT AUTO_INCREMENT PRIMARY KEY` in the `users` table.

### Q18: What is a Foreign Key and how is it used in the `comments` table?
**A**: A foreign key creates a relational constraint between two tables. `comments.user_id` references `users.id` with `ON DELETE CASCADE`.

### Q19: What SQL JOIN operation was used in the Comments module?
**A**: `SELECT comments.*, users.name FROM comments JOIN users ON comments.user_id = users.id ORDER BY created_at DESC` to fetch author names alongside comment content.

### Q20: How does the aggregate statistics calculation work in Module 3?
**A**: SQL aggregate functions `SUM(cases)`, `SUM(recovered)`, `SUM(deaths)` calculate real-time global totals dynamically from the `statistics` table.

### Q21: What is Software Testing?
**A**: The process of executing an application with the intent of finding defects, verifying functional requirements, and ensuring software quality.

### Q22: What is Quality Assurance (QA)?
**A**: A proactive set of activities focused on ensuring that software development processes, security controls, and design standards produce defect-free applications.

### Q23: What is Black-Box Testing?
**A**: Testing software functionality without knowing or inspecting the internal code structure, focusing purely on inputs and expected outputs.

### Q24: What is Functional Testing?
**A**: Verifying that each feature of the software operates in accordance with specified functional requirements (e.g., login, search, profile edit).

### Q25: What is Unit Testing?
**A**: Testing individual components or functions in isolation, such as testing standalone regex validation functions against test inputs.

### Q26: What is Integration Testing?
**A**: Testing interactions between integrated modules, such as verifying data flow from Registration -> Login -> Dashboard -> Database.

### Q27: What is System Testing?
**A**: Testing the complete integrated application end-to-end to verify that it meets all system requirements for both users and administrators.

### Q28: What is Regression Testing?
**A**: Re-testing existing modules whenever new code or modules are added to ensure no previously working functionality was broken.

### Q29: What is Boundary Value Analysis (BVA)?
**A**: A test design technique testing extreme limits of input domains (minimum, below minimum, maximum, above maximum), such as testing 1, 2, 100, and 101 characters for Name.

### Q30: What is Negative Testing?
**A**: Testing software with invalid, unexpected, or malicious inputs to ensure it fails gracefully without crashing or leaking sensitive stack traces.

### Q31: What is Security Testing?
**A**: Evaluating an application to identify vulnerabilities, verify authentication/authorization controls, and test defenses against injection and script attacks.

### Q32: How many total test cases were executed for this project?
**A**: 134 master test cases (plus 50 regex, 30 boundary, 25 negative, 10 integration, 2 system, and 15 security test cases).

### Q33: What was the pass percentage achieved during testing?
**A**: 100.0% pass rate (134 / 134 executed test cases passed cleanly).

### Q34: How does the keyword search engine work in Module 8?
**A**: It receives a search query parameter `q` and executes `SELECT * FROM covid_info WHERE title LIKE :q OR description LIKE :q` using bound wildcards.

### Q35: How does the Contact Us module integrate with the Admin Panel?
**A**: Guest submissions on `contact.php` insert records into `contact_messages`, which are immediately visible and manageable by admins on `admin/contact-messages.php`.

### Q36: Why do we send HTTP No-Cache headers on protected pages?
**A**: To prevent browsers from caching sensitive authenticated views, ensuring pressing the Browser Back button after logout does not reveal cached user state.

### Q37: What is a Self-Demotion Defense in the Admin module?
**A**: Logic that prevents an admin from removing their own admin privileges (`role = 'user'`), ensuring the system never accidentally loses its administrator.

### Q38: What HTTP methods are used in the application and why?
**A**: `HTTP GET` for retrieving data/pages and `HTTP POST` for form submissions and state mutation operations to prevent CSRF and URL tampering.

### Q39: What is responsive web design and how is it implemented here?
**A**: Creating layouts that adapt to all screen sizes (desktop, tablet, mobile) using CSS Media Queries, Flexbox, and viewport metadata tags.

### Q40: What happens if JavaScript is disabled in the user's browser?
**A**: Server-side PHP validation checks enforce all regex, mandatory field, and security rules independently, ensuring complete safety.

### Q41: How does `htmlspecialchars()` protect against XSS?
**A**: It converts special HTML characters like `<` to `&lt;`, `>` to `&gt;`, and `"` to `&quot;`, causing the browser to render them as plain text rather than executing code.

### Q42: What is the difference between `NULL` and empty string `""` in MySQL?
**A**: `NULL` signifies missing or unknown data, whereas an empty string `""` is a string value of zero length. We use `NOT NULL` constraints on core columns.

### Q43: What is the role of `session_start()`?
**A**: It initializes or resumes an existing session based on a session identifier passed via a GET or POST request, or via a cookie.

### Q44: What is the difference between client-side and server-side validation?
**A**: Client-side (JS) improves user experience with instant feedback; server-side (PHP) provides mandatory security since client scripts can be bypassed.

### Q45: How do we prevent duplicate user registration?
**A**: By querying the database (`SELECT id FROM users WHERE username = ? OR email = ?`) before inserting, alongside MySQL `UNIQUE` constraints on email and username.

### Q46: What is a database Index and why is it used?
**A**: A data structure that improves the speed of data retrieval operations on a database table, such as primary key indexes on `id` and unique indexes on `email`.

### Q47: What error handling strategy is implemented in production code?
**A**: Catching `PDOException` in try-catch blocks, logging detailed traces internally via `error_log()`, and showing clean, user-friendly messages to users.

### Q48: What is CRUD?
**A**: An acronym for Create, Read, Update, Delete — the 4 basic persistent storage operations implemented in our Admin and User modules.

### Q49: How was Regression Testing conducted for this project?
**A**: By re-running test suites across Modules 1 to 10 after every module addition to verify that later enhancements did not disrupt existing functionality.

### Q50: What is the final conclusion of your Software Testing and QA project?
**A**: The application is fully functional, aesthetically polished, highly secure against OWASP vulnerabilities, and backed by a 100% verified test pass rate across 134 test cases.
