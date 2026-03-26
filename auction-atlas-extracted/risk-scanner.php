<?php
/**
 * Auction Atlas - Risk Scanner Page
 * 
 * National risk averages, grade distribution,
 * and color-coded risk breakdown charts.
 */

$pageTitle = 'Risk Scanner';
require_once __DIR__ . '/includes/riskLogic.php';

$nationalAverages = getNationalRiskAverages();
$allAuctions = getAllScoredAuctions();

// Sort by risk (highest first)
usort($allAuctions, function($a, $b) {
    return $b['scores']['risk'] <=> $a['scores']['risk'];
});

// Get risk breakdowns for all
$auctionRisks = [];
foreach ($allAuctions as $a) {
    $a['riskBreakdown'] = calculateRiskBreakdown($a);
    $auctionRisks[] = $a;
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">National Risk Scanner</h1>
        <p class="text-slate-300 text-lg">Comprehensive risk analysis across South Africa's auction landscape</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- National Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="glass-card-static p-5 text-center">
                <div class="text-3xl font-heading font-extrabold text-primary" data-counter="<?php echo $nationalAverages['totalAuctions']; ?>">0</div>
                <div class="text-sm text-slate-500 font-ui">Total Auction Houses</div>
            </div>
            <div class="glass-card-static p-5 text-center">
                <div class="text-3xl font-heading font-extrabold <?php echo getTrustColor($nationalAverages['avgTrust']); ?>" data-counter="<?php echo $nationalAverages['avgTrust']; ?>">0</div>
                <div class="text-sm text-slate-500 font-ui">Avg Trust Score</div>
            </div>
            <div class="glass-card-static p-5 text-center">
                <div class="text-3xl font-heading font-extrabold <?php echo getRiskColor($nationalAverages['avgRisk']); ?>" data-counter="<?php echo $nationalAverages['avgRisk']; ?>">0</div>
                <div class="text-sm text-slate-500 font-ui">Avg Risk Score</div>
            </div>
            <div class="glass-card-static p-5 text-center">
                <div class="text-3xl font-heading font-extrabold text-warning" data-counter="<?php echo $nationalAverages['overall']; ?>">0</div>
                <div class="text-sm text-slate-500 font-ui">Overall Risk Index</div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            
            <!-- National Risk Averages Bar Chart -->
            <div class="glass-card-static p-6">
                <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary"></i> National Risk Averages
                </h2>
                <div class="chart-container">
                    <canvas id="national-risk-chart"></canvas>
                </div>
            </div>
            
            <!-- Grade Distribution -->
            <div class="glass-card-static p-6">
                <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-primary"></i> Grade Distribution
                </h2>
                <div class="chart-container max-w-xs mx-auto">
                    <canvas id="grade-chart"></canvas>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                    <div>
                        <div class="text-2xl font-heading font-bold text-emerald-500"><?php echo $nationalAverages['gradeDistribution']['A']; ?></div>
                        <div class="text-xs font-ui text-slate-500">Grade A</div>
                    </div>
                    <div>
                        <div class="text-2xl font-heading font-bold text-yellow-500"><?php echo $nationalAverages['gradeDistribution']['B']; ?></div>
                        <div class="text-xs font-ui text-slate-500">Grade B</div>
                    </div>
                    <div>
                        <div class="text-2xl font-heading font-bold text-red-500"><?php echo $nationalAverages['gradeDistribution']['C']; ?></div>
                        <div class="text-xs font-ui text-slate-500">Grade C</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- National Risk Breakdown Bars -->
        <div class="glass-card-static p-6 mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-6 flex items-center gap-2">
                <i data-lucide="shield-alert" class="w-5 h-5 text-primary"></i> National Risk Breakdown
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                $riskTypes = [
                    'Operational' => ['value' => $nationalAverages['operational'], 'icon' => 'settings', 'desc' => 'Transfer, inspection, settlement processes'],
                    'Compliance' => ['value' => $nationalAverages['compliance'], 'icon' => 'shield', 'desc' => 'Legal, licensing, policy compliance'],
                    'Fee' => ['value' => $nationalAverages['fee'], 'icon' => 'credit-card', 'desc' => 'Premium, hidden fees, VAT transparency'],
                    'Market' => ['value' => $nationalAverages['market'], 'icon' => 'trending-up', 'desc' => 'Reputation, reviews, complaints'],
                ];
                foreach ($riskTypes as $label => $data): 
                ?>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="<?php echo $data['icon']; ?>" class="w-6 h-6 text-slate-600"></i>
                        </div>
                        <div class="text-2xl font-heading font-bold <?php echo $data['value'] < 30 ? 'text-emerald-500' : ($data['value'] < 60 ? 'text-yellow-500' : 'text-red-500'); ?> mb-1"><?php echo $data['value']; ?></div>
                        <div class="text-sm font-heading font-semibold text-slate-800 mb-1"><?php echo $label; ?> Risk</div>
                        <div class="text-xs text-slate-500 font-ui"><?php echo $data['desc']; ?></div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
                            <div class="h-2 rounded-full risk-bar-fill <?php echo getRiskBarColor($data['value']); ?>" data-risk-width="<?php echo $data['value']; ?>" style="width: 0%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- All Auctions Risk Table -->
        <div class="glass-card-static overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h2 class="font-heading font-bold text-xl text-slate-900 flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-primary"></i> All Auction Houses by Risk
                </h2>
            </div>
            <div class="table-responsive">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="text-left px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Auction House</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Grade</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Trust</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Risk</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Operational</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Compliance</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Fee</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Market</th>
                            <th class="text-center px-4 py-3 text-xs font-heading font-bold text-slate-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($auctionRisks as $a): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-ui font-semibold text-sm text-slate-800"><?php echo e($a['name']); ?></div>
                                    <div class="text-xs text-slate-500 font-ui"><?php echo e($a['province']); ?></div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="grade-badge <?php echo getGradeBgColor($a['scores']['grade']); ?> <?php echo getGradeColor($a['scores']['grade']); ?>"><?php echo $a['scores']['grade']; ?></span>
                                </td>
                                <td class="px-4 py-3 text-center font-heading font-bold text-sm <?php echo getTrustColor($a['scores']['trust']); ?>"><?php echo $a['scores']['trust']; ?></td>
                                <td class="px-4 py-3 text-center font-heading font-bold text-sm <?php echo getRiskColor($a['scores']['risk']); ?>"><?php echo $a['scores']['risk']; ?></td>
                                <td class="px-4 py-3 text-center text-sm font-ui <?php echo $a['riskBreakdown']['operational'] < 30 ? 'text-emerald-600' : ($a['riskBreakdown']['operational'] < 60 ? 'text-yellow-600' : 'text-red-600'); ?>"><?php echo $a['riskBreakdown']['operational']; ?></td>
                                <td class="px-4 py-3 text-center text-sm font-ui <?php echo $a['riskBreakdown']['compliance'] < 30 ? 'text-emerald-600' : ($a['riskBreakdown']['compliance'] < 60 ? 'text-yellow-600' : 'text-red-600'); ?>"><?php echo $a['riskBreakdown']['compliance']; ?></td>
                                <td class="px-4 py-3 text-center text-sm font-ui <?php echo $a['riskBreakdown']['fee'] < 30 ? 'text-emerald-600' : ($a['riskBreakdown']['fee'] < 60 ? 'text-yellow-600' : 'text-red-600'); ?>"><?php echo $a['riskBreakdown']['fee']; ?></td>
                                <td class="px-4 py-3 text-center text-sm font-ui <?php echo $a['riskBreakdown']['market'] < 30 ? 'text-emerald-600' : ($a['riskBreakdown']['market'] < 60 ? 'text-yellow-600' : 'text-red-600'); ?>"><?php echo $a['riskBreakdown']['market']; ?></td>
                                <td class="px-4 py-3 text-center">
                                    <a href="profile.php?id=<?php echo $a['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-ui font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                                        View <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="assets/js/charts.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkChart = setInterval(function() {
        if (typeof Chart !== 'undefined') {
            clearInterval(checkChart);
            
            // National Risk Bar Chart
            createNationalBarChart('national-risk-chart', {
                labels: ['Operational', 'Compliance', 'Fee', 'Market', 'Overall'],
                values: [
                    <?php echo $nationalAverages['operational']; ?>,
                    <?php echo $nationalAverages['compliance']; ?>,
                    <?php echo $nationalAverages['fee']; ?>,
                    <?php echo $nationalAverages['market']; ?>,
                    <?php echo $nationalAverages['overall']; ?>
                ]
            });
            
            // Grade Distribution Chart
            createGradeChart('grade-chart', {
                A: <?php echo $nationalAverages['gradeDistribution']['A']; ?>,
                B: <?php echo $nationalAverages['gradeDistribution']['B']; ?>,
                C: <?php echo $nationalAverages['gradeDistribution']['C']; ?>
            });
        }
    }, 100);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
