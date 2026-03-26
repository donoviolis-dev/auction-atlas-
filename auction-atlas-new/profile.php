<?php
/**
 * Auction Atlas - Profile Page
 * 
 * Detailed auction house profile with overview, branches,
 * intelligence panel, risk breakdown, and embedded fee calculator.
 */

require_once __DIR__ . '/includes/scoring.php';
require_once __DIR__ . '/includes/riskLogic.php';

// Get auction ID
$auctionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$auction = getScoredAuctionById($auctionId);

if (!$auction) {
    $pageTitle = 'Not Found';
    require_once __DIR__ . '/includes/header.php';
    echo '<section class="py-20 text-center"><div class="max-w-7xl mx-auto px-4">';
    echo '<i data-lucide="alert-circle" class="w-16 h-16 text-slate-300 mx-auto mb-4"></i>';
    echo '<h1 class="font-heading font-bold text-2xl text-slate-700 mb-2">Auction House Not Found</h1>';
    echo '<p class="text-slate-500 mb-6">The requested auction house could not be found.</p>';
    echo '<a href="directory.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-ui font-semibold rounded-xl">Back to Directory</a>';
    echo '</div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $auction['name'];
$riskBreakdown = calculateRiskBreakdown($auction);

// Prepare radar chart data
$complianceCount = 0;
foreach ($auction['complianceSignals'] as $s) { if ($s) $complianceCount++; }
$complianceScore = round(($complianceCount / 7) * 100);

$reputationScore = 0;
$rep = $auction['reputationSignals'];
if ($rep['googleRating']) $reputationScore += min(40, round(($rep['googleRating'] / 5) * 40));
if ($rep['domainAgeYears']) $reputationScore += min(30, round(($rep['domainAgeYears'] / 25) * 30));
if ($rep['googleReviewCount']) $reputationScore += min(30, round(min($rep['googleReviewCount'], 200) / 200 * 30));

$opsScore = 50;
if ($auction['operationalIndicators']['inspectionOffered']) $opsScore += 15;
if ($auction['operationalIndicators']['settlementFlexibility']) $opsScore += 15;
if (!$auction['operationalIndicators']['hiddenFees']) $opsScore += 10;
if ($auction['operationalIndicators']['clearanceRate'] === 'High') $opsScore += 10;
$opsScore = min(100, $opsScore);

$feeTransparency = 100 - $riskBreakdown['fee'];

$radarData = [
    'trust' => $auction['scores']['trust'],
    'compliance' => $complianceScore,
    'reputation' => min(100, $reputationScore),
    'operations' => $opsScore,
    'feeTransparency' => $feeTransparency,
];

// Map data for branches
$mapLocations = [];
foreach ($auction['branches'] as $branch) {
    $mapLocations[] = [
        'name' => $auction['name'],
        'city' => $branch,
        'province' => $auction['province'],
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Profile Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-10 lg:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex-1">
                <div class="profile-logo flex-shrink-0">
                    <img src="<?php echo getAuctionLogo($auction['name']); ?>" 
                         alt="<?php echo e($auction['name']); ?> logo">
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-white"><?php echo e($auction['name']); ?></h1>
                    <div class="grade-badge-lg <?php echo getGradeBgColor($auction['scores']['grade']); ?> <?php echo getGradeColor($auction['scores']['grade']); ?>">
                        <?php echo $auction['scores']['grade']; ?>
                    </div>
                </div>
                <p class="text-slate-300 font-ui"><?php echo e($auction['legal_name']); ?> &middot; <?php echo e($auction['city']); ?>, <?php echo e($auction['province']); ?></p>
            </div>
            <a href="directory.php" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 text-white border border-white/20 rounded-xl font-ui text-sm hover:bg-white/20 transition-colors min-h-[44px]">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Directory
            </a>
        </div>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content (2 cols) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Overview -->
                <div class="glass-card-static p-6">
                    <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i> Overview
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Company Type</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['companyType']); ?></p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Years Operating</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['yearsOperatingRaw']); ?></p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Auction Format</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['auctionFormat']); ?></p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Frequency</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['auctionFrequency']); ?></p></div>
                        </div>
                        <div class="space-y-3">
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Reach</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['nationalOrRegional']); ?></p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Buyer Premium</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['buyerPremiumRaw']); ?></p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Transfer Days</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['transferDays']); ?> days</p></div>
                            <div><span class="text-xs font-ui font-semibold text-slate-500 uppercase">Payment Window</span><p class="text-sm font-ui text-slate-800"><?php echo e($auction['paymentWindow']); ?> days</p></div>
                        </div>
                    </div>
                </div>
                
                <!-- Branches & Provinces -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="glass-card-static p-6">
                        <h3 class="font-heading font-bold text-lg text-slate-900 mb-3 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i> Branches
                        </h3>
                        <ul class="space-y-2">
                            <?php foreach ($auction['branches'] as $branch): ?>
                                <li class="flex items-center gap-2 text-sm font-ui text-slate-700">
                                    <i data-lucide="building-2" class="w-4 h-4 text-accent"></i>
                                    <?php echo e($branch); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="glass-card-static p-6">
                        <h3 class="font-heading font-bold text-lg text-slate-900 mb-3 flex items-center gap-2">
                            <i data-lucide="globe" class="w-5 h-5 text-primary"></i> Provinces
                        </h3>
                        <ul class="space-y-2">
                            <?php foreach ($auction['provinces'] as $prov): ?>
                                <li class="flex items-center gap-2 text-sm font-ui text-slate-700">
                                    <i data-lucide="map" class="w-4 h-4 text-accent"></i>
                                    <?php echo e($prov); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Categories & Auction Types -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-3 flex items-center gap-2">
                        <i data-lucide="tag" class="w-5 h-5 text-primary"></i> Categories & Types
                    </h3>
                    <div class="mb-4">
                        <span class="text-xs font-ui font-semibold text-slate-500 uppercase block mb-2">Categories Auctioned</span>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($auction['categories'] as $cat): ?>
                                <a href="category.php?name=<?php echo urlencode($cat); ?>" class="tag-pill hover:bg-accent/20 transition-colors"><?php echo e($cat); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <span class="text-xs font-ui font-semibold text-slate-500 uppercase block mb-2">Auction Types</span>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($auction['auctionTypes'] as $type): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-ui font-semibold bg-primary/5 text-primary border border-primary/10"><?php echo e($type); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-ui font-semibold text-slate-500 uppercase block mb-2">Best For</span>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($auction['bestForTags'] as $tag): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-ui font-semibold bg-highlight/10 text-yellow-700 border border-highlight/20"><?php echo e($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-3 flex items-center gap-2">
                        <i data-lucide="phone" class="w-5 h-5 text-primary"></i> Contact
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-5 h-5 text-accent"></i>
                            <a href="mailto:<?php echo e($auction['email']); ?>" class="text-sm font-ui text-primary hover:underline"><?php echo e($auction['email']); ?></a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-5 h-5 text-accent"></i>
                            <a href="tel:<?php echo e($auction['phone']); ?>" class="text-sm font-ui text-primary hover:underline"><?php echo e($auction['phone']); ?></a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="globe" class="w-5 h-5 text-accent"></i>
                            <a href="<?php echo e($auction['website']); ?>" target="_blank" rel="noopener" class="text-sm font-ui text-primary hover:underline"><?php echo e($auction['website']); ?></a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-accent"></i>
                            <span class="text-sm font-ui text-slate-700"><?php echo e($auction['address']); ?>, <?php echo e($auction['city']); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Branch Map -->
                <div class="glass-card-static overflow-hidden">
                    <div class="p-4 border-b border-slate-100">
                        <h3 class="font-heading font-bold text-lg text-slate-900 flex items-center gap-2">
                            <i data-lucide="map" class="w-5 h-5 text-primary"></i> Branch Locations
                        </h3>
                    </div>
                    <div id="profile-map" style="height: 300px; width: 100%;"></div>
                </div>
                
                <!-- Risk Scanner Bars -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-5 h-5 text-primary"></i> Risk Breakdown
                    </h3>
                    <div class="space-y-4">
                        <?php 
                        $riskCategories = [
                            'Operational' => $riskBreakdown['operational'],
                            'Compliance' => $riskBreakdown['compliance'],
                            'Fee' => $riskBreakdown['fee'],
                            'Market' => $riskBreakdown['market'],
                        ];
                        foreach ($riskCategories as $label => $value): 
                        ?>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-ui font-semibold text-slate-700"><?php echo $label; ?> Risk</span>
                                    <span class="text-sm font-heading font-bold <?php echo $value < 30 ? 'text-emerald-500' : ($value < 60 ? 'text-yellow-500' : 'text-red-500'); ?>"><?php echo $value; ?>/100</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3">
                                    <div class="h-3 rounded-full risk-bar-fill <?php echo getRiskBarColor($value); ?>" data-risk-width="<?php echo $value; ?>" style="width: 0%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Embedded Fee Calculator -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="calculator" class="w-5 h-5 text-primary"></i> Fee Calculator
                    </h3>
                    <div id="fee-calc-form">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Hammer Price (R)</label>
                                <input type="number" id="hammer-price" value="500000" min="0" step="10000" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                                <input type="range" id="hammer-slider" min="0" max="5000000" step="10000" value="500000" class="w-full mt-2">
                            </div>
                            <div>
                                <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Premium % <span id="premium-display" class="text-primary"><?php echo $auction['buyerPremium']; ?>%</span></label>
                                <input type="number" id="premium-percent" value="<?php echo $auction['buyerPremium']; ?>" min="0" max="25" step="0.5" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                                <input type="range" id="premium-slider" min="0" max="25" step="0.5" value="<?php echo $auction['buyerPremium']; ?>" class="w-full mt-2">
                            </div>
                            <div>
                                <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">Deposit % <span id="deposit-display" class="text-primary">10%</span></label>
                                <input type="number" id="deposit-percent" value="10" min="0" max="50" step="1" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                                <input type="range" id="deposit-slider" min="0" max="50" step="1" value="10" class="w-full mt-2">
                            </div>
                            <div>
                                <label class="block text-sm font-ui font-semibold text-slate-700 mb-1">VAT % <span id="vat-display" class="text-primary">15%</span></label>
                                <input type="number" id="vat-percent" value="15" min="0" max="20" step="0.5" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-ui min-h-[44px]">
                                <input type="range" id="vat-slider" min="0" max="20" step="0.5" value="15" class="w-full mt-2">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <div class="text-xs font-ui text-slate-500 mb-1">Premium</div>
                                <div id="result-premium" class="text-sm font-heading font-bold text-primary">R 0.00</div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <div class="text-xs font-ui text-slate-500 mb-1">VAT on Premium</div>
                                <div id="result-vat" class="text-sm font-heading font-bold text-warning">R 0.00</div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <div class="text-xs font-ui text-slate-500 mb-1">Deposit</div>
                                <div id="result-deposit" class="text-sm font-heading font-bold text-accent">R 0.00</div>
                            </div>
                            <div class="bg-primary/5 rounded-xl p-3 text-center border border-primary/10">
                                <div class="text-xs font-ui text-slate-500 mb-1">Total Cost</div>
                                <div id="result-total" class="text-sm font-heading font-bold text-primary">R 0.00</div>
                            </div>
                            <div class="bg-accent/5 rounded-xl p-3 text-center border border-accent/10">
                                <div class="text-xs font-ui text-slate-500 mb-1">Balance Due</div>
                                <div id="result-balance" class="text-sm font-heading font-bold text-accent">R 0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar: Intelligence Panel -->
            <div class="space-y-6">
                
                <!-- Score Cards -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary"></i> Intelligence
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Trust Score -->
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Trust Score</div>
                            <div class="text-4xl font-heading font-extrabold <?php echo getTrustColor($auction['scores']['trust']); ?>"><?php echo $auction['scores']['trust']; ?></div>
                            <div class="text-xs text-slate-400 font-ui">/100</div>
                        </div>
                        
                        <!-- Risk Score -->
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Risk Score</div>
                            <div class="text-4xl font-heading font-extrabold <?php echo getRiskColor($auction['scores']['risk']); ?>"><?php echo $auction['scores']['risk']; ?></div>
                            <div class="text-xs text-slate-400 font-ui">/100</div>
                        </div>
                        
                        <!-- Grade -->
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Institutional Grade</div>
                            <div class="grade-badge-lg <?php echo getGradeBgColor($auction['scores']['grade']); ?> <?php echo getGradeColor($auction['scores']['grade']); ?> mx-auto">
                                <?php echo $auction['scores']['grade']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Radar Chart -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Profile Radar</h3>
                    <div class="chart-container">
                        <canvas id="profile-radar"></canvas>
                    </div>
                </div>
                
                <!-- Compliance Checklist -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i> Compliance
                    </h3>
                    <ul class="space-y-2">
                        <?php 
                        $complianceLabels = [
                            'https' => 'HTTPS Secure',
                            'vatRegistered' => 'VAT Registered',
                            'estateLicense' => 'Estate License',
                            'popiaPolicy' => 'POPIA Policy',
                            'termsPage' => 'Terms Page',
                            'refundPolicy' => 'Refund Policy',
                            'licensingClaims' => 'Licensing Claims',
                        ];
                        foreach ($complianceLabels as $key => $label): 
                            $active = $auction['complianceSignals'][$key];
                        ?>
                            <li class="flex items-center gap-2 text-sm font-ui">
                                <?php if ($active): ?>
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                                    <span class="text-slate-700"><?php echo $label; ?></span>
                                <?php else: ?>
                                    <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i>
                                    <span class="text-slate-400"><?php echo $label; ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Reputation -->
                <div class="glass-card-static p-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5 text-primary"></i> Reputation
                    </h3>
                    <div class="space-y-3">
                        <?php if ($auction['reputationSignals']['googleRating']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-ui text-slate-600">Google Rating</span>
                                <span class="font-heading font-bold text-primary"><?php echo $auction['reputationSignals']['googleRating']; ?>/5</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($auction['reputationSignals']['googleReviewCount']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-ui text-slate-600">Reviews</span>
                                <span class="font-heading font-bold text-primary"><?php echo $auction['reputationSignals']['googleReviewCount']; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($auction['reputationSignals']['domainAgeYears']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-ui text-slate-600">Domain Age</span>
                                <span class="font-heading font-bold text-primary"><?php echo $auction['reputationSignals']['domainAgeYears']; ?> years</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="assets/js/maps.js" defer></script>
<script src="assets/js/charts.js" defer></script>
<script src="assets/js/calculator.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Radar Chart
    var checkChart = setInterval(function() {
        if (typeof Chart !== 'undefined') {
            clearInterval(checkChart);
            createRadarChart('profile-radar', {
                labels: ['Trust', 'Compliance', 'Reputation', 'Operations', 'Fee Transparency'],
                datasets: [{
                    label: '<?php echo e($auction['name']); ?>',
                    data: [<?php echo $radarData['trust']; ?>, <?php echo $radarData['compliance']; ?>, <?php echo $radarData['reputation']; ?>, <?php echo $radarData['operations']; ?>, <?php echo $radarData['feeTransparency']; ?>]
                }]
            });
        }
    }, 100);
    
    // Map
    var checkLeaflet = setInterval(function() {
        if (typeof L !== 'undefined') {
            clearInterval(checkLeaflet);
            var locations = <?php echo json_encode($mapLocations); ?>;
            initAuctionMap('profile-map', locations);
        }
    }, 100);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
