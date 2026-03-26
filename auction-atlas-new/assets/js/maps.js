/**
 * Auction Atlas - Leaflet Map Utilities
 * 
 * Creates and manages Leaflet maps for auction house locations.
 * Uses South African city coordinates for branch mapping.
 */

// South African city coordinates lookup
var SA_CITIES = {
    'Centurion': [-25.8603, 28.1894],
    'Johannesburg': [-26.2041, 28.0473],
    'Cape Town': [-33.9249, 18.4241],
    'Pretoria': [-25.7479, 28.2293],
    'Durban': [-29.8587, 31.0218],
    'Bloemfontein': [-29.0852, 26.1596],
    'Midrand': [-25.9891, 28.1279],
    'Bryanston': [-26.0574, 28.0197],
    'Durbanville': [-33.8318, 18.6465],
    'North Riding': [-26.0667, 27.9500],
    'Bellville': [-33.8990, 18.6340],
    'Waterkloof': [-25.7850, 28.2350],
    'Randburg': [-26.0936, 28.0064],
    'East Rand': [-26.1750, 28.3200],
    'Sandton': [-26.1076, 28.0567],
    'Krugersdorp': [-26.0854, 27.7695],
    'Emalahleni (Witbank)': [-25.8714, 29.2340],
    'Tyger Valley': [-33.8700, 18.6300],
    'National Depots': [-28.4793, 24.6727], // Center of SA
};

// Default South Africa center
var SA_CENTER = [-28.4793, 24.6727];
var SA_ZOOM = 5;

/**
 * Initialize a Leaflet map with auction house markers
 * 
 * @param {string} containerId - Map container element ID
 * @param {Array} locations - Array of {name, city, province} objects
 * @param {object} options - Optional map configuration
 * @returns {L.Map} Leaflet map instance
 */
function initAuctionMap(containerId, locations, options) {
    var container = document.getElementById(containerId);
    if (!container) return null;
    
    options = options || {};
    
    var map = L.map(containerId, {
        scrollWheelZoom: false,
        zoomControl: true,
    }).setView(options.center || SA_CENTER, options.zoom || SA_ZOOM);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);
    
    // Custom marker icon
    var markerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #1F4E79; width: 12px; height: 12px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
        iconSize: [18, 18],
        iconAnchor: [9, 9],
    });
    
    var bounds = [];
    
    // Add markers for each location
    if (locations && locations.length > 0) {
        locations.forEach(function(loc) {
            var coords = SA_CITIES[loc.city] || SA_CITIES[loc.location] || null;
            
            if (coords) {
                var marker = L.marker(coords, { icon: markerIcon }).addTo(map);
                
                var popupContent = '<div style="font-family: Lato, sans-serif; min-width: 150px;">';
                popupContent += '<strong style="font-family: Montserrat, sans-serif; color: #1F4E79;">' + (loc.name || loc.city) + '</strong>';
                if (loc.province) {
                    popupContent += '<br><span style="color: #64748b; font-size: 12px;">' + loc.province + '</span>';
                }
                if (loc.trust !== undefined) {
                    popupContent += '<br><span style="font-size: 12px;">Trust: <strong>' + loc.trust + '</strong>/100</span>';
                }
                popupContent += '</div>';
                
                marker.bindPopup(popupContent);
                bounds.push(coords);
            }
        });
        
        // Fit bounds if we have markers
        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [30, 30] });
        } else if (bounds.length === 1) {
            map.setView(bounds[0], 10);
        }
    }
    
    // Handle resize
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
    
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });
    
    return map;
}

/**
 * Initialize the homepage overview map with all auction houses
 * 
 * @param {string} containerId - Map container element ID
 * @param {Array} auctions - Array of auction data with city/province
 * @returns {L.Map} Leaflet map instance
 */
function initOverviewMap(containerId, auctions) {
    var locations = auctions.map(function(a) {
        return {
            name: a.name,
            city: a.city,
            province: a.province,
            trust: a.trust
        };
    });
    
    return initAuctionMap(containerId, locations);
}
