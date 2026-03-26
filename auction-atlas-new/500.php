<?php
/**
 * Auction Atlas - 500 Internal Server Error
 * 
 * Custom 500 error page for server errors.
 */

$pageTitle = 'Server Error';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-slate-900 via-primary to-slate-900 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-warning rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>
    
    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <!-- 500 Icon -->
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <!-- Heading -->
        <h1 class="font-heading font-extrabold text-6xl md:text-8xl text-white mb-6">
            500
        </h1>
        <p class="text-2xl md:text-3xl font-heading font-bold text-white/90 mb-4">
            Server Error
        </p>
        <p class="text-lg text-slate-300 mb-8 max-w-xl mx-auto">
            Something went wrong on our end. We're working to fix the issue.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="index.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
            <button onclick="location.reload()" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
            <a href="directory.php" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Browse Directory
            </a>
        </div>
    </div>
</section>

<!-- Help Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-heading font-bold text-2xl text-slate-800 mb-4">
            Need Immediate Assistance?
        </h2>
        <p class="text-slate-600 mb-8">
            If this error persists, please contact us or try again later.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="education.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Learn About Auctions
            </a>
            <a href="fee-calculator.php" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary border border-primary/20 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Fee Calculator
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
