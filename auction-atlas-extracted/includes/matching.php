<?php
/**
 * Auction Atlas - Buyer Matching Engine
 * 
 * Matches buyer profiles to the most suitable auction houses
 * by reweighting scoring based on buyer type preferences.
 */

require_once __DIR__ . '/scoring.php';

/**
 * Buyer type definitions with scoring weights and preferences
 */
function getBuyerTypes() {
    return [
        'first-time' => [
            'label' => 'First-Time Buyer',
            'description' => 'New to auctions, needs transparency and low risk',
            'icon' => 'user-plus',
            'weights' => [
                'trust' => 1.5,      // Trust matters most
                'risk' => 1.8,       // Low risk critical
                'premium' => 1.2,    // Prefer lower premiums
                'compliance' => 1.5, // Full compliance important
                'branches' => 0.5,   // Less important
            ],
            'preferTags' => ['First-time buyers', 'First-time Bidders', 'Household Buyers'],
            'maxPremium' => 12,
        ],
        'reseller' => [
            'label' => 'Reseller',
            'description' => 'Buys to resell, needs volume and competitive pricing',
            'icon' => 'repeat',
            'weights' => [
                'trust' => 1.0,
                'risk' => 1.0,
                'premium' => 1.8,    // Low premium critical
                'compliance' => 0.8,
                'branches' => 1.2,   // More locations = more opportunity
            ],
            'preferTags' => ['Dealers', 'Salvage Flippers'],
            'maxPremium' => 15,
        ],
        'property-investor' => [
            'label' => 'Property Investor',
            'description' => 'Focused on property acquisitions with strong legal compliance',
            'icon' => 'building',
            'weights' => [
                'trust' => 1.5,
                'risk' => 1.5,
                'premium' => 1.0,
                'compliance' => 1.8, // Legal compliance critical
                'branches' => 0.8,
            ],
            'preferTags' => ['Property Investors', 'Commercial Developers'],
            'preferCategories' => ['Property', 'Commercial Property', 'Residential Property'],
            'maxPremium' => 15,
        ],
        'machinery-dealer' => [
            'label' => 'Machinery Dealer',
            'description' => 'Specializes in industrial and machinery acquisitions',
            'icon' => 'settings',
            'weights' => [
                'trust' => 1.2,
                'risk' => 1.0,
                'premium' => 1.5,
                'compliance' => 1.0,
                'branches' => 1.0,
            ],
            'preferTags' => ['Industrial Buyers', 'Dealers'],
            'preferCategories' => ['Industrial Assets', 'Machinery', 'Industrial'],
            'maxPremium' => 15,
        ],
        'high-value' => [
            'label' => 'High-Value Asset Acquirer',
            'description' => 'Seeks premium items: art, wine, jewellery, collectibles',
            'icon' => 'gem',
            'weights' => [
                'trust' => 1.8,      // Trust paramount
                'risk' => 1.5,
                'premium' => 0.5,    // Less price sensitive
                'compliance' => 1.5,
                'branches' => 0.5,
            ],
            'preferTags' => ['Art Collectors', 'Wine Enthusiasts', 'Jewellery Buyers', 'High-net-worth Buyers', 'High-net-worth Investors', 'Collectors'],
            'preferCategories' => ['Fine Art', 'Collectibles', 'Wine', 'Jewellery', 'Antiques', 'Art'],
            'maxPremium' => 20,
        ],
        'bulk-buyer' => [
            'label' => 'Bulk Buyer',
            'description' => 'Purchases in volume, needs frequent auctions and fast turnaround',
            'icon' => 'package',
            'weights' => [
                'trust' => 1.0,
                'risk' => 0.8,
                'premium' => 1.8,    // Volume = price sensitive
                'compliance' => 0.8,
                'branches' => 1.5,   // Multiple locations important
            ],
            'preferTags' => ['Dealers', 'Industrial Buyers', 'Estate Executors'],
            'maxPremium' => 12,
        ],
    ];
}

/**
 * Calculate match score for a buyer type against an auction house
 * 
 * @param array $auction Scored auction data
 * @param array $buyerType Buyer type configuration
 * @return array ['score' => float, 'reasons' => array]
 */
