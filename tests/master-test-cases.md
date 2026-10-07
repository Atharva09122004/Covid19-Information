# Master Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Test Cases**: 134  
**Pass Rate**: 100% (134 PASS / 0 FAIL / 0 BLOCKED)

---

## Section 1: Home Page (5 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-HOME-001** | Home Page | Verify home page loads cleanly | Server running | HTTP GET `index.php` | Page renders with HTTP 200, logo, hero banner, navigation bar | Page rendered with HTTP 200 OK and complete DOM structure | PASS |
| **TC-HOME-002** | Home Page | Verify top navigation menu alignment | Home page loaded | Browser view (desktop/mobile) | Nav menu links align horizontally without line wrapping | Nav links aligned cleanly with optimal flex spacing | PASS |
| **TC-HOME-003** | Home Page | Verify hero action button navigation | Home page loaded | Click "Explore COVID Info" | Redirects to `covid-info.php` | Navigated to `covid-info.php` successfully | PASS |
| **TC-HOME-004** | Home Page | Verify prevention highlights display | Home page loaded | View home body cards | Displays prevention guidance cards (Masks, Hand Washing, Social Distance) | Prevention cards displayed cleanly with modern CSS styling | PASS |
| **TC-HOME-005** | Home Page | Verify footer navigation and copyright | Home page loaded | Scroll to page footer | Footer renders quick links, project info, and copyright notice | Footer links and copyright notice rendered correctly | PASS |

---

## Section 2: COVID Information (5 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-INFO-001** | COVID Info | Load information module | Info page accessible | HTTP GET `covid-info.php` | Renders COVID-19 information articles from database | Articles fetched from `covid_info` table and displayed | PASS |
| **TC-INFO-002** | COVID Info | Filter info articles by category "Symptoms" | Articles exist | HTTP GET `covid-info.php?category=Symptoms` | Filters and displays only Symptoms category articles | Displayed 3 Symptoms articles | PASS |
| **TC-INFO-003** | COVID Info | Filter info articles by category "Prevention" | Articles exist | HTTP GET `covid-info.php?category=Prevention` | Filters and displays only Prevention category articles | Displayed 3 Prevention articles | PASS |
| **TC-INFO-004** | COVID Info | Filter info articles by "All" category | Filter active | HTTP GET `covid-info.php?category=All` | Resets filter and displays all information articles | All 8 information articles displayed cleanly | PASS |
| **TC-INFO-005** | COVID Info | Invalid category filter security defense | Info page loaded | HTTP GET `covid-info.php?category=INVALID_CAT` | Falls back gracefully to "All" category without SQL error | Safe fallback executed, no database error thrown | PASS |

---

## Section 3: COVID Statistics (8 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-STAT-001** | Statistics | Load statistics dashboard | DB populated | HTTP GET `statistics.php` | Renders aggregate global totals and country table | Summary cards and country table loaded cleanly | PASS |
| **TC-STAT-002** | Statistics | Global aggregate total calculation | Statistics table populated | Page load | Calculates `SUM(cases)`, `SUM(recovered)`, `SUM(deaths)`, `SUM(active)` via SQL | Accurately displayed SQL aggregate metrics | PASS |
| **TC-STAT-003** | Statistics | Filter table by country "India" | Statistics data loaded | HTTP GET `statistics.php?country=India` | Displays statistics row for India only | Filtered table showing India row | PASS |
| **TC-STAT-004** | Statistics | Sort table by cases descending | Statistics page active | HTTP GET `statistics.php?sort=cases_desc` | Sorts country table by total cases high-to-low | Country table sorted descending by cases | PASS |
| **TC-STAT-005** | Statistics | Sort table by recovered descending | Statistics page active | HTTP GET `statistics.php?sort=rec_desc` | Sorts country table by recovered count high-to-low | Country table sorted descending by recovery count | PASS |
| **TC-STAT-006** | Statistics | Sort table by deaths descending | Statistics page active | HTTP GET `statistics.php?sort=deaths_desc` | Sorts country table by deaths count high-to-low | Country table sorted descending by deaths | PASS |
| **TC-STAT-007** | Statistics | Invalid country parameter fallback | Page active | HTTP GET `statistics.php?country=INVALID_CNT` | Shows notice and safely falls back to All countries | Displayed notice banner and rendered all countries | PASS |
| **TC-STAT-008** | Statistics | Recovery rate percentage calculation | Data loaded | Page calculation | Computes `(recovered / cases) * 100` accurately | Percentage computed and formatted cleanly to 1 decimal place | PASS |

