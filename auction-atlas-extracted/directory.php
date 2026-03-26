<?php
/**
 * Auction Atlas - Directory Page
 * 
 * Filterable, paginated directory of all auction houses
 * with server-side GET filtering by province, category,
 * premium range, and trust score range.
 */

$pageTitle = 'Directory';
require_once __DIR__ . '/includes/scoring.php';
require_once __DIR__ . '/includes/logo-helper.php';

// Load all scored auctions
$allAuctions = getAllScoredAuctions();
$allProvinces = getAllProvinces();
$allCategories = getAllCategories();

// Get filter parameters
$filterProvince = isset($_GET['province']) ? trim($_GET['province']) : '';
$filterCategory = isset($_GET['category']) ? normalizeCategory(trim($_GET['category'])) : '';
$filterPremiumMin = isset($_GET['premium_min']) ? (float)$_GET['premium_min'] : 0;
$filterPremiumMax = isset($_GET['premium_max']) ? (float)$_GET['premium_max'] : 20;
$filterTrustMin = isset($_GET['trust_min']) ? (int)$_GET['trust_min'] : 0;
$filterTrustMax = isset($_GET['trust_max']) ? (int)$_GET['trust_max'] : 100;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Apply filters
$filtered = array_filter($allAuctions, function($a) use ($filterProvince, $filterCategory, $filterPremiumMin, $filterPremiumMax, $filterTrustMin, $filterTrustMax) {
    // Province filter
    if (!empty($filterProvince)) {
        $match = false;
        foreach ($a['provinces'] as $p) {
            if (strtolower($p) === strtolower($filterProvince)) {
                $match = true;
                break;
            }
        }
        if (!$match) return false;
    }
    
    // Category filter
    if (!empty($filterCategory)) {
        $match = false;
        foreach ($a['categories'] as $c) {
            if (strtolower($c) === strtolower($filterCategory)) {
                $match = true;
                break;
            }
        }
        if (!$match) return false;
    }
    
    // Premium range filter
    if ($a['buyerPremium'] < $filterPremiumMin || $a['buyerPremium'] > $filterPremiumMax) {
        return false;
    }
    
    // Trust score range filter
    if ($a['scores']['trust'] < $filterTrustMin || $a['scores']['trust'] > $filterTrustMax) {
        return false;
    }
    
    return true;
});

$filtered = array_values($filtered);

// Paginate
$pagination = paginate($filtered, $page, 9);
$displayAuctions = $pagination['items'];

