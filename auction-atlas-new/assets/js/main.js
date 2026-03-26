/**
 * Auction Atlas - Main JavaScript
 * 
 * Handles mobile menu toggle, animated counters,
 * and general UI interactions.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Mobile Menu Toggle =====
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIconOpen = document.getElementById('menu-icon-open');
    const menuIconClose = document.getElementById('menu-icon-close');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');
            
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                if (menuIconOpen) menuIconOpen.classList.remove('hidden');
                if (menuIconClose) menuIconClose.classList.add('hidden');
            } else {
                mobileMenu.classList.remove('hidden');
                if (menuIconOpen) menuIconOpen.classList.add('hidden');
                if (menuIconClose) menuIconClose.classList.remove('hidden');
            }
        });
    }
    
    // ===== Mobile Accordion Toggle =====
    const accordionTriggers = document.querySelectorAll('.mobile-accordion-trigger');
    accordionTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            const accordion = this.closest('.mobile-accordion');
            const content = accordion.querySelector('.mobile-accordion-content');
            const arrow = this.querySelector('.accordion-arrow');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
            } else {
                content.classList.add('hidden');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    });
    
    // ===== Animated Number Counters =====
    function animateCounter(element, target, duration) {
        if (!element) return;
        
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format based on data attribute
            const format = element.dataset.format || 'number';
            if (format === 'percent') {
                element.textContent = Math.round(current) + '%';
            } else if (format === 'decimal') {
                element.textContent = current.toFixed(1);
            } else {
                element.textContent = Math.round(current);
            }
        }, 16);
    }
    
    // Initialize counters when they come into view
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length > 0) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseFloat(el.dataset.counter);
                    animateCounter(el, target, 1500);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(function(counter) {
            observer.observe(counter);
        });
    }
    
    // ===== Fade-in on Scroll =====
    const fadeElements = document.querySelectorAll('.fade-on-scroll');
    if (fadeElements.length > 0) {
        const fadeObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        fadeElements.forEach(function(el) {
            el.style.opacity = '0';
            fadeObserver.observe(el);
        });
    }
    
    // ===== Risk Bar Animation =====
    const riskBars = document.querySelectorAll('[data-risk-width]');
    if (riskBars.length > 0) {
        const barObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const width = bar.dataset.riskWidth;
                    bar.style.width = width + '%';
                    barObserver.unobserve(bar);
                }
            });
        }, { threshold: 0.3 });
        
        riskBars.forEach(function(bar) {
            bar.style.width = '0%';
            barObserver.observe(bar);
        });
    }
    
    // ===== Smooth Scroll for Anchor Links =====
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // ===== Compare Checkbox Handler =====
    const compareForm = document.getElementById('compare-select-form');
    if (compareForm) {
        const checkboxes = compareForm.querySelectorAll('input[type="checkbox"]');
        const compareBtn = document.getElementById('compare-btn');
        
        function updateCompareButton() {
            const checked = compareForm.querySelectorAll('input[type="checkbox"]:checked');
            if (compareBtn) {
                if (checked.length >= 2 && checked.length <= 4) {
                    compareBtn.disabled = false;
                    compareBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    const ids = Array.from(checked).map(function(cb) { return cb.value; }).join(',');
                    compareBtn.href = 'compare.php?ids=' + ids;
                } else {
                    compareBtn.disabled = true;
                    compareBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    compareBtn.href = '#';
                }
            }
        }
        
        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', updateCompareButton);
        });
        
        updateCompareButton();
    }
    
    // ===== Re-initialize Lucide icons after dynamic content =====
    window.reinitIcons = function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };
});
