<?php
/**
 * Auction Atlas - Core Functions
 * 
 * Central data loading and utility functions for the platform.
 * All pages use this file to access auction data from the JSON source.
 */

// Cache loaded data to avoid redundant file reads within a single request
$GLOBALS['_auction_data_cache'] = null;

/**
 * Load auction data from JSON file
 * Implements single-load caching to prevent redundant file reads
 * 
 * @return array Array of auction house data
 */
function loadAuctionData() {
    if ($GLOBALS['_auction_data_cache'] !== null) {
        return $GLOBALS['_auction_data_cache'];
    }
    
    $jsonPath = __DIR__ . '/../data/auctions.json';
    
    if (!file_exists($jsonPath)) {
        return [];
    }
    
    $json = file_get_contents($jsonPath);
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }
    
    $GLOBALS['_auction_data_cache'] = $data;
    return $data;
}

/**
 * Get a single auction by ID
 * 
 * @param int $id Auction ID
 * @return array|null Auction data or null if not found
 */
function getAuctionById($id) {
    $data = loadAuctionData();
    foreach ($data as $auction) {
        if (isset($auction['id']) && (int)$auction['id'] === (int)$id) {
            return $auction;
        }
    }
    return null;
}

/**
 * Get all unique provinces from auction data
 * 
 * @return array Sorted array of province names
 */
function getAllProvinces() {
    $data = loadAuctionData();
    $provinces = [];
    foreach ($data as $auction) {
        if (!empty($auction['provincial_reach'])) {
            foreach ($auction['provincial_reach'] as $province) {
                if (!empty($province) && $province !== 'National') {
                    $provinces[$province] = true;
                }
            }
        }
    }
    $result = array_keys($provinces);
    sort($result);
    return $result;
}

/**
 * Get all unique categories from auction data
 * 
 * @return array Sorted array of category names
 */
function getAllCategories() {
    $data = loadAuctionData();
    $categories = [];
    foreach ($data as $auction) {
        if (!empty($auction['categories_auctioned'])) {
            foreach ($auction['categories_auctioned'] as $cat) {
                if (!empty($cat)) {
                    // Normalize category to unified name
                    $normalized = normalizeCategory($cat);
                    $categories[$normalized] = true;
                }
            }
        }
    }
    $result = array_keys($categories);
    sort($result);
    return $result;
}

/**
 * Normalize a raw category name to unified master category
 * 
 * @param string $category Raw category name
 * @return string Unified category name
 */
function normalizeCategory($category) {
    $category = trim($category);
    
    // Vehicles
    $vehicles = ['Cars', 'Vehicles', 'Salvage Vehicles', 'Commercial Vehicles'];
    if (in_array($category, $vehicles)) {
        return 'Vehicles';
    }
    
    // Household & Furniture
    $household = ['Household Goods', 'Household Items', 'Furniture', 'Decor'];
    if (in_array($category, $household)) {
        return 'Household & Furniture';
    }
    
    // Industrial & Machinery
    $industrial = ['Industrial', 'Industrial Assets', 'Machinery'];
    if (in_array($category, $industrial)) {
        return 'Industrial & Machinery';
    }
    
    // Property
    $property = ['Property', 'Residential Property', 'Commercial Property'];
    if (in_array($category, $property)) {
        return 'Property';
    }
    
    // Salvage & Liquidation
    $salvage = ['Salvage', 'Salvage Assets', 'Liquidation'];
    if (in_array($category, $salvage)) {
        return 'Salvage & Liquidation';
    }
    
    // Art, Antiques & Collectibles
    $art = ['Art', 'Fine Art', 'Antiques', 'Collectibles', 'Medals', 'Maps', 'Jewellery', 'Wine', 'Rare Books', 'Numismatics'];
    if (in_array($category, $art)) {
        return 'Art, Antiques & Collectibles';
    }
    
    // Livestock
    if ($category === 'Livestock') {
        return 'Livestock';
    }
    
    // General Assets
    if ($category === 'General Assets' || $category === 'General') {
        return 'General Assets';
    }
    
    // Equipment
    if ($category === 'Equipment') {
        return 'Equipment';
    }
    
    // Return original if no mapping found
    return $category;
}

