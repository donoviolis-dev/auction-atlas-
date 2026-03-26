/**
 * Auction Atlas - Calendar Application
 * Loads auction data from CSV and displays in FullCalendar
 * Features: Heatmap, Top Auction Banner, Trending Sources Chart
 */

// ==========================================
// Configuration
// ==========================================

const CONFIG = {
    csvFile: 'Upcoming Auctions.csv',
    defaultView: 'dayGridMonth',
    dateFormat: 'iso',
};

// Source color mapping
const SOURCE_COLORS = {
    'high street auction': { bg: '#10b981', class: 'event-highstreet' },
    'gobid': { bg: '#f59e0b', class: 'event-gobid' },
    'claremart auctions': { bg: '#ec4899', class: 'event-claremart' },
    'bidders choice': { bg: '#8b5cf6', class: 'event-bidders' },
    'aucor': { bg: '#3b82f6', class: 'event-aucor' },
    'aucor auctioneers': { bg: '#3b82f6', class: 'event-aucor' },
    'sa auction group': { bg: '#f97316', class: 'event-saauction' },
    'nuco': { bg: '#06b6d4', class: 'event-nuco' },
};

// Chart colors
const CHART_COLORS = [
    '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#3b82f6', '#f97316', '#06b6d4'
];

// ==========================================
// Global State
// ==========================================

let calendar;
let allEvents = [];
let trendingChart = null;

// ==========================================
// Utility Functions
// ==========================================

/**
 * Get color info for a source
 */
function getSourceColor(source) {
    if (!source) return { bg: '#64748b', class: 'event-default' };
    
    const key = source.toLowerCase();
    for (const [sourceKey, color] of Object.entries(SOURCE_COLORS)) {
        if (key.includes(sourceKey)) {
            return color;
        }
    }
    return { bg: '#64748b', class: 'event-default' };
}

/**
 * Parse date from CSV - handles multiple formats
 */
function parseDate(dateStr) {
    if (!dateStr) return null;
    
    // Try ISO format first (YYYY-MM-DD)
    const isoMatch = dateStr.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    if (isoMatch) {
        const date = new Date(isoMatch[1], isoMatch[2] - 1, isoMatch[3]);
        if (!isNaN(date.getTime())) return date;
    }
    
    // Try DD MMMM YYYY format (e.g., "26 March 2026")
    const months = ['january', 'february', 'march', 'april', 'may', 'june', 
                   'july', 'august', 'september', 'october', 'november', 'december'];
    
    const parsedMatch = dateStr.match(/(\d{1,2})\s+(\w+)\s+(\d{4})/i);
    if (parsedMatch) {
        const day = parseInt(parsedMatch[1]);
        const monthIndex = months.findIndex(m => parsedMatch[2].toLowerCase().startsWith(m));
        const year = parseInt(parsedMatch[3]);
        
        if (monthIndex >= 0) {
            const date = new Date(year, monthIndex, day);
            if (!isNaN(date.getTime())) return date;
        }
    }
    
    // Try standard Date parsing as fallback
    const date = new Date(dateStr);
    if (!isNaN(date.getTime())) return date;
    
    return null;
}

/**
 * Extract time from location string
 */
function extractTime(location) {
    if (!location) return '';
    
    const timeMatch = location.match(/(\d{1,2}:\d{2}\s*(?:am|pm)?)/i);
    return timeMatch ? timeMatch[1].toUpperCase() : '';
}

/**
 * Clean location string
 */
function cleanLocation(location) {
    if (!location) return 'Unconfirmed';
    
    // Remove time from location
    let cleaned = location.replace(/\|\s*\d{1,2}:\d{2}\s*(?:am|pm)?/i, '');
    
    // Clean up extra spaces
    cleaned = cleaned.replace(/\s+/g, ' ').trim();
    
    return cleaned || 'Unconfirmed';
}

/**
 * Format date for display
 */
