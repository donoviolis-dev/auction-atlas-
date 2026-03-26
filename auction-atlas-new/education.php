<?php
/**
 * Auction Atlas - Education Centre
 * 
 * Educational content about auctions with accordion sections.
 */

$pageTitle = 'Education';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Auction Education Centre</h1>
        <p class="text-slate-300 text-lg">Understand the rules, risks and realities before you bid</p>
        
        <div class="mt-6">
            <a href="prep-check.php" class="inline-flex items-center gap-2 px-6 py-3 bg-highlight text-slate-900 font-ui font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all min-h-[44px]">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                Go to Prep Checklist
            </a>
        </div>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Accordion Sections -->
        <div class="space-y-4" id="education-accordion">
            
            <!-- Section 1: What is an Auction -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-1">
                    <span class="font-heading font-bold text-lg text-slate-900">What is an Auction?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-1" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 font-ui leading-relaxed mb-4">
                            An auction is a public sale where property or goods are sold to the highest bidder. In South Africa, auctions are governed by the Consumer Protection Act (CPA) and the Auctioneers Act, providing legal protections for buyers.
                        </p>
                        <p class="text-slate-600 font-ui leading-relaxed mb-4">
                            Unlike traditional retail purchases, auction purchases are final and binding. This creates both opportunities (often buying below market value) and risks that must be understood before participating.
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-slate-600 font-ui">
                            <li>Competitive bidding environment</li>
                            <li>Transparent pricing through open competition</li>
                            <li>Legal framework protecting both buyers and sellers</li>
                            <li>Final and binding contracts upon winning</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Section 2: Types of Auctions -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-2">
                    <span class="font-heading font-bold text-lg text-slate-900">Types of Auctions in South Africa</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-2" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Property Auctions</h4>
                                <p class="text-sm text-slate-600 font-ui">Includes residential, commercial, and industrial properties. Often motivated seller situations or bank repossessions.</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Vehicle Auctions</h4>
                                <p class="text-sm text-slate-600 font-ui">Fleet disposals, repossessed vehicles, and dealer trade-ins. Includes salvage and repairable vehicles.</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Industrial & Machinery</h4>
                                <p class="text-sm text-slate-600 font-ui">Factory equipment, construction machinery, agricultural equipment. Often sold as complete lots.</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Fine Art & Collectibles</h4>
                                <p class="text-sm text-slate-600 font-ui">Specialist auctions for art, jewellery, wine, and rare items. May include authentication services.</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Estate Auctions</h4>
                                <p class="text-sm text-slate-600 font-ui">Contents of deceased estates, divorce settlements, or business liquidations. Mixed inventory.</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-heading font-bold text-primary mb-2">Online Auctions</h4>
                                <p class="text-sm text-slate-600 font-ui">Internet-based bidding platforms allowing remote participation. Timed or live formats available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Important Terms -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-3">
                    <span class="font-heading font-bold text-lg text-slate-900">Important Auction Terms</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-3" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <dl class="space-y-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">Hammer Price</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">The final bid price at which the auctioneer drops the hammer. This is the winning bid amount before additional fees.</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">Buyer's Premium</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">A percentage added to the hammer price, payable by the buyer. Typically 10-15% in South Africa. This is on top of the hammer price.</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">Deposit</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">A cash amount required immediately upon winning (usually 10% of purchase price or a fixed amount like R5,000-R10,000).</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">Reserve Price</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">The minimum price the seller will accept. If not met, the property may not be sold. Not always disclosed.</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">VOA (Value Added)</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">Vendor's Option to Add - the seller's right to add VAT to the hammer price if they are VAT registered.</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="font-heading font-bold text-primary">FICA</dt>
                                <dd class="text-sm text-slate-600 font-ui mt-1">Financial Intelligence Centre Act - legislation requiring buyer identification and verification.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
            
            <!-- Section 4: How Bidding Works -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-4">
                    <span class="font-heading font-bold text-lg text-slate-900">How Bidding Works</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-4" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <ol class="list-decimal list-inside space-y-3 text-slate-600 font-ui">
                            <li><strong>Registration:</strong> Provide ID, proof of address, and FICA documents. Receive a bidding number.</li>
                            <li><strong>Preview/Inspection:</strong> Attend viewings to examine assets. "Sold as seen" applies - no returns.</li>
                            <li><strong>Bidding:</strong> Bid by raising your hand, paddle, or clicking online. Each bid must exceed the previous.</li>
                            <li><strong>Hammer Falls:</strong> When no higher bid is received, the auctioneer sells to the highest bidder.</li>
                            <li><strong>Payment:</strong> Pay the deposit immediately (cash, EFT, or bank guarantee). Full payment within terms.</li>
                            <li><strong>Transfer:</strong> Complete the transfer process. For property, this involves attorneys.</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <!-- Section 5: Legal & Compliance -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-5">
                    <span class="font-heading font-bold text-lg text-slate-900">Legal & Compliance Overview</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-5" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 font-ui leading-relaxed mb-4">
                            South African auctions are regulated by several laws ensuring fair practice:
                        </p>
                        <ul class="space-y-3 text-slate-600 font-ui">
                            <li class="flex items-start gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-accent mt-0.5 flex-shrink-0"></i>
                                <span><strong>Consumer Protection Act (CPA):</strong> Provides cooling-off rights for certain auctions, requires fair pricing disclosure.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-accent mt-0.5 flex-shrink-0"></i>
                                <span><strong>Financial Intelligence Centre Act (FICA):</strong> Requires auction houses to verify buyer identity and report suspicious transactions.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-accent mt-0.5 flex-shrink-0"></i>
                                <span><strong>POPIA (Protection of Personal Information):</strong> Governs how your data is stored and used by auctioneers.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-accent mt-0.5 flex-shrink-0"></i>
                                <span><strong>Estate Agent Affairs Act:</strong> Regulates estate auctioneers and their conduct.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Section 6: Fees & Hidden Costs -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-6">
                    <span class="font-heading font-bold text-lg text-slate-900">Fees & Hidden Costs</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-6" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 font-ui leading-relaxed mb-4">
                            Beyond the hammer price, budget for these additional costs:
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                            <h4 class="font-heading font-bold text-red-700 mb-2">Common Additional Costs</h4>
                            <ul class="space-y-2 text-sm text-red-800 font-ui">
                                <li>- Buyer's Premium: 10-15% of hammer price</li>
                                <li>- VAT on Buyer's Premium: 15% (if applicable)</li>
                                <li>- Deposit: 10% of purchase price or fixed amount</li>
                                <li>- Admin/Documentation fees: Variable</li>
                                <li>- Storage fees: If not collected in time</li>
                                <li>- Transport/Logistics: For bulky items</li>
                            </ul>
                        </div>
                        <p class="text-slate-600 font-ui leading-relaxed">
                            Always request a full cost breakdown from the auction house before bidding. "Hidden fees" that appear only after winning are a red flag.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Section 7: Online vs Physical -->
            <div class="glass-card-static overflow-hidden">
                <button class="accordion-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4" data-accordion="section-7">
                    <span class="font-heading font-bold text-lg text-slate-900">Online vs Physical Auctions</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"></i>
                </button>
                <div id="section-7" class="accordion-content px-6 pb-6 hidden">
                    <div class="prose prose-slate max-w-none">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-heading font-bold text-primary mb-3">Online Auctions</h4>
                                <ul class="space-y-2 text-slate-600 font-ui text-sm">
                                    <li class="flex items-start gap-2"><i data-lucide="globe" class="w-4 h-4 text-accent mt-1"></i> Bid from anywhere</li>
                                    <li class="flex items-start gap-2"><i data-lucide="clock" class="w-4 h-4 text-accent mt-1"></i> Timed or live formats</li>
                                    <li class="flex items-start gap-2"><i data-lucide="search" class="w-4 h-4 text-accent mt-1"></i> Virtual inspections only</li>
                                    <li class="flex items-start gap-2"><i data-lucide="monitor" class="w-4 h-4 text-accent mt-1"></i> Digital payment required</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-heading font-bold text-primary mb-3">Physical Auctions</h4>
                                <ul class="space-y-2 text-slate-600 font-ui text-sm">
                                    <li class="flex items-start gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-accent mt-1"></i> Attend in person</li>
                                    <li class="flex items-start gap-2"><i data-lucide="eye" class="w-4 h-4 text-accent mt-1"></i> Physical inspection</li>
                                    <li class="flex items-start gap-2"><i data-lucide="users" class="w-4 h-4 text-accent mt-1"></i> Read the room</li>
                                    <li class="flex items-start gap-2"><i data-lucide="file-text" class="w-4 h-4 text-accent mt-1"></i> Immediate paperwork</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-10 bg-slate-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-heading font-bold text-2xl text-slate-900 mb-4">Ready to Start Bidding?</h2>
        <p class="text-slate-600 mb-6 font-ui">Use our tools to find the right auction house and understand your costs before you bid.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="directory.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-ui font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all min-h-[44px]">
                <i data-lucide="compass" class="w-5 h-5"></i>
                Browse Directory
            </a>
            <a href="fee-calculator.php" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary border border-primary/20 font-ui font-semibold rounded-xl hover:bg-slate-50 transition-all min-h-[44px]">
                <i data-lucide="calculator" class="w-5 h-5"></i>
                Calculate Fees
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion toggle
    const toggles = document.querySelectorAll('.accordion-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-accordion');
            const content = document.getElementById(targetId);
            const icon = this.querySelector('i[data-lucide="chevron-down"]');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
