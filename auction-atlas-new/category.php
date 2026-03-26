<?php
/**
 * Auction Atlas - Category Page
 * 
 * Displays auction houses filtered by category with
 * average premium, average risk, and top 3 by trust.
 */

$pageTitle = 'Categories';
require_once __DIR__ . '/includes/scoring.php';

$allAuctions = getAllScoredAuctions();
$allCategories = getAllCategories();
$selectedCategory = isset($_GET['name']) ? normalizeCategory(trim($_GET['name'])) : '';

// If a category is selected, filter auctions
$filteredAuctions = [];
$categoryStats = null;

if (!empty($selectedCategory)) {
    $pageTitle = $selectedCategory;
    
    foreach ($allAuctions as $a) {
        foreach ($a['categories'] as $cat) {
            if (strtolower($cat) === strtolower($selectedCategory)) {
                $filteredAuctions[] = $a;
                break;
            }
        }
    }
    
    // Calculate category stats
    if (!empty($filteredAuctions)) {
        $totalPremium = 0;
        $totalRisk = 0;
        $totalTrust = 0;
        
        foreach ($filteredAuctions as $a) {
            $totalPremium += $a['buyerPremium'];
            $totalRisk += $a['scores']['risk'];
            $totalTrust += $a['scores']['trust'];
        }
        
        $count = count($filteredAuctions);
        
        // Sort by trust for top 3
        usort($filteredAuctions, function($a, $b) {
            return $b['scores']['trust'] <=> $a['scores']['trust'];
        });
        
        $categoryStats = [
            'avgPremium' => round($totalPremium / $count, 1),
            'avgRisk' => round($totalRisk / $count),
            'avgTrust' => round($totalTrust / $count),
            'count' => $count,
            'top3' => array_slice($filteredAuctions, 0, 3),
        ];
    }
}

