<?php
/**
 * Auction Atlas - Dynamic Sitemap Generator
 * 
 * Generates XML sitemap dynamically from pages and auction data
 * Run via cron: 0 2 * * * /usr/bin/php /path/to/sitemap.php
 * 
 * @package AuctionAtlas
 * @version 2.0
 */

// Configuration
define('BASE_URL', 'https://auctionatlas.co.za');
define('CACHE_FILE', __DIR__ . '/cache/sitemap-cache.json');
define('CACHE_DURATION', 3600); // 1 hour

// Set content type
header('Content-Type: application/xml; charset=utf-8');

// Start output buffering
ob_start();

// Static pages array
$staticPages = [
    [
        'loc' => '',
        'priority' => '1.0',
        'changefreq' => 'daily',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'index.php',
        'priority' => '1.0',
        'changefreq' => 'daily',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'directory.php',
        'priority' => '0.9',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'compare.php',
        'priority' => '0.8',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'match.php',
        'priority' => '0.8',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'risk-scanner.php',
        'priority' => '0.8',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'fee-calculator.php',
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'strategy-simulator.php',
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'category.php',
        'priority' => '0.8',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'education.php',
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'blog.php',
        'priority' => '0.7',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'prep-check.php',
        'priority' => '0.6',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => 'scam-awareness.php',
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ],
    [
        'loc' => '404.php',
        'priority' => '0.1',
        'changefreq' => 'yearly',
        'lastmod' => date('Y-m-d')
    ]
];

// Blog posts (static examples - can be dynamic)
$blogPosts = [
    ['slug' => 'first-time-buyer-guide', 'lastmod' => '2024-01-15', 'priority' => '0.6'],
    ['slug' => 'auction-strategies-2024', 'lastmod' => '2024-02-01', 'priority' => '0.6'],
    ['slug' => 'property-auction-tips', 'lastmod' => '2024-02-15', 'priority' => '0.6']
];

// Categories
$categories = [
    'property', 'vehicle', 'industrial', 'liquidations', 'estate', 
    'agricultural', 'commercial', 'miscellaneous'
];

// Function to get auctions from CSV
function getAuctionsFromCSV($csvFile) {
    $auctions = [];
    if (file_exists($csvFile)) {
        $handle = fopen($csvFile, 'r');
        if ($handle) {
            $headers = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($headers, $row);
                $auctions[] = $data;
            }
            fclose($handle);
        }
    }
    return $auctions;
}

// Function to generate URL-safe slug
function generateSlug($name) {
    $name = strtolower($name);
    $name = preg_replace('/[^a-z0-9\s-]/', '', $name);
    $name = preg_replace('/[\s-]+/', '-', $name);
    $name = trim($name, '-');
    return $name;
}

// Build URLs array
$urls = [];

// Add static pages
foreach ($staticPages as $page) {
    $loc = $page['loc'] === '' ? BASE_URL : BASE_URL . '/' . $page['loc'];
    $urls[] = [
        'loc' => $loc,
        'priority' => $page['priority'],
        'changefreq' => $page['changefreq'],
        'lastmod' => $page['lastmod']
    ];
}

// Add blog posts
foreach ($blogPosts as $post) {
    $urls[] = [
        'loc' => BASE_URL . '/blog-post.php?slug=' . $post['slug'],
        'priority' => $post['priority'],
        'changefreq' => 'weekly',
        'lastmod' => $post['lastmod']
    ];
}

// Add categories
foreach ($categories as $category) {
    $urls[] = [
        'loc' => BASE_URL . '/category.php?type=' . $category,
        'priority' => '0.7',
        'changefreq' => 'weekly',
        'lastmod' => date('Y-m-d')
    ];
}

// Add auction houses from CSV (limit to 1000 for performance)
$csvFile = __DIR__ . '/Upcoming Auctions.csv';
$auctions = getAuctionsFromCSV($csvFile);
$auctionCount = 0;
$maxAuctions = 1000;

foreach ($auctions as $auction) {
    if ($auctionCount >= $maxAuctions) break;
    
    // Use auction name for URL
    $name = $auction['auction_name'] ?? $auction['Auction'] ?? 'auction';
    $slug = generateSlug($name);
    $id = $auction['id'] ?? $auctionCount + 1;
    
    $urls[] = [
        'loc' => BASE_URL . '/profile.php?id=' . $id,
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => date('Y-m-d')
    ];
    
    $auctionCount++;
}

// Remove duplicates
$seen = [];
$uniqueUrls = [];
foreach ($urls as $url) {
    $key = $url['loc'];
    if (!isset($seen[$key])) {
        $seen[$key] = true;
        $uniqueUrls[] = $url;
    }
}
$urls = $uniqueUrls;

// Output XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as $url) {
    echo '  <url>' . "\n";
    echo '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . "\n";
    echo '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
    echo '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
    echo '    <priority>' . $url['priority'] . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '</urlset>';

// Cache the output
$output = ob_get_contents();
ob_end_flush();

// Optionally save to cache file
$cacheDir = __DIR__ . '/cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}
file_put_contents(CACHE_FILE, $output);
