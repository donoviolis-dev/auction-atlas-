<?php
/**
 * Auction Atlas - Comparison Page
 * 
 * Side-by-side comparison of 2-4 auction houses
 * with comparison table and radar chart overlay.
 * 
 * Uses GET form submission with auctions[] array.
 */

$pageTitle = 'Compare';
require_once __DIR__ . '/includes/scoring.php';
require_once __DIR__ . '/includes/riskLogic.php';

$allAuctions = getAllScoredAuctions();

// Read selected auction IDs from GET
$selected = $_GET['auctions'] ?? [];

// Validate - must be array
if (!is_array($selected)) {
    $selected = [];
}

// Trim to max 4
if (count($selected) > 4) {
    $selected = array_slice($selected, 0, 4);
}

// Convert to integers
$selected = array_map('intval', $selected);

// Get selected auctions
$compareAuctions = [];
foreach ($selected as $id) {
    foreach ($allAuctions as $a) {
        if ($a['id'] === $id) {
            $a['riskBreakdown'] = calculateRiskBreakdown($a);
            $compareAuctions[] = $a;
            break;
        }
    }
}

// Prepare radar data for comparison
$radarDatasets = [];
foreach ($compareAuctions as $a) {
    $compCount = 0;
    foreach ($a['complianceSignals'] as $s) { if ($s) $compCount++; }
    $compScore = round(($compCount / 7) * 100);
    
    $repScore = 0;
    $rep = $a['reputationSignals'];
    if ($rep['googleRating']) $repScore += min(40, round(($rep['googleRating'] / 5) * 40));
    if ($rep['domainAgeYears']) $repScore += min(30, round(($rep['domainAgeYears'] / 25) * 30));
    if ($rep['googleReviewCount']) $repScore += min(30, round(min($rep['googleReviewCount'], 200) / 200 * 30));
    
    $opsScore = 50;
    if ($a['operationalIndicators']['inspectionOffered']) $opsScore += 15;
    if ($a['operationalIndicators']['settlementFlexibility']) $opsScore += 15;
    if (!$a['operationalIndicators']['hiddenFees']) $opsScore += 10;
    if ($a['operationalIndicators']['clearanceRate'] === 'High') $opsScore += 10;
    $opsScore = min(100, $opsScore);
    
    $feeT = 100 - $a['riskBreakdown']['fee'];
    
    $radarDatasets[] = [
        'label' => $a['name'],
        'data' => [$a['scores']['trust'], $compScore, min(100, $repScore), $opsScore, $feeT],
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Compare Auction Houses</h1>
        <p class="text-slate-300 text-lg">Side-by-side comparison of trust, risk, and operational metrics</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (count($compareAuctions) < 2): ?>
            <!-- Selection Form -->
            <div class="glass-card-static p-6 mb-8">
                <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="check-square" class="w-5 h-5 text-primary"></i> Select Auction Houses to Compare
                </h2>
                <p class="text-sm text-slate-500 mb-4 font-ui">Select 2 to 4 auction houses for comparison.</p>
                
                <form method="GET" action="compare.php">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                        <?php foreach ($allAuctions as $a): ?>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-primary/30 hover:bg-primary/5 cursor-pointer transition-all">
                                <input type="checkbox" name="auctions[]" value="<?php echo $a['id']; ?>" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-ui font-semibold text-slate-800 block truncate"><?php echo e($a['name']); ?></span>
                                    <span class="text-xs text-slate-500 font-ui"><?php echo e($a['province']); ?> &middot; Grade <?php echo $a['scores']['grade']; ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-ui font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all min-h-[44px]">
                        <i data-lucide="git-compare" class="w-5 h-5"></i> Compare Selected
                    </button>
                </form>
            </div>
            
        <?php else: ?>
            
            <!-- Radar Comparison Chart -->
            <div class="glass-card-static p-6 mb-8">
                <h2 class="font-heading font-bold text-xl text-slate-900 mb-4">Radar Comparison</h2>
                <div class="chart-container max-w-lg mx-auto">
                    <canvas id="compare-radar"></canvas>
                </div>
            </div>
            
            <!-- Comparison Table -->
            <div class="glass-card-static overflow-hidden mb-8">
                <div class="table-responsive">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="text-left px-4 py-3 text-sm font-heading font-bold text-slate-700 sticky left-0 bg-slate-50 z-10 min-w-[160px]">Metric</th>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <th class="text-center px-4 py-3 text-sm font-heading font-bold text-slate-700 min-w-[180px]">
                                        <?php echo e($a['name']); ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Grade -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Institutional Grade</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center">
                                        <span class="grade-badge <?php echo getGradeBgColor($a['scores']['grade']); ?> <?php echo getGradeColor($a['scores']['grade']); ?>"><?php echo $a['scores']['grade']; ?></span>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Trust Score -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Trust Score</td>
                                <?php 
                                $maxTrust = max(array_map(function($a) { return $a['scores']['trust']; }, $compareAuctions));
                                foreach ($compareAuctions as $a): 
                                    $isHighest = ($a['scores']['trust'] === $maxTrust && $maxTrust > 0);
                                ?>
                                    <td class="px-4 py-3 text-center font-heading font-bold <?php echo $isHighest ? 'text-emerald-600 bg-emerald-50' : getTrustColor($a['scores']['trust']); ?>">
                                        <?php echo $a['scores']['trust']; ?>/100
                                        <?php if ($isHighest): ?><i data-lucide="award" class="w-4 h-4 inline ml-1"></i><?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Risk Score -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Risk Score</td>
                                <?php 
                                $minRisk = min(array_map(function($a) { return $a['scores']['risk']; }, $compareAuctions));
                                foreach ($compareAuctions as $a): 
                                    $isLowest = ($a['scores']['risk'] === $minRisk);
                                ?>
                                    <td class="px-4 py-3 text-center font-heading font-bold <?php echo $isLowest ? 'text-emerald-600 bg-emerald-50' : getRiskColor($a['scores']['risk']); ?>">
                                        <?php echo $a['scores']['risk']; ?>/100
                                        <?php if ($isLowest): ?><i data-lucide="award" class="w-4 h-4 inline ml-1"></i><?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Buyer Premium -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Buyer Premium</td>
                                <?php 
                                $maxPremium = max(array_map(function($a) { return $a['buyerPremium']; }, $compareAuctions));
                                foreach ($compareAuctions as $a): 
                                    $isHighest = ($a['buyerPremium'] === $maxPremium);
                                ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui <?php echo $isHighest ? 'text-red-600 bg-red-50 font-semibold' : ''; ?>">
                                        <?php echo e($a['buyerPremiumRaw']); ?>
                                        <?php if ($isHighest && $maxPremium > 0): ?><span class="text-xs ml-1">(Highest)</span><?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Province -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Province</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo e($a['province']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Provincial Reach -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Provinces Covered</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo count($a['provinces']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Branches -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Branch Count</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo count($a['branches']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Auction Format -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Auction Format</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo e($a['auctionFormat']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Years Operating -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Years Operating</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo e($a['yearsOperatingRaw']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Transfer Days -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Transfer Days</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo e($a['transferDays']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Deposit -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Deposit Required</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo e($a['deposit']); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- VAT Registered -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">VAT Registered</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui">
                                        <?php if ($a['vatOnPremium'] === 'Yes'): ?>
                                            <span class="text-emerald-600 font-semibold">Yes</span>
                                        <?php else: ?>
                                            <span class="text-slate-400">No</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Hidden Fees -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Hidden Fees</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui">
                                        <?php if ($a['operationalIndicators']['hiddenFees']): ?>
                                            <span class="text-red-500 font-semibold">Yes</span>
                                        <?php else: ?>
                                            <span class="text-emerald-500 font-semibold">No</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Google Rating -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Google Rating</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui"><?php echo $a['reputationSignals']['googleRating'] ? $a['reputationSignals']['googleRating'] . '/5' : 'N/A'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- Asset Categories -->
                            <tr>
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-white z-10">Categories</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center text-sm font-ui">
                                        <?php echo implode(', ', array_slice($a['categories'], 0, 2)); ?>
                                        <?php if (count($a['categories']) > 2): ?>
                                            <span class="text-slate-400">+<?php echo count($a['categories']) - 2; ?></span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <!-- View Profile -->
                            <tr class="bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-ui font-semibold text-slate-600 sticky left-0 bg-slate-50/50 z-10">Profile</td>
                                <?php foreach ($compareAuctions as $a): ?>
                                    <td class="px-4 py-3 text-center">
                                        <a href="profile.php?id=<?php echo $a['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-ui font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                                            View <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </a>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- New Comparison -->
            <div class="text-center">
                <a href="compare.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-ui font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all min-h-[44px]">
                    <i data-lucide="refresh-ccw" class="w-5 h-5"></i> New Comparison
                </a>
            </div>
            
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($compareAuctions)): ?>
<script src="assets/js/charts.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkChart = setInterval(function() {
        if (typeof Chart !== 'undefined') {
            clearInterval(checkChart);
            createRadarChart('compare-radar', {
                labels: ['Trust', 'Compliance', 'Reputation', 'Operations', 'Fee Transparency'],
                datasets: <?php echo json_encode($radarDatasets); ?>
            });
        }
    }, 100);
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