---

## Section 4: User Registration (15 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-REG-001** | Registration | Register with valid details | User not registered | Valid name, email, mobile, username, password | User registered successfully, password hashed in DB | User inserted into DB, password hashed with BCRYPT | PASS |
| **TC-REG-002** | Registration | Empty name field | Registration page active | Name = "", valid remaining fields | Client & server validation error: "Full Name is required" | Validation error displayed, form preserved | PASS |
| **TC-REG-003** | Registration | Name with numbers/special chars | Form active | Name = "John123!" | Regex validation error: "Name must contain only letters and spaces" | Validation error displayed | PASS |
| **TC-REG-004** | Registration | Name boundary (1 character) | Form active | Name = "A" | Rejects short name (< 2 chars) | Rejection message displayed | PASS |
| **TC-REG-005** | Registration | Name boundary (101 characters) | Form active | Name = 101 letter string | Rejects long name (> 100 chars) | Rejection message displayed | PASS |
| **TC-REG-006** | Registration | Invalid email format | Form active | Email = "john@invalid" | Rejects invalid email regex pattern | Rejection message displayed | PASS |
| **TC-REG-007** | Registration | Duplicate email registration | Email exists in DB | Email = existing user email | Rejects duplicate email: "Email is already registered" | Duplicate email rejected | PASS |
| **TC-REG-008** | Registration | Invalid mobile (9 digits) | Form active | Mobile = "987654321" | Rejects short Indian mobile number | Rejection error displayed | PASS |
| **TC-REG-009** | Registration | Invalid mobile (starts with 5) | Form active | Mobile = "5876543210" | Rejects non-Indian mobile starting digit | Rejection error displayed | PASS |
| **TC-REG-010** | Registration | Short username (< 3 chars) | Form active | Username = "ab" | Rejects username under 3 characters | Rejection error displayed | PASS |
| **TC-REG-011** | Registration | Long username (> 30 chars) | Form active | Username = 31 char string | Rejects username over 30 characters | Rejection error displayed | PASS |
| **TC-REG-012** | Registration | Duplicate username registration | Username in DB | Username = existing username | Rejects duplicate username: "Username is already taken" | Duplicate username rejected | PASS |
| **TC-REG-013** | Registration | Weak password (< 8 chars) | Form active | Password = "Pass1!" | Rejects password under 8 characters | Rejection error displayed | PASS |
| **TC-REG-014** | Registration | Password lacking uppercase/symbol | Form active | Password = "password123" | Rejects weak password pattern | Rejection error displayed | PASS |
| **TC-REG-015** | Registration | Password mismatch | Form active | Password != Confirm Password | Rejects with error: "Passwords do not match" | Rejection error displayed | PASS |

---

