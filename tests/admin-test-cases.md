# MODULE 9 — ADMIN DASHBOARD AND MANAGEMENT TEST CASES

**Project:** COVID-19 Information and Awareness Website  
**Module:** Module 9 (Admin Dashboard and Management)

---

## 1. Administrative Functional & Security Test Cases (TC-ADMIN-01 to TC-ADMIN-16)

| Test Case ID | Test Scenario | Input Data / Condition | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-ADMIN-01** | Admin login & access | Admin credentials (`role=admin`) | Access granted to `admin/dashboard.php` | Access granted | **PASS** |
| **TC-ADMIN-02** | Normal user accesses admin dashboard | Normal user (`role=user`) opens `/admin/dashboard.php` | Access denied; redirected to `dashboard.php?error=admin_required` | Access denied | **PASS** |
| **TC-ADMIN-03** | Unauthenticated user accesses admin dashboard | Guest opens `/admin/dashboard.php` | Access denied; redirected to `login.php?error=auth_required` | Redirected | **PASS** |
| **TC-ADMIN-04** | Admin views registered users | Open `admin/users.php` | Full list of registered users displayed (no passwords) | User list displayed | **PASS** |
| **TC-ADMIN-05** | Admin deletes user | POST `action=delete_user`, `target_user_id=X` | User removed from DB; comments cascade deleted | User deleted | **PASS** |
| **TC-ADMIN-06** | Admin attempts self-demotion | POST `action=update_role`, `new_role=user` on self ID | Operation rejected: *"You cannot remove your own admin privileges."* | Operation rejected | **PASS** |
| **TC-ADMIN-07** | Admin adds COVID information | Title: `"Mask Guide"`, Category: `"Prevention"`, Description: `"..."` | Record inserted into `covid_info` table | Record inserted | **PASS** |
| **TC-ADMIN-08** | Admin edits COVID information | Update title & category of existing article | Article updated in DB | Record updated | **PASS** |
| **TC-ADMIN-09** | Admin deletes COVID information | POST `action=delete_info`, `id=X` | Article removed from DB | Record deleted | **PASS** |
| **TC-ADMIN-10** | Admin adds statistics | Country: `"Testland"`, Cases: `1000`, Recovered: `800`, Deaths: `50` | Record inserted into `statistics` table | Record inserted | **PASS** |
| **TC-ADMIN-11** | Invalid statistics validation | Cases: `100`, Recovered: `90`, Deaths: `20` (`90+20 > 100`) | Operation rejected: Recovered + deaths cannot exceed total cases | Rejected cleanly | **PASS** |
| **TC-ADMIN-12** | Admin edits statistics | Update cases/recovered/deaths values | Record updated in DB | Record updated | **PASS** |
| **TC-ADMIN-13** | Admin deletes statistics | POST `action=delete_stat`, `id=X` | Record removed from DB | Record deleted | **PASS** |
| **TC-ADMIN-14** | Admin deletes comment | POST `action=delete_comment`, `id=X` | Comment removed from DB | Comment deleted | **PASS** |
| **TC-ADMIN-15** | SQL injection test | Input: `' OR '1'='1` in admin forms | Handled safely via PDO prepared statements | No SQL injection | **PASS** |
| **TC-ADMIN-16** | XSS payload test | Input: `<script>alert('XSS')</script>` | Input escaped via `htmlspecialchars()`; displayed as text | Script escaped | **PASS** |