// Category icon mapping
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
    'Industrial' => 'factory',
    'Industrial Assets' => 'factory',
    'Machinery' => 'settings',
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">
            <?php echo !empty($selectedCategory) ? e($selectedCategory) : 'Browse Categories'; ?>
        </h1>
        <p class="text-slate-300 text-lg">
            <?php echo !empty($selectedCategory) 
                ? 'Auction houses specializing in ' . e($selectedCategory) 
                : 'Explore auction houses by asset category'; ?>
        </p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (empty($selectedCategory)): ?>
            <!-- Category Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <?php foreach ($allCategories as $cat): 
                    $icon = $catIcons[$cat] ?? 'tag';
                    // Count auctions in this category
                    $catCount = 0;
                    foreach ($allAuctions as $a) {
                        foreach ($a['categories'] as $c) {
                            if (strtolower($c) === strtolower($cat)) { $catCount++; break; }
                        }
                    }
                ?>
                    <a href="category.php?name=<?php echo urlencode($cat); ?>" class="glass-card p-5 text-center group">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                            <i data-lucide="<?php echo $icon; ?>" class="w-7 h-7 text-primary"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm text-slate-900 mb-1 group-hover:text-primary transition-colors"><?php echo e($cat); ?></h3>
                        <p class="text-xs text-slate-500 font-ui"><?php echo $catCount; ?> auction house<?php echo $catCount !== 1 ? 's' : ''; ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
            
        <?php elseif ($categoryStats): ?>
            
            <!-- Category Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <div class="glass-card-static p-5 text-center">
                    <div class="text-3xl font-heading font-extrabold text-primary"><?php echo $categoryStats['count']; ?></div>
                    <div class="text-sm text-slate-500 font-ui">Auction Houses</div>
                </div>
                <div class="glass-card-static p-5 text-center">
                    <div class="text-3xl font-heading font-extrabold text-warning"><?php echo $categoryStats['avgPremium']; ?>%</div>
                    <div class="text-sm text-slate-500 font-ui">Avg Premium</div>
                </div>
                <div class="glass-card-static p-5 text-center">
                    <div class="text-3xl font-heading font-extrabold <?php echo getRiskColor($categoryStats['avgRisk']); ?>"><?php echo $categoryStats['avgRisk']; ?></div>
                    <div class="text-sm text-slate-500 font-ui">Avg Risk Score</div>
                </div>
                <div class="glass-card-static p-5 text-center">
                    <div class="text-3xl font-heading font-extrabold <?php echo getTrustColor($categoryStats['avgTrust']); ?>"><?php echo $categoryStats['avgTrust']; ?></div>
                    <div class="text-sm text-slate-500 font-ui">Avg Trust Score</div>
                </div>
            </div>
            
            <!-- Top 3 by Trust -->
            <div class="mb-10">
                <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="trophy" class="w-5 h-5 text-highlight"></i> Top 3 by Trust Score
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <?php foreach ($categoryStats['top3'] as $i => $top): ?>
                        <div class="glass-card p-5 text-center <?php echo $i === 0 ? 'border-2 border-highlight/30' : ''; ?>">
                            <?php if ($i === 0): ?>
                                <div class="inline-flex items-center gap-1 px-3 py-1 bg-highlight/10 text-yellow-700 rounded-full text-xs font-ui font-bold mb-3">
                                    <i data-lucide="crown" class="w-3 h-3"></i> Top Rated
                                </div>
                            <?php endif; ?>
                            <h3 class="font-heading font-bold text-base text-slate-900 mb-1"><?php echo e($top['name']); ?></h3>
                            <p class="text-xs text-slate-500 font-ui mb-3"><?php echo e($top['city']); ?>, <?php echo e($top['province']); ?></p>
                            <div class="text-3xl font-heading font-extrabold <?php echo getTrustColor($top['scores']['trust']); ?> mb-1"><?php echo $top['scores']['trust']; ?></div>
                            <div class="text-xs text-slate-400 font-ui mb-3">Trust Score</div>
                            <a href="profile.php?id=<?php echo $top['id']; ?>" class="inline-flex items-center gap-1 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                                View Profile <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- All Results -->
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4">All <?php echo e($selectedCategory); ?> Auction Houses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($filteredAuctions as $auction): ?>
                    <div class="glass-card p-5 flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-heading font-bold text-base text-slate-900 truncate"><?php echo e($auction['name']); ?></h3>
                                <p class="text-xs text-slate-500 font-ui"><?php echo e($auction['city']); ?>, <?php echo e($auction['province']); ?></p>
                            </div>
                            <div class="grade-badge <?php echo getGradeBgColor($auction['scores']['grade']); ?> <?php echo getGradeColor($auction['scores']['grade']); ?> ml-2 flex-shrink-0">
                                <?php echo $auction['scores']['grade']; ?>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="text-lg font-heading font-bold <?php echo getTrustColor($auction['scores']['trust']); ?>"><?php echo $auction['scores']['trust']; ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase">Trust</div>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="text-lg font-heading font-bold <?php echo getRiskColor($auction['scores']['risk']); ?>"><?php echo $auction['scores']['risk']; ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase">Risk</div>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="text-lg font-heading font-bold text-primary"><?php echo e($auction['buyerPremiumRaw']); ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase">Premium</div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <a href="profile.php?id=<?php echo $auction['id']; ?>" class="block w-full text-center px-4 py-2.5 bg-primary text-white font-ui font-semibold text-sm rounded-xl hover:bg-primary/90 transition-colors min-h-[44px] leading-[44px]">
                                View Profile
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="category.php" class="inline-flex items-center gap-2 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to All Categories
                </a>
            </div>
            
        <?php else: ?>
            <div class="glass-card-static p-12 text-center">
                <i data-lucide="search-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                <h3 class="font-heading font-bold text-xl text-slate-700 mb-2">No Results Found</h3>
                <p class="text-slate-500 mb-4">No auction houses found for "<?php echo e($selectedCategory); ?>".</p>
                <a href="category.php" class="inline-flex items-center gap-2 text-sm font-ui font-semibold text-primary hover:text-accent transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to All Categories
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
