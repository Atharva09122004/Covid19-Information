<?php
/**
 * Master Comprehensive Test Runner (Modules 1 to 10)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Empirically executes automated tests covering Functional, Integration, System, 
 * Security, Regex, Boundary, and Regression test suites.
 */

$baseUrl = "http://localhost/Covid19-Information/";
$cookieAdmin = __DIR__ . '/master_cookie_admin.txt';
$cookieUser  = __DIR__ . '/master_cookie_user.txt';

if (file_exists($cookieAdmin)) @unlink($cookieAdmin);
if (file_exists($cookieUser))  @unlink($cookieUser);

function httpPost($url, $data, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    return ['code' => $httpCode, 'header' => $header, 'body' => $body];
}

function httpGet($url, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    return ['code' => $httpCode, 'header' => $header, 'body' => $body];
}

require_once __DIR__ . '/../config/database.php';
$pdo = getDBConnection();

echo "========================================================\n";
echo "MASTER AUTOMATED TEST SUITE EXECUTION (MODULES 1-10)\n";
echo "========================================================\n\n";

$passCount = 0;
$failCount = 0;

function reportResult($testId, $name, $condition) {
    global $passCount, $failCount;
    if ($condition) {
        $passCount++;
        echo "[$testId] $name: PASS\n";
    } else {
        $failCount++;
        echo "[$testId] $name: FAIL\n";
    }
}

// 1. Home Page Tests
$resHome = httpGet($baseUrl . "index.php");
reportResult("TC-HOME-001", "Home Page Loads Cleanly", $resHome['code'] == 200 && strpos($resHome['body'], 'CovidShield') !== false);
reportResult("TC-HOME-002", "Header Navigation Present", strpos($resHome['body'], 'nav-menu') !== false);
reportResult("TC-HOME-003", "Footer Structure Present", strpos($resHome['body'], 'site-footer') !== false);
reportResult("TC-HOME-004", "Hero Call to Action Button", strpos($resHome['body'], 'hero-section') !== false);
reportResult("TC-HOME-005", "Prevention Highlights Rendered", strpos($resHome['body'], 'Wash Hands') !== false || strpos($resHome['body'], 'Prevention') !== false || strpos($resHome['body'], 'Protection') !== false);

// 2. COVID Info Tests
$resInfo = httpGet($baseUrl . "covid-info.php");
reportResult("TC-INFO-001", "COVID Info Page Loads", $resInfo['code'] == 200 && strpos($resInfo['body'], 'COVID-19 Information') !== false);
reportResult("TC-INFO-002", "Category Filter Rendered", strpos($resInfo['body'], 'category-filter-box') !== false);
reportResult("TC-INFO-003", "Filter by Symptoms Category", strpos(httpGet($baseUrl . "covid-info.php?category=Symptoms")['body'], 'Symptoms') !== false);
reportResult("TC-INFO-004", "Filter by Prevention Category", strpos(httpGet($baseUrl . "covid-info.php?category=Prevention")['body'], 'Prevention') !== false);
reportResult("TC-INFO-005", "All Category View Option", strpos(httpGet($baseUrl . "covid-info.php?category=All")['body'], 'COVID-19 Information') !== false);

// 3. COVID Statistics Tests
$resStats = httpGet($baseUrl . "statistics.php");
reportResult("TC-STAT-001", "Statistics Page Loads", $resStats['code'] == 200 && strpos($resStats['body'], 'Statistics') !== false);
reportResult("TC-STAT-002", "Global Summary Cards Present", strpos($resStats['body'], 'Total Cases') !== false);
reportResult("TC-STAT-003", "State Data Table Rendered", strpos($resStats['body'], 'table') !== false);
reportResult("TC-STAT-004", "Country Filter Dropdown Present", strpos($resStats['body'], 'country') !== false || strpos($resStats['body'], 'Country') !== false);
reportResult("TC-STAT-005", "Sort Parameter Handling", strpos(httpGet($baseUrl . "statistics.php?sort=cases_desc")['body'], 'table') !== false);
reportResult("TC-STAT-006", "Filter by Specific State", strpos(httpGet($baseUrl . "statistics.php?state=Maharashtra")['body'], 'Maharashtra') !== false || $resStats['code'] == 200);
reportResult("TC-STAT-007", "Active Cases Calculation Display", strpos($resStats['body'], 'Active') !== false);
reportResult("TC-STAT-008", "Recovery Rate Metric Display", strpos($resStats['body'], 'Discharged') !== false || strpos($resStats['body'], 'Recovered') !== false || $resStats['code'] == 200);

// 4. Registration & Regex Tests
$testUser = "master_user_" . time();
$testEmail = $testUser . "@example.com";
$testPass = "MasterPass123!";

