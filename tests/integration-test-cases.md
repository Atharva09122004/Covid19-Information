# Integration Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Integration Test Cases**: 10  
**Pass Rate**: 100% (10 PASS / 0 FAIL)

---

## Cross-Module Integration Workflow Tests

### 1. Registration → Login Integration
- **Test ID**: `TC-INT-001`
- **Modules Involved**: Module 4 (Registration) & Module 5 (Authentication)
- **Workflow**: User registers on `register.php` with new credentials -> Account created in `users` table -> User navigates to `login.php` -> Authenticates using new credentials.
- **Expected Result**: Account is created cleanly with BCRYPT password hash, and subsequent login attempt succeeds, redirecting to `dashboard.php`.
- **Actual Result**: User registered and successfully authenticated on `login.php`.
- **Status**: PASS

---

### 2. Login → Dashboard Integration
- **Test ID**: `TC-INT-002`
- **Modules Involved**: Module 5 (Authentication) & Module 6 (User Dashboard)
- **Workflow**: Authenticate on `login.php` -> Session `user_id`, `role`, `logged_in` set -> Redirect to `dashboard.php` -> Middleware `includes/auth.php` verifies active session.
- **Expected Result**: Dashboard renders user details (Name, Username, Email, Registration Date) directly from database using session user ID.
- **Actual Result**: Dashboard loaded cleanly with active session profile details.
- **Status**: PASS

---

### 3. Dashboard → Edit Profile Integration
- **Test ID**: `TC-INT-003`
- **Modules Involved**: Module 6 (User Dashboard & Edit Profile)
- **Workflow**: Click "Edit Profile" link on dashboard -> Form opens pre-filled with current name & mobile -> Modify name & mobile -> Submit form -> Database record updated -> Session `name` updated -> Redirect to dashboard.
- **Expected Result**: Database record updated, success message displayed on dashboard, and top header navigation instantly reflects updated name.
- **Actual Result**: Profile updated in database, session state refreshed, success alert rendered on dashboard.
- **Status**: PASS

---

### 4. Login → Comments Integration
- **Test ID**: `TC-INT-004`
- **Modules Involved**: Module 5 (Authentication) & Module 7 (Comments & Feedback)
- **Workflow**: Authenticate as normal user -> Navigate to `comments.php` -> Session checked (`isLoggedIn = true`) -> Comment submission form enabled -> Submit comment.
- **Expected Result**: Comment submission form is active for logged-in user, accepting new comment text.
- **Actual Result**: Form enabled, comment submitted successfully.
- **Status**: PASS

---

### 5. Comments → Database Integration
- **Test ID**: `TC-INT-005`
- **Modules Involved**: Module 7 (Comments) & MySQL Database (`comments` table)
- **Workflow**: Submit comment on `comments.php` -> PDO executes `INSERT INTO comments (user_id, comment_text, created_at)` -> Page reloads -> PDO executes `SELECT comments.*, users.name FROM comments JOIN users...` -> New comment appears in public feed.
- **Expected Result**: Comment stored in MySQL database and rendered in public feed with correct author name and timestamp.
- **Actual Result**: SQL INSERT executed cleanly, comment rendered in public feed.
- **Status**: PASS

---

### 6. Search → COVID Information Database Integration
- **Test ID**: `TC-INT-006`
- **Modules Involved**: Module 2 (COVID Info) & Module 8 (Search)
- **Workflow**: Enter search term "Vaccine" on `search.php` -> Form submits GET query -> PDO executes `SELECT * FROM covid_info WHERE title LIKE ? OR description LIKE ?` -> Results rendered.
- **Expected Result**: Search queries `covid_info` database table and displays matching articles matching keyword.
- **Actual Result**: Search query executed successfully, matching articles displayed.
- **Status**: PASS

---

### 7. Contact → Database → Admin Panel Integration
- **Test ID**: `TC-INT-007`
- **Modules Involved**: Module 10 (Contact Us) & Module 9 (Admin Messages)
- **Workflow**: Guest submits inquiry on `contact.php` -> Record inserted into `contact_messages` table -> Admin logs in -> Navigates to `admin/contact-messages.php` -> Message rendered in admin inbox.
- **Expected Result**: Contact inquiry saved in database and immediately visible to website administrator in admin panel.
- **Actual Result**: Message saved in database and rendered cleanly in admin contact inbox.
- **Status**: PASS

---

### 8. Admin → COVID Information CRUD → Public Page Integration
- **Test ID**: `TC-INT-008`
- **Modules Involved**: Module 9 (Admin Info Management) & Module 2 (Public COVID Info)
- **Workflow**: Admin opens `admin/covid-info.php` -> Creates new article "Delta Variant Symptoms" -> Record inserted into `covid_info` table -> Public visitor opens `covid-info.php`.
- **Expected Result**: Article created by admin is instantly visible to public visitors on public COVID Information page.
- **Actual Result**: Article created in admin panel rendered immediately on public website.
- **Status**: PASS

---

### 9. Admin → Statistics CRUD → Public Statistics Page Integration
- **Test ID**: `TC-INT-009`
- **Modules Involved**: Module 9 (Admin Statistics Management) & Module 3 (Public Statistics)
- **Workflow**: Admin opens `admin/statistics.php` -> Updates country cases for "India" -> Record updated in `statistics` table -> Public visitor opens `statistics.php`.
- **Expected Result**: Updated statistics and aggregate global totals reflect changes on public statistics dashboard.
- **Actual Result**: Updated statistics metrics rendered cleanly on public statistics dashboard.
- **Status**: PASS

---

### 10. Admin → Comments Management → User Comment List Integration
- **Test ID**: `TC-INT-010`
- **Modules Involved**: Module 9 (Admin Comment Moderation) & Module 7 (Public Comments)
- **Workflow**: User posts comment on `comments.php` -> Admin opens `admin/comments.php` -> Clicks "Delete" on inappropriate comment -> Record removed from `comments` table -> User refreshes `comments.php`.
- **Expected Result**: Deleted comment is permanently removed from database and disappears from public feed.
- **Actual Result**: Deleted comment removed from database and no longer visible in public comment feed.
- **Status**: PASS
