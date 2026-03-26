<?php
/**
 * Auction Atlas - Blog Post
 * 
 * Individual blog post page.
 */

$pageTitle = 'Blog Post';

// Get slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Static blog posts data (same as blog.php)
$blogPosts = [
    'first-time-buyer-guide' => [
        'title' => 'Complete Guide to Your First Auction',
        'category' => 'Beginner Guide',
        'date' => '2024-01-15',
        'read_time' => '8 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Attending your first auction can be an intimidating experience. With the right preparation, however, it can also be one of the most rewarding ways to purchase property, vehicles, and other assets in South Africa.</p>
            
            <h2>Understanding the Auction Process</h2>
            <p>Before attending an auction, it is essential to understand how the process works. Unlike traditional property purchases where you negotiate directly with a seller, auctions create a competitive environment where multiple buyers bid against each other in real-time.</p>
            
            <p>The auctioneer sets the starting bid and then calls for higher bids. When no one is willing to bid higher, the "hammer falls" and the highest bidder wins the right to purchase the property or item.</p>
            
            <h2>Pre-Auction Preparation</h2>
            <ul>
                <li><strong>Research the property or item:</strong> Attend viewings, review condition reports, and research market values.</li>
                <li><strong>Understand the costs:</strong> Budget for the buyer premium, VAT, deposit, and any additional fees.</li>
                <li><strong>Arrange financing:</strong> Have your bond approval or funds ready before the auction day.</li>
                <li><strong>Register to bid:</strong> Complete the auction house registration forms and provide required FICA documents.</li>
            </ul>
            
            <h2>Setting Your Maximum Bid</h2>
            <p>One of the most critical aspects of successful auction bidding is knowing your maximum limit before the auction begins. This limit should account for:</p>
            <ul>
                <li>The hammer price you are willing to pay</li>
                <li>Buyer premium (typically 10-15%)</li>
                <li>VAT where applicable</li>
                <li>Transfer costs and legal fees</li>
                <li>Any additional fees or charges</li>
            </ul>
            
            <p>Once you have set your maximum, stick to it. The excitement of bidding can lead to paying more than you intended.</p>
            
            <h2>On Auction Day</h2>
            <p>Arrive early to familiarise yourself with the venue and observe the bidding dynamics. Sit near the front where you can see the auctioneer clearly, and have your bidding number ready.</p>
            
            <p>When bidding, make your intentions clear by raising your hand or bidding paddle firmly. Do not gesture ambiguously, as you may accidentally place a bid.</p>
            
            <h2>After Winning</h2>
            <p>If you are the successful bidder, you will typically need to:</p>
            <ul>
                <li>Sign the sale agreement immediately</li>
                <li>Pay the deposit (usually 10% or a fixed amount)</li>
                <li>Complete any required paperwork</li>
                <li>Arrange for full payment within the specified timeframe</li>
            </ul>
            
            <h2>Conclusion</h2>
            <p>With proper preparation and a clear understanding of the process, your first auction can be a successful and rewarding experience. Remember to research thoroughly, set realistic limits, and proceed with confidence.</p>
        '
    ],
    'avoid-common-mistakes' => [
        'title' => '10 Common Auction Mistakes to Avoid',
        'category' => 'Tips & Strategies',
        'date' => '2024-01-08',
        'read_time' => '6 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Buying at auction can yield excellent deals, but it also carries risks. Here are the most common mistakes that buyers make and how to avoid them.</p>
            
            <h2>1. Not Researching Enough</h2>
            <p>One of the biggest mistakes is bidding on something without adequate research. Always investigate the property or item thoroughly, attend viewings, and understand its true market value.</p>
            
            <h2>2. Ignoring Hidden Costs</h2>
            <p>Buyers often focus only on the hammer price and forget about the buyer premium, VAT, transfer fees, and other charges. Always calculate the total cost before bidding.</p>
            
            <h2>3. Setting No Maximum</h2>
            <p>Emotional bidding can lead to paying far more than intended. Always set a firm maximum before the auction and stick to it, no matter how tempting the competition.</p>
            
            <h2>4. Skipping the Inspection</h2>
            <p>Auctions sell items "as seen," meaning no returns for defects you could have discovered during inspection. Never skip the viewing, no matter how convenient it might be.</p>
            
            <h2>5. Not Understanding Terms & Conditions</h2>
            <p>Each auction house has its own rules. Not reading the T&Cs can lead to unexpected penalties or losing your deposit.</p>
            
            <h2>6. Underestimating Transfer Time</h2>
            <p>Property transfers take time. Do not plan to move in immediately; factor in the transfer process timeline.</p>
            
            <h2>7. Bidding on Multiple Items</h2>
            <p>Winning multiple auctions can leave you unable to pay for all of them. Focus on one item at a time.</p>
            
            <h2>8. Not Bringing Required Documents</h2>
            <p>You will need ID, proof of address, and proof of funds to register. Not having these means you cannot bid.</p>
            
            <h2>9. Falling for Pressure Tactics</h2>
            <p>Auctioneers are skilled at creating urgency. Do not let pressure make you bid beyond your means.</p>
            
            <h2>10. Not Verifying the Auction House</h2>
            <p>Unfortunately, not all auction houses are legitimate. Verify the company, check reviews, and ensure they are properly registered.</p>
        '
    ],
    'property-auction-vs-private-sale' => [
        'title' => 'Property Auction vs Private Sale: Which is Better?',
        'category' => 'Property',
        'date' => '2024-01-01',
        'read_time' => '7 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">When buying property in South Africa, you have two main options: auction or private sale. Each has distinct advantages and disadvantages.</p>
            
            <h2>Advantages of Buying at Auction</h2>
            <ul>
                <li><strong>Potential for discounts:</strong> Auctions can yield properties 20-40% below market value.</li>
                <li><strong>Transparency:</strong> You see exactly what others are willing to pay.</li>
                <li><strong>Speed:</strong> The process is typically faster than private treaty sales.</li>
                <li><strong>Motivated sellers:</strong> Many auction properties come from motivated sellers (foreclosures, estates).</li>
            </ul>
            
            <h2>Disadvantages of Buying at Auction</h2>
            <ul>
                <li><strong>No cooling-off period:</strong> Once you win, the sale is binding.</li>
                <li><strong>As-is condition:</strong> Limited ability to negotiate repairs or conditions.</li>
                <li><strong>Competition:</strong> May drive price up in a hot market.</li>
                <li><strong>Financing challenges:</strong> Must have finance approved before bidding.</li>
            </ul>
            
            <h2>Advantages of Private Sale</h2>
            <ul>
                <li><strong>Negotiation flexibility:</strong> More room to negotiate price and conditions.</li>
                <li><strong>Due diligence time:</strong> More time for inspections and negotiations.</li>
                <li><strong>Financing security:</strong> Can make the sale subject to bond approval.</li>
            </ul>
            
            <h2>Disadvantages of Private Sale</h2>
            <ul>
                <li><strong>Higher prices:</strong> Typically costs more than auction properties.</li>
                <li><strong>Longer process:</strong> Can take months to conclude.</li>
                <li><strong>Less transparency:</strong> You do not know if you are getting the best price.</li>
            </ul>
            
            <h2>Which is Right for You?</h2>
            <p>Choose auction if you are an experienced buyer, have financing in place, and are comfortable with the as-is condition. Choose private sale if you need more flexibility, time for inspections, or are a first-time buyer.</p>
        '
    ],
    'understanding-buyers-premium' => [
        'title' => 'Understanding Buyer Premium: What It Really Costs',
        'category' => 'Finance',
        'date' => '2023-12-20',
        'read_time' => '5 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1554224154-22dec7ec8818?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">The buyer premium is one of the most misunderstood aspects of auction purchases. Understanding it is crucial for accurate budgeting.</p>
            
            <h2>What is the Buyer Premium?</h2>
            <p>The buyer premium is a percentage added to the hammer price, charged by the auction house for their services. It is the primary way auctioneers generate revenue.</p>
            
            <h2>Typical Premium Rates</h2>
            <p>In South Africa, buyer premiums typically range from 10% to 15%, though some may charge more or less:</p>
            <ul>
                <li><strong>Standard rate:</strong> 10-12%</li>
                <li><strong>Premium properties:</strong> 12-15%</li>
                <li><strong>Specialist auctions:</strong> May vary</li>
            </ul>
            
            <h2>Calculating the Total Cost</h2>
            <p>Here is an example of how to calculate your total cost:</p>
            <ul>
                <li>Hammer price: R1,000,000</li>
                <li>Buyer premium (10%): R100,000</li>
                <li>VAT on premium (15%): R15,000</li>
                <li><strong>Total: R1,115,000</strong></li>
            </ul>
            
            <h2>Tips for Managing Premium Costs</h2>
            <ul>
                <li>Factor the premium into your maximum bid</li>
                <li>Look for auctions with lower premium rates</li>
                <li>Ask if there are discounts for certain payment methods</li>
                <li>Verify if VAT is included in the quoted premium</li>
            </ul>
        '
    ],
    'online-auction-tips' => [
        'title' => 'Mastering Online Auctions: Tips for Remote Bidders',
        'category' => 'Tips & Strategies',
        'date' => '2023-12-12',
        'read_time' => '6 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Online auctions have grown significantly, especially after 2020. Here is how to navigate digital bidding successfully.</p>
            
            <h2>Preparing for Online Auctions</h2>
            <ul>
                <li><strong>Test your technology:</strong> Ensure stable internet and a charged device.</li>
                <li><strong>Create accounts in advance:</strong> Register with the platform before auction day.</li>
                <li><strong>Verify payment methods:</strong> Have your payment options ready.</li>
                <li><strong>Understand the platform:</strong> Practice with any demo or test auctions.</li>
            </ul>
            
            <h2>Bidding Strategies</h2>
            <p>Online bidding differs from live auctions. You cannot read the room, so rely on:</p>
            <ul>
                <li>Proxy bidding features</li>
                <li>Maximum bid settings</li>
                <li>Monitoring competing bids in real-time</li>
            </ul>
            
            <h2>Avoiding Technical Issues</h2>
            <ul>
                <li>Do not bid using mobile data in areas with poor coverage</li>
                <li>Keep the auction page open in only one tab</li>
                <li>Have a backup device ready</li>
                <li>Do not wait until the last second to bid</li>
            </ul>
        '
    ],
    'vehicle-auction-guide' => [
        'title' => 'Buying Vehicles at Auction: A Complete Guide',
        'category' => 'Vehicles',
        'date' => '2023-12-05',
        'read_time' => '9 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Vehicle auctions offer excellent opportunities to buy cars, trucks, and equipment at competitive prices. Here is what you need to know.</p>
            
            <h2>Types of Vehicle Auctions</h2>
            <ul>
                <li><strong>Repossession auctions:</strong> Banks sell defaulted vehicles</li>
                <li><strong>Fleet disposals:</strong> Companies sell used fleet vehicles</li>
                <li><strong>Dealer auctions:</strong> Wholesale to dealers only</li>
                <li><strong>Public salvage auctions:</strong> Damaged vehicles</li>
            </ul>
            
            <h2>What to Look For</h2>
            <ul>
                <li>Service history and records</li>
                <li>Condition of tyres, brakes, and engine</li>
                <li>Accident history</li>
                <li>Mileage verification</li>
                <li>Outstanding finance (do a PIC check)</li>
            </ul>
            
            <h2>Budgeting for Vehicles</h2>
            <p>Remember to include:</p>
            <ul>
                <li>Buyer premium</li>
                <li>Transport/delivery costs</li>
                <li>Potential repairs</li>
                <li>Registration and licensing fees</li>
            </ul>
        '
    ],
    'legal-rights-bidders' => [
        'title' => 'Your Legal Rights as a Bidder in South Africa',
        'category' => 'Legal',
        'date' => '2023-11-28',
        'read_time' => '8 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Understanding your legal rights is essential when participating in auctions. South African law provides specific protections for auction buyers.</p>
            
            <h2>Key Legislation</h2>
            <ul>
                <li><strong>Consumer Protection Act (CPA):</strong> Provides cooling-off rights for certain auctions</li>
                <li><strong>Financial Intelligence Centre Act (FICA):</strong> Governs identification requirements</li>
                <li><strong>POPIA:</strong> Protects your personal information</li>
            </ul>
            
            <h2>Your Rights</h2>
            <ul>
                <li>Right to fair and honest dealing</li>
                <li>Right to accurate information about goods</li>
                <li>Right to cooling-off for certain auctions</li>
                <li>Right to privacy of personal information</li>
            </ul>
            
            <h2>Important Obligations</h2>
            <ul>
                <li>Winning bids are legally binding</li>
                <li>Deposits are generally non-refundable</li>
                <li>Payment must be made within specified terms</li>
            </ul>
        '
    ],
    'auction-inspection-checklist' => [
        'title' => 'The Ultimate Auction Inspection Checklist',
        'category' => 'Tips & Strategies',
        'date' => '2023-11-20',
        'read_time' => '5 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Never attend an auction inspection unprepared. Use this comprehensive checklist to ensure you evaluate all important factors.</p>
            
            <h2>Before the Inspection</h2>
            <ul>
                <li>Review auction terms and conditions</li>
                <li>Research market values</li>
                <li>Prepare questions for the auctioneer</li>
                <li>Bring a flashlight and measuring tape</li>
            </ul>
            
            <h2>During the Inspection</h2>
            <ul>
                <li>Check structural integrity</li>
                <li>Inspect all fixtures and fittings</li>
                <li>Note any damage or wear</li>
                <li>Take photographs and videos</li>
                <li>Ask about hidden defects</li>
            </ul>
            
            <h2>Questions to Ask</h2>
            <ul>
                <li>Why is the property being sold?</li>
                <li>What is included in the sale?</li>
                <li>Are there any liens or encumbrances?</li>
                <li>What are the utility costs?</li>
            </ul>
        '
    ],
    'estate-auction-guide' => [
        'title' => 'Buying at Estate Auctions: What You Need to Know',
        'category' => 'Specialist',
        'date' => '2023-11-12',
        'read_time' => '7 min read',
        'author' => 'Auction Atlas Team',
        'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1200&h=600&fit=crop',
        'content' => '
            <p class="lead">Estate auctions offer unique opportunities to purchase quality items. Here is what makes them different from other auctions.</p>
            
            <h2>What is an Estate Auction?</h2>
            <p>Estate auctions sell the contents of deceased estates, divorce settlements, or business liquidations. Items often include furniture, artwork, jewellery, and collectibles.</p>
            
            <h2>Advantages</h2>
            <ul>
                <li>Quality items at reasonable prices</li>
                <li>Transparent bidding process</li>
                <li>One-stop shopping for multiple items</li>
                <li>Often professionally curated</li>
            </ul>
            
            <h2>Special Considerations</h2>
            <ul>
                <li>Items are sold as-is</li>
                <li>Limited time for inspection</li>
                <li>May need to arrange removal immediately</li>
                <li>Payment typically required immediately</li>
            </ul>
        '
    ]
];

