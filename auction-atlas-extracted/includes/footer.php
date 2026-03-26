<?php
/**
 * Auction Atlas - Footer Component
 * 
 * 3-column footer with description, navigation links, and resources.
 * Stacks vertically on mobile.
 */
?>
</main>

<!-- Footer -->
<footer class="bg-slate-900 text-slate-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            
            <!-- Column 1: About -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
                        <i data-lucide="gavel" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-heading font-bold text-lg text-white">Auction Atlas</span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    South Africa's premier auction intelligence platform. Compare, analyze, and match with the right auction house using data-driven insights, trust scoring, and risk analysis.
                </p>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="font-heading font-semibold text-white mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="directory.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i> Directory
                    </a></li>
                    <li><a href="compare.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="git-compare" class="w-4 h-4"></i> Compare
                    </a></li>
                    <li><a href="match.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="target" class="w-4 h-4"></i> Match
                    </a></li>
                    <li><a href="risk-scanner.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i> Risk Scanner
                    </a></li>
                </ul>
            </div>
            
            <!-- Column 3: Resources -->
            <div>
                <h3 class="font-heading font-semibold text-white mb-4">Resources</h3>
                <ul class="space-y-2">
                    <li><a href="fee-calculator.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="calculator" class="w-4 h-4"></i> Fee Calculator
                    </a></li>
                    <li><a href="strategy-simulator.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="brain" class="w-4 h-4"></i> Strategy Simulator
                    </a></li>
                    <li><a href="category.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="tag" class="w-4 h-4"></i> Categories
                    </a></li>
                    <li><a href="education.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i> Education
                    </a></li>
                    <li><a href="blog.php" class="text-sm text-slate-400 hover:text-accent transition-colors flex items-center gap-2">
                        <i data-lucide="newspaper" class="w-4 h-4"></i> Insights
                    </a></li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="mt-10 pt-8 border-t border-slate-800 text-center">
            <p class="text-sm text-slate-500">&copy; <?php echo date('Y'); ?> Auction Atlas &ndash; South Africa. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Custom JavaScript -->
<script src="assets/js/main.js" defer></script>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

</body>
</html>
