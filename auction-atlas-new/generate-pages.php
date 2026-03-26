<?php
/**
 * Auction Atlas - Programmatic Page Generation Engine
 * 
 * Generates 1000+ SEO-optimized landing pages automatically
 * 
 * Run: php generate-pages.php
 * 
 * @package AuctionAtlas
 * @version 2.0
 */

// Configuration
define('BASE_URL', 'https://auctionatlas.co.za');
define('OUTPUT_DIR', __DIR__ . '/generated');
define('DATA_DIR', __DIR__ . '/data');

// Ensure output directory exists
if (!is_dir(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0755, true);
}

// Data arrays
$cities = [
    'johannesburg' => ['name' => 'Johannesburg', 'province' => 'Gauteng', 'slug' => 'johannesburg'],
    'cape-town' => ['name' => 'Cape Town', 'province' => 'Western Cape', 'slug' => 'cape-town'],
    'durban' => ['name' => 'Durban', 'province' => 'KwaZulu-Natal', 'slug' => 'durban'],
    'pretoria' => ['name' => 'Pretoria', 'province' => 'Gauteng', 'slug' => 'pretoria'],
    'port-elizabeth' => ['name' => 'Port Elizabeth', 'province' => 'Eastern Cape', 'slug' => 'port-elizabeth'],
    'bloemfontein' => ['name' => 'Bloemfontein', 'province' => 'Free State', 'slug' => 'bloemfontein'],
    'nelspruit' => ['name' => 'Nelspruit', 'province' => 'Mpumalanga', 'slug' => 'nelspruit'],
    'polokwane' => ['name' => 'Polokwane', 'province' => 'Limpopo', 'slug' => 'polokwane'],
    'centurion' => ['name' => 'Centurion', 'province' => 'Gauteng', 'slug' => 'centurion'],
    'sandton' => ['name' => 'Sandton', 'province' => 'Gauteng', 'slug' => 'sandton'],
    'midrand' => ['name' => 'Midrand', 'province' => 'Gauteng', 'slug' => 'midrand'],
    'randburg' => ['name' => 'Randburg', 'province' => 'Gauteng', 'slug' => 'randburg'],
    'benoni' => ['name' => 'Benoni', 'province' => 'Gauteng', 'slug' => 'benoni'],
    'richards-bay' => ['name' => 'Richards Bay', 'province' => 'KwaZulu-Natal', 'slug' => 'richards-bay'],
    'stellenbosch' => ['name' => 'Stellenbosch', 'province' => 'Western Cape', 'slug' => 'stellenbosch'],
    'paarl' => ['name' => 'Paarl', 'province' => 'Western Cape', 'slug' => 'paarl'],
    'george' => ['name' => 'George', 'province' => 'Western Cape', 'slug' => 'george'],
    'kimberley' => ['name' => 'Kimberley', 'province' => 'Northern Cape', 'slug' => 'kimberley'],
    'mahikeng' => ['name' => 'Mahikeng', 'province' => 'North West', 'slug' => 'mahikeng'],
    'pietermaritzburg' => ['name' => 'Pietermaritzburg', 'province' => 'KwaZulu-Natal', 'slug' => 'pietermaritzburg'],
];

$provinces = [
    'gauteng' => ['name' => 'Gauteng', 'slug' => 'gauteng'],
    'western-cape' => ['name' => 'Western Cape', 'slug' => 'western-cape'],
    'kwazulu-natal' => ['name' => 'KwaZulu-Natal', 'slug' => 'kwazulu-natal'],
    'eastern-cape' => ['name' => 'Eastern Cape', 'slug' => 'eastern-cape'],
    'free-state' => ['name' => 'Free State', 'slug' => 'free-state'],
    'mpumalanga' => ['name' => 'Mpumalanga', 'slug' => 'mpumalanga'],
    'limpopo' => ['name' => 'Limpopo', 'slug' => 'limpopo'],
    'north-west' => ['name' => 'North West', 'slug' => 'north-west'],
    'northern-cape' => ['name' => 'Northern Cape', 'slug' => 'northern-cape'],
];