## Section 5: Authentication & Login (12 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-AUTH-001** | Login | Login with valid username | Registered user | Correct username & password | Authenticates, sets session, redirects to `dashboard.php` | Authenticated and redirected to dashboard | PASS |
| **TC-AUTH-002** | Login | Login with valid email | Registered user | Correct email & password | Authenticates, sets session, redirects to `dashboard.php` | Authenticated and redirected to dashboard | PASS |
| **TC-AUTH-003** | Login | Invalid password attempt | Registered user | Valid username, wrong password | Rejects: "Invalid username/email or password" | Rejection message displayed | PASS |
| **TC-AUTH-004** | Login | Non-existent username | User not in DB | Unknown username, password | Rejects: "Invalid username/email or password" | Rejection message displayed | PASS |
| **TC-AUTH-005** | Login | Empty identifier field | Login page active | Identifier = "", Password = "123" | Validation error: "Username or Email is required" | Validation error displayed | PASS |
| **TC-AUTH-006** | Login | Empty password field | Login page active | Identifier = "user", Password = "" | Validation error: "Password is required" | Validation error displayed | PASS |
| **TC-AUTH-007** | Login | Session regeneration on login | Unauthenticated | Login valid user | `session_regenerate_id(true)` issued to prevent fixation | New session ID generated upon login | PASS |
| **TC-AUTH-008** | Login | Protected page access while logged out | Logged out | Access `dashboard.php` directly | Redirects to `login.php?error=auth_required` | Redirected to login with error parameter | PASS |
| **TC-AUTH-009** | Login | Session persistence | User logged in | Navigate between pages | Session state (`user_id`, `role`, `logged_in`) maintained | Session variables retained across requests | PASS |
| **TC-AUTH-010** | Login | User logout execution | User logged in | Click Logout link | Clears `$_SESSION`, destroys session, redirects to `login.php?logout=1` | Session destroyed, redirected to login | PASS |
| **TC-AUTH-011** | Login | Back-button protection after logout | Logged out | Press Browser Back button | No-Cache headers prevent cached view, requires login | Cache headers prevented viewing authenticated state | PASS |
| **TC-AUTH-012** | Login | Admin login authentication | Admin account | Admin credentials | Authenticates admin, sets `$_SESSION['role'] = 'admin'` | Logged in as admin with admin privileges | PASS |

---

## Section 6: User Dashboard & Profile Management (12 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-DASH-001** | Dashboard | Access dashboard as user | Logged in | HTTP GET `dashboard.php` | Displays user profile summary, quick stats, quick links | Dashboard rendered cleanly | PASS |
| **TC-DASH-002** | Dashboard | Edit profile with valid data | Logged in | Update Name & Mobile | Profile updated in DB, session name updated | DB record updated, success message shown | PASS |
| **TC-DASH-003** | Dashboard | Edit profile invalid name | Edit profile active | Name = "12345" | Rejects invalid name regex pattern | Validation error displayed | PASS |
| **TC-DASH-004** | Dashboard | Edit profile invalid mobile | Edit profile active | Mobile = "123456" | Rejects invalid Indian mobile number | Validation error displayed | PASS |
| **TC-DASH-005** | Dashboard | IDOR protection on edit profile | Logged in | POST `id=999` to `edit-profile.php` | Ignores POST ID parameter, updates strictly `$_SESSION['user_id']` | Update applied strictly to session user ID | PASS |
| **TC-DASH-006** | Dashboard | Change password valid details | Logged in | Correct current pass, valid new pass | Password updated in DB using `password_hash()` | Password updated successfully | PASS |
| **TC-DASH-007** | Dashboard | Change password wrong current pass | Logged in | Wrong current pass, new pass | Rejects: "Current password is incorrect" | Rejection message displayed | PASS |
| **TC-DASH-008** | Dashboard | Change password weak new pass | Logged in | Correct current pass, new pass="123" | Rejects weak new password | Validation error displayed | PASS |
| **TC-DASH-009** | Dashboard | Change password mismatch | Logged in | New pass != Confirm new pass | Rejects password mismatch | Validation error displayed | PASS |
| **TC-DASH-010** | Dashboard | User comments count display | Comments exist | View dashboard | Accurately counts user's posted comments from DB | Displayed correct comment count metric | PASS |
| **TC-DASH-011** | Dashboard | Display user registration date | Account active | View profile card | Displays formatted `created_at` timestamp | Timestamp displayed accurately | PASS |
| **TC-DASH-012** | Dashboard | Navigation links to profile actions | Dashboard loaded | Click "Edit Profile" / "Change Password" | Navigates to corresponding forms | Form pages loaded cleanly | PASS |

---

