# Regression Test Cases Document (Module 11)
**Project**: COVID-19 Information and Awareness Website  
**Total Executed Regression Test Cases**: 10  
**Pass Rate**: 100% (10 PASS / 0 FAIL)

---

## Regression Verification Suite

| Test Case ID | Target Feature | Initial Module | Test Procedure | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-REGRESS-001** | Home Page Layout & Navigation | Module 1 | Execute full navigation check across header & footer links after completing Module 10 | All links resolve correctly without 404 broken links or broken styles | All home page components intact and aligned | PASS |
| **TC-REGRESS-002** | COVID Info Filtering | Module 2 | Filter articles by category on `covid-info.php` after completing Module 10 | Category filtering functions cleanly as implemented in Module 2 | Category filtering working intact | PASS |
| **TC-REGRESS-003** | Statistics Calculations | Module 3 | Verify aggregate SUM calculations and country sorting on `statistics.php` | Statistics calculations and sorting remain accurate | Aggregate calculations accurate | PASS |
| **TC-REGRESS-004** | Regex Registration Validation | Module 4 | Submit invalid form inputs on `register.php` after completing Module 10 | All 5 Regex validation patterns enforce strict client & server rules | Form regex validation intact | PASS |
| **TC-REGRESS-005** | Authentication & Sessions | Module 5 | Login, access protected routes, and logout after completing Module 10 | Password hashing, session creation, and session destruction function cleanly | Session management intact | PASS |
| **TC-REGRESS-006** | Dashboard Profile Management | Module 6 | Update profile & change password on `dashboard.php` after completing Module 10 | User profile updates commit to database without breaking user state | Profile & password update intact | PASS |
| **TC-REGRESS-007** | Community Comments Feed | Module 7 | Post, edit, and delete comments on `comments.php` after completing Module 10 | User comment CRUD operations work cleanly with IDOR protection | Comment operations intact | PASS |
| **TC-REGRESS-008** | Keyword Search Engine | Module 8 | Perform keyword search queries on `search.php` after completing Module 10 | Search engine returns accurate matches from database | Search matching intact | PASS |
| **TC-REGRESS-009** | Admin Portal CRUD | Module 9 | Perform admin user promotion, info CRUD, and statistics CRUD | Admin management features remain operational with strict authorization | Admin CRUD intact | PASS |
| **TC-REGRESS-010** | Contact Us Submission | Module 10 | Submit guest contact inquiry on `contact.php` and verify admin inbox | Contact message saved to DB and rendered in admin inbox cleanly | Contact form & admin inbox intact | PASS |