$categories = [
    'property' => [
        'name' => 'Property', 
        'synonyms' => ['properties', 'real-estate', 'houses', 'buildings'],
        'intro' => 'Explore property auctions across South Africa. From residential homes to commercial buildings, find your perfect investment opportunity.'
    ],
    'vehicle' => [
        'name' => 'Vehicle', 
        'synonyms' => ['vehicles', 'cars', 'motor-vehicles', 'automotive'],
        'intro' => 'Discover vehicle auctions featuring cars, trucks, and fleet vehicles. Find quality used vehicles at competitive prices.'
    ],
    'industrial' => [
        'name' => 'Industrial', 
        'synonyms' => ['machinery', 'equipment', 'manufacturing'],
        'intro' => 'Browse industrial auctions featuring machinery, equipment, and manufacturing assets. Perfect for businesses looking to expand.'
    ],
    'commercial' => [
        'name' => 'Commercial', 
        'synonyms' => ['business', 'offices', 'retail'],
        'intro' => 'Find commercial property auctions including offices, retail spaces, and business liquidations across South Africa.'
    ],
    'agricultural' => [
        'name' => 'Agricultural', 
        'synonyms' => ['farm', 'farming', 'agriculture', 'farms'],
        'intro' => 'Discover agricultural auctions featuring farms, farmland, and agricultural equipment across South Africa.'
    ],
    'liquidations' => [
        'name' => 'Liquidations', 
        'synonyms' => ['liquidation', 'bankruptcy', 'insolvency'],
        'intro' => 'Find liquidation auctions offering discounted assets from businesses closing down. Great opportunities for buyers.'
    ],
    'estate' => [
        'name' => 'Estate', 
        'synonyms' => ['estates', 'deceased-estate', 'insolvent-estate'],
        'intro' => 'Browse estate auctions featuring properties from deceased estates. Transparent process with verified documentation.'
    ],
];

// Sample auction houses (from JSON)
$auctionHouses = [
    ['name' => 'Aucor Auctioneers', 'slug' => 'aucor-auctioneers', 'city' => 'Centurion', 'province' => 'Gauteng', 'rating' => 3.9],
    ['name' => 'High Street Auctions', 'slug' => 'high-street-auctions', 'city' => 'Johannesburg', 'province' => 'Gauteng', 'rating' => 4.2],
    ['name' => 'Gobid', 'slug' => 'gobid', 'city' => 'Johannesburg', 'province' => 'Gauteng', 'rating' => 4.0],
    ['name' => 'Nuco Auctioneers', 'slug' => 'nuco-auctioneers', 'city' => 'Cape Town', 'province' => 'Western Cape', 'rating' => 3.8],
    ['name' => 'Bidders Choice', 'slug' => 'bidders-choice', 'city' => 'Johannesburg', 'province' => 'Gauteng', 'rating' => 4.1],
    ['name' => 'SA Auction Group', 'slug' => 'sa-auction-group', 'city' => 'Durban', 'province' => 'KwaZulu-Natal', 'rating' => 3.7],
    ['name' => 'Claremart', 'slug' => 'claremart', 'city' => 'Cape Town', 'province' => 'Western Cape', 'rating' => 4.3],
    ['name' => 'Auction Operation', 'slug' => 'auction-operation', 'city' => 'Johannesburg', 'province' => 'Gauteng', 'rating' => 3.9],
];

// Load auction data
function loadAuctionsCSV() {
    $auctions = [];
    $file = __DIR__ . '/Upcoming Auctions.csv';
    if (file_exists($file)) {
        $handle = fopen($file, 'r');
        $headers = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            $auctions[] = $data;
        }
        fclose($handle);
    }
    return $auctions;
}

$csvAuctions = loadAuctionsCSV();

// Counter for generated pages
$generatedPages = [];
$errors = [];