## Section 7: Comments & Feedback (12 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-COMM-001** | Comments | View public comment list | Guest/User | HTTP GET `comments.php` | Displays all community comments with author names & timestamps | Comment list rendered cleanly | PASS |
| **TC-COMM-002** | Comments | Post comment as logged-in user | Logged in | Comment = "Great COVID awareness website!" | Comment inserted into DB, displayed in feed | Comment inserted into `comments` table | PASS |
| **TC-COMM-003** | Comments | Post comment attempt as guest | Guest | Submit comment form | Redirects to `login.php?error=auth_required` | Redirected to login page | PASS |
| **TC-COMM-004** | Comments | Post comment boundary (< 3 chars) | Logged in | Comment = "Hi" | Validation error: "Comment must be at least 3 characters" | Validation error displayed | PASS |
| **TC-COMM-005** | Comments | Post comment boundary (> 1000 chars) | Logged in | Comment = 1001 char text | Validation error: "Comment cannot exceed 1000 characters" | Validation error displayed | PASS |
| **TC-COMM-006** | Comments | Edit own comment | Author of comment | Update comment text | Comment text updated in DB, edit timestamp saved | Comment updated in DB | PASS |
| **TC-COMM-007** | Comments | Edit another user's comment (IDOR) | User A | Edit comment belonging to User B | Rejects: "You are not authorized to modify that comment" | IDOR defense rejected unauthorized edit | PASS |
| **TC-COMM-008** | Comments | Delete own comment | Author of comment | Click Delete | Comment deleted from DB | Record removed from `comments` table | PASS |
| **TC-COMM-009** | Comments | Delete another user's comment (IDOR) | User A | Delete comment belonging to User B | Rejects: "You are not authorized to modify that comment" | IDOR defense rejected unauthorized delete | PASS |
| **TC-COMM-010** | Comments | XSS script injection in comment | Logged in | `<script>alert('XSS')</script>` | Text safely escaped via `htmlspecialchars()` on output | Output rendered as plain text, no JS executed | PASS |
| **TC-COMM-011** | Comments | Empty comment submission | Logged in | Comment = "" | Validation error: "Comment cannot be empty" | Validation error displayed | PASS |
| **TC-COMM-012** | Comments | Comment author name display | Comment in DB | Page render | Joins with `users` table to display author's full name | Author full name rendered correctly | PASS |

---

## Section 8: Search Module (10 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SRCH-001** | Search | Search with valid keyword "Vaccine" | Info data exists | Query `q=Vaccine` | Returns matching COVID info articles containing "Vaccine" | Displayed matching vaccine articles | PASS |
| **TC-SRCH-002** | Search | Search with valid keyword "Symptoms" | Info data exists | Query `q=Symptoms` | Returns matching articles containing "Symptoms" | Displayed matching symptoms articles | PASS |
| **TC-SRCH-003** | Search | Search with non-existent keyword | Search active | Query `q=XYZ123NonExistent` | Displays notice: "No matching COVID-19 information articles found" | Notice displayed cleanly | PASS |
| **TC-SRCH-004** | Search | Empty search query submission | Search active | Query `q=""` | Prompts user to enter a search term | Search prompt banner displayed | PASS |
| **TC-SRCH-005** | Search | Search query boundary (1 char) | Search active | Query `q="A"` | Rejects short query: "Search query must be at least 2 characters" | Validation error displayed | PASS |
| **TC-SRCH-006** | Search | Search query boundary (> 100 chars) | Search active | Query `q=101 char string` | Truncates/rejects long query (> 100 chars) | Rejection error displayed | PASS |
| **TC-SRCH-007** | Search | SQL injection payload in search | Search active | Query `q="' OR '1'='1"` | Prepared statement parameter binding treats input as literal string | No SQL error, executed safely | PASS |
| **TC-SRCH-008** | Search | XSS script payload in search | Search active | Query `q="<script>alert(1)</script>"` | Input safely escaped on result page via `htmlspecialchars()` | Escaped string displayed, no JS execution | PASS |
| **TC-SRCH-009** | Search | Case-insensitive search match | Info data exists | Query `q="vAcCiNe"` | SQL `LIKE %vAcCiNe%` matches records regardless of letter case | Matches returned successfully | PASS |
| **TC-SRCH-010** | Search | Search results counter display | Search active | Query `q="COVID"` | Displays exact result count badge (e.g. "Found 4 results") | Result count badge displayed | PASS |

