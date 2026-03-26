<?php
/**
 * Auction Atlas - Header Component
 * 
 * Sticky header with logo, navigation, and mobile hamburger menu.
 * Included at the top of every page.
 */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/navigation.php';

$navItems = getNavItems();
$pageTitle = $pageTitle ?? 'Auction Atlas';

// =====================================================
// CANONICAL URL SYSTEM - Enhanced for SEO
// =====================================================

// Force HTTPS protocol
$protocol = 'https';
$host = 'auctionatlas.co.za';
$baseUrl = $protocol . '://' . $host;

// Get current path
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/');

// Get query string and clean it
$queryString = '';

// Allowed safe params for canonical (page, id, slug, type, category, search)
$allowedParams = ['page', 'id', 'slug', 'type', 'category', 'search'];

// Parse current query string
parse_str($_SERVER['QUERY_STRING'] ?? '', $params);

// Filter to only allowed parameters
$safeParams = array_intersect_key($params, array_flip($allowedParams));

// Only include page parameter if it's > 1 (to avoid duplicate for page 1)
if (isset($safeParams['page']) && $safeParams['page'] <= 1) {
    unset($safeParams['page']);
}

if (!empty($safeParams)) {
    $queryString = '?' . http_build_query($safeParams);
}

// Build canonical URL
if (empty($path) || $path === '/index.php') {
    $canonicalUrl = $baseUrl . '/';
} else {
    $canonicalUrl = $baseUrl . $path . $queryString;
}

// Ensure no double slashes and proper protocol
$canonicalUrl = preg_replace('/\/+/', '/', $canonicalUrl);
$canonicalUrl = str_replace('https:/', 'https://', $canonicalUrl);

// DEBUG: Uncomment to log canonical URL for verification
// error_log('Canonical URL: ' . $canonicalUrl);

// Set page-specific meta description
$pageDescription = $pageDescription ?? 'Auction Atlas is South Africa\'s leading auction directory. Find verified auction houses, compare services, and make informed bidding decisions.';
// =====================================================
// END CANONICAL URL SYSTEM
// =====================================================

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | Auction Atlas</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo e($pageDescription); ?>">
    <meta name="keywords" content="auction, South Africa, auction houses, property auction, vehicle auction, bid, online auction">
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:title" content="<?php echo e($pageTitle); ?> | Auction Atlas">
    <meta property="og:description" content="<?php echo e($pageDescription); ?>">
    <meta property="og:image" content="<?php echo $baseUrl; ?>/assets/icons/auction-atlas.svg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="twitter:title" content="<?php echo e($pageTitle); ?> | Auction Atlas">
    <meta property="twitter:description" content="<?php echo e($pageDescription); ?>">
    <meta property="twitter:image" content="<?php echo $baseUrl; ?>/assets/icons/auction-atlas.svg">
    
    <!-- Robots -->
    <meta name="robots" content="index, follow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.png">
    <link rel="apple-touch-icon" href="favicon.png">
    <meta name="theme-color" content="#1F4E79">
    
    <!-- Tailwind CSS CDN -->
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
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body class="font-sans bg-slate-50 text-slate-800">

<!-- Sticky Header -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">
            
            <!-- Logo - Far Left -->
            <a href="index.php" class="flex items-center gap-2 flex-shrink-0 ml-2">
                <img 
                    src="auction-atlas-logo.png" 
                    alt="Auction Atlas" 
                    class="h-10 w-auto lg:h-12 object-contain"
                >
            </a>
            
            <!-- Desktop Navigation with Dropdowns -->
            <nav class="hidden lg:flex items-center gap-1">
                <?php foreach ($navItems as $item): ?>
                    <?php if (isset($item['dropdown'])): ?>
                        <!-- Dropdown Menu -->
                        <div class="relative group">
                            <button type="button" 
                                    class="dropdown-trigger px-3 py-2 rounded-lg text-sm font-ui font-medium text-slate-600 hover:bg-slate-100 hover:text-primary transition-all duration-200 flex items-center gap-1">
                                <?php echo $item['label']; ?>
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>
                            <!-- Dropdown Panel -->
                            <div class="dropdown-panel absolute top-full left-0 mt-1 w-56 bg-white rounded-xl shadow-lg border border-slate-200 py-2 hidden group-hover:block">
                                <?php foreach ($item['dropdown'] as $dropdownItem): ?>
                                    <a href="<?php echo $dropdownItem['url']; ?>" 
                                       class="flex items-center gap-3 px-4 py-3 text-sm font-ui text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                        <i data-lucide="<?php echo $dropdownItem['icon']; ?>" class="w-4 h-4"></i>
                                        <?php echo $dropdownItem['label']; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Regular Nav Item -->
                        <a href="<?php echo $item['url']; ?>" 
                           class="px-3 py-2 rounded-lg text-sm font-ui font-medium transition-all duration-200 
                                  <?php echo isActivePage($item['url']) 
                                      ? 'bg-primary text-white shadow-md' 
                                      : 'text-slate-600 hover:bg-slate-100 hover:text-primary'; ?>">
                            <?php echo $item['label']; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100" 
                    onclick="document.body.classList.toggle('mobile-menu-open')">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </div>
</header>
