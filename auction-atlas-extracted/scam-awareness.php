<?php
/**
 * Auction Atlas - Scam Awareness
 * 
 * Scam prevention and red flag detector.
 */

$pageTitle = 'Scam Awareness';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-red-900 to-warning py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Scam Awareness Centre</h1>
        <p class="text-red-100 text-lg">Protect yourself from auction fraud</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Red Flag Detector -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-warning"></i> Red Flag Detector
            </h2>
            
            <div class="glass-card-static p-6">
                <p class="text-sm text-slate-600 font-ui mb-6">Check all that apply to the auction house you're considering:</p>
                
                <div class="space-y-3" id="red-flag-checklist">
                    <!-- High Risk Items -->
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">No physical address or verifiable location</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Requires payment only to personal bank account</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">No terms & conditions or refund policy</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Pressures you to pay immediately without viewing</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Prices too good to be true</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="high">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Website has poor grammar or looks unprofessional</span>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">HIGH RISK</span>
                        </label>
                    </div>
                    
                    <!-- Medium Risk Items -->
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="medium">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">No online presence or social media</span>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">MEDIUM RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="medium">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Limited or no reviews available</span>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">MEDIUM RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="medium">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Hidden fees not disclosed upfront</span>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">MEDIUM RISK</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="medium">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Cannot verify company registration</span>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">MEDIUM RISK</span>
                        </label>
                    </div>
                    
                    <!-- Lower Risk Items -->
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="low">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">Limited payment options</span>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">VERIFY</span>
                        </label>
                    </div>
                    
                    <div class="risk-item p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors" data-risk="low">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="risk-check w-5 h-5 rounded border-slate-300 text-warning focus:ring-warning/20">
                            <div class="flex-1">
                                <span class="font-ui font-semibold text-slate-700">New company with limited track record</span>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">VERIFY</span>
                        </label>
                    </div>
                </div>
                
                <!-- Risk Level Display -->
                <div id="risk-result" class="mt-6 p-4 rounded-xl hidden">
                    <div class="flex items-center justify-center gap-3">
                        <span id="risk-icon" class="w-12 h-12 rounded-full flex items-center justify-center"></span>
                        <div>
                            <p id="risk-level" class="font-heading font-bold text-lg"></p>
                            <p id="risk-message" class="text-sm font-ui"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Common Scam Types -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="fingerprint" class="w-5 h-5 text-primary"></i> Common Scam Types
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="glass-card-static p-5 border-l-4 border-red-500">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="ghost" class="w-5 h-5 text-red-500"></i>
                        <h3 class="font-heading font-bold text-slate-900">Phantom Auctions</h3>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Fake auctions for items that don't exist. Scammers create convincing listings and collect deposits from multiple victims.</p>
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <p class="text-xs font-ui text-slate-500"><strong>Warning signs:</strong> No physical viewings, pressure to pay quickly, generic photos.</p>
                    </div>
                </div>
                
                <div class="glass-card-static p-5 border-l-4 border-red-500">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="copy" class="w-5 h-5 text-red-500"></i>
                        <h3 class="font-heading font-bold text-slate-900">Duplicate Listings</h3>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Same item listed on multiple genuine platforms at different prices by a scammer.</p>
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <p class="text-xs font-ui text-slate-500"><strong>Warning signs:</strong> Same photo appears elsewhere, price varies between sites.</p>
                    </div>
                </div>
                
                <div class="glass-card-static p-5 border-l-4 border-red-500">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="piggy-bank" class="w-5 h-5 text-red-500"></i>
                        <h3 class="font-heading font-bold text-slate-900">Deposit Theft</h3>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Legitimate-looking auction that keeps deposits but never delivers goods or transfers property.</p>
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <p class="text-xs font-ui text-slate-500"><strong>Warning signs:</strong> Delayed transfers, excuses, no communication after deposit.</p>
                    </div>
                </div>
                
                <div class="glass-card-static p-5 border-l-4 border-red-500">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="trending-up" class="w-5 h-5 text-red-500"></i>
                        <h3 class="font-heading font-bold text-slate-900">Fake Bidding</h3>
                    </div>
                    <p class="text-sm text-slate-600 font-ui">Scammers use bot accounts to artificially inflate prices or use shill bidding to drive up costs.</p>
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <p class="text-xs font-ui text-slate-500"><strong>Warning signs:</strong> Last-minute bidding wars, suspicious bidder names.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Verification Steps -->
        <div class="mb-10">
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-primary"></i> Verification Steps
            </h2>
            
            <div class="glass-card-static p-6">
                <ol class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">1</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Verify Company Registration</h4>
                            <p class="text-sm text-slate-600 font-ui">Check CIPC (Companies and Intellectual Property Commission) for company registration details.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">2</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Search Reviews and Complaints</h4>
                            <p class="text-sm text-slate-600 font-ui">Google the company name with "review", "scam", or "complaint". Check Trustpilot and Hellopeter.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">3</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Call and Verify</h4>
                            <p class="text-sm text-slate-600 font-ui">Call the provided phone numbers. Verify physical address exists. Visit in person if possible.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">4</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Check Domain Age</h4>
                            <p class="text-sm text-slate-600 font-ui">Use WHOIS to check domain registration age. New domains (< 1 year) are higher risk.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">5</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Request References</h4>
                            <p class="text-sm text-slate-600 font-ui">Ask for recent buyer references. Contact them directly to verify their experience.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold font-ui">6</div>
                        <div>
                            <h4 class="font-heading font-bold text-slate-900">Use Escrow for Large Purchases</h4>
                            <p class="text-sm text-slate-600 font-ui">For high-value items, use an escrow service that holds funds until delivery is verified.</p>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
        
    </div>
