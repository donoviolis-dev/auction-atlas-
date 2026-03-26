<?php
/**
 * Auction Atlas - 404 Error Page
 * Displays when a page is not found
 */

$pageTitle = 'Page Not Found';
$robotsMeta = 'noindex, nofollow';

// Get the requested URL for display
$requestedUrl = $_SERVER['REQUEST_URI'] ?? 'Unknown';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800">
    <div class="text-center px-4">
        <!-- 404 Icon -->
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-heading font-bold text-white mb-4">404</h1>
        <h2 class="text-2xl font-heading font-semibold text-white mb-4">Page Not Found</h2>
        <p class="text-slate-400 mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved.
        </p>
        
        <!-- Search Box -->
        <form action="index.html" method="get" class="max-w-md mx-auto mb-8">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search auctions..." 
                    class="flex-1 px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-primary"
                >
                <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                    Search
                </button>
            </div>
        </form>
        
        <!-- Quick Links -->
        <div class="flex flex-wrap justify-center gap-4">
            <a href="index.html" class="px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/90 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Calendar
            </a>
            <a href="directory.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Directory
            </a>
            <a href="compare.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Compare
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
