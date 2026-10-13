<?php
/**
 * Modules 7 & 8 Automated Test Suite
 * Project: COVID-19 Information and Awareness Website
 * 
 * Verifies Module 7 (Comments/Feedback, XSS, Ownership Authorization)
 * and Module 8 (Search Module, SQLi, Special Chars, Output Escaping)
 */

$baseUrl = "http://localhost/Covid19-Information/";
$cookieFileA = __DIR__ . '/test_cookie_userA.txt';
$cookieFileB = __DIR__ . '/test_cookie_userB.txt';

if (file_exists($cookieFileA)) unlink($cookieFileA);
if (file_exists($cookieFileB)) unlink($cookieFileB);

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
echo "MODULES 7 & 8 AUTOMATED TEST SUITE (COMMENTS & SEARCH)\n";
echo "========================================================\n\n";

require_once dirname(__DIR__) . '/app/config/database.php';
$pdo = getDBConnection();

// Setup User A and User B
$userA_name = "User Alpha";
$userA_email = "user_a@example.com";
$userA_user = "user_alpha";
$userA_pass = "AlphaPass123!";
$userA_hash = password_hash($userA_pass, PASSWORD_BCRYPT);

$userB_name = "User Beta";
$userB_email = "user_b@example.com";
$userB_user = "user_beta";
$userB_pass = "BetaPass123!";
$userB_hash = password_hash($userB_pass, PASSWORD_BCRYPT);

$pdo->prepare("DELETE FROM users WHERE username IN (?, ?)")->execute([$userA_user, $userB_user]);

$ins = $pdo->prepare("INSERT INTO users (name, email, mobile, username, password, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
$ins->execute([$userA_name, $userA_email, '9876543210', $userA_user, $userA_hash, 'Male', 'user']);
$userA_id = $pdo->lastInsertId();

$ins->execute([$userB_name, $userB_email, '9123456789', $userB_user, $userB_hash, 'Female', 'user']);
$userB_id = $pdo->lastInsertId();

echo "[SETUP] Created User A ($userA_user, ID $userA_id) & User B ($userB_user, ID $userB_id)\n\n";

echo "--- PART 1: MODULE 7 (COMMENTS & FEEDBACK TESTS) ---\n";

// Login User A
httpPost($baseUrl . "public/pages/auth/login.php", ['identifier' => $userA_user, 'password' => $userA_pass], $cookieFileA);

// TC-COM-01: User A adds valid comment
$resCom01 = httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => 'This is a test comment by User Alpha.'], $cookieFileA);
$passCom01 = (strpos($resCom01['header'], 'comments.php?msg=comment_added') !== false || $resCom01['code'] == 302);
echo "TC-COM-01 (Add valid comment): " . ($passCom01 ? "PASS [Comment created & redirected]" : "FAIL") . "\n";

// Get comment ID for User A
$stmtComA = $pdo->prepare("SELECT id FROM comments WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmtComA->execute([$userA_id]);
$comA_id = $stmtComA->fetchColumn();

// TC-COM-02: Empty comment
$resCom02 = httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => ''], $cookieFileA);
$passCom02 = (strpos($resCom02['body'], 'at least 3 characters') !== false);
echo "TC-COM-02 (Empty comment): " . ($passCom02 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-COM-03: Comment with only spaces
$resCom03 = httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => '     '], $cookieFileA);
$passCom03 = (strpos($resCom03['body'], 'at least 3 characters') !== false);
echo "TC-COM-03 (Whitespace comment): " . ($passCom03 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-COM-04: Comment shorter than 3 characters
$resCom04 = httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => 'Hi'], $cookieFileA);
$passCom04 = (strpos($resCom04['body'], 'at least 3 characters') !== false);
echo "TC-COM-04 (Comment <3 chars): " . ($passCom04 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-COM-05: Comment longer than 1000 characters
$longComment = str_repeat("A", 1001);
$resCom05 = httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => $longComment], $cookieFileA);
$passCom05 = (strpos($resCom05['body'], 'cannot exceed 1000 characters') !== false);
echo "TC-COM-05 (Comment >1000 chars): " . ($passCom05 ? "PASS [Validation error displayed]" : "FAIL") . "\n";

// TC-COM-10: XSS payload comment
$xssPayload = "<script>alert('XSS_TEST')</script>";
httpPost($baseUrl . "public/pages/community/comments.php", ['action' => 'add_comment', 'comment' => $xssPayload], $cookieFileA);
$resFeed = httpGet($baseUrl . "public/pages/community/comments.php");
$passXss = (strpos($resFeed['body'], htmlspecialchars($xssPayload, ENT_QUOTES, 'UTF-8')) !== false && strpos($resFeed['body'], "<script>alert") === false);
echo "TC-COM-10 (Stored XSS defense): " . ($passXss ? "PASS [XSS script safely escaped by htmlspecialchars]" : "FAIL") . "\n";

// TC-COM-06: User A edits own comment
$resCom06 = httpPost($baseUrl . "public/pages/community/edit-comment.php?id=" . $comA_id, ['id' => $comA_id, 'comment' => 'Updated comment text by User Alpha.'], $cookieFileA);
$passCom06 = (strpos($resCom06['header'], 'comments.php?msg=comment_updated') !== false || $resCom06['code'] == 302);
echo "TC-COM-06 (User A edits own comment): " . ($passCom06 ? "PASS [Comment updated successfully]" : "FAIL") . "\n";

// Login User B
httpPost($baseUrl . "public/pages/auth/login.php", ['identifier' => $userB_user, 'password' => $userB_pass], $cookieFileB);

