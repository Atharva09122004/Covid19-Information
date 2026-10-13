<?php
// index.php - Home Page Module
$pageTitle = "Home - COVID-19 Information & Awareness";

require_once __DIR__ . '/app/config/database.php';

// Fetch Statistics and Info from Database if available
$db = getDBConnection();
$dbConnected = ($db !== null);

$stats = [];
$infoCards = [];

if ($dbConnected) {
    try {
        // Fetch top 3 statistics
        $stmtStats = $db->query("SELECT country, cases, recovered, deaths FROM statistics ORDER BY id ASC LIMIT 3");
        $stats = $stmtStats->fetchAll();

        // Fetch COVID information cards
        $stmtInfo = $db->query("SELECT title, description, category FROM covid_info LIMIT 4");
        $infoCards = $stmtInfo->fetchAll();
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
    }
}
?>

<?php include __DIR__ . '/app/includes/header.php'; ?>

<!-- Database Warning Banner (Only shown if MySQL connection fails) -->
<?php if (!$dbConnected): ?>
    <div class="container" style="margin-top: 20px;">
        <div class="db-status-banner warning">
            <span>⚠️ <strong>Database Notice:</strong> MySQL Database not connected. Please start MySQL in XAMPP and import <code>database/database.sql</code> in phpMyAdmin to activate dynamic database data.</span>
        </div>
    </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-centered">
        <div class="hero-content">
            <div class="hero-badge">
                <span>🛡️ Official COVID-19 Health & Awareness Portal</span>
            </div>
            <h1 class="hero-title">
                Stay Informed, <span>Stay Safe</span> from COVID-19
            </h1>
            <p class="hero-subtitle">
                Access verified COVID-19 safety guidelines, global health statistics, prevention measures, and community awareness resources in one place.
            </p>
            <div class="hero-actions">
                <a href="<?php echo url('public/pages/covid/covid-info.php'); ?>" class="btn btn-primary">Explore Information</a>
                <a href="<?php echo url('public/pages/covid/statistics.php'); ?>" class="btn btn-outline">View Statistics</a>
            </div>
        </div>
    </div>
</section>

<!-- COVID-19 Information Section -->
<section class="section" id="info">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">COVID-19 Essential Knowledge</h2>
            <p class="section-subtitle">Understanding transmission, key symptoms, and essential preventive measures.</p>
        </div>

        <div class="cards-grid">
            <?php if ($dbConnected && !empty($infoCards)): ?>
                <?php foreach ($infoCards as $info): ?>
                    <div class="info-card">
                        <div class="card-icon">🦠</div>
                        <span class="card-tag"><?php echo htmlspecialchars($info['category']); ?></span>
                        <h3 class="card-title" style="margin-top: 10px;"><?php echo htmlspecialchars($info['title']); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars($info['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default Static Content -->
                <div class="info-card">
                    <div class="card-icon">🦠</div>
                    <span class="card-tag">Overview</span>
                    <h3 class="card-title" style="margin-top: 10px;">What is COVID-19?</h3>
                    <p class="card-desc">Coronavirus disease (COVID-19) is an infectious disease caused by the SARS-CoV-2 virus causing mild to severe respiratory illnesses.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon">⚠️</div>
                    <span class="card-tag">Symptoms</span>
                    <h3 class="card-title" style="margin-top: 10px;">Recognize Symptoms</h3>
                    <p class="card-desc">Fever, dry cough, fatigue, loss of taste/smell, and sore throat are common early signs of infection.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon">🧼</div>
                    <span class="card-tag">Prevention</span>
                    <h3 class="card-title" style="margin-top: 10px;">Hygiene & Distance</h3>
                    <p class="card-desc">Wash hands with soap for 20 seconds, wear face masks in crowded areas, and practice social distancing.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon">💉</div>
                    <span class="card-tag">Vaccination</span>
                    <h3 class="card-title" style="margin-top: 10px;">Vaccination Care</h3>
                    <p class="card-desc">Vaccines train your immune system to produce antibodies safely, reducing severe illness and hospitalization risks.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Detailed Statistics Callout Banner -->
<section class="section" id="stats" style="background: rgba(255,255,255,0.7);">
    <div class="container">
        <div class="stats-cta-box">
            <div class="cta-content">
                <h2>📈 Country & Regional COVID-19 Statistics</h2>
                <p>Explore comprehensive regional data, total recoveries, active case tracking, and mortality metrics across global territories in our dedicated statistical dashboard.</p>
                <a href="<?php echo url('public/pages/covid/statistics.php'); ?>" class="btn btn-primary" style="margin-top: 15px;">Open Statistics Dashboard</a>
            </div>
            <div class="cta-badge-icon">📊</div>
        </div>
    </div>
</section>

<!-- Prevention Tips Section -->
<section class="section prevention-section" id="prevention">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">4 Simple Steps to Prevent Spread</h2>
            <p class="section-subtitle">Follow standard safety guidelines to safeguard your community.</p>
        </div>

        <div class="prevention-grid">
            <div class="prevention-item">
                <div class="prevention-badge">1</div>
                <h4>Wear Masks</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 5px;">Use well-fitting multi-layer masks when in indoor or public places.</p>
            </div>
            <div class="prevention-item">
                <div class="prevention-badge">2</div>
                <h4>Wash Hands</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 5px;">Clean hands frequently with soap & water or alcohol-based hand rub.</p>
            </div>
            <div class="prevention-item">
                <div class="prevention-badge">3</div>
                <h4>Maintain Distance</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 5px;">Maintain at least 1 meter distance from others to reduce droplet risk.</p>
            </div>
            <div class="prevention-item">
                <div class="prevention-badge">4</div>
                <h4>Get Vaccinated</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 5px;">Complete full vaccination & booster doses as recommended by health authorities.</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/app/includes/footer.php'; ?>

