# System Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total System Scenarios**: 2 End-to-End Complete Workflows  
**Pass Rate**: 100% (2 PASS / 0 FAIL)

---

## System Test 1: Complete Public Visitor & Registered User End-to-End Workflow
- **Test ID**: `TC-SYS-001`
- **Objective**: Verify that a new visitor can seamlessly navigate the public website, register an account, log in, manage profile details, post community comments, perform search queries, send contact inquiries, and log out.

### Execution Sequence:
1. **Visitor Landing**: Access `index.php` -> Home page loads cleanly with hero section and prevention cards.
2. **Information Browsing**: Navigate to `covid-info.php` -> Filter articles by category "Prevention".
3. **Statistics Inspection**: Navigate to `statistics.php` -> Inspect global summary cards and filter by country "India".
4. **User Registration**: Navigate to `register.php` -> Fill valid details (Name, Email, Mobile, Username, Password) -> Submit form -> Account created in MySQL.
5. **Authentication**: Navigate to `login.php` -> Log in with new credentials -> Authenticated and redirected to `dashboard.php`.
6. **Dashboard & Profile**: View `dashboard.php` -> Click "Edit Profile" (`edit-profile.php`) -> Update Mobile Number -> Save changes -> Redirect back to dashboard.
7. **Password Security**: Click "Change Password" (`change-password.php`) -> Update password -> Password hash updated in database.
8. **Community Feedback**: Navigate to `comments.php` -> Post new comment -> Comment saved and displayed in public feed.
9. **Information Search**: Navigate to `search.php` -> Enter query "Vaccine" -> View matching COVID-19 articles.
10. **Contact Inquiry**: Navigate to `contact.php` -> Name & Email pre-filled -> Fill subject and message -> Submit form -> Inquiry saved in database.
11. **Session Termination**: Click "Logout" link -> Session destroyed -> Redirected to `login.php?logout=1`.

- **Expected Result**: All steps execute sequentially without error, database updates commit correctly, and session state transitions safely.
- **Actual Result**: Complete visitor-to-user workflow executed flawlessly.
- **Status**: PASS

---

## System Test 2: Complete Administrator End-to-End Management Workflow
- **Test ID**: `TC-SYS-002`
- **Objective**: Verify that an administrator can authenticate into the admin portal, manage registered users, maintain COVID-19 information articles, update country statistics metrics, moderate community comments, handle contact inquiries, and log out cleanly.

### Execution Sequence:
1. **Admin Login**: Access `login.php` -> Enter admin credentials (`admin` / `AdminPass123!`) -> Redirected to `admin/dashboard.php`.
2. **Dashboard Overview**: Inspect `admin/dashboard.php` -> Review aggregate system metrics (Total Users, Info Articles, Statistics Records, Comments, Contact Messages).
3. **User Management**: Navigate to `admin/users.php` -> View registered users list -> Promote user role -> Attempt self-demotion (verified self-demotion defense blocked).
4. **COVID Info CRUD**: Navigate to `admin/covid-info.php` -> Add new article "Booster Doses" -> Edit article title -> Verify article visible on public `covid-info.php` -> Delete article.
5. **Statistics CRUD**: Navigate to `admin/statistics.php` -> Add statistics record for new country -> Edit cases/recovered count -> Verify changes reflect on public `statistics.php` -> Delete test record.
6. **Comment Moderation**: Navigate to `admin/comments.php` -> Review user comments -> Delete inappropriate test comment -> Verified comment removed from public `comments.php`.
7. **Contact Inbox**: Navigate to `admin/contact-messages.php` -> Review guest messages -> Delete handled contact inquiry.
8. **Main Website Navigation**: Click "🌐 Main Website" link in header -> Navigate to public home page while retaining admin session.
9. **Admin Logout**: Click "Logout (Admin)" -> Session destroyed -> Redirected to `login.php?logout=1`.

- **Expected Result**: Full administrative portal CRUD and moderation features function cleanly with strict authorization checks.
- **Actual Result**: Complete admin workflow executed cleanly without defect.
- **Status**: PASS