</section>

<!-- Report Scam CTA -->
<section class="py-8 bg-red-50">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="glass-card-static p-6">
            <i data-lucide="phone-call" class="w-12 h-12 text-red-500 mx-auto mb-4"></i>
            <h2 class="font-heading font-bold text-xl text-slate-900 mb-2">Been Scammed?</h2>
            <p class="text-slate-600 font-ui mb-4">Report fraud to the relevant authorities immediately.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://www.saps.gov.za" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-ui font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all min-h-[44px]">
                    <i data-lucide="external-link" class="w-5 h-5"></i>
                    SAPS Website
                </a>
                <a href="https://www.fica.co.za" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border border-slate-300 font-ui font-bold rounded-xl hover:bg-slate-50 transition-all min-h-[44px]">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                    FICA Hotline
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Red flag checklist functionality
    const riskChecks = document.querySelectorAll('.risk-check');
    const riskResult = document.getElementById('risk-result');
    const riskLevel = document.getElementById('risk-level');
    const riskMessage = document.getElementById('risk-message');
    const riskIcon = document.getElementById('risk-icon');
    
    riskChecks.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let highCount = 0;
            let mediumCount = 0;
            
            riskChecks.forEach(cb => {
                if (cb.checked) {
                    const item = cb.closest('.risk-item');
                    const risk = item.getAttribute('data-risk');
                    if (risk === 'high') highCount++;
                    if (risk === 'medium') mediumCount++;
                }
            });
            
            const totalChecked = highCount + mediumCount;
            
            if (totalChecked === 0) {
                riskResult.classList.add('hidden');
            } else {
                riskResult.classList.remove('hidden');
                
                if (highCount >= 3 || highCount >= 1 && mediumCount >= 2) {
                    riskLevel.textContent = 'HIGH RISK - Do Not Proceed';
                    riskLevel.className = 'font-heading font-bold text-lg text-red-600';
                    riskMessage.textContent = 'This auction house shows multiple high-risk red flags. We strongly recommend avoiding this seller.';
                    riskIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center bg-red-100 text-red-600';
                    riskIcon.innerHTML = '<i data-lucide="x-circle" class="w-6 h-6"></i>';
                } else if (highCount >= 1 || mediumCount >= 2) {
                    riskLevel.textContent = 'MEDIUM RISK - Proceed with Caution';
                    riskLevel.className = 'font-heading font-bold text-lg text-yellow-600';
                    riskMessage.textContent = 'Several red flags detected. Conduct thorough verification before proceeding.';
                    riskIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center bg-yellow-100 text-yellow-600';
                    riskIcon.innerHTML = '<i data-lucide="alert-triangle" class="w-6 h-6"></i>';
                } else {
                    riskLevel.textContent = 'LOW RISK - Likely Safe';
                    riskLevel.className = 'font-heading font-bold text-lg text-green-600';
                    riskMessage.textContent = 'Few or no red flags detected. Still verify normally before committing funds.';
                    riskIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center bg-green-100 text-green-600';
                    riskIcon.innerHTML = '<i data-lucide="check-circle" class="w-6 h-6"></i>';
                }
                
                // Reinitialize icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