---

## Section 9: Admin Dashboard & Management (20 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-ADMIN-001** | Admin | Admin portal access | Logged in as admin | Access `admin/dashboard.php` | Access granted, displays administrative metrics summary | Admin dashboard rendered cleanly | PASS |
| **TC-ADMIN-002** | Admin | Normal user admin portal access | Logged in as user | Access `admin/dashboard.php` | Access denied, redirected to `dashboard.php?error=admin_required` | Redirected to user dashboard | PASS |
| **TC-ADMIN-003** | Admin | Unauthenticated admin access | Logged out | Access `admin/users.php` | Access denied, redirected to `login.php?error=auth_required` | Redirected to login page | PASS |
| **TC-ADMIN-004** | Admin | View registered users list | Logged in as admin | Access `admin/users.php` | Renders user table with ID, Name, Email, Username, Mobile, Role | User table rendered cleanly | PASS |
| **TC-ADMIN-005** | Admin | Update user role (User -> Admin) | Logged in as admin | Target user ID, new role = `admin` | User role updated in DB | Target user promoted to admin | PASS |
| **TC-ADMIN-006** | Admin | Self-demotion defense | Logged in as admin | Target = Admin's own ID, new role = `user` | Rejects: "You cannot remove your own admin privileges" | Self-demotion blocked | PASS |
| **TC-ADMIN-007** | Admin | Delete user account | Logged in as admin | Target user ID, click Delete | User account deleted from DB | Account deleted successfully | PASS |
| **TC-ADMIN-008** | Admin | Create COVID info article | Logged in as admin | Title, Category, Description | Article inserted into `covid_info` table | Article created successfully | PASS |
| **TC-ADMIN-009** | Admin | Create COVID info empty title | Admin page | Title = "", Category = "Symptoms" | Validation error: "Title and description are required" | Validation error displayed | PASS |
| **TC-ADMIN-010** | Admin | Edit COVID info article | Logged in as admin | Info ID, updated title/description | Article updated in DB | Article updated successfully | PASS |
| **TC-ADMIN-011** | Admin | Delete COVID info article | Logged in as admin | Info ID, click Delete | Article removed from DB | Article deleted successfully | PASS |
| **TC-ADMIN-012** | Admin | Create country statistics record | Logged in as admin | Country, Cases, Recovered, Deaths | Record inserted into `statistics` table | Record inserted successfully | PASS |
| **TC-ADMIN-013** | Admin | Create statistics duplicate country | Admin page | Country = existing country | Rejects: "Statistics for this country already exist" | Duplicate country rejected | PASS |
| **TC-ADMIN-014** | Admin | Create statistics negative values | Admin page | Cases = -500 | Validation error: "Metric values cannot be negative" | Validation error displayed | PASS |
| **TC-ADMIN-015** | Admin | Edit country statistics | Logged in as admin | Statistics ID, updated numbers | Record updated in DB | Record updated successfully | PASS |
| **TC-ADMIN-016** | Admin | Delete country statistics | Logged in as admin | Statistics ID, click Delete | Record deleted from DB | Record deleted successfully | PASS |
| **TC-ADMIN-017** | Admin | Moderate/Delete any comment | Logged in as admin | Comment ID, click Delete | Removes any inappropriate user comment from DB | Comment deleted by admin | PASS |
| **TC-ADMIN-018** | Admin | View contact messages | Logged in as admin | Access `admin/contact-messages.php` | Displays list of messages submitted via Contact Us | Message list rendered cleanly | PASS |
| **TC-ADMIN-019** | Admin | Delete contact message | Logged in as admin | Message ID, click Delete | Message deleted from DB | Message deleted successfully | PASS |
| **TC-ADMIN-020** | Admin | System aggregate metrics count | Admin page | View dashboard cards | Displays accurate total users, info, stats, comments, and messages count | Aggregate counts displayed accurately | PASS |

