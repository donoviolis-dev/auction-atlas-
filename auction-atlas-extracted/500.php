<?php
/**
 * Auction Atlas - 500 Error Page
 * Displays when a server error occurs
 */

$pageTitle = 'Server Error';
$robotsMeta = 'noindex, nofollow';

// Log the error
$errorMsg = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 'Unknown';
error_log("500 Error hit - Status: " . $errorMsg);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center justify-center bg-gradient-to-br from-red-900 to-slate-900">
    <div class="text-center px-4">
        <!-- Error Icon -->
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-heading font-bold text-white mb-4">500</h1>
        <h2 class="text-2xl font-heading font-semibold text-white mb-4">Server Error</h2>
        <p class="text-slate-400 mb-8 max-w-md mx-auto">
            Something went wrong on our end. Our team has been notified and is working to fix the issue.
        </p>
        
        <!-- Quick Links -->
        <div class="flex flex-wrap justify-center gap-4">
            <a href="index.html" class="px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/90 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
            <button onclick="window.location.reload()" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
            <a href="mailto:support@auction-atlas.co.za" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contact Support
            </a>
        </div>
        
        <p class="text-slate-500 text-sm mt-8">
            If this problem persists, please email us at support@auction-atlas.co.za
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