function formatDate(date) {
    if (!date) return 'Date TBD';
    
    return date.toLocaleDateString('en-ZA', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// ==========================================
// Data Loading
// ==========================================

/**
 * Load and parse CSV data
 */
async function loadCSVData() {
    return new Promise((resolve, reject) => {
        showLoading();
        
        Papa.parse(CONFIG.csvFile, {
            header: true,
            skipEmptyLines: true,
            complete: function(results) {
                if (results.errors.length > 0) {
                    console.warn('CSV Parse Warnings:', results.errors);
                }
                
                const events = results.data
                    .filter(row => row['Auction Date'] || row['date'])
                    .map(row => {
                        const dateStr = row['Auction Date'] || row['date'];
                        const date = parseDate(dateStr);
                        
                        if (!date) return null;
                        
                        const title = row['Auction Title'] || row['title'] || 'Untitled Auction';
                        const source = row['Auction House Name'] || row['source'] || 'Unknown';
                        const location = row['Location'] || row['location'] || '';
                        const time = extractTime(location);
                        
                        const color = getSourceColor(source);
                        
                        return {
                            id: `${source}-${dateStr}-${Math.random().toString(36).substr(2, 9)}`,
                            title: title,
                            start: date,
                            backgroundColor: color.bg,
                            borderColor: color.bg,
                            classNames: [color.class],
                            extendedProps: {
                                location: cleanLocation(location),
                                locationRaw: location,
                                source: source,
                                time: time,
                                url: row['url'] || ''
                            }
                        };
                    })
                    .filter(e => e !== null);
                
                resolve(events);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

// ==========================================
// Heatmap Feature
// ==========================================

/**
 * Calculate auctions per day for heatmap
 */
function calculateAuctionsPerDay(events) {
    const auctionsPerDay = {};
    
    events.forEach(event => {
        const dateStr = event.start.toISOString().split('T')[0];
        
        if (!auctionsPerDay[dateStr]) {
            auctionsPerDay[dateStr] = 0;
        }
        
        auctionsPerDay[dateStr]++;
    });
    
    return auctionsPerDay;
}

/**
 * Apply heatmap styling to calendar days
 */
function applyHeatmap(auctionsPerDay, maxCount) {
    if (!calendar) return;
    
    // Get all day cells
    const dayCells = calendar.el.querySelectorAll('.fc-daygrid-day');
    
    dayCells.forEach(cell => {
        const dateEl = cell.querySelector('.fc-daygrid-day-number');
        if (!dateEl) return;
        
        const date = cell.getAttribute('data-date');
        if (!date) return;
        
        const count = auctionsPerDay[date] || 0;
        
        // Remove existing heatmap classes
        cell.classList.remove('heatmap-low', 'heatmap-medium', 'heatmap-high', 'heatmap-very-high');
        
        if (count > 0) {
            // Calculate intensity based on max count in view
            const intensity = Math.min(count / Math.max(maxCount, 3), 1);
            
            let heatClass = 'heatmap-low';
            if (intensity > 0.75) heatClass = 'heatmap-very-high';
            else if (intensity > 0.5) heatClass = 'heatmap-high';
            else if (intensity > 0.25) heatClass = 'heatmap-medium';
            
            cell.classList.add(heatClass);
            
            // Add tooltip
            cell.title = `${count} auction${count > 1 ? 's' : ''} on this day`;
        }
    });
}

// ==========================================
// Top Auction Feature
// ==========================================

/**
 * Keywords that indicate important auctions
 */
const IMPORTANT_KEYWORDS = [
    'liquidation', 'fleet', 'warehouse', 'government', 'industrial', 
    'commercial', 'property', 'farm', 'mining', 'multiple'
];

/**
 * Calculate importance score for an auction
 */
function calculateImportanceScore(event) {
    const title = event.title.toLowerCase();
    const props = event.extendedProps;
    
    let score = 0;
    
    // Boost for important keywords
    IMPORTANT_KEYWORDS.forEach(keyword => {
        if (title.includes(keyword)) {
            score += 10;
        }
    });
    
    // Boost for longer titles (more detail = likely important)
    score += Math.min(event.title.length / 10, 5);
    
    // Boost for properties (usually higher value)
    if (title.includes('property') || title.includes('farm')) {
        score += 5;
    }
    
    // Boost if has location (not unconfirmed)
    if (props.location && props.location !== 'Unconfirmed') {
        score += 2;
    }
    
    return score;
}

/**
 * Get the top auction for the next 7 days
 */
function getTopAuction(events) {
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    
    const nextWeek = new Date(now);
    nextWeek.setDate(now.getDate() + 7);
    
    // Filter to upcoming events in next 7 days
    const upcoming = events.filter(e => {
        const d = new Date(e.start);
        d.setHours(0, 0, 0, 0);
        return d >= now && d <= nextWeek;
    });
    
    if (upcoming.length === 0) {
        // If no events this week, get next upcoming
        const sorted = events.sort((a, b) => new Date(a.start) - new Date(b.start));
        return sorted[0] || null;
    }
    
    // Sort by importance score
    upcoming.sort((a, b) => {
        return calculateImportanceScore(b) - calculateImportanceScore(a);
    });
    
    return upcoming[0];
}

/**
 * Display the top auction banner
 */
function displayTopAuction(event) {
    const banner = document.getElementById('topAuctionBanner');
    const titleEl = document.getElementById('topAuctionTitle');
    const locationEl = document.getElementById('topAuctionLocation');
    const dateEl = document.getElementById('topAuctionDate');
    const linkEl = document.getElementById('topAuctionLink');
    
    if (!event) {
        banner.classList.add('hidden');
        return;
    }
    
    const props = event.extendedProps;
    
    titleEl.textContent = event.title;
    
    locationEl.innerHTML = `
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>
        ${props.location || 'Unconfirmed'}
    `;
    
    dateEl.innerHTML = `
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        ${formatDate(event.start)}
    `;
    
    if (props.url) {
        linkEl.href = props.url;
        linkEl.style.display = 'flex';
    } else {
        linkEl.style.display = 'none';
    }
    
    banner.classList.remove('hidden');
}

// ==========================================
// Trending Sources Feature
// ==========================================

/**
 * Calculate trending auction sources
 */
function calculateTrendingSources(events) {
    const sourceCount = {};
    
    events.forEach(event => {
        const source = event.extendedProps.source || 'Unknown';
        
        if (!sourceCount[source]) {
            sourceCount[source] = 0;
        }
        
        sourceCount[source]++;
    });
    
    // Sort by count and get top 5
    const sorted = Object.entries(sourceCount)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);
    
    return sorted;
}

/**
 * Render trending sources chart
 */
function renderTrendingChart(trendingData) {
    const ctx = document.getElementById('trendingChart');
    if (!ctx) return;
    
    // Destroy existing chart if any
    if (trendingChart) {
        trendingChart.destroy();
    }
    
    const labels = trendingData.map(item => item[0]);
    const data = trendingData.map(item => item[1]);
    
    // Get colors for each source
    const colors = labels.map((label, index) => {
        const color = getSourceColor(label);
        return color.bg;
    });
    
    trendingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Auctions',
                data: data,
                backgroundColor: colors,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.raw} auction${context.raw > 1 ? 's' : ''}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    grid: {
                        color: '#334155'
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 10
                        },
                        stepSize: 1
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

// ==========================================
// Filter & Search
// ==========================================

/**
 * Extract unique locations for filter
 */
function extractLocations(events) {
    const locations = new Set();
    
    events.forEach(event => {
        const loc = event.extendedProps.location;
        if (loc && loc !== 'Unconfirmed') {
            // Split by common delimiters
            const parts = loc.split(/[,|]/);
            parts.forEach(part => {
                const trimmed = part.trim();
                if (trimmed && trimmed.length > 2) {
                    locations.add(trimmed);
                }
            });
        }
    });
    
    return Array.from(locations).sort();
}

/**
 * Initialize location filter dropdown
 */
function initLocationFilter(locations) {
    const select = document.getElementById('locationFilter');
    select.innerHTML = '<option value="">All Locations</option>';
    
    locations.forEach(loc => {
        const option = document.createElement('option');
        option.value = loc;
        option.textContent = loc;
        select.appendChild(option);
    });
}

/**
 * Filter events based on current filters
 */
function filterEvents() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const source = document.getElementById('sourceFilter').value;
    const location = document.getElementById('locationFilter').value;
    
    return allEvents.filter(event => {
        const props = event.extendedProps;
        
        // Search filter
        if (search) {
            const titleMatch = event.title.toLowerCase().includes(search);
            const locMatch = props.location?.toLowerCase().includes(search);
            if (!titleMatch && !locMatch) return false;
        }
        
        // Source filter
        if (source && !props.source?.toLowerCase().includes(source.toLowerCase())) {
            return false;
        }
        
        // Location filter
        if (location && !props.location?.includes(location)) {
            return false;
        }
        
        return true;
    });
}

/**
 * Update calendar with filtered events
 */
function refreshCalendar() {
    const filtered = filterEvents();
    
    calendar.removeAllEvents();
    calendar.addEventSource(filtered);
    
    updateStats(filtered);
    
    // Update insights
    const auctionsPerDay = calculateAuctionsPerDay(filtered);
    const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
    applyHeatmap(auctionsPerDay, maxCount);
    
    const topAuction = getTopAuction(filtered);
    displayTopAuction(topAuction);
    
    const trending = calculateTrendingSources(filtered);
    renderTrendingChart(trending);
}

/**
 * Update statistics display
 */
function updateStats(events) {
    const sources = new Set();
    
    events.forEach(e => {
        if (e.extendedProps.source) sources.add(e.extendedProps.source);
    });
    
    document.getElementById('totalAuctions').textContent = events.length;
    document.getElementById('totalSources').textContent = sources.size;
}

// ==========================================
// Modal
// ==========================================

/**
 * Show event modal
 */
function showModal(event) {
    const overlay = document.getElementById('modalOverlay');
    const props = event.extendedProps;
    const color = getSourceColor(props.source);
    
    // Set source badge
    const sourceBadge = document.getElementById('modalSource');
    sourceBadge.textContent = props.source || 'Unknown';
    sourceBadge.style.backgroundColor = color.bg;
    
    // Set title
    document.getElementById('modalTitle').textContent = event.title;
    
    // Set date
    const timeStr = props.time ? ` at ${props.time}` : '';
    document.getElementById('modalDate').textContent = formatDate(event.start) + timeStr;
    
    // Set location
    document.getElementById('modalLocation').textContent = props.location || 'Unconfirmed';
    
    // Set link
    const linkBtn = document.getElementById('modalLink');
    if (props.url) {
        linkBtn.href = props.url;
        linkBtn.style.display = 'flex';
    } else {
        linkBtn.style.display = 'none';
    }
    
    // Show modal
    overlay.classList.add('active');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

/**
 * Hide event modal
 */
function hideModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.remove('active');
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// ==========================================
// Calendar Initialization
// ==========================================

/**
 * Initialize FullCalendar
 */
async function initCalendar() {
    try {
        // Load CSV data
        allEvents = await loadCSVData();
        
        // Initialize location filter
        const locations = extractLocations(allEvents);
        initLocationFilter(locations);
        
        // Update stats
        updateStats(allEvents);
        
        // Calculate heatmap data
        const auctionsPerDay = calculateAuctionsPerDay(allEvents);
        const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
        
        // Get top auction
        const topAuction = getTopAuction(allEvents);
        displayTopAuction(topAuction);
        
        // Get trending sources
        const trending = calculateTrendingSources(allEvents);
        renderTrendingChart(trending);
        
        // Create calendar
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: CONFIG.defaultView,
            headerToolbar: false,
            events: allEvents,
            eventClick: function(info) {
                showModal(info.event);
            },
            eventDidMount: function(info) {
                // Add title attribute for native tooltip
                const props = info.event.extendedProps;
                info.el.title = `${info.event.title}\n${props.location || ''}\n${props.source || ''}`;
            },
            dayCellDidMount: function(info) {
                const dateStr = info.date.toISOString().split('T')[0];
                const count = auctionsPerDay[dateStr] || 0;
                
                if (count > 0) {
                    const intensity = Math.min(count / maxCount, 1);
                    let heatClass = 'heatmap-low';
                    if (intensity > 0.75) heatClass = 'heatmap-very-high';
                    else if (intensity > 0.5) heatClass = 'heatmap-high';
                    else if (intensity > 0.25) heatClass = 'heatmap-medium';
                    
                    info.el.classList.add(heatClass);
                    info.el.title = `${count} auction${count > 1 ? 's' : ''} on this day`;
                }
            },
            height: 'auto',
            dayMaxEvents: 3,
            moreLinkClick: 'popover',
            nowIndicator: true,
            eventDisplay: 'block',
            dayHeaderFormat: { weekday: 'short' },
            slotMinTime: '07:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: true,
            firstDay: 1,
        });
        
        calendar.render();
        
        // Setup view switcher
        setupViewSwitcher();
        
        // Setup navigation
        setupNavigation();
        
        // Setup filters
        setupFilters();
        
        // Setup modal
        setupModal();
        
        // Update calendar title
        updateCalendarTitle();
        
        // Hide loading
        hideLoading();
        
    } catch (error) {
        console.error('Error initializing calendar:', error);
        hideLoading();
        
        // Show error message
        const calendarEl = document.getElementById('calendar');
        calendarEl.innerHTML = `
            <div class="error-message">
                <p>Unable to load auction data. Please try again later.</p>
                <p class="error-detail">${error.message}</p>
            </div>
        `;
    }
}

/**
 * Setup view switcher buttons
 */
function setupViewSwitcher() {
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active state
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Change view
            calendar.changeView(view);
            updateCalendarTitle();
            
            // Re-apply heatmap on view change
            const auctionsPerDay = calculateAuctionsPerDay(filterEvents());
            const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
            applyHeatmap(auctionsPerDay, maxCount);
        });
    });
}

/**
 * Setup navigation buttons
 */
function setupNavigation() {
    document.getElementById('prevBtn').addEventListener('click', () => {
        calendar.prev();
        updateCalendarTitle();
        // Re-apply heatmap
        const auctionsPerDay = calculateAuctionsPerDay(filterEvents());
        const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
        applyHeatmap(auctionsPerDay, maxCount);
    });
    
    document.getElementById('nextBtn').addEventListener('click', () => {
        calendar.next();
        updateCalendarTitle();
        // Re-apply heatmap
        const auctionsPerDay = calculateAuctionsPerDay(filterEvents());
        const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
        applyHeatmap(auctionsPerDay, maxCount);
    });
    
    document.getElementById('todayBtn').addEventListener('click', () => {
        calendar.today();
        updateCalendarTitle();
        // Re-apply heatmap
        const auctionsPerDay = calculateAuctionsPerDay(filterEvents());
        const maxCount = Math.max(...Object.values(auctionsPerDay), 1);
        applyHeatmap(auctionsPerDay, maxCount);
    });
}

/**
 * Setup filter inputs
 */
function setupFilters() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(refreshCalendar, 300);
    });
    
    // Source filter
    document.getElementById('sourceFilter').addEventListener('change', refreshCalendar);
    
    // Location filter
    document.getElementById('locationFilter').addEventListener('change', refreshCalendar);
    
    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('sourceFilter').value = '';
        document.getElementById('locationFilter').value = '';
        refreshCalendar();
    });
}

