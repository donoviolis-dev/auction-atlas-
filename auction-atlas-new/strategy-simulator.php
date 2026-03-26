<?php
/**
 * Auction Atlas - Strategy Simulator Page
 * 
 * Simulates auction strategies based on capital, risk tolerance,
 * experience level, asset type, and time horizon.
 */

$pageTitle = 'Strategy Simulator';
require_once __DIR__ . '/includes/scoring.php';

$allAuctions = getAllScoredAuctions();

// Process form submission
$results = null;
$formSubmitted = false;

if (isset($_GET['capital'])) {
    $formSubmitted = true;
    
    $capital = max(0, (float)($_GET['capital'] ?? 500000));
    $riskTolerance = $_GET['risk_tolerance'] ?? 'moderate';
    $experience = $_GET['experience'] ?? 'intermediate';
    $assetType = $_GET['asset_type'] ?? 'property';
    $timeHorizon = $_GET['time_horizon'] ?? 'medium';
    
    // Calculate strategy recommendations
    $premiumTolerance = 10;
    $aggressiveness = 50;
    $recommendedTypes = [];
    $suggestedPlatforms = [];
    
    // Risk tolerance adjustments
    switch ($riskTolerance) {
        case 'low':
            $premiumTolerance = 8;
            $aggressiveness = 25;
            break;
        case 'moderate':
            $premiumTolerance = 12;
            $aggressiveness = 50;
            break;
        case 'high':
            $premiumTolerance = 15;
            $aggressiveness = 75;
            break;
        case 'aggressive':
            $premiumTolerance = 18;
            $aggressiveness = 90;
            break;
    }
    
    // Experience adjustments
    switch ($experience) {
        case 'beginner':
            $aggressiveness = max(10, $aggressiveness - 20);
            $premiumTolerance = min($premiumTolerance, 10);
            break;
        case 'intermediate':
            // No adjustment
            break;
        case 'advanced':
            $aggressiveness = min(95, $aggressiveness + 10);
            break;
        case 'expert':
            $aggressiveness = min(100, $aggressiveness + 20);
            $premiumTolerance = min(20, $premiumTolerance + 2);
            break;
    }
    
    // Asset type recommendations
    $assetCategories = [];
    switch ($assetType) {
        case 'property':
            $recommendedTypes = ['Property', 'Commercial', 'Residential'];
            $assetCategories = ['Property', 'Commercial Property', 'Residential Property'];
            break;
        case 'vehicles':
            $recommendedTypes = ['Vehicle', 'Salvage'];
            $assetCategories = ['Vehicles', 'Cars', 'Salvage Assets'];
            break;
        case 'industrial':
            $recommendedTypes = ['Industrial', 'Machinery'];
            $assetCategories = ['Industrial Assets', 'Machinery', 'Industrial'];
            break;
        case 'art-collectibles':
            $recommendedTypes = ['Fine Art', 'Antiques', 'Collectibles'];
            $assetCategories = ['Fine Art', 'Antiques', 'Collectibles', 'Art', 'Jewellery'];
            break;
        case 'general':
            $recommendedTypes = ['General', 'Estate', 'Assets'];
            $assetCategories = ['General Assets', 'Furniture', 'Household Items'];
            break;
    }
    
    // Time horizon adjustments
    switch ($timeHorizon) {
        case 'short':
            $aggressiveness = min(100, $aggressiveness + 15);
            break;
        case 'medium':
            // No adjustment
            break;
        case 'long':
            $aggressiveness = max(10, $aggressiveness - 10);
            $premiumTolerance = max(5, $premiumTolerance - 2);
            break;
    }
    
    // Capital-based adjustments
    if ($capital < 100000) {
        $aggressiveness = max(10, $aggressiveness - 15);
        $premiumTolerance = min($premiumTolerance, 10);
    } elseif ($capital > 2000000) {
        $aggressiveness = min(100, $aggressiveness + 10);
    }
    
    // Find suggested platforms
    foreach ($allAuctions as $a) {
        $match = false;
        foreach ($a['categories'] as $cat) {
            if (in_array($cat, $assetCategories)) {
                $match = true;
                break;
            }
        }
        
        if ($match && $a['buyerPremium'] <= $premiumTolerance) {
            // Filter by risk tolerance
            if ($riskTolerance === 'low' && $a['scores']['risk'] > 40) continue;
            if ($riskTolerance === 'moderate' && $a['scores']['risk'] > 60) continue;
            
            $suggestedPlatforms[] = $a;
        }
    }
    
    // Sort by trust score
    usort($suggestedPlatforms, function($a, $b) {
        return $b['scores']['trust'] <=> $a['scores']['trust'];
    });
    
    $suggestedPlatforms = array_slice($suggestedPlatforms, 0, 5);
    
    $results = [
        'capital' => $capital,
        'riskTolerance' => $riskTolerance,
        'experience' => $experience,
        'assetType' => $assetType,
        'timeHorizon' => $timeHorizon,
        'premiumTolerance' => $premiumTolerance,
        'aggressiveness' => $aggressiveness,
        'recommendedTypes' => $recommendedTypes,
        'suggestedPlatforms' => $suggestedPlatforms,
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Strategy Simulator</h1>
        <p class="text-slate-300 text-lg">Simulate your auction strategy based on your profile and goals</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Strategy Form -->
        <form method="GET" action="strategy-simulator.php" class="glass-card-static p-6 lg:p-8 mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-6 flex items-center gap-2">
                <i data-lucide="sliders" class="w-5 h-5 text-primary"></i> Configure Your Strategy
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                
                <!-- Capital -->
                <div>
                    <label class="block text-sm font-ui font-semibold text-slate-700 mb-2">Available Capital (R)</label>
                    <input type="number" name="capital" value="<?php echo $results ? $results['capital'] : 500000; ?>" min="0" step="10000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]"
                           placeholder="e.g. 500000">
                </div>
                
                <!-- Risk Tolerance -->
                <div>
                    <label class="block text-sm font-ui font-semibold text-slate-700 mb-2">Risk Tolerance</label>
                    <select name="risk_tolerance" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                        <option value="low" <?php echo ($results && $results['riskTolerance'] === 'low') ? 'selected' : ''; ?>>Low - Conservative</option>
                        <option value="moderate" <?php echo (!$results || ($results && $results['riskTolerance'] === 'moderate')) ? 'selected' : ''; ?>>Moderate - Balanced</option>
                        <option value="high" <?php echo ($results && $results['riskTolerance'] === 'high') ? 'selected' : ''; ?>>High - Growth-focused</option>
                        <option value="aggressive" <?php echo ($results && $results['riskTolerance'] === 'aggressive') ? 'selected' : ''; ?>>Aggressive - Maximum returns</option>
                    </select>
                </div>
                
                <!-- Experience Level -->
                <div>
                    <label class="block text-sm font-ui font-semibold text-slate-700 mb-2">Experience Level</label>
                    <select name="experience" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                        <option value="beginner" <?php echo ($results && $results['experience'] === 'beginner') ? 'selected' : ''; ?>>Beginner - First time</option>
                        <option value="intermediate" <?php echo (!$results || ($results && $results['experience'] === 'intermediate')) ? 'selected' : ''; ?>>Intermediate - Some experience</option>
                        <option value="advanced" <?php echo ($results && $results['experience'] === 'advanced') ? 'selected' : ''; ?>>Advanced - Regular buyer</option>
                        <option value="expert" <?php echo ($results && $results['experience'] === 'expert') ? 'selected' : ''; ?>>Expert - Professional dealer</option>
                    </select>
                </div>
                
                <!-- Asset Type -->
                <div>
                    <label class="block text-sm font-ui font-semibold text-slate-700 mb-2">Target Asset Type</label>
                    <select name="asset_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                        <option value="property" <?php echo (!$results || ($results && $results['assetType'] === 'property')) ? 'selected' : ''; ?>>Property</option>
                        <option value="vehicles" <?php echo ($results && $results['assetType'] === 'vehicles') ? 'selected' : ''; ?>>Vehicles</option>
                        <option value="industrial" <?php echo ($results && $results['assetType'] === 'industrial') ? 'selected' : ''; ?>>Industrial & Machinery</option>
                        <option value="art-collectibles" <?php echo ($results && $results['assetType'] === 'art-collectibles') ? 'selected' : ''; ?>>Art & Collectibles</option>
                        <option value="general" <?php echo ($results && $results['assetType'] === 'general') ? 'selected' : ''; ?>>General Assets</option>
                    </select>
                </div>
                
                <!-- Time Horizon -->
                <div>
                    <label class="block text-sm font-ui font-semibold text-slate-700 mb-2">Time Horizon</label>
                    <select name="time_horizon" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                        <option value="short" <?php echo ($results && $results['timeHorizon'] === 'short') ? 'selected' : ''; ?>>Short - Under 3 months</option>
                        <option value="medium" <?php echo (!$results || ($results && $results['timeHorizon'] === 'medium')) ? 'selected' : ''; ?>>Medium - 3-12 months</option>
                        <option value="long" <?php echo ($results && $results['timeHorizon'] === 'long') ? 'selected' : ''; ?>>Long - Over 12 months</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-primary to-accent text-white font-ui font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all min-h-[44px]">
                <i data-lucide="play" class="w-5 h-5"></i> Run Simulation
            </button>
        </form>
        
        <?php if ($results): ?>
            <!-- Strategy Results -->
            <div class="space-y-8">
                
                <!-- Strategy Overview -->
                <div class="glass-card-static p-6">
                    <h2 class="font-heading font-bold text-xl text-slate-900 mb-6 flex items-center gap-2">
                        <i data-lucide="zap" class="w-5 h-5 text-highlight"></i> Strategy Results
                    </h2>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Premium Tolerance</div>
                            <div class="text-2xl font-heading font-extrabold text-primary"><?php echo $results['premiumTolerance']; ?>%</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Aggressiveness</div>
                            <div class="text-2xl font-heading font-extrabold <?php echo $results['aggressiveness'] > 70 ? 'text-red-500' : ($results['aggressiveness'] > 40 ? 'text-yellow-500' : 'text-emerald-500'); ?>"><?php echo $results['aggressiveness']; ?>%</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Capital</div>
                            <div class="text-2xl font-heading font-extrabold text-accent">R <?php echo number_format($results['capital']); ?></div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Matches Found</div>
                            <div class="text-2xl font-heading font-extrabold text-primary"><?php echo count($results['suggestedPlatforms']); ?></div>
                        </div>
                    </div>
                    
                    <!-- Aggressiveness Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-ui font-semibold text-slate-700">Aggressiveness Score</span>
                            <span class="text-sm font-heading font-bold"><?php echo $results['aggressiveness']; ?>/100</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-4">
                            <div class="h-4 rounded-full transition-all duration-1000 <?php echo $results['aggressiveness'] > 70 ? 'bg-red-500' : ($results['aggressiveness'] > 40 ? 'bg-yellow-500' : 'bg-emerald-500'); ?>" style="width: <?php echo $results['aggressiveness']; ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 font-ui mt-1">
                            <span>Conservative</span>
                            <span>Moderate</span>
                            <span>Aggressive</span>
                        </div>
                    </div>
                    
                    <!-- Recommended Auction Types -->
                    <div class="mb-4">
                        <h3 class="text-sm font-ui font-semibold text-slate-700 mb-2">Recommended Auction Types</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($results['recommendedTypes'] as $type): ?>
                                <span class="tag-pill"><?php echo e($type); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Suggested Platforms -->
                <?php if (!empty($results['suggestedPlatforms'])): ?>
                    <div>
                        <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                            <i data-lucide="building-2" class="w-5 h-5 text-primary"></i> Suggested Platforms
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php foreach ($results['suggestedPlatforms'] as $platform): ?>
                                <div class="glass-card p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-heading font-bold text-base text-slate-900 truncate"><?php echo e($platform['name']); ?></h3>
                                            <p class="text-xs text-slate-500 font-ui"><?php echo e($platform['city']); ?>, <?php echo e($platform['province']); ?></p>
                                        </div>
                                        <div class="grade-badge <?php echo getGradeBgColor($platform['scores']['grade']); ?> <?php echo getGradeColor($platform['scores']['grade']); ?> ml-2 flex-shrink-0">
                                            <?php echo $platform['scores']['grade']; ?>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <div class="text-center p-2 bg-slate-50 rounded-lg">
                                            <div class="text-lg font-heading font-bold <?php echo getTrustColor($platform['scores']['trust']); ?>"><?php echo $platform['scores']['trust']; ?></div>
                                            <div class="text-[10px] text-slate-500 font-ui uppercase">Trust</div>
                                        </div>
                                        <div class="text-center p-2 bg-slate-50 rounded-lg">
                                            <div class="text-lg font-heading font-bold <?php echo getRiskColor($platform['scores']['risk']); ?>"><?php echo $platform['scores']['risk']; ?></div>
                                            <div class="text-[10px] text-slate-500 font-ui uppercase">Risk</div>
                                        </div>
                                        <div class="text-center p-2 bg-slate-50 rounded-lg">
                                            <div class="text-lg font-heading font-bold text-primary"><?php echo e($platform['buyerPremiumRaw']); ?></div>
                                            <div class="text-[10px] text-slate-500 font-ui uppercase">Premium</div>
                                        </div>
                                    </div>
                                    <a href="profile.php?id=<?php echo $platform['id']; ?>" class="block w-full text-center px-4 py-2.5 bg-primary text-white font-ui font-semibold text-sm rounded-xl hover:bg-primary/90 transition-colors min-h-[44px] leading-[44px]">
                                        View Profile
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="glass-card-static p-8 text-center">
                        <i data-lucide="search-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                        <h3 class="font-heading font-bold text-xl text-slate-700 mb-2">No Matching Platforms</h3>
                        <p class="text-slate-500 font-ui">Try adjusting your risk tolerance or premium expectations to find more matches.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
