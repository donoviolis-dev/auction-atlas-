<!-- Mobile Bottom Navigation Component -->
<!-- Include this in all pages that need mobile navigation -->

<nav class="mobile-bottom-nav" role="navigation" aria-label="Main navigation">
    <a href="/index.html" class="nav-link" aria-label="Home">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span class="nav-label">Home</span>
    </a>
    
    <a href="/directory.php" class="nav-link" aria-label="Browse Auctions">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
            <path d="M8 11h6"/>
            <path d="M11 8v6"/>
        </svg>
        <span class="nav-label">Search</span>
    </a>
    
    <a href="/calendar.html" class="nav-link" aria-label="Calendar">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span class="nav-label">Calendar</span>
    </a>
    
    <a href="/directory.php?type=vehicles" class="nav-link" aria-label="Vehicles">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-3.6a1 1 0 0 0-.8-.4H5.24a2 2 0 0 0-1.8 1.1l-.8 1.63A6 6 0 0 0 2 12.42V16h2"/>
            <circle cx="6.5" cy="16.5" r="2.5"/>
            <circle cx="16.5" cy="16.5" r="2.5"/>
        </svg>
        <span class="nav-label">Vehicles</span>
    </a>
    
    <a href="/directory.php?type=property" class="nav-link" aria-label="Property">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span class="nav-label">Property</span>
    </a>
</nav>

<!-- Mobile Top Bar Component -->
<header class="mobile-top-bar">
    <button class="mobile-menu-btn" aria-label="Menu" onclick="document.body.classList.toggle('mobile-menu-open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
    
    <a href="/index.php" class="mobile-logo">
        <img src="/auction-atlas-logo.png" alt="Auction Atlas" width="32" height="32">
    </a>
    
    <button class="mobile-search-btn" aria-label="Search" onclick="window.location.href='/directory.php'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
        </svg>
    </button>
</header>

<!-- Floating Action Button -->
<a href="/directory.php?filter=new" class="mobile-fab" aria-label="Add Listing">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 5v14M5 12h14"/>
    </svg>
</a>