function calculateMatchScore($auction, $buyerType) {
    $weights = $buyerType['weights'];
    $score = 0;
    $reasons = [];
    
    // Trust component (weighted)
    $trustScore = $auction['scores']['trust'] * $weights['trust'];
    $score += $trustScore;
    if ($auction['scores']['trust'] >= 75) {
        $reasons[] = 'High trust score (' . $auction['scores']['trust'] . '/100)';
    }
    
    // Risk component (inverted - lower risk = higher match score)
    $riskComponent = (100 - $auction['scores']['risk']) * $weights['risk'];
    $score += $riskComponent;
    if ($auction['scores']['risk'] < 30) {
        $reasons[] = 'Low risk profile (' . $auction['scores']['risk'] . '/100)';
    }
    
    // Premium component (lower premium = higher score)
    $premiumScore = max(0, (20 - $auction['buyerPremium']) * 5) * $weights['premium'];
    $score += $premiumScore;
    if ($auction['buyerPremium'] <= 10) {
        $reasons[] = 'Competitive buyer premium (' . $auction['buyerPremiumRaw'] . ')';
    }
    
    // Compliance component
    $complianceCount = 0;
    foreach ($auction['complianceSignals'] as $signal) {
        if ($signal) $complianceCount++;
    }
    $complianceScore = ($complianceCount / 7) * 50 * $weights['compliance'];
    $score += $complianceScore;
    if ($complianceCount >= 6) {
        $reasons[] = 'Strong compliance profile (' . $complianceCount . '/7 signals)';
    }
    
    // Branch presence component
    $branchScore = min(30, count($auction['branches']) * 10) * $weights['branches'];
    $score += $branchScore;
    if (count($auction['branches']) >= 3) {
        $reasons[] = 'Wide branch network (' . count($auction['branches']) . ' locations)';
    }
    
    // Tag matching bonus (significant)
    $tagMatches = 0;
    foreach ($auction['bestForTags'] as $tag) {
        if (in_array($tag, $buyerType['preferTags'])) {
            $tagMatches++;
            $score += 30;
        }
    }
    if ($tagMatches > 0) {
        $reasons[] = 'Matches buyer profile tags (' . $tagMatches . ' match' . ($tagMatches > 1 ? 'es' : '') . ')';
    }
    
    // Category matching bonus
    if (isset($buyerType['preferCategories'])) {
        $catMatches = 0;
        foreach ($auction['categories'] as $cat) {
            if (in_array($cat, $buyerType['preferCategories'])) {
                $catMatches++;
                $score += 20;
            }
        }
        if ($catMatches > 0) {
            $reasons[] = 'Relevant categories (' . $catMatches . ' match' . ($catMatches > 1 ? 'es' : '') . ')';
        }
    }
    
    // Grade bonus
    if ($auction['scores']['grade'] === 'A') {
        $score += 25;
        $reasons[] = 'Institutional Grade A';
    } elseif ($auction['scores']['grade'] === 'B') {
        $score += 10;
    }
    
    return [
        'score' => round($score, 1),
        'reasons' => $reasons,
    ];
}

/**
 * Get top matches for a buyer type
 * 
 * @param string $buyerTypeKey Buyer type key
 * @param int $limit Number of results to return
 * @return array Top matched auctions with scores and reasons
 */
function getTopMatches($buyerTypeKey, $limit = 3) {
    $buyerTypes = getBuyerTypes();
    
    if (!isset($buyerTypes[$buyerTypeKey])) {
        return [];
    }
    
    $buyerType = $buyerTypes[$buyerTypeKey];
    $auctions = getAllScoredAuctions();
    $matches = [];
    
    foreach ($auctions as $auction) {
        $match = calculateMatchScore($auction, $buyerType);
        $auction['matchScore'] = $match['score'];
        $auction['matchReasons'] = $match['reasons'];
        $matches[] = $auction;
    }
    
    // Sort by match score descending
    usort($matches, function($a, $b) {
        return $b['matchScore'] <=> $a['matchScore'];
    });
    
    return array_slice($matches, 0, $limit);
}