/**
 * Setup modal events
 */
function setupModal() {
    // Close button
    document.getElementById('modalClose').addEventListener('click', hideModal);
    
    // Click overlay to close
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });
    
    // Escape key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideModal();
    });
}

/**
 * Update calendar title
 */
function updateCalendarTitle() {
    const titleEl = document.getElementById('calendarTitle');
    const view = calendar.view;
    
    const start = view.activeStart;
    const end = view.activeEnd;
    
    let title = '';
    
    switch (view.type) {
        case 'dayGridMonth':
            title = start.toLocaleDateString('en-ZA', { month: 'long', year: 'numeric' });
            break;
        case 'timeGridWeek':
            title = `${start.toLocaleDateString('en-ZA', { day: 'numeric', month: 'short' })} - ${end.toLocaleDateString('en-ZA', { day: 'numeric', month: 'short', year: 'numeric' })}`;
            break;
        case 'timeGridDay':
            title = start.toLocaleDateString('en-ZA', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            break;
        case 'listMonth':
            title = start.toLocaleDateString('en-ZA', { month: 'long', year: 'numeric' });
            break;
        default:
            title = start.toLocaleDateString('en-ZA', { month: 'long', year: 'numeric' });
    }
    
    titleEl.textContent = title;
}

// ==========================================
// Loading State
// ==========================================

function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

// ==========================================
// Initialize on DOM Ready
// ==========================================

document.addEventListener('DOMContentLoaded', initCalendar);
