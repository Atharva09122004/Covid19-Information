<?php
/**
 * Module 8 — Search Module
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows users to search COVID-19 educational topics across title, description,
 * and category fields using prepared SQL LIKE queries.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 3) . '/app/config/database.php';

$pageTitle = "Search COVID-19 Information";
$searchQuery = trim($_GET['q'] ?? '');
$searchQuery = substr($searchQuery, 0, 100); // Cap at 100 characters

$results = [];
$searched = false;
$errorMessage = "";

if ($searchQuery !== '') {
    $searched = true;
    try {
        $pdo = getDBConnection();
        if ($pdo) {
            // Prepared statement with LIKE parameters across title, description, and category
            $sql = "SELECT id, title, description, category, created_at 
                    FROM covid_info 
                    WHERE title LIKE ? OR description LIKE ? OR category LIKE ? 
                    ORDER BY id DESC";
            
            $stmt = $pdo->prepare($sql);
            $likeParam = '%' . $searchQuery . '%';
            $stmt->execute([$likeParam, $likeParam, $likeParam]);
            $results = $stmt->fetchAll();
        } else {
            $errorMessage = "Database connection error. Please try again.";
        }
    } catch (PDOException $e) {
        error_log("Search Error: " . $e->getMessage());
        $errorMessage = "An error occurred while executing the search.";
    }
}

include dirname(__DIR__, 3) . '/app/includes/header.php';
?>

<main class="container section">
    <div class="search-page-wrapper">
        
        <!-- Search Header & Input Box -->
        <div class="search-hero-card">
            <div class="search-hero-icon">🔍</div>
            <h1 class="search-title">Search COVID-19 Information</h1>
            <p class="search-subtitle">Search articles, symptoms, prevention tips, vaccination info, and safety guidelines.</p>

            <form action="<?php echo url('public/pages/covid/search.php'); ?>" method="GET" class="search-form" id="searchForm">
                <div class="search-input-wrapper">
                    <input 
                        type="text" 
                        name="q" 
                        id="searchInput" 
                        class="search-control" 
                        placeholder="Enter search keyword (e.g. symptoms, mask, vaccine)..." 
                        value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>"
                        maxlength="100"
                    >
                    <button type="submit" class="btn btn-primary search-btn">🔍 Search</button>
                </div>
            </form>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" style="margin-top: 25px;">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Search Results Section -->
        <div class="search-results-container">
            <?php if (!$searched): ?>
                <div class="search-notice-box">
                    <div class="notice-icon">💡</div>
                    <h3>Enter a Keyword to Search</h3>
                    <p>Type any health topic like <em>"symptoms"</em>, <em>"prevention"</em>, or <em>"vaccine"</em> in the search bar above.</p>
                </div>
            <?php elseif (empty($results)): ?>
                <div class="search-notice-box no-results-box">
                    <div class="notice-icon">🔎</div>
                    <h3>No Matching COVID-19 Information Found</h3>
                    <p>No articles matched your search for <strong>"<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>"</strong>. Please try searching with a different keyword like <em>"mask"</em> or <em>"symptoms"</em>.</p>
                </div>
            <?php else: ?>
                <div class="search-results-meta">
                    <h2>
                        Search Results for <span class="highlight-query">"<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>"</span>
                    </h2>
                    <span class="count-badge"><?php echo count($results); ?> Result(s) Found</span>
                </div>

                <div class="search-results-grid">
                    <?php foreach ($results as $item): ?>
                        <div class="info-card search-result-card">
                            <div class="card-header-row">
                                <span class="card-tag tag-<?php echo strtolower($item['category']); ?>">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                            </div>

                            <h3 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="card-desc"><?php echo htmlspecialchars($item['description']); ?></p>

                            <div style="margin-top: 20px;">
                                <a href="<?php echo url('public/pages/covid/covid-info.php?category=' . urlencode($item['category'])); ?>" class="btn-sm btn-outline">
                                    📖 View Category
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php include dirname(__DIR__, 3) . '/app/includes/footer.php'; ?>
