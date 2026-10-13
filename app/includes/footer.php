<?php
// app/includes/footer.php
require_once dirname(__DIR__) . '/helpers/helpers.php';
?>
    <!-- Footer Section -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>🛡️ COVID-19 Awareness Portal</h4>
                    <p>Providing accurate global COVID-19 health data, preventive guidelines, symptoms tracking, and community safety awareness.</p>
                </div>
                <div class="footer-col">
                    <h5>Quick Navigation</h5>
                    <ul>
                        <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
                        <li><a href="<?php echo url('public/pages/covid/covid-info.php'); ?>">COVID Information</a></li>
                        <li><a href="<?php echo url('public/pages/covid/statistics.php'); ?>">COVID Statistics</a></li>
                        <li><a href="<?php echo url('index.php#prevention'); ?>">Prevention Tips</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Portal Features</h5>
                    <ul>
                        <li><a href="<?php echo url('public/pages/covid/covid-info.php'); ?>">Health Overview</a></li>
                        <li><a href="<?php echo url('public/pages/covid/statistics.php'); ?>">Regional Tracking</a></li>
                        <li><a href="<?php echo url('index.php#prevention'); ?>">Safety Measures</a></li>
                        <li><a href="<?php echo url('public/pages/covid/covid-info.php?category=Vaccination'); ?>">Vaccination Info</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> COVID-19 Information & Awareness Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Main JavaScript -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>
</body>
</html>