/**
 * Extract numeric premium value from string like "10-15%" or "10%"
 * Returns the average if a range, or the single value
 * 
 * @param string|null $premium Premium string
 * @return float Numeric premium percentage
 */
function parsePremium($premium) {
    if (empty($premium)) return 10; // Default
    
    // Remove % sign
    $clean = str_replace('%', '', $premium);
    
    // Check for range (e.g., "10-15")
    if (strpos($clean, '-') !== false) {
        $parts = explode('-', $clean);
        $low = floatval(trim($parts[0]));
        $high = floatval(trim($parts[1]));
        return ($low + $high) / 2;
    }
    
    return floatval(trim($clean));
}

/**
 * Parse years operating string to numeric value
 * 
 * @param string|null $years Years string like "50+" or "10+"
 * @return int Numeric years
 */
function parseYears($years) {
    if (empty($years)) return 0;
    return intval(str_replace('+', '', $years));
}

/**
 * Paginate an array of results
 * 
 * @param array $items All items
 * @param int $page Current page (1-based)
 * @param int $perPage Items per page
 * @return array ['items' => array, 'total' => int, 'pages' => int, 'current' => int]
 */
function paginate($items, $page = 1, $perPage = 9) {
    $total = count($items);
    $pages = max(1, ceil($total / $perPage));
    $page = max(1, min($page, $pages));
    $offset = ($page - 1) * $perPage;
    
    return [
        'items' => array_slice($items, $offset, $perPage),
        'total' => $total,
        'pages' => $pages,
        'current' => $page
    ];
}

/**
 * Sanitize output for HTML display
 * 
 * @param string $str Input string
 * @return string Escaped string safe for HTML output
 */
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Get the current page name for navigation highlighting
 * 
 * @return string Current page filename
 */
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}

/**
 * Generate pagination HTML
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param string $baseUrl Base URL for pagination links
 * @return string HTML pagination markup
 */
function renderPagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) return '';
    
    // Ensure baseUrl has proper query string separator
    $separator = (strpos($baseUrl, '?') !== false) ? '&' : '?';
    
    $html = '<nav class="flex justify-center mt-8" aria-label="Pagination">';
    $html .= '<div class="flex items-center gap-2">';
    
    // Previous button
    if ($currentPage > 1) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . ($currentPage - 1) . '" class="px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/20 text-slate-700 hover:bg-[#1F4E79] hover:text-white transition-all">';
        $html .= '<i data-lucide="chevron-left" class="w-4 h-4"></i></a>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=1" class="px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/20 text-slate-700 hover:bg-[#1F4E79] hover:text-white transition-all">1</a>';
        if ($start > 2) {
            $html .= '<span class="px-2 text-slate-400">...</span>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i === $currentPage) {
            $html .= '<span class="px-4 py-2 rounded-lg bg-[#1F4E79] text-white font-semibold">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $baseUrl . $separator . 'page=' . $i . '" class="px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/20 text-slate-700 hover:bg-[#1F4E79] hover:text-white transition-all">' . $i . '</a>';
        }
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<span class="px-2 text-slate-400">...</span>';
        }
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . $totalPages . '" class="px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/20 text-slate-700 hover:bg-[#1F4E79] hover:text-white transition-all">' . $totalPages . '</a>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . ($currentPage + 1) . '" class="px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/20 text-slate-700 hover:bg-[#1F4E79] hover:text-white transition-all">';
        $html .= '<i data-lucide="chevron-right" class="w-4 h-4"></i></a>';
    }
    
    $html .= '</div></nav>';
    
    return $html;
}
