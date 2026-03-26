<?php
/**
 * Auction Atlas - Dynamic Router for Programmatic SEO
 * 
 * Handles URL patterns:
 * - /auctions/{city}
 * - /auctions/{city}-{province}
 * - /auctions/{category}
 * - /auctions/{city}-{category}
 * - /auctions/{province}-{category}
 * - /auctioneer/{province}/{name}
 * - /category/{type}
 * 
 * @package AuctionAtlas
 * @version 2.0
 */

// Configuration
define('BASE_URL', 'https://auctionatlas.co.za');
define('DATA_DIR', __DIR__ . '/data');
define('PAGES_DIR', __DIR__);

// Load data files
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

function loadAuctionsJSON() {
    $file = __DIR__ . '/data/auctions.json';
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return [];
}

// Data arrays
$cities = [
    'johannesburg' => ['name' => 'Johannesburg', 'province' => 'Gauteng'],
    'cape-town' => ['name' => 'Cape Town', 'province' => 'Western Cape'],
    'durban' => ['name' => 'Durban', 'province' => 'KwaZulu-Natal'],
    'pretoria' => ['name' => 'Pretoria', 'province' => 'Gauteng'],
    'port-elizabeth' => ['name' => 'Port Elizabeth', 'province' => 'Eastern Cape'],
    'bloemfontein' => ['name' => 'Bloemfontein', 'province' => 'Free State'],
    'nelspruit' => ['name' => 'Nelspruit', 'province' => 'Mpumalanga'],
    'polokwane' => ['name' => 'Polokwane', 'province' => 'Limpopo'],
    'centurion' => ['name' => 'Centurion', 'province' => 'Gauteng'],
    'sandton' => ['name' => 'Sandton', 'province' => 'Gauteng'],
    'midrand' => ['name' => 'Midrand', 'province' => 'Gauteng'],
    'randburg' => ['name' => 'Randburg', 'province' => 'Gauteng'],
    'benoni' => ['name' => 'Benoni', 'province' => 'Gauteng'],
    'richards-bay' => ['name' => 'Richards Bay', 'province' => 'KwaZulu-Natal'],
    'stellenbosch' => ['name' => 'Stellenbosch', 'province' => 'Western Cape'],
    'paarl' => ['name' => 'Paarl', 'province' => 'Western Cape'],
];

$provinces = [
    'gauteng' => ['name' => 'Gauteng'],
    'western-cape' => ['name' => 'Western Cape'],
    'kwazulu-natal' => ['name' => 'KwaZulu-Natal'],
    'eastern-cape' => ['name' => 'Eastern Cape'],
    'free-state' => ['name' => 'Free State'],
    'mpumalanga' => ['name' => 'Mpumalanga'],
    'limpopo' => ['name' => 'Limpopo'],
    'north-west' => ['name' => 'North West'],
    'northern-cape' => ['name' => 'Northern Cape'],
];

$categories = [
    'property' => ['name' => 'Property', 'synonyms' => ['properties', 'real-estate', 'houses', 'buildings']],
    'vehicle' => ['name' => 'Vehicle', 'synonyms' => ['vehicles', 'cars', 'motor-vehicles', 'automotive']],
    'industrial' => ['name' => 'Industrial', 'synonyms' => ['machinery', 'equipment', 'manufacturing']],
    'commercial' => ['name' => 'Commercial', 'synonyms' => ['business', 'offices', 'retail']],
    'agricultural' => ['name' => 'Agricultural', 'synonyms' => ['farm', 'farming', 'agriculture', 'farms']],
    'liquidations' => ['name' => 'Liquidations', 'synonyms' => ['liquidation', 'bankruptcy', 'insolvency']],
    'estate' => ['name' => 'Estate', 'synonyms' => ['estates', 'deceased-estate', 'insolvent-estate']],
    'miscellaneous' => ['name' => 'Miscellaneous', 'synonyms' => ['other', 'general', 'mixed']],
];

// Get request URI and parse
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');
$segments = array_filter(explode('/', $requestUri));

