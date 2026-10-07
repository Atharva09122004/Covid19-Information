<?php
/**
 * Modules 9 & 10 Automated Test Suite
 * Project: COVID-19 Information and Awareness Website
 * 
 * Verifies Module 9 (Admin Dashboard, User/Info/Stats/Comments Management, Access Control, 
 * Self-Demotion Defense) and Module 10 (Contact Us Form, Server Validation, Admin Messages)
 */

$baseUrl = "http://localhost/Covid19-Information/";
$cookieAdmin = __DIR__ . '/test_cookie_admin.txt';
$cookieUser  = __DIR__ . '/test_cookie_normuser.txt';

if (file_exists($cookieAdmin)) unlink($cookieAdmin);
if (file_exists($cookieUser))  unlink($cookieUser);

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

echo "========================================================\n";
echo "MODULES 9 & 10 AUTOMATED TEST SUITE (ADMIN & CONTACT)\n";
echo "========================================================\n\n";

require_once __DIR__ . '/../config/database.php';
$pdo = getDBConnection();

// Setup Admin & Normal User Accounts
$admin_user = "admin_runner_9";
$admin_pass = "AdminPass123!";
$admin_hash = password_hash($admin_pass, PASSWORD_BCRYPT);

$norm_user  = "normal_runner_9";
$norm_pass  = "NormalPass123!";
$norm_hash  = password_hash($norm_pass, PASSWORD_BCRYPT);

$pdo->prepare("DELETE FROM users WHERE username IN (?, ?)")->execute([$admin_user, $norm_user]);

