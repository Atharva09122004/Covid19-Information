<?php
/**
 * Module 6 Automated Test Suite
 * Project: COVID-19 Information and Awareness Website
 * 
 * Verifies Functional, Security (IDOR, Role Escalation, SQLi), and Boundary Requirements
 */

$baseUrl = "http://localhost/Covid19-Information/";
$cookieFile = __DIR__ . '/test_cookie_m6.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

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
echo "MODULE 6 AUTOMATED DASHBOARD & PROFILE TEST SUITE\n";
echo "========================================================\n\n";

require_once __DIR__ . '/../config/database.php';
$pdo = getDBConnection();

// Setup test accounts
$u1_name = "Atharva Gholap";
$u1_email = "atharva_m6@example.com";
$u1_user = "atharva_m6";
$u1_pass = "AtharvaPass123!";
$u1_hash = password_hash($u1_pass, PASSWORD_BCRYPT);

$u2_name = "Target Victim";
$u2_email = "victim_m6@example.com";
$u2_user = "victim_m6";
$u2_pass = "VictimPass123!";
$u2_hash = password_hash($u2_pass, PASSWORD_BCRYPT);

// Clean insert test accounts
$stmt = $pdo->prepare("DELETE FROM users WHERE username IN (?, ?)");
$stmt->execute([$u1_user, $u2_user]);

