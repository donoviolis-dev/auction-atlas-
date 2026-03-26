<?php
/**
 * Auction Atlas - Homepage
 * 
 * Hero section, feature grid, category preview,
 * trust map preview, and CTA section.
 */

$pageTitle = 'Home';
require_once __DIR__ . '/includes/scoring.php';
require_once __DIR__ . '/includes/riskLogic.php';

// Load all scored auctions
$auctions = getAllScoredAuctions();
$nationalAverages = getNationalRiskAverages();
$allCategories = getAllCategories();

// Calculate stats
$totalAuctions = count($auctions);
$avgTrust = $nationalAverages['avgTrust'];
$avgRisk = $nationalAverages['avgRisk'];
$categoryCount = count($allCategories);

// Prepare map data
$mapData = [];
foreach ($auctions as $a) {
    foreach ($a['branches'] as $branch) {
        $mapData[] = [
            'name' => $a['name'],
            'city' => $branch,
            'province' => $a['province'],
            'trust' => $a['scores']['trust'],
        ];
    }
}

// Load upcoming auctions from CSV
$auctionEvents = [];
$csvFile = __DIR__ . '/data/upcoming-auctions.csv';
if (file_exists($csvFile) && ($handle = fopen($csvFile, 'r')) !== false) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        $row = array_combine($headers, $data);
        if (!empty($row['Date'])) {
            $auctionEvents[] = [
                'title' => $row['Auction'] ?? 'Auction',
                'start' => date('Y-m-d', strtotime($row['Date'])),
                'location' => $row['Location'] ?? '',
                'category' => $row['Category'] ?? '',
                'source' => $row['Source'] ?? ''
            ];
        }
    }
    fclose($handle);
}

require_once __DIR__ . '/includes/header.php'; ?>

<!-- Auction Calendar Data -->
<script>
    const auctionEvents = <?php echo json_encode($auctionEvents); ?>;
</script>
?>

<!-- Hero Section -->
<section class="hero-gradient text-white py-16 lg:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            
            <!-- Left: Hero Content -->
            <div class="text-center lg:text-left">
                <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl xl:text-6xl leading-tight mb-6">
                    South Africa's Auction Intelligence Infrastructure
                </h1>
                <p class="text-lg sm:text-xl text-slate-300 mb-8 max-w-xl mx-auto lg:mx-0">
                    Compare, analyze and match with the right auction house using data-driven trust scoring, risk analysis, and buyer matching.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="directory.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary font-heading font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 min-h-[44px]">
                        <i data-lucide="compass" class="w-5 h-5"></i>
                        Explore Directory
                    </a>
                    <a href="match.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 backdrop-blur text-white border border-white/20 font-heading font-bold rounded-2xl hover:bg-white/20 transition-all duration-300 min-h-[44px]">
                        <i data-lucide="target" class="w-5 h-5"></i>
                        Find My Match
                    </a>
                </div>
            </div>
            
            <!-- Right: Animated Stats Panel -->
            <div class="grid grid-cols-2 gap-4">
                <div class="glass-card-dark p-5 sm:p-6 text-center fade-in stagger-1">
                    <div class="text-3xl sm:text-4xl font-heading font-extrabold text-highlight mb-1" data-counter="<?php echo $totalAuctions; ?>">0</div>
                    <div class="text-sm text-slate-400 font-ui">Total Auctions</div>
                </div>
                <div class="glass-card-dark p-5 sm:p-6 text-center fade-in stagger-2">
                    <div class="text-3xl sm:text-4xl font-heading font-extrabold text-accent mb-1" data-counter="<?php echo $avgTrust; ?>">0</div>
                    <div class="text-sm text-slate-400 font-ui">Avg Trust Score</div>
                </div>
                <div class="glass-card-dark p-5 sm:p-6 text-center fade-in stagger-3">
                    <div class="text-3xl sm:text-4xl font-heading font-extrabold text-warning mb-1" data-counter="<?php echo $avgRisk; ?>">0</div>
                    <div class="text-sm text-slate-400 font-ui">National Risk Level</div>
                </div>
                <div class="glass-card-dark p-5 sm:p-6 text-center fade-in stagger-4">
                    <div class="text-3xl sm:text-4xl font-heading font-extrabold text-white mb-1" data-counter="<?php echo $categoryCount; ?>">0</div>
                    <div class="text-sm text-slate-400 font-ui">Categories Covered</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Grid -->

<!-- Auction Calendar Section -->
<section class="py-16 bg-slate-900/50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-white mb-8">Auction Calendar</h2>
        <p class="text-slate-400 mb-6">Upcoming auctions across South Africa</p>
        
        <!-- Filter Tabs -->
        <div id="calendar-filters" class="mb-4"></div>
        
        <!-- Summary Strip -->
        <div id="calendar-summary" class="mb-4"></div>
        
        <div id="calendar" class="bg-slate-800/50 rounded-xl p-6 min-h-[400px]">
            <!-- FullCalendar will be initialized here -->
            <div class="calendar-loading text-center text-slate-400 py-20">
                <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading calendar...
            </div>
        </div>
        
        <div class="mt-6 flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                <span class="text-slate-400 text-sm">Property</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-slate-400 text-sm">Vehicle</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="text-slate-400 text-sm">Industrial</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span class="text-slate-400 text-sm">Livestock</span>
            </div>
        </div>
    </div>