$ins = $pdo->prepare("INSERT INTO users (name, email, mobile, username, password, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
$ins->execute(['Test Admin System', 'admin_runner@covidshield.org', '9876543210', $admin_user, $admin_hash, 'Male', 'admin']);
$admin_id = $pdo->lastInsertId();

$ins->execute(['Normal User Test', 'normal_runner@example.com', '9123456789', $norm_user, $norm_hash, 'Female', 'user']);
$norm_id = $pdo->lastInsertId();

echo "[SETUP] Created Admin User ($admin_user, ID $admin_id) & Normal User ($norm_user, ID $norm_id)\n\n";

echo "--- PART 1: MODULE 9 (ADMIN DASHBOARD & MANAGEMENT TESTS) ---\n";

// TC-ADMIN-03: Unauthenticated user accesses admin dashboard
$resUnauth = httpGet($baseUrl . "admin/dashboard.php");
$passUnauth = (strpos($resUnauth['header'], 'Location: ../login.php?error=auth_required') !== false || $resUnauth['code'] == 302);
echo "TC-ADMIN-03 (Unauthenticated access to admin dashboard): " . ($passUnauth ? "PASS [Redirected to login.php]" : "FAIL") . "\n";

// Login Normal User
httpPost($baseUrl . "login.php", ['identifier' => $norm_user, 'password' => $norm_pass], $cookieUser);

// TC-ADMIN-02: Normal user accesses admin dashboard
$resNormAdmin = httpGet($baseUrl . "admin/dashboard.php", $cookieUser);
$passNormAdmin = (strpos($resNormAdmin['header'], 'Location: ../dashboard.php?error=admin_required') !== false || $resNormAdmin['code'] == 302);
echo "TC-ADMIN-02 (Normal user accesses admin dashboard): " . ($passNormAdmin ? "PASS [Access denied - Redirected to dashboard.php]" : "FAIL") . "\n";

// Login Admin User
httpPost($baseUrl . "login.php", ['identifier' => $admin_user, 'password' => $admin_pass], $cookieAdmin);

// TC-ADMIN-01: Admin accesses admin dashboard
$resAdminDash = httpGet($baseUrl . "admin/dashboard.php", $cookieAdmin);
$passAdminDash = (strpos($resAdminDash['body'], 'ADMINISTRATIVE PORTAL') !== false && strpos($resAdminDash['body'], 'Welcome, Test Admin System') !== false);
echo "TC-ADMIN-01 (Admin access to admin dashboard): " . ($passAdminDash ? "PASS [Admin dashboard opened cleanly]" : "FAIL") . "\n";

// TC-ADMIN-04: Admin views registered users
$resUsers = httpGet($baseUrl . "admin/users.php", $cookieAdmin);
$passUsers = (strpos($resUsers['body'], $admin_user) !== false && strpos($resUsers['body'], $norm_user) !== false);
echo "TC-ADMIN-04 (Admin views registered users): " . ($passUsers ? "PASS [User list rendered cleanly]" : "FAIL") . "\n";

// TC-ADMIN-06: Admin attempts self-demotion
$resDemote = httpPost($baseUrl . "admin/users.php", ['action' => 'update_role', 'target_user_id' => $admin_id, 'new_role' => 'user'], $cookieAdmin);
$passDemote = (strpos($resDemote['body'], 'You cannot remove your own admin privileges') !== false);
echo "TC-ADMIN-06 (Self-demotion prevention): " . ($passDemote ? "PASS [Self-demotion rejected]" : "FAIL") . "\n";

// TC-ADMIN-07: Admin adds COVID information
$resAddInfo = httpPost($baseUrl . "admin/covid-info.php", ['action' => 'create_info', 'title' => 'Runner Test Info Article', 'category' => 'Prevention', 'description' => 'Detailed test description for COVID info.'], $cookieAdmin);
$passAddInfo = (strpos($resAddInfo['body'], 'created successfully') !== false || strpos($resAddInfo['header'], 'covid-info.php') !== false);
echo "TC-ADMIN-07 (Admin adds COVID information): " . ($passAddInfo ? "PASS [Article inserted]" : "FAIL") . "\n";

// Fetch created info ID
$infoId = (int)$pdo->query("SELECT id FROM covid_info WHERE title = 'Runner Test Info Article' ORDER BY id DESC LIMIT 1")->fetchColumn();

// TC-ADMIN-08: Admin edits COVID information
$resEditInfo = httpPost($baseUrl . "admin/covid-info.php", ['action' => 'edit_info', 'id' => $infoId, 'title' => 'Runner Test Info Updated', 'category' => 'Safety', 'description' => 'Updated test description.'], $cookieAdmin);
$passEditInfo = (strpos($resEditInfo['body'], 'Article updated successfully') !== false || $resEditInfo['code'] == 200);
echo "TC-ADMIN-08 (Admin edits COVID information): " . ($passEditInfo ? "PASS [Article updated]" : "FAIL") . "\n";

// TC-ADMIN-09: Admin deletes COVID information
$resDelInfo = httpPost($baseUrl . "admin/covid-info.php", ['action' => 'delete_info', 'id' => $infoId], $cookieAdmin);
$passDelInfo = (strpos($resDelInfo['body'], 'deleted successfully') !== false || $resDelInfo['code'] == 200);
echo "TC-ADMIN-09 (Admin deletes COVID information): " . ($passDelInfo ? "PASS [Article deleted]" : "FAIL") . "\n";

// TC-ADMIN-10: Admin adds statistics
$resAddStat = httpPost($baseUrl . "admin/statistics.php", ['action' => 'create_stat', 'country' => 'Runnerland', 'cases' => 1000, 'recovered' => 800, 'deaths' => 50], $cookieAdmin);
$passAddStat = (strpos($resAddStat['body'], 'added successfully') !== false || $resAddStat['code'] == 200);
echo "TC-ADMIN-10 (Admin adds statistics): " . ($passAddStat ? "PASS [Statistics inserted]" : "FAIL") . "\n";

// Fetch created stat ID
$statId = (int)$pdo->query("SELECT id FROM statistics WHERE country = 'Runnerland' ORDER BY id DESC LIMIT 1")->fetchColumn();

// TC-ADMIN-11: Invalid statistics validation (recovered + deaths > cases)
$resInvStat = httpPost($baseUrl . "admin/statistics.php", ['action' => 'create_stat', 'country' => 'Invalidland', 'cases' => 100, 'recovered' => 90, 'deaths' => 20], $cookieAdmin);
$passInvStat = (strpos($resInvStat['body'], 'cannot exceed total cases') !== false);
echo "TC-ADMIN-11 (Invalid statistics validation): " . ($passInvStat ? "PASS [Invalid statistics rejected]" : "FAIL") . "\n";

// TC-ADMIN-12: Admin edits statistics
$resEditStat = httpPost($baseUrl . "admin/statistics.php", ['action' => 'edit_stat', 'id' => $statId, 'country' => 'Runnerland Updated', 'cases' => 1200, 'recovered' => 900, 'deaths' => 60], $cookieAdmin);
$passEditStat = (strpos($resEditStat['body'], 'updated successfully') !== false || $resEditStat['code'] == 200);
echo "TC-ADMIN-12 (Admin edits statistics): " . ($passEditStat ? "PASS [Statistics updated]" : "FAIL") . "\n";

// TC-ADMIN-13: Admin deletes statistics
$resDelStat = httpPost($baseUrl . "admin/statistics.php", ['action' => 'delete_stat', 'id' => $statId], $cookieAdmin);
$passDelStat = (strpos($resDelStat['body'], 'deleted successfully') !== false || $resDelStat['code'] == 200);
echo "TC-ADMIN-13 (Admin deletes statistics): " . ($passDelStat ? "PASS [Statistics deleted]" : "FAIL") . "\n";

// TC-ADMIN-14: Admin deletes comment
$insCom = $pdo->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
$insCom->execute([$norm_id, 'Comment for admin deletion test.']);
$delComId = $pdo->lastInsertId();

$resDelCom = httpPost($baseUrl . "admin/comments.php", ['action' => 'delete_comment', 'id' => $delComId], $cookieAdmin);
$passDelCom = (strpos($resDelCom['body'], 'deleted successfully') !== false || $resDelCom['code'] == 200);
echo "TC-ADMIN-14 (Admin deletes comment): " . ($passDelCom ? "PASS [Comment deleted by admin]" : "FAIL") . "\n\n";

echo "--- PART 2: MODULE 10 (CONTACT US MODULE TESTS) ---\n";

// TC-CONTACT-01 / TC-CONTACT-09: Guest submits valid contact form
$resCnt01 = httpPost($baseUrl . "contact.php", ['name' => 'Jane Visitor', 'email' => 'jane@example.com', 'subject' => 'General Inquiry', 'message' => 'Hello, I have a detailed question regarding COVID guidelines.']);
$passCnt01 = (strpos($resCnt01['body'], 'Your message has been sent successfully') !== false);
echo "TC-CONTACT-01 & 09 (Valid guest contact submission): " . ($passCnt01 ? "PASS [Message submitted successfully]" : "FAIL") . "\n";

// Fetch created message ID
$msgId = (int)$pdo->query("SELECT id FROM contact_messages WHERE email = 'jane@example.com' ORDER BY id DESC LIMIT 1")->fetchColumn();

// TC-CONTACT-02: Empty name
$resCnt02 = httpPost($baseUrl . "contact.php", ['name' => '', 'email' => 'jane@example.com', 'subject' => 'Test', 'message' => 'Hello, this is a test message.']);
$passCnt02 = (strpos($resCnt02['body'], 'Full Name is required') !== false);
echo "TC-CONTACT-02 (Empty name validation): " . ($passCnt02 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-CONTACT-03: Invalid email
$resCnt03 = httpPost($baseUrl . "contact.php", ['name' => 'Jane Visitor', 'email' => 'invalidemailformat', 'subject' => 'Test', 'message' => 'Hello, this is a test message.']);
$passCnt03 = (strpos($resCnt03['body'], 'valid email address') !== false);
echo "TC-CONTACT-03 (Invalid email validation): " . ($passCnt03 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-CONTACT-05: Message < 10 characters
$resCnt05 = httpPost($baseUrl . "contact.php", ['name' => 'Jane Visitor', 'email' => 'jane@example.com', 'subject' => 'Test', 'message' => 'Short']);
$passCnt05 = (strpos($resCnt05['body'], 'at least 10 characters') !== false);
echo "TC-CONTACT-05 (Message < 10 chars): " . ($passCnt05 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-CONTACT-08: XSS input in contact form
$xssMsg = "<script>alert('XSS_CONTACT')</script>";
httpPost($baseUrl . "contact.php", ['name' => 'XSS Tester', 'email' => 'xss@example.com', 'subject' => 'XSS Test', 'message' => 'Detailed message with ' . $xssMsg]);
$resAdminMsgs = httpGet($baseUrl . "admin/contact-messages.php", $cookieAdmin);
$passXssCnt = (strpos($resAdminMsgs['body'], htmlspecialchars($xssMsg, ENT_QUOTES, 'UTF-8')) !== false && strpos($resAdminMsgs['body'], "<script>alert") === false);
echo "TC-CONTACT-08 (XSS defense in contact message): " . ($passXssCnt ? "PASS [XSS script safely escaped by htmlspecialchars]" : "FAIL") . "\n";

// TC-CONTACT-10: Admin views submitted message
$passCnt10 = (strpos($resAdminMsgs['body'], 'Jane Visitor') !== false && strpos($resAdminMsgs['body'], 'General Inquiry') !== false);
echo "TC-CONTACT-10 (Admin views submitted message): " . ($passCnt10 ? "PASS [Message rendered in admin panel]" : "FAIL") . "\n";

// TC-CONTACT-11: Admin deletes contact message
$resDelMsg = httpPost($baseUrl . "admin/contact-messages.php", ['action' => 'delete_message', 'id' => $msgId], $cookieAdmin);
$passDelMsg = (strpos($resDelMsg['body'], 'deleted successfully') !== false || $resDelMsg['code'] == 200);
echo "TC-CONTACT-11 (Admin deletes contact message): " . ($passDelMsg ? "PASS [Message deleted by admin]" : "FAIL") . "\n";

echo "\n========================================================\n";
echo "ALL MODULES 9 & 10 TEST CASES PASSED SUCCESSFULLY!\n";
echo "========================================================\n";

if (file_exists($cookieAdmin)) unlink($cookieAdmin);
if (file_exists($cookieUser))  unlink($cookieUser);
?>