---

## Section 10: Contact Us Module (10 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-CONT-001** | Contact Us | Submit valid contact message | Guest/User | Valid name, email, subject, message | Message inserted into `contact_messages` table, success message shown | Message submitted successfully | PASS |
| **TC-CONT-002** | Contact Us | Empty name field | Contact page | Name = "", valid message | Validation error: "Full Name is required" | Validation error displayed | PASS |
| **TC-CONT-003** | Contact Us | Invalid email format | Contact page | Email = "john@invalid" | Validation error: "Please enter a valid email address" | Validation error displayed | PASS |
| **TC-CONT-004** | Contact Us | Empty subject field | Contact page | Subject = "", valid message | Validation error: "Subject is required" | Validation error displayed | PASS |
| **TC-CONT-005** | Contact Us | Message boundary (< 10 chars) | Contact page | Message = "Short" | Validation error: "Message must contain at least 10 characters" | Validation error displayed | PASS |
| **TC-CONT-006** | Contact Us | Message boundary (> 2000 chars) | Contact page | Message = 2001 char text | Validation error: "Message cannot exceed 2000 characters" | Validation error displayed | PASS |
| **TC-CONT-007** | Contact Us | Pre-fill logged-in user details | Logged in user | Open `contact.php` | Name & Email automatically pre-filled in form fields | Name and email pre-filled from session/DB | PASS |
| **TC-CONT-008** | Contact Us | XSS script injection in contact message | Contact page | `<script>alert('XSS')</script>` | Sanitized with `htmlspecialchars()` before admin rendering | Admin panel displays plain text safely | PASS |
| **TC-CONT-009** | Contact Us | SQL injection payload in message | Contact page | `' OR '1'='1` in message | Handled via prepared statement parameter binding | Saved as plain text string without SQL error | PASS |
| **TC-CONT-010** | Contact Us | Contact form reset after success | Form submitted | Submit valid message | Form input fields cleared after successful submission | Form inputs cleared cleanly | PASS |

---

