# Auction Atlas - Platform Scope & Technical Breakdown

## Project Overview

**Auction Atlas** is a South African auction house directory and comparison platform that helps buyers find, compare, and evaluate auction houses across the country. The platform provides trust scoring, risk analysis, fee calculations, and upcoming auction calendars.

---

## 1. Core Purpose

### Primary Value Proposition
- **Trust & Transparency**: Calculate trust scores (0-100) for each auction house based on compliance, reputation, and operational indicators
- **Risk Analysis**: Provide detailed risk breakdowns to help buyers make informed decisions
- **Comparison**: Side-by-side comparison of multiple auction houses
- **Matching**: Buyer-type matching algorithm to recommend suitable auction houses
- **Calendar**: Upcoming auction events with date, location, and source tracking
- **SEO**: Programmatic generation of 1000+ location/category-specific landing pages

### Target Audience
- Property buyers looking for auction houses
- Vehicle buyers seeking competitive deals
- Industrial asset buyers
- First-time auction participants needing guidance
- Investment professionals evaluating auction houses

---

## 2. Data Architecture

### Primary Data Sources

#### 2.1 [`data/auctions.json`](auction-atlas-new/data/auctions.json)
The core database containing ~30 auction houses with comprehensive profiles.

**Structure** (per auction house):
```json
{
  "id": 1,
  "auction_house_name": "Aucor Auctioneers",
  "legal_name": "Aucor Auctioneers (Pty) Ltd",
  "website_url": "https://www.aucor.com",
  "address": "19 Nelson Mandela Drive",
  "city": "Centurion",
  "province": "Gauteng",
  "contact_email": "info@aucor.com",
  "contact_phone": "+27 12 677 8060",
  "years_operating": "50+",
  "company_type": "Pty Ltd",
  "https_secure": "Yes",
  "registration_number": "",
  "vat_registered": "Yes",
  "estate_license": "Yes",
  "popia_policy": "Yes",
  "terms_page": "Yes",
  "refund_policy": "Yes",
  "licensing_claims": "Yes",
  "auction_types": ["Vehicle", "Property", "Industrial", "Machinery", "Liquidation"],
  "auction_format": "Hybrid",
  "auction_platform": "Proprietary Online Platform",
  "auction_frequency": "Weekly",
  "national_or_regional": "National",
  "operational_metrics": {
    "avg_transfer_days": "7-14",
    "avg_refund_time": "3-5",
    "deposit_amount": "R5,000-R10,000",
    "buyer_premium_percent": "10-15%"
  },
  "reputation_signals": {
    "google_rating": 3.9,
    "google_review_count": 450,
    "domain_age_years": 20
  }
}
```

#### 2.2 [`data/upcoming-auctions.csv`](auction-atlas-new/data/upcoming-auctions.csv)
Calendar data for upcoming auctions (updated periodically).

**Columns**: `Auction House Name`, `Auction Title`, `Auction Date`, `Location`

**Sources tracked**:
- High Street Auction
- Gobid
- Claremart Auctions
- Bidders Choice
- Aucor
- SA Auction Group
- Nuco

#### 2.3 Assets - Logo System
- Location: [`assets/logos/`](auction-atlas-new/assets/logos/)
- 27 logo files (PNG format)
- Naming convention: `{slug}.png` (e.g., `aucor-auctioneers.png`)
- Default fallback: `default.png`
- Logo resolution via [`getAuctionLogoSlug()`](auction-atlas-new/includes/functions.php:50) mapping function

---

## 3. Core Pages & Features

### 3.1 Main Pages

| Page | File | Purpose |
|------|------|---------|
| **Home** | [`index.php`](auction-atlas-new/index.php) | Hero, stats, category grid, map preview |
| **Directory** | [`directory.php`](auction-atlas-new/directory.php) | Filterable list with pagination, GET params for filtering |
| **Profile** | [`profile.php`](auction-atlas-new/profile.php) | Detailed auction house profile, scores, risk breakdown |
| **Category** | [`category.php`](auction-atlas-new/category.php) | Filter by auction category |
| **Compare** | [`compare.php`](auction-atlas-new/compare.php) | Side-by-side comparison (2-4 auctions) |
| **Fee Calculator** | [`fee-calculator.php`](auction-atlas-new/fee-calculator.php) | Standalone calculator with sliders |
| **Risk Scanner** | [`risk-scanner.php`](auction-atlas-new/risk-scanner.php) | National averages, risk rankings |
| **Match** | [`match.php`](auction-atlas-new/match.php) | Buyer type matching |
| **Sitemap** | [`sitemap.php`](auction-atlas-new/sitemap.php) | XML sitemap generator |
| **Router** | [`router.php`](auction-atlas-new/router.php) | Programmatic SEO URL handling |