// Get post or redirect to blog
$post = isset($blogPosts[$slug]) ? $blogPosts[$slug] : null;

if (!$post) {
    header('Location: blog.php');
    exit;
}

$pageTitle = $post['title'];
require_once __DIR__ . '/includes/header.php';
?>

<!-- Blog Post Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex items-center justify-center gap-2 mb-4">
            <a href="blog.php" class="text-slate-300 hover:text-white font-ui text-sm flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Insights
            </a>
        </div>
        <span class="inline-block px-3 py-1 bg-white/20 text-white text-sm font-ui font-semibold rounded-full mb-4">
            <?php echo $post['category']; ?>
        </span>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-white mb-4">
            <?php echo $post['title']; ?>
        </h1>
        <div class="flex items-center justify-center gap-4 text-slate-300 font-ui text-sm">
            <span class="flex items-center gap-1">
                <i data-lucide="user" class="w-4 h-4"></i>
                <?php echo $post['author']; ?>
            </span>
            <span class="flex items-center gap-1">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <?php echo date('F j, Y', strtotime($post['date'])); ?>
            </span>
            <span class="flex items-center gap-1">
                <i data-lucide="clock" class="w-4 h-4"></i>
                <?php echo $post['read_time']; ?>
            </span>
        </div>
    </div>
</section>

<!-- Featured Image -->
<?php if (!empty($post['image'])): ?>
<div class="max-w-5xl mx-auto px-4 -mt-8 relative z-10">
    <img 
        src="<?php echo $post['image']; ?>" 
        alt="<?php echo htmlspecialchars($post['title']); ?>"
        class="w-full h-64 sm:h-80 lg:h-96 object-cover rounded-2xl shadow-xl"
    >
</div>
<?php endif; ?>

<!-- Article Content -->
<article class="py-8 lg:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card-static p-6 sm:p-10">
            <div class="prose prose-slate prose-lg max-w-none">
                <?php echo $post['content']; ?>
            </div>
            
            <!-- Share Section -->
            <div class="mt-10 pt-6 border-t border-slate-200">
                <p class="font-ui font-semibold text-slate-900 mb-3">Share this article:</p>
                <div class="flex gap-3">
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Back to Blog Link -->
        <div class="mt-8 text-center">
            <a href="blog.php" class="inline-flex items-center gap-2 text-primary font-ui font-semibold hover:underline">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                View All Articles
            </a>
        </div>
    </div>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
