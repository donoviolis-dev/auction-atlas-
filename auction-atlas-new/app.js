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

// === CALENDAR FILTER SYSTEM ===
// ==========================================

/**
 * Date Range Helper - calculates dynamic date ranges based on today
 */
const DateRangeHelper = {
    /**
     * Get today's date range (start/end of day)
     */
    getToday: function() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const end = new Date(today);
        end.setHours(23, 59, 59, 999);
        return { start: today, end: end };
    },

    /**
     * Get Monday of current ISO week
     */
    getMondayOfWeek: function(date) {
        const d = new Date(date);
        const day = d.getDay(); // 0 = Sun
        const diff = (day === 0) ? -6 : 1 - day;
        d.setDate(d.getDate() + diff);
        d.setHours(0, 0, 0, 0);
        return d;
    },

    /**
     * Get this week's date range (Mon-Sun of current ISO week)
     */
    getThisWeek: function() {
        const today = new Date();
        const monday = this.getMondayOfWeek(today);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        sunday.setHours(23, 59, 59, 999);
        return { start: monday, end: sunday };
    },

    /**
     * Get next week's date range (Mon-Sun of following ISO week)
     */
    getNextWeek: function() {
        const thisWeek = this.getThisWeek();
        const monday = new Date(thisWeek.start);
        monday.setDate(monday.getDate() + 7);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        sunday.setHours(23, 59, 59, 999);
        return { start: monday, end: sunday };
    },

    /**
     * Get this month's date range (1st to last day of current month)
     */
    getThisMonth: function() {
        const today = new Date();
        const start = new Date(today.getFullYear(), today.getMonth(), 1);
        const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        end.setHours(23, 59, 59, 999);
        return { start: start, end: end };
    },

    /**
     * Get next month's date range
     */
    getNextMonth: function() {
        const today = new Date();
        const start = new Date(today.getFullYear(), today.getMonth() + 1, 1);
        const end = new Date(today.getFullYear(), today.getMonth() + 2, 0);
        end.setHours(23, 59, 59, 999);
        return { start: start, end: end };
    },

    /**
     * Get all upcoming events (from today inclusive, no upper bound)
     */
    getAllUpcoming: function() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return { start: today, end: null };
    },

    /**
     * Get past events (any date strictly before today)
     */
    getPast: function() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return { start: null, end: today };
    }
};

// Current filter state
let currentFilter = 'all-upcoming';

// Filter tab configuration
const FILTER_TABS = [
    { key: 'today', label: 'Today' },
    { key: 'this-week', label: 'This Week' },
    { key: 'next-week', label: 'Next Week' },
    { key: 'this-month', label: 'This Month' },
    { key: 'next-month', label: 'Next Month' },
    { key: 'all-upcoming', label: 'All Upcoming' },
    { key: 'past', label: 'Past' }
];

/**
 * Create filter tabs UI
 */
function createFilterTabs() {
    const container = document.getElementById('calendar-filters');
    if (!container) return;

    // Get counts for each filter
    const counts = calculateFilterCounts();

    container.innerHTML = `
        <div class="flex gap-2 overflow-x-auto pb-2" style="scrollbar-width: thin;">
            ${FILTER_TABS.map(tab => `
                <button 
                    data-filter="${tab.key}" 
                    class="filter-tab whitespace-nowrap px-4 py-2 text-sm rounded-lg transition-colors ${currentFilter === tab.key ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-700'}"
                >
                    ${tab.label} <span class="ml-1 text-xs opacity-70">(${counts[tab.key] || 0})</span>
                </button>
            `).join('')}
        </div>
    `;

    // Add click handlers
    container.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const filter = tab.dataset.filter;
            setFilter(filter);
        });
    });
}

/**
 * Calculate counts for each filter
 */
function calculateFilterCounts() {
    const counts = {};
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    FILTER_TABS.forEach(tab => {
        const range = DateRangeHelper[tab.key.replace('-', '').replace('all', 'AllUpcoming')]();
        if (!range) return;

        const filtered = allEvents.filter(event => {
            const eventDate = new Date(event.start);
            eventDate.setHours(0, 0, 0, 0);

            if (tab.key === 'today') {
                return eventDate.getTime() === today.getTime();
            } else if (tab.key === 'this-week' || tab.key === 'next-week') {
                const rangeFn = tab.key === 'this-week' ? 'getThisWeek' : 'getNextWeek';
                const range = DateRangeHelper[rangeFn]();
                return eventDate >= range.start && eventDate <= range.end;
            } else if (tab.key === 'this-month' || tab.key === 'next-month') {
                const rangeFn = tab.key === 'this-month' ? 'getThisMonth' : 'getNextMonth';
                const range = DateRangeHelper[rangeFn]();
                return eventDate >= range.start && eventDate <= range.end;
            } else if (tab.key === 'all-upcoming') {
                return eventDate >= today;
            } else if (tab.key === 'past') {
                return eventDate < today;
            }
            return false;
        });

        counts[tab.key] = filtered.length;
    });

    return counts;
}

