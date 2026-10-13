<?php
/**
 * COVID-19 Statistics Module (Module 3)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Displays calculated national and global COVID-19 statistics from MySQL.
 */

$pageTitle = "COVID-19 Statistics Dashboard";

require_once dirname(__DIR__, 3) . '/app/config/database.php';

// Allowed list of countries for GET parameter security validation
$allowedCountries = [
    'India',
    'United States',
    'Brazil',
    'United Kingdom',
    'Germany',
    'Japan'
];

$selectedCountry = isset($_GET['country']) ? trim($_GET['country']) : 'All';
$isValidCountry = false;
$invalidCountryNotice = false;

// Validate country parameter against allowed list
if ($selectedCountry !== 'All') {
    if (in_array($selectedCountry, $allowedCountries, true)) {
        $isValidCountry = true;
    } else {
        // Invalid country passed in URL (e.g. ?country=XYZ)
        $invalidCountryNotice = true;
        $selectedCountry = 'All'; // Safe fallback
    }
} else {
    $isValidCountry = true;
}

// Database Connection & Retrieval
$db = getDBConnection();
$countryRecords = [];
$summaryData = [
    'total_cases'     => 0,
    'total_recovered' => 0,
    'total_deaths'    => 0,
    'total_active'    => 0
];

$dbError = false;

if ($db !== null) {
    try {
        // 1. Calculate Aggregate Global Totals using SQL SUM()
        $stmtSummary = $db->query("SELECT SUM(cases) as total_cases, SUM(recovered) as total_recovered, SUM(deaths) as total_deaths FROM statistics");
        $sumRow = $stmtSummary->fetch();

        if ($sumRow && $sumRow['total_cases'] !== null) {
            $summaryData['total_cases']     = (int)$sumRow['total_cases'];
            $summaryData['total_recovered'] = (int)$sumRow['total_recovered'];
            $summaryData['total_deaths']    = (int)$sumRow['total_deaths'];
            // Dynamic calculation: Active = Total - Recovered - Deaths
            $summaryData['total_active']    = max(0, $summaryData['total_cases'] - $summaryData['total_recovered'] - $summaryData['total_deaths']);
        }

        // 2. Retrieve Country-wise Statistics Records
        if ($selectedCountry !== 'All' && $isValidCountry) {
            // Prepared Statement for country filter query
            $stmtCountry = $db->prepare("SELECT id, country, cases, recovered, deaths, updated_at FROM statistics WHERE country = :country ORDER BY cases DESC");
            $stmtCountry->execute([':country' => $selectedCountry]);
            $countryRecords = $stmtCountry->fetchAll();
        } else {
            // Retrieve all countries ordered by total cases descending
            $stmtCountry = $db->query("SELECT id, country, cases, recovered, deaths, updated_at FROM statistics ORDER BY cases DESC");
            $countryRecords = $stmtCountry->fetchAll();
        }

    } catch (PDOException $e) {
        error_log("Database Query Error in statistics.php: " . $e->getMessage());
        $dbError = true;
    }
} else {
    $dbError = true;
}

// Country Flag Emoji Mapping Helper
function getCountryFlag($countryName) {
    switch ($countryName) {
        case 'India': return '🇮🇳';
        case 'United States': return '🇺🇸';
        case 'Brazil': return '🇧🇷';
        case 'United Kingdom': return '🇬🇧';
        case 'Germany': return '🇩🇪';
        case 'Japan': return '🇯🇵';
        default: return '🌐';
    }
}
?>

<?php include dirname(__DIR__, 3) . '/app/includes/header.php'; ?>

