<?php
/**
 * Auction Atlas - Fee Calculator Page
 * 
 * Standalone fee calculator with sliders and live JS calculations.
 * Responsive form layout with mobile-friendly inputs.
 */

$pageTitle = 'Fee Calculator';
require_once __DIR__ . '/includes/functions.php';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Auction Fee Calculator</h1>
        <p class="text-slate-300 text-lg">Calculate total costs including premium, VAT, and deposit requirements</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="glass-card-static p-6 lg:p-8" id="fee-calc-form">
            
            <!-- Input Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                
                <!-- Hammer Price -->
                <div class="sm:col-span-2">
                    <label class="flex justify-between items-center mb-2">
                        <span class="text-sm font-ui font-semibold text-slate-700">Hammer Price</span>
                        <span id="hammer-display" class="text-sm font-heading font-bold text-primary">R 500,000.00</span>
                    </label>
                    <input type="number" id="hammer-price" value="500000" min="0" max="50000000" step="10000" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]"
                           placeholder="Enter hammer price">
                    <input type="range" id="hammer-slider" min="0" max="5000000" step="10000" value="500000" class="w-full mt-3">
                    <div class="flex justify-between text-xs text-slate-400 font-ui mt-1">
                        <span>R 0</span>
                        <span>R 5,000,000</span>
                    </div>
                </div>
                
                <!-- Buyer Premium -->
                <div>
                    <label class="flex justify-between items-center mb-2">
                        <span class="text-sm font-ui font-semibold text-slate-700">Buyer Premium</span>
                        <span id="premium-display" class="text-sm font-heading font-bold text-primary">10%</span>
                    </label>
                    <input type="number" id="premium-percent" value="10" min="0" max="25" step="0.5" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                    <input type="range" id="premium-slider" min="0" max="25" step="0.5" value="10" class="w-full mt-3">
                    <div class="flex justify-between text-xs text-slate-400 font-ui mt-1">
                        <span>0%</span>
                        <span>25%</span>
                    </div>
                </div>
                
                <!-- Deposit -->
                <div>
                    <label class="flex justify-between items-center mb-2">
                        <span class="text-sm font-ui font-semibold text-slate-700">Deposit Required</span>
                        <span id="deposit-display" class="text-sm font-heading font-bold text-primary">10%</span>
                    </label>
                    <input type="number" id="deposit-percent" value="10" min="0" max="50" step="1" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                    <input type="range" id="deposit-slider" min="0" max="50" step="1" value="10" class="w-full mt-3">
                    <div class="flex justify-between text-xs text-slate-400 font-ui mt-1">
                        <span>0%</span>
                        <span>50%</span>
                    </div>
                </div>
                
                <!-- VAT -->
                <div>
                    <label class="flex justify-between items-center mb-2">
                        <span class="text-sm font-ui font-semibold text-slate-700">VAT on Premium</span>
                        <span id="vat-display" class="text-sm font-heading font-bold text-primary">15%</span>
                    </label>
                    <input type="number" id="vat-percent" value="15" min="0" max="20" step="0.5" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-ui focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]">
                    <input type="range" id="vat-slider" min="0" max="20" step="0.5" value="15" class="w-full mt-3">
                    <div class="flex justify-between text-xs text-slate-400 font-ui mt-1">
                        <span>0%</span>
                        <span>20%</span>
                    </div>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="border-t border-slate-200 my-6"></div>
            
            <!-- Results Section -->
            <h3 class="font-heading font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="receipt" class="w-5 h-5 text-primary"></i> Cost Breakdown
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-5 text-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="percent" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Buyer Premium</div>
                    <div id="result-premium" class="text-xl font-heading font-bold text-primary">R 0.00</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-5 text-center">
                    <div class="w-10 h-10 bg-warning/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="landmark" class="w-5 h-5 text-warning"></i>
                    </div>
                    <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">VAT on Premium</div>
                    <div id="result-vat" class="text-xl font-heading font-bold text-warning">R 0.00</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-5 text-center">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="lock" class="w-5 h-5 text-accent"></i>
                    </div>
                    <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-1">Deposit Required</div>
                    <div id="result-deposit" class="text-xl font-heading font-bold text-accent">R 0.00</div>
                </div>
            </div>
            
            <!-- Total & Balance -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-primary/5 rounded-xl p-6 text-center border-2 border-primary/10">
                    <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-2">Total Cost (Hammer + Premium + VAT)</div>
                    <div id="result-total" class="text-3xl font-heading font-extrabold text-primary">R 0.00</div>
                </div>
                <div class="bg-accent/5 rounded-xl p-6 text-center border-2 border-accent/10">
                    <div class="text-xs font-ui font-semibold text-slate-500 uppercase mb-2">Balance Due After Deposit</div>
                    <div id="result-balance" class="text-3xl font-heading font-extrabold text-accent">R 0.00</div>
                </div>
            </div>
        </div>
        
        <!-- Info Note -->
        <div class="mt-6 p-4 bg-slate-100 rounded-xl">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-slate-500 font-ui">
                    This calculator provides estimates based on standard auction fee structures. Actual fees may vary by auction house. 
                    VAT is calculated on the buyer's premium only. Always confirm exact fees with the auction house before bidding.
                </p>
            </div>
        </div>
    </div>
</section>

<script src="assets/js/calculator.js" defer></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
