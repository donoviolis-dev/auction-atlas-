/**
 * Auction Atlas - Service Worker
 * 
 * Provides offline functionality and faster loading
 * through caching and pre-fetching.
 * 
 * @version 2.0
 */

const CACHE_NAME = 'auction-atlas-v2';
const OFFLINE_URL = '/offline.html';

// Assets to cache immediately on install
const PRECACHE_ASSETS = [
    '/',
    '/index.php',
    '/manifest.json',
    '/styles.css',
    '/styles-mobile.css',
    '/assets/icons/auction-atlas.svg',
    '/assets/icons/favicon.svg'
];

// Install event - cache core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Caching core assets...');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip cross-origin requests
    if (url.origin !== location.origin) return;

    // Network-first strategy for HTML pages (PHP)
    if (request.headers.get('accept')?.includes('text/html') || request.url.endsWith('.php')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone and cache the response
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // Fallback to cache or offline page
                    return caches.match(request)
                        .then((cachedResponse) => {
                            return cachedResponse || caches.match(OFFLINE_URL);
                        });
                })
        );
        return;
    }

    // Cache-first strategy for static assets
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    ) {
        event.respondWith(
            caches.match(request)
                .then((cachedResponse) => {
                    if (cachedResponse) {
                        // Return cached response and update in background
                        fetch(request).then((response) => {
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(request, response);
                            });
                        });
                        return cachedResponse;
                    }
                    // Not in cache, fetch and cache
                    return fetch(request).then((response) => {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                        return response;
                    });
                })
        );
        return;
    }

    // Default: network with cache fallback
    event.respondWith(
        fetch(request)
            .catch(() => caches.match(request))
    );
});