// Route matching
function matchRoute($segments) {
    global $cities, $provinces, $categories;
    
    // Empty path - homepage
    if (empty($segments)) {
        return ['type' => 'homepage'];
    }
    
    // /auctions/{anything}
    if (isset($segments[1]) && $segments[1] === 'auctions') {
        if (!isset($segments[2]) || empty($segments[2])) {
            return ['type' => 'auctions-index'];
        }
        
        $slug = $segments[2];
        
        // Check if it's just a category
        foreach ($categories as $key => $cat) {
            if ($slug === $key || in_array($slug, $cat['synonyms'])) {
                return ['type' => 'category', 'category' => $key];
            }
        }
        
        // Check if it's a city
        foreach ($cities as $key => $city) {
            if ($slug === $key) {
                return ['type' => 'city', 'city' => $key, 'province' => $city['province']];
            }
        }
        
        // Check if it's a province
        foreach ($provinces as $key => $province) {
            if ($slug === $key) {
                return ['type' => 'province', 'province' => $key];
            }
        }
        
        // Check for combined: city-category (e.g., johannesburg-property)
        foreach ($cities as $cityKey => $city) {
            foreach ($categories as $catKey => $cat) {
                $combined = $cityKey . '-' . $catKey;
                if ($slug === $combined) {
                    return [
                        'type' => 'city-category',
                        'city' => $cityKey,
                        'category' => $catKey,
                        'province' => $city['province']
                    ];
                }
            }
        }
        
        // Check for province-category (e.g., gauteng-property)
        foreach ($provinces as $provKey => $province) {
            foreach ($categories as $catKey => $cat) {
                $combined = $provKey . '-' . $catKey;
                if ($slug === $combined) {
                    return [
                        'type' => 'province-category',
                        'province' => $provKey,
                        'category' => $catKey
                    ];
                }
            }
        }
        
        return ['type' => '404'];
    }
    
    // /auctioneer/{province}/{name}
    if (isset($segments[1]) && $segments[1] === 'auctioneer') {
        if (isset($segments[2]) && isset($segments[3])) {
            return [
                'type' => 'auctioneer',
                'province' => $segments[2],
                'name' => $segments[3]
            ];
        }
        return ['type' => '404'];
    }
    
    // /category/{type}
    if (isset($segments[1]) && $segments[1] === 'category') {
        if (isset($segments[2])) {
            foreach ($categories as $key => $cat) {
                if ($segments[2] === $key) {
                    return ['type' => 'category', 'category' => $key];
                }
            }
        }
        return ['type' => '404'];
    }
    
    return ['type' => '404'];
}

// Match route
$route = matchRoute($segments);

// Load data
$csvAuctions = loadAuctionsCSV();
$jsonAuctions = loadAuctionsJSON();

// Filter auctions based on route
function filterAuctions($auctions, $route) {
    $filtered = [];
    
    foreach ($auctions as $auction) {
        $include = true;
        
        if (isset($route['city'])) {
            $location = strtolower($auction['Location'] ?? '');
            $city = strtolower($route['city']);
            if (strpos($location, str_replace('-', ' ', $city)) === false) {
                $include = false;
            }
        }
        
        if (isset($route['province'])) {
            $location = strtolower($auction['Location'] ?? '');
            $province = strtolower($route['province']);
            if (strpos($location, str_replace('-', ' ', $province)) === false) {
                $include = false;
            }
        }
        
        if ($include) {
            $filtered[] = $auction;
        }
    }
    
    return $filtered;
}

