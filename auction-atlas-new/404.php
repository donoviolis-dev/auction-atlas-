<?php
/**
 * Auction Atlas - 404 Not Found
 * 
 * Custom 404 error page for missing pages.
 */

$pageTitle = 'Page Not Found';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-slate-900 via-primary to-slate-900 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-accent rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-highlight rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>
    
    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <!-- 404 Icon -->
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <!-- Heading -->
        <h1 class="font-heading font-extrabold text-6xl md:text-8xl text-white mb-6">
            404
        </h1>
        <p class="text-2xl md:text-3xl font-heading font-bold text-white/90 mb-4">
            Page Not Found
        </p>
        <p class="text-lg text-slate-300 mb-8 max-w-xl mx-auto">
            Oops! The page you're looking for doesn't exist or has been moved.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="index.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
            <a href="directory.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Browse Directory
            </a>
            <a href="compare.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Compare
            </a>
        </div>
    </div>
</section>

<!-- Suggested Pages -->
<section class="py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-heading font-bold text-2xl text-slate-800 text-center mb-8">
            Explore Our Platform
        </h2>
        
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Directory -->
            <a href="directory.php" class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow group">
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-800 mb-2">Auction Directory</h3>
                <p class="text-slate-600 text-sm">Browse verified auction houses across South Africa</p>
            </a>
            
            <!-- Match -->
            <a href="match.php" class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow group">
                <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-accent/20 transition-colors">
                    <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-800 mb-2">Find Your Match</h3>
                <p class="text-slate-600 text-sm">Get matched with the perfect auction house for your needs</p>
            </a>
            
            <!-- Risk Scanner -->
            <a href="risk-scanner.php" class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow group">
                <div class="w-12 h-12 bg-warning/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-warning/20 transition-colors">
                    <svg class="w-6 h-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-800 mb-2">Risk Scanner</h3>
                <p class="text-slate-600 text-sm">Check auction houses for red flags and compliance</p>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