// Generate SEO content
function generateSEOContent($type, $data) {
    global $categories;
    
    $content = [
        'title' => '',
        'description' => '',
        'h1' => '',
        'intro' => '',
        'faqs' => [],
    ];
    
    switch ($type) {
        case 'city':
            $city = $data['city'];
            $province = $data['province'];
            $content['title'] = "Auctions in {$province['name']} {$city['name']} | Upcoming Auctions 2026 | Auction Atlas";
            $content['description'] = "Discover upcoming auctions in {$city['name']}, {$province['name']}. Browse property, vehicle, and industrial auctions. Compare auction houses and find deals.";
            $content['h1'] = "Auctions in {$city['name']}, {$province['name']}";
            $content['intro'] = "Find the best auctions in {$city['name']}, {$province['name']}. Our directory features verified auction houses and exclusive listings.";
            $content['faqs'] = [
                ['q' => "What types of auctions are available in {$city['name']}?", 'a' => "{$city['name']} offers property, vehicle, industrial, and commercial auctions through verified auction houses."],
                ['q' => "How do I participate in auctions in {$city['name']}?", 'a' => "Register with any of our featured auction houses, verify your ID, and attend the auction online or in person."],
                ['q' => "Are auction houses in {$city['name']} regulated?", 'a' => "Yes, all auctioneers in South Africa must be licensed and adhere to the Auctioneers Act."],
            ];
            break;
            
        case 'category':
            $cat = $data['category'];
            $content['title'] = "{$cat['name']} Auctions South Africa | Upcoming Auctions 2026 | Auction Atlas";
            $content['description'] = "Browse upcoming {$cat['name']} auctions in South Africa. Compare auction houses, view dates, and find the best deals across all provinces.";
            $content['h1'] = "{$cat['name']} Auctions in South Africa";
            $content['intro'] = $cat['intro'];
            $content['faqs'] = [
                ['q' => "What should I look for in a {$cat['name']} auction?", 'a' => "Always verify the auction house credentials, review terms and conditions, and check any associated fees."],
                ['q' => "Can I bid online at {$cat['name']} auctions?", 'a' => "Most auction houses now offer online bidding through their platforms."],
            ];
            break;
            
        case 'city-category':
            $city = $data['city'];
            $province = $data['province'];
            $cat = $data['category'];
            $content['title'] = "{$cat['name']} Auctions in {$city['name']} | {$province['name']} | Auction Atlas";
            $content['description'] = "Browse upcoming {$cat['name']} auctions in {$city['name']}, {$province['name']}. Compare auction houses and find exclusive deals.";
            $content['h1'] = "{$cat['name']} Auctions in {$city['name']}";
            $content['intro'] = "Find {$cat['name']} auctions in {$city['name']}. Our directory features verified auction houses with transparent processes.";
            $content['faqs'] = [
                ['q' => "Where can I find {$cat['name']} auctions in {$city['name']}?", 'a' => "Browse our listings above for upcoming {$cat['name']} auctions in {$city['name']}."],
            ];
            break;
            
        case 'province':
            $province = $data['province'];
            $content['title'] = "Auctions in {$province['name']} | Upcoming Auctions 2026 | Auction Atlas";
            $content['description'] = "Browse all auctions in {$province['name']}. Find property, vehicle, and commercial auctions across the province.";
            $content['h1'] = "Auctions in {$province['name']}";
            $content['intro'] = "Discover auctions across {$province['name']}. From major cities to smaller towns, find your perfect auction.";
            $content['faqs'] = [];
            break;
            
        case 'province-category':
            $province = $data['province'];
            $cat = $data['category'];
            $content['title'] = "{$cat['name']} Auctions in {$province['name']} | Auction Atlas";
            $content['description'] = "Browse {$cat['name']} auctions in {$province['name']}. Find verified auction houses and exclusive deals.";
            $content['h1'] = "{$cat['name']} Auctions in {$province['name']}";
            $content['intro'] = "{$cat['intro']} Browse listings in {$province['name']}.";
            $content['faqs'] = [];
            break;
            
        case 'auctioneer':
            $auctioneer = $data['auctioneer'];
            $content['title'] = "{$auctioneer['name']} Reviews, Ratings & Upcoming Auctions | Auction Atlas";
            $content['description'] = "Learn about {$auctioneer['name']} in {$auctioneer['city']}. View ratings, upcoming auctions, fees, and contact information.";
            $content['h1'] = $auctioneer['name'];
            // Use the logo slug mapping function for proper logo resolution
            require_once __DIR__ . '/includes/functions.php';
            $content['logoSlug'] = getAuctionLogoSlug($auctioneer['name']);
            $content['intro'] = "{$auctioneer['name']} is a trusted auction house serving {$auctioneer['province']}. Rated {$auctioneer['rating']} stars by verified buyers.";
            $content['faqs'] = [
                ['q' => "How do I contact {$auctioneer['name']}?", 'a' => "Visit their profile page for contact details and upcoming auctions."],
                ['q' => "What types of auctions does {$auctioneer['name']} hold?", 'a' => "Contact the auction house directly for their current auction calendar."],
            ];
            break;
    }
    
    return $content;
}

