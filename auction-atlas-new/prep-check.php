<?php
/**
 * Auction Atlas - Preparation Checklist
 * 
 * Interactive checklist for auction preparation.
 */

$pageTitle = 'Prep Checklist';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Auction Preparation Checklist</h1>
        <p class="text-slate-300 text-lg">Ensure you're ready before you bid</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section 1: Required Documents -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="folder-open" class="w-5 h-5 text-primary"></i> Required Documents
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="id-card" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">ID Document</h3>
                            <p class="text-xs text-slate-500 font-ui">Valid SA ID or passport</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Copy of your South African ID book/card or valid passport with visa.</p>
                </div>
                
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="home" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">Proof of Address</h3>
                            <p class="text-xs text-slate-500 font-ui">Utility bill or bank statement</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Recent utility bill, bank statement, or lease agreement (within 3 months).</p>
                </div>
                
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">Registration Forms</h3>
                            <p class="text-xs text-slate-500 font-ui">Auction house registration</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Completed bidder registration form from the auction house.</p>
                </div>
                
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">Deposit Confirmation</h3>
                            <p class="text-xs text-slate-500 font-ui">Proof of funds available</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Evidence you can pay the required deposit immediately upon winning.</p>
                </div>
                
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="landmark" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">Bank Guarantee</h3>
                            <p class="text-xs text-slate-500 font-ui">For property auctions</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Letter from your bank confirming financing approval for property purchases.</p>
                </div>
                
                <div class="glass-card-static p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="building" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900">Company Documents</h3>
                            <p class="text-xs text-slate-500 font-ui">For business purchases</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">CK registration, tax clearance, and company resolution for entity purchases.</p>
                </div>
            </div>
        </div>
        
        <!-- Section 2: Financial Readiness -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="wallet" class="w-5 h-5 text-primary"></i> Financial Readiness
            </h2>
            
            <div class="glass-card-static p-6">
                <p class="text-sm text-slate-600 font-ui mb-6">Toggle each item as you complete your preparation:</p>
                
                <div class="space-y-4" id="financial-toggles">
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">Deposit funds ready and accessible</span>
                        </div>
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">Buyer premium structure understood</span>
                        </div>
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">VAT implications calculated</span>
                        </div>
                        <i data-lide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">Transport/logistics costs estimated</span>
                        </div>
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">Repair/restoration costs estimated (if applicable)</span>
                        </div>
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="finance-check w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                            <span class="font-ui font-semibold text-slate-700">Total budget including all fees calculated</span>
                        </div>
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500 hidden"></i>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Section 3: Pre-Bid Strategy -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="target" class="w-5 h-5 text-primary"></i> Pre-Bid Strategy Reminders
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="eye" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Inspect Assets</h3>
                    <p class="text-sm text-slate-600 font-ui">Attend all viewings. "Sold as seen" means no returns. Document condition with photos.</p>
                </div>
                
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="file-text" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Read Terms & Conditions</h3>
                    <p class="text-sm text-slate-600 font-ui">Understand payment terms, collection deadlines, and any special conditions before bidding.</p>
                </div>
                
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="clock" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Confirm Deadlines</h3>
                    <p class="text-sm text-slate-600 font-ui">Note deposit due date, full payment deadline, and collection timeframe.</p>
                </div>
                
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="percent" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Understand Premium Structure</h3>
                    <p class="text-sm text-slate-600 font-ui">Know exactly what you'll pay including premium, VAT, and any admin fees.</p>
                </div>
                
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="calculator" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Set Maximum Bid</h3>
                    <p class="text-sm text-slate-600 font-ui">Decide your absolute maximum before attending. Factor in all costs. Stick to it!</p>
                </div>
                
                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-3">
                        <i data-lucide="phone" class="w-5 h-5 text-accent"></i>
                    </div>
                    <h3 class="font-heading font-bold text-slate-900 mb-2">Verify Auctioneer</h3>
                    <p class="text-sm text-slate-600 font-ui">Confirm the auction house is legitimate. Check registration and reviews.</p>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- Link to Scam Awareness -->
<section class="py-8 bg-slate-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="glass-card-static p-6">
            <i data-lucide="shield-alert" class="w-12 h-12 text-warning mx-auto mb-4"></i>
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-2">Protect Yourself from Scams</h2>
            <p class="text-slate-600 font-ui mb-4">Learn to identify red flags and verify auction houses before bidding.</p>
            <a href="scam-awareness.php" class="inline-flex items-center gap-2 px-6 py-3 bg-warning text-slate-900 font-ui font-bold rounded-xl shadow-lg hover:shadow-xl transition-all min-h-[44px]">
                <i data-lucide="shield" class="w-5 h-5"></i>
                View Scam Prevention Guide
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Financial toggle checkboxes
    const financeChecks = document.querySelectorAll('.finance-check');
    financeChecks.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('label');
            const icon = row.querySelector('i[data-lucide="check-circle-2"]');
            if (this.checked) {
                icon.classList.remove('hidden');
                row.classList.add('bg-emerald-50');
            } else {
                icon.classList.add('hidden');
                row.classList.remove('bg-emerald-50');
            }
            // Reinitialize icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
    
    // Fix typo in checkbox HTML
    const brokenCheckbox = document.querySelector('[data-lide]');
    if (brokenCheckbox) {
        brokenCheckbox.setAttribute('data-lucide', 'check-circle-2');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
