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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | Auction Atlas</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/icons/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon.svg">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon.svg">
    <link rel="apple-touch-icon" href="assets/icons/favicon.svg">
    <meta name="theme-color" content="#1F4E79">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($metaDescription) ? e($metaDescription) : 'South Africa\'s premier auction intelligence platform. Compare auction houses, analyze trust scores, and find the right auctions using data-driven insights.'; ?>">
    <meta name="keywords" content="auctions, South Africa, property auctions, vehicle auctions, auction houses, bidding, online auctions">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo isset($canonicalUrl) ? e($canonicalUrl) : 'https://auction-atlas.co.za' . str_replace('/var/www/html', '', $_SERVER['REQUEST_URI']); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e($pageTitle ?? 'Auction Atlas'); ?> | Auction Atlas">
    <meta property="og:description" content="<?php echo isset($metaDescription) ? e($metaDescription) : 'South Africa\'s premier auction intelligence platform.'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($canonicalUrl) ? e($canonicalUrl) : 'https://auction-atlas.co.za' . str_replace('/var/www/html', '', $_SERVER['REQUEST_URI']); ?>">
    
    <!-- Robots Meta -->
    <meta name="robots" content="<?php echo isset($robotsMeta) ? e($robotsMeta) : 'index, follow'; ?>">
    
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
                    },
                    fontFamily: {
                        heading: ['Montserrat', 'sans-serif'],
                        body: ['Open Sans', 'sans-serif'],
                        ui: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="font-body bg-slate-50 text-slate-800 min-h-screen">

<!-- Sticky Header -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">
            
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-2 flex-shrink-0">
                <img 
                    src="assets/icons/auction-atlas.svg" 
                    alt="Auction Atlas" 
                    class="h-8 w-auto lg:h-10 object-contain"
                >
                <span class="font-heading font-bold text-lg text-primary hidden sm:block">Auction Atlas</span>
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
            
            <!-- CTA Button (Desktop) -->
            <a href="directory.php" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary to-accent text-white font-ui font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="compass" class="w-4 h-4"></i>
                Explore Directory
            </a>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Toggle menu">
                <i data-lucide="menu" class="w-6 h-6" id="menu-icon-open"></i>
                <i data-lucide="x" class="w-6 h-6 hidden" id="menu-icon-close"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Navigation Panel with Accordions -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white/95 backdrop-blur-xl border-t border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <?php foreach ($navItems as $item): ?>
                <?php if (isset($item['dropdown'])): ?>
                    <!-- Mobile Accordion -->
                    <div class="mobile-accordion">
                        <button type="button" 
                                class="mobile-accordion-trigger w-full flex items-center justify-between px-4 py-3 rounded-xl text-base font-ui font-medium text-slate-600 hover:bg-slate-100 transition-colors min-h-[44px]">
                            <span class="flex items-center gap-3">
                                <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i>
                                <?php echo $item['label']; ?>
                            </span>
                            <i data-lucide="chevron-down" class="w-5 h-5 accordion-arrow"></i>
                        </button>
                        <div class="mobile-accordion-content hidden pl-4 space-y-1">
                            <?php foreach ($item['dropdown'] as $dropdownItem): ?>
                                <a href="<?php echo $dropdownItem['url']; ?>" 
                                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-ui text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors min-h-[44px]">
                                    <i data-lucide="<?php echo $dropdownItem['icon']; ?>" class="w-5 h-5"></i>
                                    <?php echo $dropdownItem['label']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $item['url']; ?>" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-ui font-medium transition-all duration-200 min-h-[44px]
                              <?php echo isActivePage($item['url']) 
                                  ? 'bg-primary text-white' 
                                  : 'text-slate-600 hover:bg-slate-100'; ?>">
                        <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i>
                        <?php echo $item['label']; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="pt-3 border-t border-slate-200">
                <a href="directory.php" class="flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-primary to-accent text-white font-ui font-semibold rounded-xl shadow-lg min-h-[44px]">
                    <i data-lucide="compass" class="w-5 h-5"></i>
                    Explore Directory
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Main Content Wrapper -->
<main>
