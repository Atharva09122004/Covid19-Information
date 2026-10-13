# ⚙️ COVID-19 Portal — Administrative Subsystem

This directory contains the administrative control panel and moderation interface for the **COVID-19 Information and Awareness Website**.

---

## 🔒 Security & Access Control

Access to this directory is strictly restricted to authenticated users with `role = 'admin'`.

- **Middleware Enforcement**: Every file in this directory includes `includes/admin-auth.php`.
- **Unauthorized Handling**: If an unauthenticated user or normal user (`role = 'user'`) attempts to access any file in this directory, the request is immediately terminated, and the user is redirected to the login page or user dashboard with an `admin_required` error parameter.
- **Self-Demotion Defense**: The user management controller (`users.php`) explicitly blocks an active administrator from revoking their own administrator privileges, preventing administrative lockout.

---

## 📂 Subsystem File Map

| File | Purpose | Key Operations |
| :--- | :--- | :--- |
| [`dashboard.php`](dashboard.php) | Admin Overview | Real-time aggregate count cards (Users, Articles, Countries, Comments, Messages) and quick administrative actions. |
| [`users.php`](users.php) | User Management | Lists all registered accounts, role updates (Promote to Admin / Demote to User), and user account deletion. |
| [`covid-info.php`](covid-info.php) | Information CRUD | Create, edit, and delete COVID-19 educational articles (Overview, Symptoms, Prevention, Safety). |
| [`statistics.php`](statistics.php) | Statistics CRUD | Manage national and global statistics records (Total Cases, Discharged/Recovered, Deaths). |
| [`comments.php`](comments.php) | Content Moderation | Review community feedback and delete inappropriate or abusive user comments. |
| [`contact-messages.php`](contact-messages.php) | Support Inbox | View and manage visitor inquiries submitted via the public Contact Us form. |
| `includes/admin-header.php` | Shared Admin Nav | Global administrative navigation bar with links to the main website and admin tools. |
| `includes/admin-footer.php` | Shared Admin Footer | Consistent administrative portal footer with copyright and environment metadata. |

---

## 🔑 Default Administrator Credentials

| Field | Value |
| :--- | :--- |
| **Login URL** | `http://localhost/Covid19-Information/login.php` |
| **Username** | `admin` *(or `admin@covidshield.org`)* |
| **Password** | `AdminPass123!` |
| **Target Route** | `http://localhost/Covid19-Information/admin/dashboard.php` |
