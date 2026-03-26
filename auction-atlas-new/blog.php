<?php
/**
 * Auction Atlas - Blog/Insights
 * 
 * Blog index page with static posts.
 */

$pageTitle = 'Insights';

// Static blog posts data
$blogPosts = [
    [
        'slug' => 'first-time-buyer-guide',
        'title' => 'Complete Guide to Your First Auction',
        'excerpt' => 'Everything you need to know before attending your first auction in South Africa, from registration to winning your first bid.',
        'category' => 'Beginner Guide',
        'date' => '2024-01-15',
        'read_time' => '8 min read',
        'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'avoid-common-mistakes',
        'title' => '10 Common Auction Mistakes to Avoid',
        'excerpt' => 'Learn from the experiences of others. These costly errors can be avoided with proper preparation and knowledge.',
        'category' => 'Tips & Strategies',
        'date' => '2024-01-08',
        'read_time' => '6 min read',
        'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'property-auction-vs-private-sale',
        'title' => 'Property Auction vs Private Sale: Which is Better?',
        'excerpt' => 'Comparing the pros and cons of buying property at auction versus through traditional private treaty methods.',
        'category' => 'Property',
        'date' => '2024-01-01',
        'read_time' => '7 min read',
        'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'understanding-buyers-premium',
        'title' => 'Understanding Buyer Premium: What It Really Costs',
        'excerpt' => 'The buyer premium is often misunderstood. We break down exactly what it is and how it affects your final cost.',
        'category' => 'Finance',
        'date' => '2023-12-20',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1554224154-22dec7ec8818?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'online-auction-tips',
        'title' => 'Mastering Online Auctions: Tips for Remote Bidders',
        'excerpt' => 'Online auctions offer convenience but come with unique challenges. Here is how to bid successfully from anywhere.',
        'category' => 'Tips & Strategies',
        'date' => '2023-12-12',
        'read_time' => '6 min read',
        'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'vehicle-auction-guide',
        'title' => 'Buying Vehicles at Auction: A Complete Guide',
        'excerpt' => 'From repossessed cars to fleet disposals, vehicle auctions offer great deals. Learn how to spot a bargain and avoid lemons.',
        'category' => 'Vehicles',
        'date' => '2023-12-05',
        'read_time' => '9 min read',
        'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'legal-rights-bidders',
        'title' => 'Your Legal Rights as a Bidder in South Africa',
        'excerpt' => 'Understanding the Consumer Protection Act and your rights when buying at auction can save you from costly mistakes.',
        'category' => 'Legal',
        'date' => '2023-11-28',
        'read_time' => '8 min read',
        'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'auction-inspection-checklist',
        'title' => 'The Ultimate Auction Inspection Checklist',
        'excerpt' => 'Never attend an inspection unprepared. Use this comprehensive checklist to ensure you evaluate every important factor.',
        'category' => 'Tips & Strategies',
        'date' => '2023-11-20',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&h=400&fit=crop'
    ],
    [
        'slug' => 'estate-auction-guide',
        'title' => 'Buying at Estate Auctions: What You Need to Know',
        'excerpt' => 'Estate auctions can offer quality items at great prices. Learn the unique aspects of buying from deceased estates.',
        'category' => 'Specialist',
        'date' => '2023-11-12',
        'read_time' => '7 min read',
        'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=400&fit=crop'
    ]
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-primary py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mb-3">Auction Insights</h1>
        <p class="text-slate-300 text-lg">Expert guides, tips, and industry analysis</p>
    </div>
</section>

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Featured Categories -->
        <div class="flex flex-wrap gap-2 justify-center mb-8">
            <a href="blog.php" class="px-4 py-2 bg-primary text-white font-ui font-semibold rounded-full text-sm">All Posts</a>
            <a href="blog.php?category=Beginner%20Guide" class="px-4 py-2 bg-white text-slate-700 border border-slate-200 font-ui font-semibold rounded-full text-sm hover:border-primary hover:text-primary transition-colors">Beginner Guide</a>
            <a href="blog.php?category=Tips%20%26%20Strategies" class="px-4 py-2 bg-white text-slate-700 border border-slate-200 font-ui font-semibold rounded-full text-sm hover:border-primary hover:text-primary transition-colors">Tips & Strategies</a>
            <a href="blog.php?category=Property" class="px-4 py-2 bg-white text-slate-700 border border-slate-200 font-ui font-semibold rounded-full text-sm hover:border-primary hover:text-primary transition-colors">Property</a>
            <a href="blog.php?category=Finance" class="px-4 py-2 bg-white text-slate-700 border border-slate-200 font-ui font-semibold rounded-full text-sm hover:border-primary hover:text-primary transition-colors">Finance</a>
            <a href="blog.php?category=Legal" class="px-4 py-2 bg-white text-slate-700 border border-slate-200 font-ui font-semibold rounded-full text-sm hover:border-primary hover:text-primary transition-colors">Legal</a>
        </div>
        
        <!-- Filter by category if set -->
        <?php
        $categoryFilter = isset($_GET['category']) ? $_GET['category'] : null;
        if ($categoryFilter) {
            $blogPosts = array_filter($blogPosts, function($post) use ($categoryFilter) {
                return $post['category'] === $categoryFilter;
            });
        }
        ?>
        
        <!-- Blog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($blogPosts as $post): ?>
            <article class="glass-card-static overflow-hidden group hover:shadow-xl transition-shadow">
                <a href="blog-post.php?slug=<?php echo $post['slug']; ?>">
                    <div class="relative h-48 overflow-hidden">
                        <img 
                            src="<?php echo $post['image']; ?>" 
                            alt="<?php echo htmlspecialchars($post['title']); ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 bg-primary/90 text-white text-xs font-ui font-semibold rounded">
                                <?php echo $post['category']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 text-xs text-slate-500 font-ui mb-2">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <?php echo date('M j, Y', strtotime($post['date'])); ?>
                            <span class="mx-1">|</span>
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <?php echo $post['read_time']; ?>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-slate-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                            <?php echo $post['title']; ?>
                        </h2>
                        <p class="text-sm text-slate-600 font-ui line-clamp-2">
                            <?php echo $post['excerpt']; ?>
                        </p>
                        <div class="mt-4 pt-4 border-t border-slate-200 flex items-center text-primary font-ui font-semibold text-sm">
                            Read Article
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Empty state if no posts match filter -->
        <?php if (empty($blogPosts)): ?>
        <div class="text-center py-12">
            <i data-lucide="file-text" class="w-16 h-16 text-slate-300 mx-auto mb-4"></i>
            <h3 class="font-heading font-bold text-xl text-slate-900 mb-2">No posts found</h3>
            <p class="text-slate-600 font-ui">No articles in this category yet.</p>
            <a href="blog.php" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary text-white font-ui font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                View All Posts
            </a>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- Newsletter CTA -->
<section class="py-10 bg-slate-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="glass-card-static p-8">
            <i data-lucide="mail" class="w-12 h-12 text-primary mx-auto mb-4"></i>
            <h2 class="font-heading font-bold text-2xl text-slate-900 mb-3">Stay Updated</h2>
            <p class="text-slate-600 font-ui mb-6 max-w-md mx-auto">Get the latest auction insights, tips, and market updates delivered to your inbox.</p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                <input 
                    type="email" 
                    placeholder="Enter your email" 
                    required
                    class="flex-1 px-4 py-3 border border-slate-300 rounded-xl font-ui focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary min-h-[44px]"
                >
                <button type="submit" class="px-6 py-3 bg-primary text-white font-ui font-bold rounded-xl shadow-lg hover:bg-primary/90 transition-all min-h-[44px] whitespace-nowrap">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