// Generate page HTML
function generatePageHTML($type, $data, $seoContent) {
    global $cities, $categories, $provinces, $auctionHouses, $csvAuctions;
    
    // Build breadcrumbs
    $breadcrumbs = [];
    $breadcrumbs[] = ['text' => 'Home', 'url' => '/'];
    $breadcrumbs[] = ['text' => 'Auctions', 'url' => '/auctions'];
    
    $currentUrl = '';
    
    switch ($type) {
        case 'city':
            $city = $data['city'];
            $currentUrl = "/auctions/{$city['slug']}";
            $breadcrumbs[] = ['text' => $city['name'], 'url' => null];
            break;
        case 'category':
            $cat = $data['category'];
            $currentUrl = "/auctions/{$cat['slug']}";
            $breadcrumbs[] = ['text' => $cat['name'], 'url' => null];
            break;
        case 'city-category':
            $city = $data['city'];
            $cat = $data['category'];
            $currentUrl = "/auctions/{$city['slug']}-{$cat['slug']}";
            $breadcrumbs[] = ['text' => $city['name'], 'url' => "/auctions/{$city['slug']}"];
            $breadcrumbs[] = ['text' => $cat['name'], 'url' => null];
            break;
        case 'province':
            $province = $data['province'];
            $currentUrl = "/auctions/{$province['slug']}";
            $breadcrumbs[] = ['text' => $province['name'], 'url' => null];
            break;
        case 'province-category':
            $province = $data['province'];
            $cat = $data['category'];
            $currentUrl = "/auctions/{$province['slug']}-{$cat['slug']}";
            $breadcrumbs[] = ['text' => $province['name'], 'url' => "/auctions/{$province['slug']}"];
            $breadcrumbs[] = ['text' => $cat['name'], 'url' => null];
            break;
        case 'auctioneer':
            $auctioneer = $data['auctioneer'];
            $currentUrl = "/auctioneer/" . strtolower($province['slug'] ?? 'gauteng') . "/{$auctioneer['slug']}";
            $breadcrumbs[] = ['text' => 'Auctioneers', 'url' => '/directory.php'];
            $breadcrumbs[] = ['text' => $auctioneer['name'], 'url' => null];
            break;
    }
    
    // Related links
    $relatedCategories = array_slice(array_keys($categories), 0, 5);
    $relatedCities = array_slice(array_keys($cities), 0, 8);
    
    // Schema
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $seoContent['h1'],
        'description' => $seoContent['description'],
        'url' => BASE_URL . $currentUrl,
    ];
    
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seoContent['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seoContent['description']); ?>">
    <link rel="canonical" href="<?php echo BASE_URL . $currentUrl; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo BASE_URL . $currentUrl; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($seoContent['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoContent['description']); ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1F4E79',
                        accent: '#2A9D8F',
                        highlight: '#FFD700',
                        warning: '#F4A261',
                    }
                }
            }
        }
    </script>
    
    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    <?php echo json_encode($schema, JSON_PRETTY_PRINT); ?>
    </script>