<!-- Main Section -->
<section class="section">
    <div class="container">

        <!-- Page Header -->
        <div class="section-header">
            <h1 class="section-title">COVID-19 Statistical Dashboard</h1>
            <p class="section-subtitle">
                Real-time country tracking, global cumulative figures, recovery metrics, and active case monitoring.
            </p>
        </div>

        <!-- Invalid Country Filter Warning Alert -->
        <?php if ($invalidCountryNotice): ?>
            <div class="db-status-banner warning" style="margin-bottom: 25px;">
                <span>⚠️ <strong>Notice:</strong> Invalid country filter requested. Displaying all countries by default.</span>
            </div>
        <?php endif; ?>

        <!-- Database Error Alert -->
        <?php if ($dbError): ?>
            <div class="db-status-banner warning" style="margin-bottom: 25px;">
                <span>⚠️ <strong>Database Notice:</strong> Unable to connect to the database or retrieve statistical records. Please ensure MySQL is running in XAMPP and <code>covid19_db</code> is imported.</span>
            </div>
        <?php endif; ?>

        <!-- Global Summary Cards Grid -->
        <div class="stats-summary-grid">
            <div class="summary-card cases-card">
                <div class="card-icon-wrap">📊</div>
                <div class="summary-info">
                    <span class="summary-label">Total Cases</span>
                    <h2 class="summary-value"><?php echo number_format($summaryData['total_cases']); ?></h2>
                </div>
            </div>

            <div class="summary-card recovered-card">
                <div class="card-icon-wrap">💚</div>
                <div class="summary-info">
                    <span class="summary-label">Total Recovered</span>
                    <h2 class="summary-value"><?php echo number_format($summaryData['total_recovered']); ?></h2>
                </div>
            </div>

            <div class="summary-card deaths-card">
                <div class="card-icon-wrap">💔</div>
                <div class="summary-info">
                    <span class="summary-label">Total Deaths</span>
                    <h2 class="summary-value"><?php echo number_format($summaryData['total_deaths']); ?></h2>
                </div>
            </div>

            <div class="summary-card active-card">
                <div class="card-icon-wrap">⚡</div>
                <div class="summary-info">
                    <span class="summary-label">Total Active Cases</span>
                    <h2 class="summary-value"><?php echo number_format($summaryData['total_active']); ?></h2>
                </div>
            </div>
        </div>

        <!-- Country Filter Box -->
        <div class="filter-panel-box">
            <form method="GET" action="statistics.php" class="filter-form">
                <label for="countrySelect" class="filter-label">📍 Filter by Country:</label>
                <div class="select-wrapper">
                    <select name="country" id="countrySelect" onchange="this.form.submit()" class="country-dropdown">
                        <option value="All" <?php echo ($selectedCountry === 'All') ? 'selected' : ''; ?>>All Countries (Global Overview)</option>
                        <?php foreach ($allowedCountries as $country): ?>
                            <option value="<?php echo htmlspecialchars($country); ?>" <?php echo ($selectedCountry === $country) ? 'selected' : ''; ?>>
                                <?php echo getCountryFlag($country) . ' ' . htmlspecialchars($country); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <noscript>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </noscript>
            </form>
            <div class="filter-summary-tag">
                Showing data for: <strong><?php echo htmlspecialchars($selectedCountry === 'All' ? 'All Countries' : $selectedCountry); ?></strong>
            </div>
        </div>

        <!-- Country-wise Statistics Table Section -->
        <?php if (!empty($countryRecords)): ?>
            <div class="table-card-container">
                <div class="table-header-bar">
                    <h3>Detailed Breakdown by Region</h3>
                    <span class="table-subtitle">Active Cases calculated dynamically as: <code>Cases - (Recovered + Deaths)</code></span>
                </div>

                <div class="table-responsive">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Total Cases</th>
                                <th>Recovered</th>
                                <th>Deaths</th>
                                <th>Active Cases</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($countryRecords as $row): ?>
                                <?php
                                    $cases = (int)$row['cases'];
                                    $recovered = (int)$row['recovered'];
                                    $deaths = (int)$row['deaths'];

                                    // Data Integrity Validation
                                    $isDataValid = ($cases >= 0 && $recovered >= 0 && $deaths >= 0 && ($recovered + $deaths) <= $cases);
                                    
                                    // Dynamic calculation of active cases with boundary protection
                                    $active = $isDataValid ? ($cases - $recovered - $deaths) : 0;
                                ?>
                                <tr>
                                    <td class="country-cell">
                                        <span class="country-flag"><?php echo getCountryFlag($row['country']); ?></span>
                                        <strong><?php echo htmlspecialchars($row['country']); ?></strong>
                                        <?php if (!$isDataValid): ?>
                                            <span class="warning-tag" title="Data anomaly detected in database values">⚠️ Data Error</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="cases-col"><?php echo number_format($cases); ?></td>
                                    <td class="recovered-col"><?php echo number_format($recovered); ?></td>
                                    <td class="deaths-col"><?php echo number_format($deaths); ?></td>
                                    <td class="active-col">
                                        <span class="active-pill"><?php echo number_format($active); ?></span>
                                    </td>
                                    <td class="date-col">
                                        <?php echo date('d M Y, h:i A', strtotime($row['updated_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State Display -->
            <div class="empty-state-box">
                <div class="empty-icon">📊</div>
                <h3>No Statistics Available</h3>
                <p>No statistical records found for the requested country "<strong><?php echo htmlspecialchars($selectedCountry); ?></strong>".</p>
                <a href="statistics.php?country=All" class="btn btn-primary" style="margin-top: 15px;">View Global Statistics</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include dirname(__DIR__, 3) . '/app/includes/footer.php'; ?>