</section>

<!-- FullCalendar Initialization -->
<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize the main calendar system
    await initCalendar();
    
    // Initialize filter system after calendar is ready
    if (typeof initCalendarFilters === 'function') {
        initCalendarFilters();
    }
});
</script>
<section class="py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mb-3">Platform Features</h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Comprehensive tools for auction intelligence and decision-making</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Directory -->
            <div class="glass-card p-6 text-center">
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="layout-grid" class="w-7 h-7 text-primary"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Auction Directory</h3>
                <p class="text-sm text-slate-500 mb-4">Browse and filter <?php echo $totalAuctions; ?> auction houses across South Africa with detailed profiles.</p>
                <a href="directory.php" class="inline-flex items-center gap-1 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    Browse Directory <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <!-- Card 2: Trust & Risk -->
            <div class="glass-card p-6 text-center">
                <div class="w-14 h-14 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-7 h-7 text-accent"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Trust & Risk Scoring</h3>
                <p class="text-sm text-slate-500 mb-4">Algorithmic scoring based on compliance, reputation, and operational signals.</p>
                <a href="risk-scanner.php" class="inline-flex items-center gap-1 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    View Risk Scanner <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <!-- Card 3: Matching -->
            <div class="glass-card p-6 text-center">
                <div class="w-14 h-14 bg-highlight/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="target" class="w-7 h-7 text-yellow-600"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Buyer Matching Engine</h3>
                <p class="text-sm text-slate-500 mb-4">Get matched with the ideal auction house based on your buyer profile and preferences.</p>
                <a href="match.php" class="inline-flex items-center gap-1 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    Find My Match <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <!-- Card 4: Strategy -->
            <div class="glass-card p-6 text-center">
                <div class="w-14 h-14 bg-warning/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="brain" class="w-7 h-7 text-warning"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Strategy Simulator</h3>
                <p class="text-sm text-slate-500 mb-4">Simulate auction strategies based on your capital, risk tolerance, and experience level.</p>
                <a href="strategy-simulator.php" class="inline-flex items-center gap-1 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    Simulate Strategy <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Category Preview -->
<section class="py-16 bg-slate-100/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mb-3">Browse by Category</h2>
            <p class="text-slate-500">Explore auction houses by asset category</p>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php 
            $topCategories = array_slice($allCategories, 0, 6);
            $catIcons = [
                'Property' => 'building-2',
                'Vehicles' => 'car',
                'Industrial & Machinery' => 'factory',
                'Art, Antiques & Collectibles' => 'palette',
                'Salvage & Liquidation' => 'recycle',
                'Household & Furniture' => 'armchair',
                'Commercial Property' => 'building',
                'Residential Property' => 'home',
                'Jewellery' => 'gem',
                'Wine' => 'wine',
                'General Assets' => 'package',
                'Equipment' => 'settings',
                'Livestock' => 'leaf',
            ];
            foreach ($topCategories as $cat): 
                $icon = $catIcons[$cat] ?? 'tag';
            ?>
                <a href="category.php?name=<?php echo urlencode($cat); ?>" class="glass-card p-4 sm:p-5 text-center group">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                        <i data-lucide="<?php echo $icon; ?>" class="w-6 h-6 text-primary"></i>
                    </div>
                    <span class="text-sm font-ui font-semibold text-slate-700 group-hover:text-primary transition-colors"><?php echo e($cat); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Trust Map Preview -->
<section class="py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mb-3">National Coverage Map</h2>
            <p class="text-slate-500">Auction house locations across South Africa</p>
        </div>
        
        <div class="glass-card-static overflow-hidden">
            <div id="homepage-map" style="height: 400px; width: 100%;"></div>
        </div>
        
        <div class="text-center mt-6">
            <a href="directory.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-ui font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all min-h-[44px]">
                <i data-lucide="users" class="w-5 h-5"></i>
                View Full Profiles
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 lg:py-20 bg-gradient-to-r from-primary to-accent">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-white mb-4">
            Ready to Make Smarter Auction Decisions?
        </h2>
        <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
            Access comprehensive auction intelligence, trust scoring, and buyer matching tools designed for the South African market.
        </p>
        <a href="directory.php" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-heading font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 min-h-[44px]">
            <i data-lucide="rocket" class="w-5 h-5"></i>
            Start Exploring
        </a>
    </div>
</section>

<!-- Map Script -->
<script src="assets/js/maps.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Leaflet to load
    var checkLeaflet = setInterval(function() {
        if (typeof L !== 'undefined') {
            clearInterval(checkLeaflet);
            var mapData = <?php echo json_encode($mapData); ?>;
            initAuctionMap('homepage-map', mapData);
        }
    }, 100);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