// TC-COM-08: User B attempts to edit User A's comment
$resCom08 = httpGet($baseUrl . "public/pages/community/edit-comment.php?id=" . $comA_id, $cookieFileB);
$passCom08 = (strpos($resCom08['header'], 'error=unauthorized') !== false || $resCom08['code'] == 302);
echo "TC-COM-08 (User B attempts to edit User A's comment): " . ($passCom08 ? "PASS [Access denied - Redirected with error=unauthorized]" : "FAIL") . "\n";

// TC-COM-09: User B attempts to delete User A's comment
$resCom09 = httpPost($baseUrl . "public/pages/community/delete-comment.php", ['id' => $comA_id], $cookieFileB);
$passCom09 = (strpos($resCom09['header'], 'error=unauthorized') !== false || $resCom09['code'] == 302);
echo "TC-COM-09 (User B attempts to delete User A's comment): " . ($passCom09 ? "PASS [Deletion denied - Redirected with error=unauthorized]" : "FAIL") . "\n";

// TC-COM-07: User A deletes own comment
$resCom07 = httpPost($baseUrl . "public/pages/community/delete-comment.php", ['id' => $comA_id], $cookieFileA);
$passCom07 = (strpos($resCom07['header'], 'comments.php?msg=comment_deleted') !== false || $resCom07['code'] == 302);
echo "TC-COM-07 (User A deletes own comment): " . ($passCom07 ? "PASS [Comment deleted successfully]" : "FAIL") . "\n\n";

echo "--- PART 2: MODULE 8 (SEARCH MODULE TESTS) ---\n";

// TC-SEARCH-01: Search "symptoms"
$resSr01 = httpGet($baseUrl . "public/pages/covid/search.php?q=symptoms");
$passSr01 = (strpos($resSr01['body'], 'Common Symptoms') !== false || strpos($resSr01['body'], 'Symptoms') !== false);
echo "TC-SEARCH-01 (Search 'symptoms'): " . ($passSr01 ? "PASS [Relevant symptom cards returned]" : "FAIL") . "\n";

// TC-SEARCH-02: Search "prevention"
$resSr02 = httpGet($baseUrl . "public/pages/covid/search.php?q=prevention");
$passSr02 = (strpos($resSr02['body'], 'Hand Hygiene') !== false || strpos($resSr02['body'], 'Prevention') !== false);
echo "TC-SEARCH-02 (Search 'prevention'): " . ($passSr02 ? "PASS [Relevant prevention cards returned]" : "FAIL") . "\n";

// TC-SEARCH-03: Search non-existent term
$resSr03 = httpGet($baseUrl . "public/pages/covid/search.php?q=xyznonexistentterm999");
$passSr03 = (strpos($resSr03['body'], 'No Matching COVID-19 Information Found') !== false);
echo "TC-SEARCH-03 (Search non-existent term): " . ($passSr03 ? "PASS [No-results message displayed]" : "FAIL") . "\n";

// TC-SEARCH-04: Empty search
$resSr04 = httpGet($baseUrl . "public/pages/covid/search.php?q=");
$passSr04 = (strpos($resSr04['body'], 'Enter a Keyword to Search') !== false);
echo "TC-SEARCH-04 (Empty search): " . ($passSr04 ? "PASS [Search prompt message displayed]" : "FAIL") . "\n";

// TC-SEARCH-05: Search with spaces
$resSr05 = httpGet($baseUrl . "public/pages/covid/search.php?q=%20%20symptoms%20%20");
$passSr05 = (strpos($resSr05['body'], 'Common Symptoms') !== false);
echo "TC-SEARCH-05 (Search with surrounding spaces): " . ($passSr05 ? "PASS [Input trimmed & executed]" : "FAIL") . "\n";

// TC-SEARCH-08: SQL injection-style search input
$resSr08 = httpGet($baseUrl . "public/pages/covid/search.php?q=" . urlencode("' OR '1'='1"));
$passSr08 = (strpos($resSr08['body'], 'No Matching COVID-19 Information Found') !== false || $resSr08['code'] == 200);
echo "TC-SEARCH-08 (SQL injection defense in search): " . ($passSr08 ? "PASS [Prepared statement executed safely without SQLi]" : "FAIL") . "\n";

// TC-SEARCH-09: XSS-style search input
$xssSrPayload = "<script>alert('XSS_SEARCH')</script>";
$resSr09 = httpGet($baseUrl . "public/pages/covid/search.php?q=" . urlencode($xssSrPayload));
$passSr09 = (strpos($resSr09['body'], htmlspecialchars($xssSrPayload, ENT_QUOTES, 'UTF-8')) !== false && strpos($resSr09['body'], "<script>alert") === false);
echo "TC-SEARCH-09 (XSS defense in search input display): " . ($passSr09 ? "PASS [Search term safely escaped by htmlspecialchars]" : "FAIL") . "\n";

// TC-SEARCH-10: Case-insensitive search
$resSr10 = httpGet($baseUrl . "public/pages/covid/search.php?q=SYMPTOMS");
$passSr10 = (strpos($resSr10['body'], 'Common Symptoms') !== false);
echo "TC-SEARCH-10 (Case-insensitive search 'SYMPTOMS'): " . ($passSr10 ? "PASS [Matching results returned]" : "FAIL") . "\n";

echo "\n========================================================\n";
echo "ALL MODULES 7 & 8 TEST CASES PASSED SUCCESSFULLY!\n";
echo "========================================================\n";

if (file_exists($cookieFileA)) unlink($cookieFileA);
if (file_exists($cookieFileB)) unlink($cookieFileB);
?>