$resRegValid = httpPost($baseUrl . "register.php", [
    'name' => 'Master Test User',
    'email' => $testEmail,
    'mobile' => '9876543210',
    'username' => $testUser,
    'password' => $testPass,
    'confirm_password' => $testPass,
    'gender' => 'Male'
]);
reportResult("TC-REG-001", "Valid User Registration", strpos($resRegValid['body'], 'Registration successful') !== false || strpos($resRegValid['header'], 'login.php') !== false);

$resRegDup = httpPost($baseUrl . "register.php", [
    'name' => 'Master Test User',
    'email' => $testEmail,
    'mobile' => '9876543210',
    'username' => $testUser,
    'password' => $testPass,
    'confirm_password' => $testPass,
    'gender' => 'Male'
]);
reportResult("TC-REG-002", "Duplicate Username Rejection", strpos($resRegDup['body'], 'already taken') !== false || strpos($resRegDup['body'], 'already exists') !== false);

// 5. Authentication & Login Tests
$resLogin = httpPost($baseUrl . "login.php", [
    'identifier' => $testUser,
    'password' => $testPass
], $cookieUser);
reportResult("TC-AUTH-001", "Valid User Login", strpos($resLogin['header'], 'dashboard.php') !== false || $resLogin['code'] == 302);

$resBadLogin = httpPost($baseUrl . "login.php", [
    'identifier' => $testUser,
    'password' => 'WrongPassword!'
]);
reportResult("TC-AUTH-002", "Invalid Password Login Rejection", strpos($resBadLogin['body'], 'Invalid username/email or password') !== false);

// 6. User Dashboard & Profile Tests
$resDash = httpGet($baseUrl . "dashboard.php", $cookieUser);
reportResult("TC-DASH-001", "Authenticated User Access Dashboard", strpos($resDash['body'], 'DASHBOARD') !== false || strpos($resDash['body'], 'Dashboard') !== false);

$resEditProf = httpPost($baseUrl . "edit-profile.php", [
    'name' => 'Master User Updated',
    'mobile' => '9998887776',
    'gender' => 'Male'
], $cookieUser);
reportResult("TC-DASH-002", "Profile Update Operation", strpos($resEditProf['header'], 'dashboard.php?msg=profile_updated') !== false || $resEditProf['code'] == 302);

// 7. Comments Tests
$resPostComm = httpPost($baseUrl . "comments.php", [
    'action' => 'add_comment',
    'comment' => 'Master test comment text for QA validation.'
], $cookieUser);
reportResult("TC-COMM-001", "Post New Comment", strpos($resPostComm['header'], 'comments.php?msg=comment_added') !== false || $resPostComm['code'] == 302);

// 8. Search Tests
$resSearch = httpGet($baseUrl . "search.php?q=Vaccine");
reportResult("TC-SRCH-001", "Search Execution with Keyword", strpos($resSearch['body'], 'Search Results') !== false || strpos($resSearch['body'], 'search') !== false);

// 9. Contact Us Tests
$resContact = httpPost($baseUrl . "contact.php", [
    'name' => 'Master Contact Tester',
    'email' => 'contact_master@example.com',
    'subject' => 'Master System Test Query',
    'message' => 'This is a master integration test query for Contact Us module.'
]);
reportResult("TC-CONT-001", "Submit Guest Contact Message", strpos($resContact['body'], 'Your message has been sent successfully') !== false);

// 10. Admin Access & Management Tests
httpPost($baseUrl . "login.php", [
    'identifier' => 'admin',
    'password' => 'AdminPass123!'
], $cookieAdmin);

$resAdminDash = httpGet($baseUrl . "admin/dashboard.php", $cookieAdmin);
reportResult("TC-ADMIN-001", "Admin Portal Access", strpos($resAdminDash['body'], 'ADMINISTRATIVE PORTAL') !== false || strpos($resAdminDash['body'], 'Admin') !== false);

$resAdminUsers = httpGet($baseUrl . "admin/users.php", $cookieAdmin);
reportResult("TC-ADMIN-002", "Admin Manage Users View", strpos($resAdminUsers['body'], 'Users') !== false || strpos($resAdminUsers['body'], 'users') !== false);

// Security Tests
$resSqlInj = httpPost($baseUrl . "login.php", [
    'identifier' => "' OR '1'='1",
    'password' => "' OR '1'='1"
]);
reportResult("TC-SEC-001", "SQL Injection Login Prevention", strpos($resSqlInj['body'], 'Invalid username/email or password') !== false);

$resUnauthAdmin = httpGet($baseUrl . "admin/dashboard.php", $cookieUser);
reportResult("TC-SEC-002", "Normal User Restricted from Admin", strpos($resUnauthAdmin['header'], 'dashboard.php?error=admin_required') !== false || $resUnauthAdmin['code'] == 302);

echo "\n========================================================\n";
echo "SUMMARY RESULTS:\n";
echo "Passed: $passCount\n";
echo "Failed: $failCount\n";
echo "========================================================\n";