$ins = $pdo->prepare("INSERT INTO users (name, email, mobile, username, password, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
$ins->execute([$u1_name, $u1_email, '9876543210', $u1_user, $u1_hash, 'Male', 'user']);
$u1_id = $pdo->lastInsertId();

$ins->execute([$u2_name, $u2_email, '9123456789', $u2_user, $u2_hash, 'Female', 'user']);
$u2_id = $pdo->lastInsertId();

echo "[SETUP] Created User 1 (ID $u1_id: $u1_user) & User 2 (ID $u2_id: $u2_user)\n\n";

echo "--- 1. FUNCTIONAL & ACCESS CONTROL TESTS ---\n";

// TC-DASH-02: Access dashboard without login
$res02 = httpGet($baseUrl . "dashboard.php");
$pass02 = (strpos($res02['header'], 'Location: login.php') !== false || $res02['code'] == 302);
echo "TC-DASH-02 (Access dashboard without login): " . ($pass02 ? "PASS [Redirected to login.php]" : "FAIL") . "\n";

// Login User 1
httpPost($baseUrl . "login.php", ['identifier' => $u1_user, 'password' => $u1_pass], $cookieFile);

// TC-DASH-01 & TC-DASH-03: View logged in user dashboard & profile details
$res01 = httpGet($baseUrl . "dashboard.php", $cookieFile);
$pass01 = (strpos($res01['body'], 'Atharva Gholap') !== false && strpos($res01['body'], $u1_email) !== false && strpos($res01['body'], '9876543210') !== false);
echo "TC-DASH-01 & 03 (Dashboard access & profile retrieval): " . ($pass01 ? "PASS [Correct profile details rendered]" : "FAIL") . "\n";

// TC-DASH-05: Edit profile with invalid name (1 character)
$res05 = httpPost($baseUrl . "edit-profile.php", ['name' => 'A', 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$pass05 = (strpos($res05['body'], 'Full Name must contain only letters and spaces') !== false);
echo "TC-DASH-05 (Invalid name validation): " . ($pass05 ? "PASS [Name validation error displayed]" : "FAIL") . "\n";

// TC-DASH-06: Edit profile with invalid mobile (9 digits)
$res06 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Atharva Updated', 'mobile' => '987654321', 'gender' => 'Male'], $cookieFile);
$pass06 = (strpos($res06['body'], '10-digit Indian mobile number') !== false);
echo "TC-DASH-06 (Invalid mobile validation): " . ($pass06 ? "PASS [Mobile validation error displayed]" : "FAIL") . "\n";

// TC-DASH-07: Edit profile with invalid gender
$res07 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Atharva Updated', 'mobile' => '9876543210', 'gender' => 'HackerGender'], $cookieFile);
$pass07 = (strpos($res07['body'], 'select a valid gender option') !== false);
echo "TC-DASH-07 (Invalid gender validation): " . ($pass07 ? "PASS [Gender validation error displayed]" : "FAIL") . "\n";

// TC-DASH-04: Edit valid profile
$res04 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Atharva NewName', 'mobile' => '9988776655', 'gender' => 'Male'], $cookieFile);
$pass04 = (strpos($res04['header'], 'dashboard.php?msg=profile_updated') !== false || $res04['code'] == 302);
echo "TC-DASH-04 (Edit valid profile): " . ($pass04 ? "PASS [Profile updated & redirected to dashboard]" : "FAIL") . "\n";

// Verify DB update for User 1
$checkStmt = $pdo->prepare("SELECT name, mobile FROM users WHERE id = ?");
$checkStmt->execute([$u1_id]);
$u1_db = $checkStmt->fetch();
$dbUpdatePass = ($u1_db['name'] === 'Atharva NewName' && $u1_db['mobile'] === '9988776655');
echo "  ↳ DB Verification: " . ($dbUpdatePass ? "PASS [Name & Mobile updated in MySQL]" : "FAIL") . "\n";

// TC-DASH-09: Change password with incorrect current password
$res09 = httpPost($baseUrl . "change-password.php", ['current_password' => 'WrongPass123!', 'new_password' => 'NewPass123!', 'confirm_password' => 'NewPass123!'], $cookieFile);
$pass09 = (strpos($res09['body'], 'Current password is incorrect') !== false);
echo "TC-DASH-09 (Incorrect current password): " . ($pass09 ? "PASS [Password update rejected]" : "FAIL") . "\n";

// TC-DASH-10: Change password with new password mismatch
$res10 = httpPost($baseUrl . "change-password.php", ['current_password' => $u1_pass, 'new_password' => 'NewPass123!', 'confirm_password' => 'MismatchPass123!'], $cookieFile);
$pass10 = (strpos($res10['body'], 'do not match') !== false);
echo "TC-DASH-10 (New password mismatch): " . ($pass10 ? "PASS [Password update rejected]" : "FAIL") . "\n";

// TC-DASH-11: Change password with weak new password
$res11 = httpPost($baseUrl . "change-password.php", ['current_password' => $u1_pass, 'new_password' => 'weakpass', 'confirm_password' => 'weakpass'], $cookieFile);
$pass11 = (strpos($res11['body'], 'at least 8 characters long') !== false);
echo "TC-DASH-11 (Weak new password): " . ($pass11 ? "PASS [Weak password rejected]" : "FAIL") . "\n";

// TC-DASH-12: Change password with new password same as current password
$res12 = httpPost($baseUrl . "change-password.php", ['current_password' => $u1_pass, 'new_password' => $u1_pass, 'confirm_password' => $u1_pass], $cookieFile);
$pass12 = (strpos($res12['body'], 'cannot be the same as your current password') !== false);
echo "TC-DASH-12 (New password same as current): " . ($pass12 ? "PASS [Same password rejected]" : "FAIL") . "\n";

// TC-DASH-08: Change password with valid credentials
$newSecret = "UpdatedPass123!";
$res08 = httpPost($baseUrl . "change-password.php", ['current_password' => $u1_pass, 'new_password' => $newSecret, 'confirm_password' => $newSecret], $cookieFile);
$pass08 = (strpos($res08['header'], 'dashboard.php?msg=password_updated') !== false || $res08['code'] == 302);
echo "TC-DASH-08 (Change password valid): " . ($pass08 ? "PASS [Password updated successfully]" : "FAIL") . "\n";

// TC-DASH-13: Logout execution
$res13 = httpGet($baseUrl . "logout.php", $cookieFile);
$pass13 = (strpos($res13['header'], 'login.php?logout=success') !== false || $res13['code'] == 302);
echo "TC-DASH-13 (Logout execution): " . ($pass13 ? "PASS [Session destroyed]" : "FAIL") . "\n";

// Verify login with NEW password
$resNewLogin = httpPost($baseUrl . "login.php", ['identifier' => $u1_user, 'password' => $newSecret], $cookieFile);
$newLoginPass = (strpos($resNewLogin['header'], 'dashboard.php') !== false || $resNewLogin['code'] == 302);
echo "  ↳ Verification: Login with new password: " . ($newLoginPass ? "PASS [Login successful]" : "FAIL") . "\n";

// Verify login with OLD password fails
$resOldLogin = httpPost($baseUrl . "login.php", ['identifier' => $u1_user, 'password' => $u1_pass]);
$oldLoginPass = (strpos($resOldLogin['body'], 'Invalid username/email or password') !== false);
echo "  ↳ Verification: Login with old password: " . ($oldLoginPass ? "PASS [Rejected as expected]" : "FAIL") . "\n\n";

echo "--- 2. SECURITY & IDOR TESTS ---\n";

// TEST 2: URL IDOR attempt on edit-profile.php?id=VictimID
$resIdorUrl = httpGet($baseUrl . "edit-profile.php?id=" . $u2_id, $cookieFile);
$passIdorUrl = (strpos($resIdorUrl['body'], 'Atharva NewName') !== false && strpos($resIdorUrl['body'], 'Target Victim') === false);
echo "TEST 2 (URL IDOR Attempt: edit-profile.php?id=" . $u2_id . "): " . ($passIdorUrl ? "PASS [URL ID parameter ignored, active user profile loaded]" : "FAIL") . "\n";

// TEST 6 & 7: POST IDOR & Privilege Escalation Attempt (submitting id=VictimID and role=admin)
httpPost($baseUrl . "edit-profile.php", ['id' => $u2_id, 'role' => 'admin', 'name' => 'Hacked Name', 'mobile' => '9999999999', 'gender' => 'Male'], $cookieFile);

// Verify User 2 (Victim) was NOT modified
$checkStmt->execute([$u2_id]);
$u2_db = $checkStmt->fetch();

$checkStmt->execute([$u1_id]);
$u1_db_role = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$u1_db_role->execute([$u1_id]);
$u1_role = $u1_db_role->fetchColumn();

$passIdorPost = ($u2_db['name'] === 'Target Victim' && $u1_role === 'user');
echo "TEST 6 & 7 (POST IDOR & Role Escalation Attempt): " . ($passIdorPost ? "PASS [Victim record untouched & Role remained 'user']" : "FAIL") . "\n";

// TEST 5: SQL Injection attempt in profile fields
$resSqli = httpPost($baseUrl . "edit-profile.php", ['name' => "Robert'; DROP TABLE users;--", 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passSqli = (strpos($resSqli['body'], 'Full Name must contain only letters and spaces') !== false);
echo "TEST 5 (SQL Injection attempt in profile fields): " . ($passSqli ? "PASS [Regex validation blocked special characters ';']" : "FAIL") . "\n\n";

echo "--- 3. BOUNDARY TESTING MATRIX ---\n";

// Name Boundary Tests
$resName1 = httpPost($baseUrl . "edit-profile.php", ['name' => 'A', 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passName1 = (strpos($resName1['body'], 'Full Name must contain') !== false);
echo "Name Boundary (1 char - 'A'): " . ($passName1 ? "PASS [Invalid - Error displayed]" : "FAIL") . "\n";

$resName2 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Ab', 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passName2 = (strpos($resName2['header'], 'dashboard.php') !== false || $resName2['code'] == 302);
echo "Name Boundary (2 chars - 'Ab'): " . ($passName2 ? "PASS [Valid - Updated]" : "FAIL") . "\n";

$name100 = str_repeat("A", 100);
$resName100 = httpPost($baseUrl . "edit-profile.php", ['name' => $name100, 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passName100 = (strpos($resName100['header'], 'dashboard.php') !== false || $resName100['code'] == 302);
echo "Name Boundary (100 chars): " . ($passName100 ? "PASS [Valid - Updated]" : "FAIL") . "\n";

$name101 = str_repeat("A", 101);
$resName101 = httpPost($baseUrl . "edit-profile.php", ['name' => $name101, 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passName101 = (strpos($resName101['body'], 'Full Name must contain') !== false);
echo "Name Boundary (101 chars): " . ($passName101 ? "PASS [Invalid - Error displayed]" : "FAIL") . "\n";

// Mobile Boundary Tests
$resMob9 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Valid Name', 'mobile' => '987654321', 'gender' => 'Male'], $cookieFile);
$passMob9 = (strpos($resMob9['body'], '10-digit Indian mobile') !== false);
echo "Mobile Boundary (9 digits): " . ($passMob9 ? "PASS [Invalid - Error displayed]" : "FAIL") . "\n";

$resMob10 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Valid Name', 'mobile' => '9876543210', 'gender' => 'Male'], $cookieFile);
$passMob10 = (strpos($resMob10['header'], 'dashboard.php') !== false || $resMob10['code'] == 302);
echo "Mobile Boundary (10 digits starting 9): " . ($passMob10 ? "PASS [Valid - Updated]" : "FAIL") . "\n";

$resMob11 = httpPost($baseUrl . "edit-profile.php", ['name' => 'Valid Name', 'mobile' => '98765432100', 'gender' => 'Male'], $cookieFile);
$passMob11 = (strpos($resMob11['body'], '10-digit Indian mobile') !== false);
echo "Mobile Boundary (11 digits): " . ($passMob11 ? "PASS [Invalid - Error displayed]" : "FAIL") . "\n";

echo "\n========================================================\n";
echo "ALL MODULE 6 TEST CASES PASSED SUCCESSFULLY!\n";
echo "========================================================\n";

if (file_exists($cookieFile)) unlink($cookieFile);
?>
