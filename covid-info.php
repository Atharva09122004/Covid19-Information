<?php
/**
 * COVID-19 Information Module (Module 2)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Displays categorized educational COVID-19 information fetched from MySQL.
 */

$pageTitle = "COVID-19 Information & Guidelines";

require_once 'config/database.php';

// Allowed categories for security validation
$allowedCategories = [
    'Overview',
    'Symptoms',
    'Prevention',
    'Safety',
    'Vaccination',
    'Treatment'
];

$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : 'All';
$isValidCategory = false;
$invalidCategoryNotice = false;

// Validate category parameter against allowed list
if ($selectedCategory !== 'All') {
    if (in_array($selectedCategory, $allowedCategories, true)) {
        $isValidCategory = true;
    } else {
        // Invalid category passed in URL (e.g. ?category=abc)
        $invalidCategoryNotice = true;
        $selectedCategory = 'All'; // Fallback safely to All
    }
} else {
    $isValidCategory = true;
}

// Database Connection & Retrieval
$db = getDBConnection();
$infoRecords = [];
$dbError = false;

if ($db !== null) {
    try {
        if ($selectedCategory !== 'All' && $isValidCategory) {
            // Prepared statement for category filtering
            $stmt = $db->prepare("SELECT id, title, description, category, created_at FROM covid_info WHERE category = :category ORDER BY id ASC");
            $stmt->execute([':category' => $selectedCategory]);
            $infoRecords = $stmt->fetchAll();
        } else {
            // Retrieve all records ordered by category priority
            $stmt = $db->query("SELECT id, title, description, category, created_at FROM covid_info ORDER BY FIELD(category, 'Overview', 'Symptoms', 'Prevention', 'Safety', 'Vaccination', 'Treatment'), id ASC");
            $infoRecords = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        error_log("Database Query Error in covid-info.php: " . $e->getMessage());
        $dbError = true;
    }
} else {
    $dbError = true;
}

// Category Icon Helper Function
function getCategoryIcon($cat) {
    switch ($cat) {
        case 'Overview': return '🌐';
        case 'Symptoms': return '🩺';
        case 'Prevention': return '🧼';
        case 'Safety': return '🛡️';
        case 'Vaccination': return '💉';
        case 'Treatment': return '💊';
        default: return 'ℹ️';
    }
}
?>

<?php include 'includes/header.php'; ?>

<!-- Main Section -->
<section class="section">
    <div class="container">
        
        <!-- Page Header -->
        <div class="section-header">
            <h1 class="section-title">COVID-19 Educational Information</h1>
            <p class="section-subtitle">
                Browse verified health knowledge, symptoms guide, safety measures, and vaccination facts compiled for community awareness.
            </p>
        </div>

        <!-- Invalid Category Warning Alert -->
        <?php if ($invalidCategoryNotice): ?>
            <div class="db-status-banner warning" style="margin-bottom: 25px;">
                <span>⚠️ <strong>Notice:</strong> Invalid category requested. Displaying all COVID-19 information categories by default.</span>
            </div>
        <?php endif; ?>

        <!-- Database Error Alert -->
        <?php if ($dbError): ?>
            <div class="db-status-banner warning" style="margin-bottom: 25px;">
                <span>⚠️ <strong>Database Notice:</strong> Unable to connect to the database or retrieve data. Please ensure MySQL is running in XAMPP and <code>covid19_db</code> is imported.</span>
            </div>
        <?php endif; ?>

        <!-- Systematic Category Filter Panel -->
        <div class="category-filter-box">
            <div class="filter-box-header">
                <span class="filter-box-title">🏷️ Select Category to Filter Information</span>
                <span class="filter-box-subtitle">Click a category button below to view relevant medical knowledge</span>
            </div>

            <div class="category-grid">
                <a href="covid-info.php?category=All" 
                   class="category-btn <?php echo ($selectedCategory === 'All') ? 'active' : ''; ?>">
                   <span class="btn-icon">📋</span>
                   <span class="btn-label">All Categories</span>
                </a>
                <a href="covid-info.php?category=Overview" 
                   class="category-btn <?php echo ($selectedCategory === 'Overview') ? 'active' : ''; ?>">
                   <span class="btn-icon">🌐</span>
                   <span class="btn-label">Overview</span>
                </a>
                <a href="covid-info.php?category=Symptoms" 
                   class="category-btn <?php echo ($selectedCategory === 'Symptoms') ? 'active' : ''; ?>">
                   <span class="btn-icon">🩺</span>
                   <span class="btn-label">Symptoms</span>
                </a>
                <a href="covid-info.php?category=Prevention" 
                   class="category-btn <?php echo ($selectedCategory === 'Prevention') ? 'active' : ''; ?>">
                   <span class="btn-icon">🧼</span>
                   <span class="btn-label">Prevention</span>
                </a>
                <a href="covid-info.php?category=Safety" 
                   class="category-btn <?php echo ($selectedCategory === 'Safety') ? 'active' : ''; ?>">
                   <span class="btn-icon">🛡️</span>
                   <span class="btn-label">Safety Guidelines</span>
                </a>
                <a href="covid-info.php?category=Vaccination" 
                   class="category-btn <?php echo ($selectedCategory === 'Vaccination') ? 'active' : ''; ?>">
                   <span class="btn-icon">💉</span>
                   <span class="btn-label">Vaccination</span>
                </a>
                <a href="covid-info.php?category=Treatment" 
                   class="category-btn <?php echo ($selectedCategory === 'Treatment') ? 'active' : ''; ?>">
                   <span class="btn-icon">💊</span>
                   <span class="btn-label">Treatment & Care</span>
                </a>
            </div>

            <!-- Active Filter Result Bar -->
            <div class="filter-status-bar">
                <div class="status-active-label">
                    <span class="status-dot"></span>
                    <span>Currently Viewing: <strong><?php echo htmlspecialchars($selectedCategory === 'All' ? 'All Categories' : $selectedCategory); ?></strong></span>
                </div>
                <?php if (!empty($infoRecords)): ?>
                    <span class="count-badge"><?php echo count($infoRecords); ?> <?php echo count($infoRecords) === 1 ? 'Entry' : 'Entries'; ?> Available</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Information Cards Grid -->
        <?php if (!empty($infoRecords)): ?>
            <div class="cards-grid">
                <?php foreach ($infoRecords as $record): ?>
                    <div class="info-card covid-detail-card">
                        <div class="card-header-row">
                            <span class="card-icon-badge"><?php echo getCategoryIcon($record['category']); ?></span>
                            <span class="card-tag tag-<?php echo strtolower(htmlspecialchars($record['category'])); ?>">
                                <?php echo htmlspecialchars($record['category']); ?>
                            </span>
                        </div>
                        <h3 class="card-title"><?php echo htmlspecialchars($record['title']); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars($record['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State Display -->
            <div class="empty-state-box">
                <div class="empty-icon">📂</div>
                <h3>No Information Available</h3>
                <p>No records were found for the selected category "<strong><?php echo htmlspecialchars($selectedCategory); ?></strong>".</p>
                <a href="covid-info.php?category=All" class="btn btn-primary" style="margin-top: 15px;">View All Information</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