// Generate page content based on route
function generatePageContent($route, $csvAuctions, $jsonAuctions) {
    global $cities, $provinces, $categories;
    
    $content = [
        'title' => '',
        'description' => '',
        'h1' => '',
        'auctions' => [],
        'auctioneers' => [],
        'relatedCities' => [],
        'relatedCategories' => [],
        ' breadcrumbs' => [],
        'schema' => null,
    ];
    
    switch ($route['type']) {
        case 'homepage':
            $content['title'] = 'Auction Atlas | South Africa\'s Premier Auction Directory';
            $content['description'] = 'Discover auctions across South Africa. Browse property, vehicle, industrial & commercial auctions. Compare auction houses, calculate fees, and find your perfect auction.';
            $content['h1'] = 'South Africa\'s Auction Directory';
            $content['auctions'] = $csvAuctions;
            break;
            
        case 'category':
            $cat = $categories[$route['category']];
            $content['title'] = $cat['name'] . ' Auctions South Africa | Upcoming Auctions 2026';
            $content['description'] = 'Browse upcoming ' . strtolower($cat['name']) . ' auctions in South Africa. Compare auction houses, view dates, and find the best deals.';
            $content['h1'] = ucfirst($cat['name']) . ' Auctions in South Africa';
            $content[' breadcrumbs'] = [
                ['text' => 'Home', 'url' => '/'],
                ['text' => 'Auctions', 'url' => '/auctions'],
                ['text' => $cat['name'], 'url' => null]
            ];
            $content['schema'] = 'CollectionPage';
            break;
            
        case 'city':
            $city = $cities[$route['city']];
            $province = $provinces[strtolower($city['province'])];
            $content['title'] = 'Auctions in ' . $city['name'] . ' | Upcoming Auctions 2026';
            $content['description'] = 'Discover upcoming auctions in ' . $city['name'] . ', ' . $province['name'] . '. Browse property, vehicle, and industrial auctions near you.';
            $content['h1'] = 'Auctions in ' . $city['name'] . ', ' . $province['name'];
            $content[' breadcrumbs'] = [
                ['text' => 'Home', 'url' => '/'],
                ['text' => 'Auctions', 'url' => '/auctions'],
                ['text' => $city['name'], 'url' => null]
            ];
            $content['relatedCategories'] = array_keys($categories);
            $content['schema'] = 'CollectionPage';
            break;
            
        case 'province':
            $province = $provinces[$route['province']];
            $content['title'] = 'Auctions in ' . $province['name'] . ' | Upcoming Auctions 2026';
            $content['description'] = 'Browse upcoming auctions in ' . $province['name'] . '. Find property, vehicle, and commercial auctions across the province.';
            $content['h1'] = 'Auctions in ' . $province['name'];
            $content[' breadcrumbs'] = [
                ['text' => 'Home', 'url' => '/'],
                ['text' => 'Auctions', 'url' => '/auctions'],
                ['text' => $province['name'], 'url' => null]
            ];
            $content['schema'] = 'CollectionPage';
            break;
            
        case 'city-category':
            $city = $cities[$route['city']];
            $cat = $categories[$route['category']];
            $province = $provinces[strtolower($city['province'])];
            $content['title'] = ucfirst($cat['name']) . ' Auctions in ' . $city['name'] . ' | ' . $province['name'];
            $content['description'] = 'Browse upcoming ' . strtolower($cat['name']) . ' auctions in ' . $city['name'] . ', ' . $province['name'] . '. Compare auction houses and find deals.';
            $content['h1'] = ucfirst($cat['name']) . ' Auctions in ' . $city['name'];
            $content[' breadcrumbs'] = [
                ['text' => 'Home', 'url' => '/'],
                ['text' => 'Auctions', 'url' => '/auctions'],
                ['text' => $city['name'], 'url' => '/auctions/' . $route['city']],
                ['text' => $cat['name'], 'url' => null]
            ];
            $content['relatedCities'] = array_keys($cities);
            $content['schema'] = 'CollectionPage';
            break;
            
        case 'province-category':
            $province = $provinces[$route['province']];
            $cat = $categories[$route['category']];
            $content['title'] = ucfirst($cat['name']) . ' Auctions in ' . $province['name'] . ' | Upcoming Auctions';
            $content['description'] = 'Browse ' . strtolower($cat['name']) . ' auctions in ' . $province['name'] . '. Find verified auction houses and exclusive deals.';
            $content['h1'] = ucfirst($cat['name']) . ' Auctions in ' . $province['name'];
            $content[' breadcrumbs'] = [
                ['text' => 'Home', 'url' => '/'],
                ['text' => 'Auctions', 'url' => '/auctions'],
                ['text' => $province['name'], 'url' => '/auctions/' . $route['province']],
                ['text' => $cat['name'], 'url' => null]
            ];
            $content['relatedCities'] = array_keys($cities);
            $content['schema'] = 'CollectionPage';
            break;
            
        case 'auctioneer':
            // Find auctioneer in JSON data
            foreach ($jsonAuctions as $auctioneer) {
                $slug = strtolower(preg_replace('/[^a-z0-9]/', '-', $auctioneer['auction_house_name'] ?? ''));
                if ($slug === $route['name']) {
                    $content['title'] = $auctioneer['auction_house_name'] . ' | Auction House Reviews & Upcoming Auctions';
                    $content['description'] = 'Learn about ' . $auctioneer['auction_house_name'] . '. View ratings, fees, upcoming auctions, and contact information.';
                    $content['h1'] = $auctioneer['auction_house_name'];
                    $content['auctioneers'] = [$auctioneer];
                    $content['schema'] = 'LocalBusiness';
                    break;
                }
            }
            if (empty($content['title'])) {
                $content['title'] = 'Auction House Not Found';
                $content['description'] = 'The requested auction house could not be found.';
                $content['h1'] = 'Auction House Not Found';
            }
            break;
            
        case '404':
        default:
            http_response_code(404);
            $content['title'] = 'Page Not Found | Auction Atlas';
            $content['description'] = 'The page you are looking for does not exist.';
            $content['h1'] = '404 - Page Not Found';
            $content['schema'] = null;
            break;
    }
    
    return $content;
}

// Generate content
$pageContent = generatePageContent($route, $csvAuctions, $jsonAuctions);