/**
 * Filter events by date range
 */
function filterEventsByDate(filterKey) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return allEvents.filter(event => {
        const eventDate = new Date(event.start);
        eventDate.setHours(0, 0, 0, 0);

        switch (filterKey) {
            case 'today':
                return eventDate.getTime() === today.getTime();
            case 'this-week':
                const thisWeek = DateRangeHelper.getThisWeek();
                return eventDate >= thisWeek.start && eventDate <= thisWeek.end;
            case 'next-week':
                const nextWeek = DateRangeHelper.getNextWeek();
                return eventDate >= nextWeek.start && eventDate <= nextWeek.end;
            case 'this-month':
                const thisMonth = DateRangeHelper.getThisMonth();
                return eventDate >= thisMonth.start && eventDate <= thisMonth.end;
            case 'next-month':
                const nextMonth = DateRangeHelper.getNextMonth();
                return eventDate >= nextMonth.start && eventDate <= nextMonth.end;
            case 'all-upcoming':
                return eventDate >= today;
            case 'past':
                return eventDate < today;
            default:
                return true;
        }
    });
}

/**
 * Set active filter and update calendar
 */
function setFilter(filterKey) {
    currentFilter = filterKey;

    // Update URL
    const url = new URL(window.location);
    url.searchParams.set('filter', filterKey);
    window.history.pushState({}, '', url);

    // Update tab UI
    updateFilterTabs();

    // Only update calendar if it's initialized
    if (calendar) {
        // Filter events and update calendar
        const filteredEvents = filterEventsByDate(filterKey);
        updateCalendarEvents(filteredEvents);

        // Update summary strip
        updateSummaryStrip(filteredEvents);
    }
}

/**
 * Update filter tabs styling
 */
function updateFilterTabs() {
    const container = document.getElementById('calendar-filters');
    if (!container) return;

    // Update counts
    const counts = calculateFilterCounts();
    container.querySelectorAll('.filter-tab').forEach(tab => {
        const key = tab.dataset.filter;
        tab.classList.toggle('bg-blue-600', key === currentFilter);
        tab.classList.toggle('text-white', key === currentFilter);
        tab.classList.toggle('font-semibold', key === currentFilter);
        tab.classList.toggle('text-gray-400', key !== currentFilter);
        tab.classList.toggle('hover:text-white', key !== currentFilter);
        tab.classList.toggle('hover:bg-gray-700', key !== currentFilter);
        
        // Update count
        const countSpan = tab.querySelector('span');
        if (countSpan) {
            countSpan.textContent = `(${counts[key] || 0})`;
        }
    });
}

/**
 * Update calendar with filtered events and navigate to appropriate view
 */
function updateCalendarEvents(events) {
    if (!calendar) return;

    // Clear and add filtered events
    calendar.removeAllEvents();
    calendar.addEventSource(events);

    // Navigate to appropriate view based on filter
    const today = DateRangeHelper.getToday();

    switch (currentFilter) {
        case 'today':
            calendar.gotoDate(today.start);
            calendar.changeView('timeGridDay');
            break;
        case 'this-week':
            const thisWeek = DateRangeHelper.getThisWeek();
            calendar.gotoDate(thisWeek.start);
            calendar.changeView('timeGridWeek');
            break;
        case 'next-week':
            const nextWeek = DateRangeHelper.getNextWeek();
            calendar.gotoDate(nextWeek.start);
            calendar.changeView('timeGridWeek');
            break;
        case 'this-month':
            const thisMonth = DateRangeHelper.getThisMonth();
            calendar.gotoDate(thisMonth.start);
            calendar.changeView('dayGridMonth');
            break;
        case 'next-month':
            const nextMonth = DateRangeHelper.getNextMonth();
            calendar.gotoDate(nextMonth.start);
            calendar.changeView('dayGridMonth');
            break;
        case 'all-upcoming':
        case 'past':
            calendar.changeView('listMonth');
            break;
    }
}

/**
 * Update summary strip
 */
function updateSummaryStrip(events) {
    const container = document.getElementById('calendar-summary');
    if (!container) return;

    const totalCount = events.length;
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Count today's auctions
    const todayCount = events.filter(e => {
        const eventDate = new Date(e.start);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === today.getTime();
    }).length;

    // Find next upcoming auction
    let nextAuctionText = '';
    if (currentFilter === 'all-upcoming' && allEvents.length > 0) {
        const upcoming = allEvents
            .filter(e => new Date(e.start) >= today)
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        if (upcoming.length > 0) {
            const nextDate = new Date(upcoming[0].start);
            const diffTime = nextDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            nextAuctionText = ` · next auction in ${diffDays} day${diffDays !== 1 ? 's' : ''}`;
        }
    }

    container.innerHTML = `
        <p class="text-sm text-gray-400 py-2 px-1">
            Showing ${totalCount} auction${totalCount !== 1 ? 's' : ''}${todayCount > 0 ? ` · ${todayCount} today` : ''}${nextAuctionText}
        </p>
    `;
}

