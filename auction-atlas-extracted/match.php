<?php
/**
 * Auction Atlas - Match Engine Page
 * 
 * Buyer type selection and top 3 match results
 * with explanation panels.
 */

$pageTitle = 'Buyer Match';
require_once __DIR__ . '/includes/matching.php';

$buyerTypes = getBuyerTypes();
$selectedType = isset($_GET['type']) ? trim($_GET['type']) : '';
$matches = [];

if (!empty($selectedType) && isset($buyerTypes[$selectedType])) {
    $matches = getTopMatches($selectedType, 3);
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Buyer Matching Engine</h1>
        <p class="text-slate-300 text-lg">Find the perfect auction house for your buyer profile</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Buyer Type Selection -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-6 text-center">Select Your Buyer Type</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($buyerTypes as $key => $type): ?>
                    <a href="match.php?type=<?php echo urlencode($key); ?>" 
                       class="glass-card p-5 text-center group <?php echo $selectedType === $key ? 'ring-2 ring-primary shadow-lg' : ''; ?>">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors
                                    <?php echo $selectedType === $key ? 'bg-primary text-white' : 'bg-primary/10 text-primary group-hover:bg-primary/20'; ?>">
                            <i data-lucide="<?php echo $type['icon']; ?>" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-base text-slate-900 mb-1"><?php echo e($type['label']); ?></h3>
                        <p class="text-xs text-slate-500 font-ui"><?php echo e($type['description']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if (!empty($matches)): ?>
            <!-- Match Results -->
            <div class="mb-6">
                <h2 class="font-heading font-bold text-2xl text-slate-900 mb-2 text-center">
                    Top 3 Matches for <?php echo e($buyerTypes[$selectedType]['label']); ?>
                </h2>
                <p class="text-slate-500 text-center font-ui mb-8">Ranked by weighted match score based on your buyer profile</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($matches as $i => $match): ?>
                    <div class="glass-card p-6 <?php echo $i === 0 ? 'border-2 border-highlight/30 relative' : ''; ?>">
                        <?php if ($i === 0): ?>
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1 px-3 py-1 bg-highlight text-slate-900 rounded-full text-xs font-ui font-bold shadow-lg">
                                <i data-lucide="crown" class="w-3 h-3"></i> Best Match
                            </div>
                        <?php endif; ?>
                        
                        <!-- Match Score -->
                        <div class="text-center mb-4 <?php echo $i === 0 ? 'mt-2' : ''; ?>">
                            <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Match Score</div>
                            <div class="text-4xl font-heading font-extrabold text-primary"><?php echo round($match['matchScore']); ?></div>
                        </div>
                        
                        <!-- Auction Info -->
                        <div class="text-center mb-4">
                            <h3 class="font-heading font-bold text-lg text-slate-900"><?php echo e($match['name']); ?></h3>
                            <p class="text-xs text-slate-500 font-ui"><?php echo e($match['city']); ?>, <?php echo e($match['province']); ?></p>
                        </div>
                        
                        <!-- Scores -->
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="text-lg font-heading font-bold <?php echo getTrustColor($match['scores']['trust']); ?>"><?php echo $match['scores']['trust']; ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase">Trust</div>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="text-lg font-heading font-bold <?php echo getRiskColor($match['scores']['risk']); ?>"><?php echo $match['scores']['risk']; ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase">Risk</div>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-lg">
                                <div class="grade-badge <?php echo getGradeBgColor($match['scores']['grade']); ?> <?php echo getGradeColor($match['scores']['grade']); ?> mx-auto"><?php echo $match['scores']['grade']; ?></div>
                                <div class="text-[10px] text-slate-500 font-ui uppercase mt-1">Grade</div>
                            </div>
                        </div>
                        
                        <!-- Match Reasons -->
                        <div class="mb-4">
                            <h4 class="text-xs font-ui font-semibold text-slate-500 uppercase mb-2">Why This Match</h4>
                            <ul class="space-y-1.5">
                                <?php foreach ($match['matchReasons'] as $reason): ?>
                                    <li class="flex items-start gap-2 text-xs font-ui text-slate-600">
                                        <i data-lucide="check" class="w-3 h-3 text-accent mt-0.5 flex-shrink-0"></i>
                                        <?php echo e($reason); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-1 mb-4">
                            <?php foreach (array_slice($match['bestForTags'], 0, 3) as $tag): ?>
                                <span class="tag-pill text-[10px]"><?php echo e($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Action -->
                        <a href="profile.php?id=<?php echo $match['id']; ?>" class="block w-full text-center px-4 py-2.5 bg-primary text-white font-ui font-semibold text-sm rounded-xl hover:bg-primary/90 transition-colors min-h-[44px] leading-[44px]">
                            View Full Profile
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($selectedType)): ?>
            <div class="glass-card-static p-12 text-center">
                <i data-lucide="search-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                <h3 class="font-heading font-bold text-xl text-slate-700 mb-2">No Matches Found</h3>
                <p class="text-slate-500">Try selecting a different buyer type.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