### 3.2 Additional Pages

| Page | File | Purpose |
|------|------|---------|
| Blog | [`blog.php`](auction-atlas-new/blog.php) | Educational content |
| Blog Post | [`blog-post.php`](auction-atlas-new/blog-post.php) | Individual blog article |
| Education | [`education.php`](auction-atlas-new/education.php) | Auction education guides |
| Scam Awareness | [`scam-awareness.php`](auction-atlas-new/scam-awareness.php) | Fraud prevention |
| Strategy Simulator | [`strategy-simulator.php`](auction-atlas-new/strategy-simulator.php) | Bid strategy tool |
| Prep Check | [`prep-check.php`](auction-atlas-new/prep-check.php) | Pre-auction checklist |
| 404 | [`404.php`](auction-atlas-new/404.php) | Not found page |
| 500 | [`500.php`](auction-atlas-new/500.php) | Server error page |

---

## 4. Scoring & Analysis System

### 4.1 Trust Score Calculation
**File**: [`includes/scoring.php`](auction-atlas-new/includes/scoring.php)

```
Total: 100 points

├── Compliance Signals (max 35)
│   ├── HTTPS (5)
│   ├── VAT Registered (5)
│   ├── Estate License (5)
│   ├── POPIA Policy (5)
│   ├── Terms Page (5)
│   ├── Refund Policy (5)
│   └── Licensing Claims (5)
│
├── Reputation Signals (max 35)
│   ├── Google Rating (10) - scaled 0-5 to 0-10
│   ├── Domain Age (10) - up to 25 years
│   ├── Google Reviews (10) - up to 200 reviews
│   └── Media Mentions (5)
│
└── Operational Indicators (max 30)
    ├── Inspection Offered (10)
    ├── Settlement Flexibility (10)
    └── Hidden Fees (10)
```

### 4.2 Risk Score Calculation
**File**: [`includes/riskLogic.php`](auction-atlas-new/includes/riskLogic.php)

Categories:
- **Operational Risk**: Inspection, flexibility, storage, clearance rate
- **Fee Risk**: Premium variability, deposit requirements
- **Compliance Risk**: Missing policies, unclear terms

### 4.3 Institutional Grades
- **A+**: 90-100 (Elite)
- **A**: 80-89 (Excellent)
- **B+**: 70-79 (Good)
- **B**: 60-69 (Average)
- **C**: 40-59 (Below Average)
- **D**: 0-39 (High Risk)

---

## 5. Directory Filtering System

**File**: [`directory.php`](auction-atlas-new/directory.php)

### GET Parameters
```php
?province=Gauteng              // Filter by province
?category=property             // Filter by category
?premium_min=5                 // Minimum buyer premium %
?premium_max=15                // Maximum buyer premium %
?trust_min=50                  // Minimum trust score
?trust_max=100                 // Maximum trust score
?page=2                        // Pagination (12 per page)
```

### Filtered Fields
- Province (array match)
- Category (array match)
- Premium range (numeric)
- Trust score range (numeric)

---

## 6. Programmatic SEO System

### 6.1 Page Generator
**File**: [`generate-pages.php`](auction-atlas-new/generate-pages.php)

Generates static HTML pages to `generated/` directory.

**Page Types**:
- **City Pages**: `auctions-johannesburg.html`, `auctions-cape-town.html`
- **Category Pages**: `auctions-property.html`, `auctions-vehicle.html`
- **City + Category**: `auctions-johannesburg-property.html`
- **Auctioneer Pages**: `auctioneer-gauteng-aucor-auctioneers.html`

**Coverage**:
- 16 cities
- 9 provinces
- 6 categories (Property, Vehicle, Industrial, Agricultural, Commercial, Liquidation)
- 30+ auction houses
- **~200+ generated pages** currently in `/generated/`

### 6.2 Router
**File**: [`router.php`](auction-atlas-new/router.php)

Dynamic URL handling for:
- `/auctions/{city}`
- `/auctions/{city}-{category}`
- `/auctioneer/{province}/{name}`
- `/category/{type}`

### 6.3 Sitemap
**File**: [`sitemap.php`](auction-atlas-new/sitemap.php)

Generates XML sitemap with:
- Static pages (home, directory, etc.)
- Dynamic auctioneer pages
- City/category combinations
- Priority/change frequency assignments

---

## 7. Frontend Technologies

### 7.1 CSS
- **Tailwind CSS** via CDN (primary styling)
- **Custom styles**: [`styles.css`](auction-atlas-new/styles.css) (23KB+)
- **Mobile styles**: [`styles-mobile.css`](auction-atlas-new/styles-mobile.css)
- **Component styles**: [`assets/css/style.css`](auction-atlas-new/assets/css/style.css)