/**
 * Add status badges to calendar events
 */
function addEventStatusBadges() {
    if (!calendar) return;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    calendar.getEvents().forEach(event => {
        const eventDate = new Date(event.start);
        eventDate.setHours(0, 0, 0, 0);
        const daysDiff = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));

        const el = event.el;
        if (!el) return;

        // Remove existing badge
        const existingBadge = el.querySelector('.status-badge');
        if (existingBadge) existingBadge.remove();

        let badgeHtml = '';
        let shouldReduceOpacity = false;

        if (daysDiff === 0) {
            // Today
            badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-green-500 text-white status-badge">TODAY</span>';
        } else if (daysDiff > 0 && daysDiff <= 7) {
            // Upcoming (within 7 days)
            badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-blue-500 text-white status-badge">UPCOMING</span>';
        } else if (daysDiff < 0) {
            // Past
            badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-gray-600 text-gray-300 line-through status-badge">PAST</span>';
            shouldReduceOpacity = true;
        }

        if (badgeHtml) {
            // Insert badge after the title
            const titleEl = el.querySelector('.fc-event-title');
            if (titleEl) {
                titleEl.insertAdjacentHTML('beforeend', badgeHtml);
            }

            // Reduce opacity for past events
            if (shouldReduceOpacity) {
                el.style.opacity = '0.5';
            }
        }
    });
}

/**
 * Show empty state when no events match filter
 */
function showEmptyState() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Hide calendar
    calendarEl.style.display = 'none';

    // Create empty state container
    let emptyContainer = document.getElementById('empty-state-container');
    if (!emptyContainer) {
        emptyContainer = document.createElement('div');
        emptyContainer.id = 'empty-state-container';
        emptyContainer.className = 'text-center py-16 text-gray-500';
        
        const summaryStrip = document.getElementById('calendar-summary');
        if (summaryStrip) {
            summaryStrip.parentNode.insertBefore(emptyContainer, summaryStrip);
        }
    }

    emptyContainer.innerHTML = `
        <p class="text-lg font-semibold text-gray-400">No auctions found</p>
        <p class="text-sm mt-1">Try "All Upcoming" to see what's coming next.</p>
        <button onclick="window.setFilter('all-upcoming')" class="mt-4 bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-500">
            Show All Upcoming
        </button>
    `;
}

/**
 * Hide empty state and show calendar
 */
function hideEmptyState() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        calendarEl.style.display = '';
    }

    const emptyContainer = document.getElementById('empty-state-container');
    if (emptyContainer) {
        emptyContainer.remove();
    }
}

/**
 * Initialize filter system from URL params
 */
function initFilterFromURL() {
    const params = new URLSearchParams(window.location.search);
    const filter = params.get('filter');

    // Validate filter key
    const validKeys = FILTER_TABS.map(t => t.key);
    if (filter && validKeys.includes(filter)) {
        currentFilter = filter;
    } else {
        currentFilter = 'all-upcoming';
    }
}

/**
 * Initialize calendar filter system
 */
function initCalendarFilters() {
    // Initialize filter from URL
    initFilterFromURL();

    // Create filter tabs
    createFilterTabs();

    // Set initial filter
    setFilter(currentFilter);
}

// Expose setFilter globally for the empty state button
window.setFilter = setFilter;

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
                // Add status badges to events
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const eventDate = new Date(info.event.start);
                eventDate.setHours(0, 0, 0, 0);
                const daysDiff = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));

                const el = info.el;
                let badgeHtml = '';
                let shouldReduceOpacity = false;

                if (daysDiff === 0) {
                    // Today
                    badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-green-500 text-white status-badge">TODAY</span>';
                } else if (daysDiff > 0 && daysDiff <= 7) {
                    // Upcoming (within 7 days)
                    badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-blue-500 text-white status-badge">UPCOMING</span>';
                } else if (daysDiff < 0) {
                    // Past
                    badgeHtml = '<span class="ml-2 text-xs font-bold px-1.5 py-0.5 rounded bg-gray-600 text-gray-300 line-through status-badge">PAST</span>';
                    shouldReduceOpacity = true;
                }

                if (badgeHtml) {
                    const titleEl = el.querySelector('.fc-event-title');
                    if (titleEl) {
                        titleEl.insertAdjacentHTML('beforeend', badgeHtml);
                    }
                    if (shouldReduceOpacity) {
                        el.style.opacity = '0.5';
                    }
                }
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
