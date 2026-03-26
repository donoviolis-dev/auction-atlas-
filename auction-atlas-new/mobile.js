/**
 * Auction Atlas - Mobile Interactions & PWA
 * 
 * Mobile-first interactions including:
 * - Service worker registration
 * - Bottom navigation
 * - Pull-to-refresh
 * - Touch gestures
 * - Bottom sheets
 * - Toast notifications
 * 
 * @version 1.0
 */

// Mobile detection and setup
(function() {
    'use strict';
    
    const isMobile = () => window.innerWidth <= 768;
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
    
    // Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', async () => {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                console.log('SW registered:', registration.scope);
                
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            showToast('New version available! Refresh to update.', 'info');
                        }
                    });
                });
            } catch (error) {
                console.log('SW registration failed:', error);
            }
        });
    }
    
    // Bottom Navigation
    function initBottomNav() {
        if (!isMobile()) return;
        
        const nav = document.querySelector('.mobile-bottom-nav');
        if (!nav) return;
        
        // Set active state based on current page
        const currentPath = window.location.pathname;
        const navLinks = nav.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath || 
                (href !== '/' && currentPath.includes(href.replace('.php', '').replace('.html', '')))) {
                link.classList.add('active');
            }
            
            // Touch feedback
            link.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.95)';
            });
            
            link.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Hide nav on scroll down, show on scroll up
        let lastScroll = 0;
        const scrollThreshold = 50;
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                nav.style.transform = 'translateY(0)';
                return;
            }
            
            if (currentScroll > lastScroll && currentScroll > scrollThreshold) {
                // Scroll down - hide nav
                nav.style.transform = 'translateY(100%)';
            } else if (currentScroll < lastScroll - scrollThreshold) {
                // Scroll up - show nav
                nav.style.transform = 'translateY(0)';
            }
            
            lastScroll = currentScroll;
        }, { passive: true });
    }
    
    // Pull to Refresh
    function initPullToRefresh() {
        if (!isMobile() || !('ontouchstart' in window)) return;
        
        const body = document.body;
        let startY = 0;
        let currentY = 0;
        let pulling = false;
        const pullThreshold = 80;
        
        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pulling = true;
            }
        }, { passive: true });
        
        document.addEventListener('touchmove', (e) => {
            if (!pulling || window.scrollY > 0) return;
            
            currentY = e.touches[0].clientY;
            const diff = currentY - startY;
            
            if (diff > 0 && diff < pullThreshold * 2) {
                body.style.setProperty('--pull-distance', `${diff}px`);
                body.classList.add('pulling');
            }
        }, { passive: true });
        
        document.addEventListener('touchend', () => {
            if (!pulling) return;
            pulling = false;
            
            if (currentY - startY > pullThreshold) {
                // Trigger refresh
                body.classList.add('refreshing');
                showToast('Refreshing...', 'info');
                
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
            
            body.classList.remove('pulling');
            body.style.removeProperty('--pull-distance');
        }, { passive: true });
    }
    
    // Bottom Sheet
    function initBottomSheets() {
        if (!isMobile()) return;
        
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-bottom-sheet]');
            if (!trigger) return;
            
            const sheetId = trigger.dataset.bottomSheet;
            const sheet = document.getElementById(sheetId);
            if (!sheet) return;
            
            openBottomSheet(sheet);
        });
        
        // Close on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('bottom-sheet-backdrop')) {
                closeBottomSheet(e.target.closest('.bottom-sheet'));
            }
        });
        
        // Close on swipe down
        document.querySelectorAll('.bottom-sheet').forEach(sheet => {
            let startY = 0;
            let currentY = 0;
            
            sheet.addEventListener('touchstart', (e) => {
                startY = e.touches[0].clientY;
            }, { passive: true });
            
            sheet.addEventListener('touchmove', (e) => {
                currentY = e.touches[0].clientY;
                const diff = currentY - startY;
                
                if (diff > 0 && window.scrollY === 0) {
                    sheet.style.transform = `translateY(${diff}px)`;
                }
            }, { passive: true });
            
            sheet.addEventListener('touchend', () => {
                if (currentY - startY > 150) {
                    closeBottomSheet(sheet);
                } else {
                    sheet.style.transform = '';
                }
            }, { passive: true });
        });
    }
    
    function openBottomSheet(sheet) {
        sheet.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeBottomSheet(sheet) {
        sheet.classList.remove('active');
        document.body.style.overflow = '';
        sheet.style.transform = '';
    }
    
    // Toast Notifications
    const toastContainer = createToastContainer();
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }
    
    window.showToast = function(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });
        
        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };
    
    // Floating Action Button
    function initFAB() {
        if (!isMobile()) return;
        
        const fab = document.querySelector('.mobile-fab');
        if (!fab) return;
        
        // Scroll hide/show
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > lastScroll && currentScroll > 200) {
                fab.style.transform = 'scale(0)';
            } else {
                fab.style.transform = 'scale(1)';
            }
            
            lastScroll = currentScroll;
        }, { passive: true });
        
        // Touch ripple effect
        fab.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.className = 'fab-ripple';
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            
            ripple.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${e.clientX - rect.left - size/2}px;
                top: ${e.clientY - rect.top - size/2}px;
            `;
            
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    }
    
    // Skeleton Loading
    window.showSkeleton = function(element, template) {
        element.innerHTML = template;
        element.classList.add('skeleton-loading');
    };
    
    window.hideSkeleton = function(element) {
        element.classList.remove('skeleton-loading');
    };
    
    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        initBottomNav();
        initPullToRefresh();
        initBottomSheets();
        initFAB();
        
        // Add CSS for toast and animations
        addMobileStyles();
    });
    
    function addMobileStyles() {
        if (document.getElementById('mobile-dynamic-styles')) return;
        
        const styles = document.createElement('style');
        styles.id = 'mobile-dynamic-styles';
        styles.textContent = `
            /* Toast Container */
            .toast-container {
                position: fixed;
                bottom: 80px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 8px;
                max-width: 90%;
                width: 400px;
            }
            
            .toast {
                background: #1a1a2e;
                color: white;
                padding: 14px 16px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                transform: translateY(100px);
                opacity: 0;
                transition: all 0.3s ease;
            }
            
            .toast.show {
                transform: translateY(0);
                opacity: 1;
            }
            
            .toast-info { background: #1F4E79; }
            .toast-success { background: #10b981; }
            .toast-warning { background: #f59e0b; }
            .toast-error { background: #ef4444; }
            
            .toast-message {
                flex: 1;
                font-size: 14px;
                font-weight: 500;
            }
            
            .toast-close {
                background: none;
                border: none;
                color: white;
                opacity: 0.7;
                cursor: pointer;
                padding: 4px;
                display: flex;
                transition: opacity 0.2s;
            }
            
            .toast-close:hover { opacity: 1; }
            
            /* Pull to Refresh */
            body.pulling::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: var(--pull-distance);
                background: linear-gradient(180deg, rgba(31,78,121,0.1) 0%, transparent 100%);
                z-index: 9998;
                pointer-events: none;
            }
            
            /* FAB Ripple */
            .mobile-fab {
                position: relative;
                overflow: hidden;
            }
            
            .fab-ripple {
                position: absolute;
                background: rgba(255,255,255,0.4);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            /* Skeleton Loading */
            .skeleton-loading {
                position: relative;
                overflow: hidden;
            }
            
            .skeleton-loading::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(
                    90deg,
                    transparent 0%,
                    rgba(255,255,255,0.4) 50%,
                    transparent 100%
                );
                animation: shimmer 1.5s infinite;
            }
            
            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }
            
            /* Bottom Sheet */
            .bottom-sheet-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 9990;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .bottom-sheet {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-radius: 24px 24px 0 0;
                z-index: 9991;
                transform: translateY(100%);
                transition: transform 0.3s ease;
                max-height: 90vh;
                overflow-y: auto;
            }
            
            .bottom-sheet.active {
                transform: translateY(0);
            }
            
            .bottom-sheet.active + .bottom-sheet-backdrop {
                opacity: 1;
                visibility: visible;
            }
            
            .bottom-sheet-handle {
                width: 40px;
                height: 4px;
                background: #ddd;
                border-radius: 2px;
                margin: 12px auto;
            }
            
            /* Mobile Bottom Nav Animation */
            .mobile-bottom-nav {
                transition: transform 0.3s ease;
            }
            
            /* Mobile Sticky Elements */
            .mobile-sticky-search {
                position: sticky;
                top: 56px;
                z-index: 100;
                background: white;
                padding: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
        `;
        
        document.head.appendChild(styles);
    }
})();