// Build base URL for pagination (preserve filters)
$queryParams = [];
if (!empty($filterProvince)) $queryParams[] = 'province=' . urlencode($filterProvince);
if (!empty($filterCategory)) $queryParams[] = 'category=' . urlencode($filterCategory);
if ($filterPremiumMin > 0) $queryParams[] = 'premium_min=' . $filterPremiumMin;
if ($filterPremiumMax < 20) $queryParams[] = 'premium_max=' . $filterPremiumMax;
if ($filterTrustMin > 0) $queryParams[] = 'trust_min=' . $filterTrustMin;
if ($filterTrustMax < 100) $queryParams[] = 'trust_max=' . $filterTrustMax;
$baseUrl = 'directory.php' . (!empty($queryParams) ? '?' . implode('&', $queryParams) : '');

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Auction Directory</h1>
        <p class="text-slate-300 text-lg">Browse <?php echo $pagination['total']; ?> auction houses across South Africa</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Filter Sidebar -->
            <aside class="w-full lg:w-72 flex-shrink-0">
                <form method="GET" action="directory.php" class="glass-card-static p-6 space-y-5">
                    <h3 class="font-heading font-bold text-lg text-slate-900 flex items-center gap-2">
                        <i data-lucide="filter" class="w-5 h-5 text-primary"></i>
                        Filters
                    </h3>
                    
                    <!-- Province -->
                    <div>
                        <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Province</label>
                        <select name="province" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                            <option value="">All Provinces</option>
                            <?php foreach ($allProvinces as $prov): ?>
                                <option value="<?php echo e($prov); ?>" <?php echo $filterProvince === $prov ? 'selected' : ''; ?>><?php echo e($prov); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Category</label>
                        <select name="category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                            <option value="">All Categories</option>
                            <?php foreach ($allCategories as $cat): ?>
                                <option value="<?php echo e($cat); ?>" <?php echo $filterCategory === $cat ? 'selected' : ''; ?>><?php echo e($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Premium Range -->
                    <div>
                        <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Premium Range (%)</label>
                        <div class="flex gap-2">
                            <input type="number" name="premium_min" value="<?php echo $filterPremiumMin; ?>" min="0" max="20" step="1" placeholder="Min" class="w-1/2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                            <input type="number" name="premium_max" value="<?php echo $filterPremiumMax; ?>" min="0" max="20" step="1" placeholder="Max" class="w-1/2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                        </div>
                    </div>
                    
                    <!-- Trust Score Range -->
                    <div>
                        <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Trust Score Range</label>
                        <div class="flex gap-2">
                            <input type="number" name="trust_min" value="<?php echo $filterTrustMin; ?>" min="0" max="100" step="5" placeholder="Min" class="w-1/2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                            <input type="number" name="trust_max" value="<?php echo $filterTrustMax; ?>" min="0" max="100" step="5" placeholder="Max" class="w-1/2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-3 bg-primary text-white font-ui font-semibold rounded-xl hover:bg-primary/90 transition-colors min-h-[44px]">
                        Apply Filters
                    </button>
                    
                    <a href="directory.php" class="block text-center text-sm text-slate-500 hover:text-primary transition-colors font-ui">
                        Clear All Filters
                    </a>
                </form>
            </aside>
            
            <!-- Results Grid -->
            <div class="flex-1">
                <?php if (empty($displayAuctions)): ?>
                    <div class="glass-card-static p-12 text-center">
                        <i data-lucide="search-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                        <h3 class="font-heading font-bold text-xl text-slate-700 mb-2">No Results Found</h3>
                        <p class="text-slate-500">Try adjusting your filters to see more results.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach ($displayAuctions as $auction): ?>
                            <div class="glass-card p-5 flex flex-col">
                                <!-- Header -->
                                <div class="flex items-start gap-3 mb-3">
                                    <img src="<?php echo getLogo($auction['name']); ?>" alt="<?php echo e($auction['name']); ?>" class="w-12 h-12 object-contain rounded-lg bg-white border border-slate-200 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-heading font-bold text-base text-slate-900 truncate"><?php echo e($auction['name']); ?></h3>
                                        <p class="text-xs text-slate-500 font-ui"><?php echo e($auction['city']); ?>, <?php echo e($auction['province']); ?></p>
                                    </div>
                                    <div class="grade-badge <?php echo getGradeBgColor($auction['scores']['grade']); ?> <?php echo getGradeColor($auction['scores']['grade']); ?> ml-2 flex-shrink-0">
                                        <?php echo $auction['scores']['grade']; ?>
                                    </div>
                                </div>
                                
                                <!-- Stats Row -->
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="text-center p-2 bg-slate-50 rounded-lg">
                                        <div class="text-lg font-heading font-bold <?php echo getTrustColor($auction['scores']['trust']); ?>"><?php echo $auction['scores']['trust']; ?></div>
                                        <div class="text-[10px] text-slate-500 font-ui uppercase tracking-wide">Trust</div>
                                    </div>
                                    <div class="text-center p-2 bg-slate-50 rounded-lg">
                                        <div class="text-lg font-heading font-bold <?php echo getRiskColor($auction['scores']['risk']); ?>"><?php echo $auction['scores']['risk']; ?></div>
                                        <div class="text-[10px] text-slate-500 font-ui uppercase tracking-wide">Risk</div>
                                    </div>
                                    <div class="text-center p-2 bg-slate-50 rounded-lg">
                                        <div class="text-lg font-heading font-bold text-primary"><?php echo e($auction['buyerPremiumRaw']); ?></div>
                                        <div class="text-[10px] text-slate-500 font-ui uppercase tracking-wide">Premium</div>
                                    </div>
                                </div>
                                
                                <!-- Branches -->
                                <div class="mb-3">
                                    <div class="flex items-center gap-1 text-xs text-slate-500 mb-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        <span class="font-ui"><?php echo count($auction['branches']); ?> branch<?php echo count($auction['branches']) !== 1 ? 'es' : ''; ?></span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-ui"><?php echo e(implode(', ', array_slice($auction['branches'], 0, 2))); ?><?php echo count($auction['branches']) > 2 ? ' +' . (count($auction['branches']) - 2) . ' more' : ''; ?></p>
                                </div>
                                
                                <!-- Categories -->
                                <div class="flex flex-wrap gap-1 mb-3">
                                    <?php foreach (array_slice($auction['categories'], 0, 3) as $cat): ?>
                                        <span class="tag-pill text-[10px]"><?php echo e($cat); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Best For Tags -->
                                <div class="flex flex-wrap gap-1 mb-4">
                                    <?php foreach (array_slice($auction['bestForTags'], 0, 3) as $tag): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-ui font-semibold bg-primary/5 text-primary border border-primary/10"><?php echo e($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Action -->
                                <div class="mt-auto">
                                    <a href="profile.php?id=<?php echo $auction['id']; ?>" class="block w-full text-center px-4 py-2.5 bg-primary text-white font-ui font-semibold text-sm rounded-xl hover:bg-primary/90 transition-colors min-h-[44px] leading-[44px]">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php echo renderPagination($pagination['current'], $pagination['pages'], $baseUrl); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