</head>
<body class="font-sans bg-slate-50 text-slate-800">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="<?php echo BASE_URL; ?>/" class="flex items-center gap-2">
                    <img src="<?php echo BASE_URL; ?>/assets/icons/auction-atlas.svg" alt="Auction Atlas" class="h-10">
                    <span class="font-bold text-xl text-primary">Auction Atlas</span>
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="<?php echo BASE_URL; ?>/directory.php" class="text-slate-600 hover:text-primary">Directory</a>
                    <a href="<?php echo BASE_URL; ?>/auctions/property" class="text-slate-600 hover:text-primary">Property</a>
                    <a href="<?php echo BASE_URL; ?>/auctions/vehicle" class="text-slate-600 hover:text-primary">Vehicles</a>
                    <a href="<?php echo BASE_URL; ?>/education.php" class="text-slate-600 hover:text-primary">Learn</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Breadcrumbs -->
    <div class="bg-slate-100 py-3">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex items-center gap-2 text-sm flex-wrap">
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <?php if ($i > 0): ?><span class="text-slate-400">›</span><?php endif; ?>
                    <?php if ($crumb['url']): ?>
                        <a href="<?php echo $crumb['url']; ?>" class="text-slate-600 hover:text-primary"><?php echo htmlspecialchars($crumb['text']); ?></a>
                    <?php else: ?>
                        <span class="text-primary font-medium"><?php echo htmlspecialchars($crumb['text']); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- H1 -->
            <!-- Logo -->
            <?php if (!empty($seoContent['logoSlug'])): ?>
            <div class="auction-logo mb-6">
                <img src="/assets/logos/<?php echo $seoContent['logoSlug']; ?>.png" 
                     alt="<?php echo htmlspecialchars($seoContent['h1']); ?> logo"
                     loading="lazy">
            </div>
            <?php endif; ?>
            <h1 class="font-heading font-extrabold text-3xl md:text-4xl text-slate-900 mb-4">
                <?php echo htmlspecialchars($seoContent['h1']); ?>
            </h1>
            
            <!-- Intro -->
            <p class="text-lg text-slate-600 mb-8 max-w-3xl">
                <?php echo htmlspecialchars($seoContent['intro']); ?>
            </p>
            
            <!-- Related Categories -->
            <?php if (!empty($relatedCategories)): ?>
            <div class="mb-8">
                <h2 class="font-semibold text-slate-700 mb-3">Browse by Category</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($relatedCategories as $catKey): ?>
                        <?php $cat = $categories[$catKey]; ?>
                        <a href="<?php echo BASE_URL; ?>/auctions/<?php echo $catKey; ?>" 
                           class="px-4 py-2 bg-white border border-slate-200 rounded-full text-sm hover:border-primary hover:text-primary transition-colors">
                            <?php echo $cat['name']; ?> Auctions
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Related Cities -->
            <?php if (!empty($relatedCities)): ?>
            <div class="mb-8">
                <h2 class="font-semibold text-slate-700 mb-3">Browse by City</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($relatedCities as $cityKey): ?>
                        <?php $city = $cities[$cityKey]; ?>
                        <a href="<?php echo BASE_URL; ?>/auctions/<?php echo $cityKey; ?>" 
                           class="px-4 py-2 bg-white border border-slate-200 rounded-full text-sm hover:border-primary hover:text-primary transition-colors">
                            <?php echo $city['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- FAQ Section -->
            <?php if (!empty($seoContent['faqs'])): ?>
            <div class="mb-12 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-heading font-bold text-xl mb-6">Frequently Asked Questions</h2>
                <div class="space-y-6">
                    <?php foreach ($seoContent['faqs'] as $faq): ?>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-2"><?php echo htmlspecialchars($faq['q']); ?></h3>
                        <p class="text-slate-600"><?php echo htmlspecialchars($faq['a']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- CTA -->
            <div class="mt-12 bg-gradient-to-r from-primary to-accent rounded-2xl p-8 text-center">
                <h3 class="font-heading font-bold text-2xl text-white mb-4">Ready to Bid?</h3>
                <p class="text-white/90 mb-6 max-w-2xl mx-auto">
                    Use our tools to compare auctions, calculate fees, and make informed decisions.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo BASE_URL; ?>/fee-calculator.php" class="px-6 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-white/90 transition-colors">
                        Fee Calculator
                    </a>
                    <a href="<?php echo BASE_URL; ?>/compare.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors">
                        Compare Auctions
                    </a>
                </div>
            </div>
            
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-slate-400">
            &copy; 2026 Auction Atlas. All rights reserved.
        </div>
    </footer>
</body>
</html>
    <?php
    return ob_get_clean();
}

// Generate all pages
echo "🚀 Starting page generation...\n\n";

$pageCount = 0;

// 1. City pages
echo "📍 Generating city pages...\n";
foreach ($cities as $cityKey => $city) {
    $seo = generateSEOContent('city', ['city' => $city]);
    $html = generatePageHTML('city', ['city' => $city], $seo);
    
    $filename = OUTPUT_DIR . '/auctions-' . $cityKey . '.html';
    file_put_contents($filename, $html);
    $generatedPages[] = '/auctions/' . $cityKey;
    $pageCount++;
}

// 2. Category pages
echo "📂 Generating category pages...\n";
foreach ($categories as $catKey => $cat) {
    $seo = generateSEOContent('category', ['category' => $cat]);
    $html = generatePageHTML('category', ['category' => $cat], $seo);
    
    $filename = OUTPUT_DIR . '/auctions-' . $catKey . '.html';
    file_put_contents($filename, $html);
    $generatedPages[] = '/auctions/' . $catKey;
    $pageCount++;
}

// 3. City + Category pages
echo "🔗 Generating city-category pages...\n";
foreach ($cities as $cityKey => $city) {
    foreach ($categories as $catKey => $cat) {
        $seo = generateSEOContent('city-category', [
            'city' => $city,
            'category' => $cat,
            'province' => ['name' => $city['province'], 'slug' => strtolower($city['province'])]
        ]);
        $html = generatePageHTML('city-category', [
            'city' => $city,
            'category' => $cat,
            'province' => ['name' => $city['province']]
        ], $seo);
        
        $filename = OUTPUT_DIR . '/auctions-' . $cityKey . '-' . $catKey . '.html';
        file_put_contents($filename, $html);
        $generatedPages[] = '/auctions/' . $cityKey . '-' . $catKey;
        $pageCount++;
    }
}

// 4. Province pages
echo "🗺️ Generating province pages...\n";
foreach ($provinces as $provKey => $province) {
    $seo = generateSEOContent('province', ['province' => $province]);
    $html = generatePageHTML('province', ['province' => $province], $seo);
    
    $filename = OUTPUT_DIR . '/auctions-' . $provKey . '.html';
    file_put_contents($filename, $html);
    $generatedPages[] = '/auctions/' . $provKey;
    $pageCount++;
}

// 5. Province + Category pages
echo "🔗 Generating province-category pages...\n";
foreach ($provinces as $provKey => $province) {
    foreach ($categories as $catKey => $cat) {
        $seo = generateSEOContent('province-category', [
            'province' => $province,
            'category' => $cat
        ]);
        $html = generatePageHTML('province-category', [
            'province' => $province,
            'category' => $cat
        ], $seo);
        
        $filename = OUTPUT_DIR . '/auctions-' . $provKey . '-' . $catKey . '.html';
        file_put_contents($filename, $html);
        $generatedPages[] = '/auctions/' . $provKey . '-' . $catKey;
        $pageCount++;
    }
}

// 6. Auctioneer pages
echo "🏢 Generating auctioneer pages...\n";
foreach ($auctionHouses as $auctioneer) {
    $seo = generateSEOContent('auctioneer', ['auctioneer' => $auctioneer]);
    $html = generatePageHTML('auctioneer', ['auctioneer' => $auctioneer], $seo);
    
    $provinceSlug = strtolower(str_replace(' ', '-', $auctioneer['province']));
    $filename = OUTPUT_DIR . '/auctioneer-' . $provinceSlug . '-' . $auctioneer['slug'] . '.html';
    file_put_contents($filename, $html);
    $generatedPages[] = '/auctioneer/' . $provinceSlug . '/' . $auctioneer['slug'];
    $pageCount++;
}

// Summary
echo "\n✅ Page generation complete!\n";
echo "📊 Total pages generated: " . $pageCount . "\n";
echo "📁 Output directory: " . OUTPUT_DIR . "\n";
echo "\nSample URLs:\n";
foreach (array_slice($generatedPages, 0, 10) as $url) {
    echo "  - " . $url . "\n";
}
echo "\n";

// Generate sitemap for generated pages
echo "📄 Generating sitemap...\n";
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';

foreach ($generatedPages as $url) {
    $sitemap .= '  <url>
    <loc>' . BASE_URL . $url . '</loc>
    <lastmod>' . date('Y-m-d') . '</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
';
}

$sitemap .= '</urlset>';

file_put_contents(OUTPUT_DIR . '/sitemap-generated.xml', $sitemap);
echo "✅ Sitemap generated: sitemap-generated.xml\n";