### 7.2 JavaScript
- **FullCalendar** - Calendar display ([`app.js`](auction-atlas-new/app.js))
- **Chart.js** - Data visualization
- **Lucide Icons** - Icon system
- **Service Worker** - PWA offline capability ([`sw.js`](auction-atlas-new/sw.js))
- **Mobile nav** - [`mobile.js`](auction-atlas-new/mobile.js)

### 7.3 API Endpoints
- [`api/calendar.php`](auction-atlas-extracted/api/calendar.php) - Calendar data API

---

## 8. Key Helper Systems

### 8.1 Logo Resolution
**File**: [`includes/functions.php`](auction-atlas-new/includes/functions.php)

```php
getAuctionLogo($name)      // Returns logo path with fallback
getAuctionLogoSlug($name) // Maps names to slugs (handles 30+ auction houses)
```

### 8.2 Data Normalization
**File**: [`includes/normalization.php`](auction-atlas-new/includes/normalization.php)

Converts raw JSON data to standardized internal format with:
- `complianceSignals` (boolean flags)
- `reputationSignals` (numeric values)
- `operationalIndicators` (normalized values)

### 8.3 Matching Engine
**File**: [`includes/matching.php`](auction-atlas-new/includes/matching.php)

Buyer type profiles:
- First-time buyer
- Property investor
- Vehicle dealer
- Industrial buyer
- Bargain hunter

Matches buyer type to best-suited auction houses.

---

## 9. Infrastructure

### 9.1 Server Requirements
- **PHP 7.4+** (modern features used)
- **Apache/Nginx** with URL rewriting
- **Write permission**: `generated/` directory (for page generation)
- **Recommended**: Cron job for `generate-pages.php` and `sitemap.php`

### 9.2 File Structure
```
auction-atlas-new/
├── index.php              # Homepage
├── directory.php          # Main directory
├── profile.php            # Auction profile
├── category.php           # Category filter
├── compare.php            # Comparison tool
├── fee-calculator.php     # Fee calculator
├── risk-scanner.php       # Risk analysis
├── match.php              # Buyer matching
├── router.php             # SEO router
├── generate-pages.php    # Page generator
├── sitemap.php            # Sitemap generator
├── app.js                 # Calendar app
├── sw.js                  # Service worker
├── styles.css             # Main styles
├── data/
│   ├── auctions.json      # Auction database
│   └── upcoming-auctions.csv
├── generated/             # SEO pages (200+)
├── includes/
│   ├── functions.php       # Core utilities
│   ├── scoring.php        # Trust calculation
│   ├── riskLogic.php      # Risk analysis
│   ├── normalization.php  # Data transformation
│   ├── matching.php       # Buyer matching
│   ├── header.php         # Page header
│   ├── footer.php         # Page footer
│   └── navigation.php     # Nav menu
└── assets/
    ├── css/style.css      # Component styles
    ├── js/                # JS modules
    ├── logos/             # 27 logo files
    └── icons/             # PWA icons
```

### 9.3 SEO Features
- Dynamic meta tags per page
- Structured data (Schema.org)
- Canonical URLs
- robots.txt configuration
- PWA manifest.json

---

## 10. Known Technical Notes

### Current Logo System Fixes Applied
The following were recently fixed in the deployed version:
1. Added `getAuctionLogoSlug()` mapping function in `functions.php`
2. Fixed path resolution for development/production environments
3. Updated `generate-pages.php` to use proper slug mapping

### Pagination
- 12 auctions per page in directory
- Page 2+ triggers server-side filtering

### Cache Strategy
- Service worker caches: `index.php`, static assets
- JSON data loaded once per request via `$GLOBALS['_auction_data_cache']`

---

## 11. Potential Improvement Areas

1. **Auto-refresh upcoming auctions** - Cron job to update CSV from scrapers
2. **User accounts** - Save comparisons, preferences
3. **Reviews system** - User-submitted auction house reviews
4. **Alerts/notifications** - Email when matching auctions added
5. **API for third-party** - Public JSON endpoints
6. **Real-time bidding** - Integration with live auction platforms
7. **Multi-language** - Afrikaans support
8. **Accessibility** - WCAG 2.1 AA compliance pass

---

## 12. Deployment Notes

1. Upload all files to web root
2. Ensure `/generated/` is writable for page generation
3. Run `php generate-pages.php` to generate SEO pages
4. Optionally set up cron: `0 2 * * * php /path/to/generate-pages.php`
5. Test with `?province=Gauteng&category=property` filters
6. Verify service worker on HTTPS

---

*Document generated for Claude AI context - Last updated: March 2026*