// Output the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageContent['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageContent['description']); ?>">
    <link rel="canonical" href="<?php echo BASE_URL . $requestUri; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo BASE_URL . $requestUri; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageContent['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageContent['description']); ?>">
    
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
    
    <?php if ($pageContent['schema']): ?>
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    <?php
    $schema = [];
    
    if ($pageContent['schema'] === 'CollectionPage') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $pageContent['h1'],
            'description' => $pageContent['description'],
            'url' => BASE_URL . $requestUri,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Auction Atlas',
                'url' => BASE_URL
            ]
        ];
    } elseif ($pageContent['schema'] === 'LocalBusiness' && !empty($pageContent['auctioneers'])) {
        $ae = $pageContent['auctioneers'][0];
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $ae['auction_house_name'],
            'telephone' => $ae['contact_phone'] ?? '',
            'email' => $ae['contact_email'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $ae['address'] ?? '',
                'addressLocality' => $ae['city'] ?? '',
                'addressRegion' => $ae['province'] ?? '',
                'addressCountry' => 'ZA'
            ],
            'url' => $ae['website_url'] ?? BASE_URL . $requestUri,
            'priceRange' => '$$'
        ];
    }
    
    echo json_encode($schema, JSON_PRETTY_PRINT);
    ?>
    </script>
    <?php endif; ?>
</head>
<body class="font-sans bg-slate-50 text-slate-800">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
    <?php if (!empty($pageContent['breadcrumbs'])): ?>
    <div class="bg-slate-100 py-3">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex items-center gap-2 text-sm">
                <?php foreach ($pageContent['breadcrumbs'] as $i => $crumb): ?>
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
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- H1 -->
            <h1 class="font-heading font-extrabold text-3xl md:text-4xl text-slate-900 mb-4">
                <?php echo htmlspecialchars($pageContent['h1']); ?>
            </h1>
            
            <!-- Description -->
            <p class="text-lg text-slate-600 mb-8 max-w-3xl">
                <?php echo htmlspecialchars($pageContent['description']); ?>
            </p>
            
            <!-- Related Links -->
            <?php if (!empty($pageContent['relatedCategories'])): ?>
            <div class="mb-8">
                <h2 class="font-semibold text-slate-700 mb-3">Browse by Category</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($pageContent['relatedCategories'] as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>/auctions/<?php echo $cat; ?>" 
                           class="px-4 py-2 bg-white border border-slate-200 rounded-full text-sm hover:border-primary hover:text-primary transition-colors">
                            <?php echo ucfirst($cat); ?> Auctions
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($pageContent['relatedCities'])): ?>
            <div class="mb-8">
                <h2 class="font-semibold text-slate-700 mb-3">Browse by City</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach (array_slice($pageContent['relatedCities'], 0, 8) as $city): ?>
                        <a href="<?php echo BASE_URL; ?>/auctions/<?php echo $city; ?>" 
                           class="px-4 py-2 bg-white border border-slate-200 rounded-full text-sm hover:border-primary hover:text-primary transition-colors">
                            <?php echo ucwords(str_replace('-', ' ', $city)); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Auction Listings -->
            <?php if ($route['type'] !== '404'): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-heading font-bold text-lg">Upcoming Auctions</h2>
                </div>
                
                <?php if (empty($csvAuctions)): ?>
                    <div class="p-8 text-center text-slate-500">
                        No upcoming auctions found. Check back soon!
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-slate-100">
                        <?php foreach (array_slice($csvAuctions, 0, 20) as $auction): ?>
                        <div class="p-6 hover:bg-slate-50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-800"><?php echo htmlspecialchars($auction['Auction Title'] ?? 'Auction'); ?></h3>
                                    <p class="text-sm text-slate-500"><?php echo htmlspecialchars($auction['Auction House Name'] ?? ''); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-primary"><?php echo htmlspecialchars($auction['Auction Date'] ?? ''); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo htmlspecialchars($auction['Location'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="font-bold mb-4">Auction Atlas</h4>
                    <p class="text-slate-400 text-sm">South Africa's premier auction directory.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/directory.php" class="hover:text-white">Directory</a></li>
                        <li><a href="/auctions/property" class="hover:text-white">Property</a></li>
                        <li><a href="/auctions/vehicle" class="hover:text-white">Vehicles</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tools</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/fee-calculator.php" class="hover:text-white">Fee Calculator</a></li>
                        <li><a href="/compare.php" class="hover:text-white">Compare</a></li>
                        <li><a href="/risk-scanner.php" class="hover:text-white">Risk Scanner</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Learn</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/education.php" class="hover:text-white">Education</a></li>
                        <li><a href="/blog.php" class="hover:text-white">Blog</a></li>
                        <li><a href="/scam-awareness.php" class="hover:text-white">Scam Awareness</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm text-slate-400">
                &copy; 2026 Auction Atlas. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
