<?php
/**
 * Auction Atlas - Risk Analysis Logic
 * 
 * Provides detailed risk breakdown and national risk averages
 * for the Risk Scanner page and profile risk panels.
 */

require_once __DIR__ . '/scoring.php';

/**
 * Calculate detailed risk breakdown for an auction house
 * 
 * @param array $normalized Normalized auction data
 * @return array Detailed risk breakdown with category scores
 */
function calculateRiskBreakdown($normalized) {
    // Operational Risk (0-100)
    $operationalRisk = 0;
    $ops = $normalized['operationalIndicators'];
    
    if (!$ops['inspectionOffered']) $operationalRisk += 25;
    if (!$ops['settlementFlexibility']) $operationalRisk += 20;
    if ($ops['storagePenalties']) $operationalRisk += 10;
    
    $clearance = $ops['clearanceRate'];
    if ($clearance === 'Low') $operationalRisk += 25;
    elseif ($clearance === 'Moderate') $operationalRisk += 10;
    
    // Transfer days risk
    $transferDays = $normalized['transferDays'];
    if ($transferDays === 'N/A' || $transferDays === null) {
        $operationalRisk += 15;
    } else {
        $days = intval($transferDays);
        if ($days > 14) $operationalRisk += 15;
        elseif ($days > 7) $operationalRisk += 5;
    }
    
    $operationalRisk = min(100, $operationalRisk);
    
    // Compliance Risk (0-100)
    $complianceRisk = 0;
    $compliance = $normalized['complianceSignals'];
    $totalSignals = 7;
    $activeSignals = 0;
    foreach ($compliance as $signal) {
        if ($signal) $activeSignals++;
    }
    $complianceRisk = round((1 - ($activeSignals / $totalSignals)) * 100);
    
    // Fee Risk (0-100)
    $feeRisk = 0;
    $premium = $normalized['buyerPremium'];
    if ($premium >= 15) $feeRisk += 40;
    elseif ($premium >= 12) $feeRisk += 20;
    elseif ($premium >= 10) $feeRisk += 10;
    
    if ($normalized['operationalIndicators']['hiddenFees']) $feeRisk += 30;
    if ($normalized['vatOnPremium'] === 'Yes') $feeRisk += 10;
    if ($normalized['adminFees'] === 'Variable') $feeRisk += 10;
    if ($normalized['storagePenalties'] === 'Yes') $feeRisk += 10;
    
    $feeRisk = min(100, $feeRisk);
    
    // Market Risk (0-100)
    $marketRisk = 0;
    $reputation = $normalized['reputationSignals'];
    
    $googleRating = $reputation['googleRating'] ?? 0;
    if ($googleRating === 0 || $googleRating === null) {
        $marketRisk += 25;
    } elseif ($googleRating < 2) {
        $marketRisk += 40;
    } elseif ($googleRating < 3) {
        $marketRisk += 25;
    } elseif ($googleRating < 4) {
        $marketRisk += 10;
    }
    
    $domainAge = $reputation['domainAgeYears'] ?? 0;
    if ($domainAge === 0 || $domainAge === null) $marketRisk += 20;
    elseif ($domainAge < 5) $marketRisk += 15;
    elseif ($domainAge < 10) $marketRisk += 5;
    
    // Complaints
    $complaints = $normalized['complaints'];
    if ($complaints['misrepresentation']) $marketRisk += 20;
    if ($complaints['depositRefund']) $marketRisk += 10;
    if ($complaints['transferDelay']) $marketRisk += 10;
    
    $marketRisk = min(100, $marketRisk);
    
    return [
        'operational' => $operationalRisk,
        'compliance' => $complianceRisk,
        'fee' => $feeRisk,
        'market' => $marketRisk,
        'overall' => round(($operationalRisk + $complianceRisk + $feeRisk + $marketRisk) / 4),
    ];
}

/**
 * Get national risk averages across all auction houses
 * 
 * @return array National averages for each risk category
 */
function getNationalRiskAverages() {
    $auctions = getAllScoredAuctions();
    
    $totals = [
        'operational' => 0,
        'compliance' => 0,
        'fee' => 0,
        'market' => 0,
        'overall' => 0,
        'trust' => 0,
        'risk' => 0,
    ];
    
    $count = count($auctions);
    if ($count === 0) return $totals;
    
    $gradeDistribution = ['A' => 0, 'B' => 0, 'C' => 0];
    
    foreach ($auctions as $auction) {
        $breakdown = calculateRiskBreakdown($auction);
        $totals['operational'] += $breakdown['operational'];
        $totals['compliance'] += $breakdown['compliance'];
        $totals['fee'] += $breakdown['fee'];
        $totals['market'] += $breakdown['market'];
        $totals['overall'] += $breakdown['overall'];
        $totals['trust'] += $auction['scores']['trust'];
        $totals['risk'] += $auction['scores']['risk'];
        
        $grade = $auction['scores']['grade'];
        if (isset($gradeDistribution[$grade])) {
            $gradeDistribution[$grade]++;
        }
    }
    
    return [
        'operational' => round($totals['operational'] / $count),
        'compliance' => round($totals['compliance'] / $count),
        'fee' => round($totals['fee'] / $count),
        'market' => round($totals['market'] / $count),
        'overall' => round($totals['overall'] / $count),
        'avgTrust' => round($totals['trust'] / $count),
        'avgRisk' => round($totals['risk'] / $count),
        'gradeDistribution' => $gradeDistribution,
        'totalAuctions' => $count,
    ];
}

/**
 * Get risk level label based on score
 * 
 * @param int $score Risk score
 * @return string Risk level label
 */
function getRiskLevel($score) {
    if ($score < 25) return 'Low';
    if ($score < 50) return 'Moderate';
    if ($score < 75) return 'Elevated';
    return 'High';
}

/**
 * Get risk bar color based on score
 * 
 * @param int $score Risk score
 * @return string Tailwind color class for background
 */
function getRiskBarColor($score) {
    if ($score < 25) return 'bg-emerald-500';
    if ($score < 50) return 'bg-yellow-500';
    if ($score < 75) return 'bg-orange-500';
    return 'bg-red-500';
}