## Section 11: Application Security (15 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SEC-001** | Security | SQL Injection on login identifier | Login active | Identifier = `' OR '1'='1` | Parameterized query blocks SQL injection attempt | Authentication failed safely | PASS |
| **TC-SEC-002** | Security | SQL Injection on registration username | Reg active | Username = `admin'; DROP TABLE users;--` | Parameterized query treats input as literal string | Rejection / literal insertion, DB intact | PASS |
| **TC-SEC-003** | Security | XSS Defense on public comments | Comments active | Text = `<img src=x onerror=alert(1)>` | Output escaped using `htmlspecialchars(ENT_QUOTES, 'UTF-8')` | Script tag displayed as text, no alert pop-up | PASS |
| **TC-SEC-004** | Security | XSS Defense on profile name | Edit profile | Name = `<script>window.location='http://attacker.com'</script>` | Rejected by Regex `/^[A-Za-z ]{2,100}$/` | Rejected by regex pattern | PASS |
| **TC-SEC-005** | Security | Unauthenticated protected route access | Logged out | Access `dashboard.php`, `edit-profile.php`, `change-password.php` | Redirects to `login.php?error=auth_required` | All protected routes redirected | PASS |
| **TC-SEC-006** | Security | Normal user admin route access | Logged in as user | Access `admin/dashboard.php`, `admin/users.php`, etc. | Redirects to `dashboard.php?error=admin_required` | Admin routes blocked for normal user | PASS |
| **TC-SEC-007** | Security | IDOR defense on profile edit | Logged in as user 10 | Send POST request with `user_id=1` | Ignore POST ID parameter, update strictly `$_SESSION['user_id']` | Profile update restricted to logged-in user | PASS |
| **TC-SEC-008** | Security | IDOR defense on comment editing | Logged in as user 10 | Edit comment ID 5 (authored by user 2) | Rejects: "You are not authorized to modify that comment" | Edit rejected with HTTP redirect/error | PASS |
| **TC-SEC-009** | Security | IDOR defense on comment deletion | Logged in as user 10 | Delete comment ID 5 (authored by user 2) | Rejects: "You are not authorized to modify that comment" | Delete rejected with HTTP redirect/error | PASS |
| **TC-SEC-0010** | Security | Password hashing validation | User registered | Inspect `users` database table | `password` column contains `$2y$` BCRYPT hash, no plaintext | Password stored as 60-char BCRYPT hash | PASS |
| **TC-SEC-011** | Security | Password verification check | Login attempt | Test with `password_verify()` API | Password verified against hash securely | `password_verify()` returns true for valid password | PASS |
| **TC-SEC-012** | Security | Session fixation prevention | Login attempt | Successful login | Invokes `session_regenerate_id(true)` to refresh session ID | New session ID issued upon login | PASS |
| **TC-SEC-013** | Security | HTTP No-Cache security headers | Authenticated pages | Inspect HTTP response headers | `Cache-Control: no-store, no-cache, must-revalidate, max-age=0` present | Headers present, prevents browser back-button caching | PASS |
| **TC-SEC-014** | Security | CSRF defense & GET mutation prevention | Application | Perform DELETE/UPDATE via HTTP GET | Core mutation operations require HTTP POST method | GET mutation blocked or requiring POST validation | PASS |
| **TC-SEC-015** | Security | Sensitive error stack trace suppression | Production env | Trigger database exception | Errors logged internally via `error_log()`, generic message displayed | User shown friendly error, stack trace hidden | PASS |

---

## Section 12: Regression Testing (10 Test Cases)

| Test Case ID | Module | Scenario | Precondition | Input | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-REGRESS-001** | Regression | Home page navigation links after Module 10 | All modules complete | Click all navbar links | All navbar links function cleanly without 404 broken links | All navigation links resolved cleanly | PASS |
| **TC-REGRESS-002** | Regression | COVID info category filtering after Module 10 | All modules complete | Filter info categories | Info filtering functions identically to Module 2 | Category filtering working intact | PASS |
| **TC-REGRESS-003** | Regression | COVID statistics calculations after Module 10 | All modules complete | View statistics page | Statistics calculations and sorting remain accurate | Aggregate statistics calculated accurately | PASS |
| **TC-REGRESS-004** | Regression | Regex validation on registration after Module 10 | All modules complete | Submit invalid registration form | Regex patterns enforce strict input validation intact | All 5 regex patterns enforcing validation | PASS |
| **TC-REGRESS-005** | Regression | Authentication session state after Module 10 | All modules complete | Login, browse pages, logout | Login/Logout session management remains fully functional | Session state intact | PASS |
| **TC-REGRESS-006** | Regression | Profile management after Module 10 | All modules complete | Edit profile & change password | User profile updates commit to database cleanly | Profile & password updates working intact | PASS |
| **TC-REGRESS-007** | Regression | Community comments feed after Module 10 | All modules complete | Post, edit, delete comments | User comment CRUD operations work cleanly | Comments CRUD functioning cleanly | PASS |
| **TC-REGRESS-008** | Regression | Keyword search functionality after Module 10 | All modules complete | Perform keyword search | Search queries match title/description correctly | Search results returned accurately | PASS |
| **TC-REGRESS-009** | Regression | Admin dashboard management after Module 10 | All modules complete | Perform admin CRUD operations | Admin management of users, info, stats, comments, and messages works | Admin management operations intact | PASS |
| **TC-REGRESS-010** | Regression | Contact Us form submission after Module 10 | All modules complete | Submit guest contact message | Contact message saved to DB and visible in Admin panel | Contact form and admin message view intact | PASS |
