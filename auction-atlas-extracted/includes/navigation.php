<?php
/**
 * Auction Atlas - Navigation Component
 * 
 * Generates the navigation menu items used by header.php
 * Supports active page highlighting and dropdown menus.
 */

/**
 * Get navigation menu items
 * 
 * @return array Navigation items with label, url, icon, and dropdown items
 */
function getNavItems() {
    return [
        ['label' => 'Directory', 'url' => 'directory.php', 'icon' => 'layout-grid'],
        ['label' => 'Compare', 'url' => 'compare.php', 'icon' => 'git-compare'],
        ['label' => 'Match', 'url' => 'match.php', 'icon' => 'target'],
        [
            'label' => 'Tools',
            'url' => '#',
            'icon' => 'wrench',
            'dropdown' => [
                ['label' => 'Risk Scanner', 'url' => 'risk-scanner.php', 'icon' => 'shield-alert'],
                ['label' => 'Fee Calculator', 'url' => 'fee-calculator.php', 'icon' => 'calculator'],
                ['label' => 'Strategy Simulator', 'url' => 'strategy-simulator.php', 'icon' => 'brain']
            ]
        ],
        [
            'label' => 'Learn',
            'url' => '#',
            'icon' => 'graduation-cap',
            'dropdown' => [
                ['label' => 'Education', 'url' => 'education.php', 'icon' => 'book-open'],
                ['label' => 'Prep Checklist', 'url' => 'prep-check.php', 'icon' => 'clipboard-check'],
                ['label' => 'Scam Awareness', 'url' => 'scam-awareness.php', 'icon' => 'shield'],
                ['label' => 'Insights', 'url' => 'blog.php', 'icon' => 'newspaper']
            ]
        ]
    ];
}

/**
 * Check if a nav item is the current active page
 * 
 * @param string $url Nav item URL
 * @return bool True if this is the current page
 */
function isActivePage($url) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $url;
}
