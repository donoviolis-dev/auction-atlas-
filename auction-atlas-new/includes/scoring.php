<?php
/**
 * Auction Atlas - Global Scoring System
 * 
 * Calculates Trust Score, Risk Score, and Institutional Grade
 * for each auction house based on normalized data.
 */

require_once __DIR__ . '/normalization.php';

/**
 * Calculate Trust Score (0-100)
 * 
 * Combines compliance signals, reputation signals, and operational indicators
 * 
 * @param array $normalized Normalized auction data
 * @return int Trust score between 0 and 100
 */
function calculateTrustScore($normalized) {
    $score = 0;
    $maxScore = 100;
    
    // --- Compliance Signals (max 35 points) ---
    $compliance = $normalized['complianceSignals'];
    $compliancePoints = 0;
    if ($compliance['https']) $compliancePoints += 5;
    if ($compliance['vatRegistered']) $compliancePoints += 5;
    if ($compliance['estateLicense']) $compliancePoints += 5;
    if ($compliance['popiaPolicy']) $compliancePoints += 5;
    if ($compliance['termsPage']) $compliancePoints += 5;
    if ($compliance['refundPolicy']) $compliancePoints += 5;
    if ($compliance['licensingClaims']) $compliancePoints += 5;
    $score += $compliancePoints;
    
    // --- Reputation Signals (max 35 points) ---
    $reputation = $normalized['reputationSignals'];
    
    // Google rating (max 10 points) - scale from 0-5 to 0-10
    $googleRating = $reputation['googleRating'] ?? 0;
    $score += min(10, round(($googleRating / 5) * 10));
    
    // Google review count (max 8 points)
    $reviewCount = $reputation['googleReviewCount'] ?? 0;
    if ($reviewCount >= 200) $score += 8;
    elseif ($reviewCount >= 100) $score += 6;
    elseif ($reviewCount >= 50) $score += 4;
    elseif ($reviewCount >= 20) $score += 2;
    elseif ($reviewCount > 0) $score += 1;
    
    // Domain age (max 7 points)
    $domainAge = $reputation['domainAgeYears'] ?? 0;
    if ($domainAge >= 20) $score += 7;
    elseif ($domainAge >= 15) $score += 5;
    elseif ($domainAge >= 10) $score += 4;
    elseif ($domainAge >= 5) $score += 2;
    elseif ($domainAge > 0) $score += 1;
    
    // Media mentions (max 5 points)
    $mediaMentions = $reputation['mediaMentionsCount'] ?? 0;
    if ($mediaMentions >= 30) $score += 5;
    elseif ($mediaMentions >= 10) $score += 3;
    elseif ($mediaMentions >= 5) $score += 2;
    elseif ($mediaMentions > 0) $score += 1;
    
    // Social media presence (max 5 points)
    $socialCount = count($reputation['socialMediaFollowers'] ?? []);
    if ($socialCount >= 3) $score += 5;
    elseif ($socialCount >= 2) $score += 3;
    elseif ($socialCount >= 1) $score += 2;
    
    // --- Operational Indicators (max 30 points) ---
    $ops = $normalized['operationalIndicators'];
    
    if ($ops['inspectionOffered']) $score += 6;
    if ($ops['settlementFlexibility']) $score += 6;
    if (!$ops['hiddenFees']) $score += 6;
    
    // Clearance rate
    $clearance = $ops['clearanceRate'];
    if ($clearance === 'High') $score += 6;
    elseif ($clearance === 'Moderate') $score += 3;
    
    // Years operating bonus
    $years = $normalized['yearsOperating'];
    if ($years >= 40) $score += 6;
    elseif ($years >= 20) $score += 4;
    elseif ($years >= 10) $score += 2;
    
    // --- Complaint Deductions ---
    $complaints = $normalized['complaints'];
    if ($complaints['depositRefund']) $score -= 3;
    if ($complaints['transferDelay']) $score -= 3;
    if ($complaints['misrepresentation']) $score -= 5;
    
    // Clamp to 0-100
    return max(0, min(100, $score));
}

/**
 * Calculate Risk Score (0-100)
 * 
 * Higher score = higher risk
 * 
 * @param array $normalized Normalized auction data
 * @return int Risk score between 0 and 100
 */
function calculateRiskScore($normalized) {
    $risk = 0;
    
    // High premium increases risk (max 20 points)
    $premium = $normalized['buyerPremium'];
    if ($premium >= 15) $risk += 20;
    elseif ($premium >= 12) $risk += 12;
    elseif ($premium >= 10) $risk += 5;
    
    // Low compliance increases risk (max 25 points)
    $compliance = $normalized['complianceSignals'];
    $complianceCount = 0;
    foreach ($compliance as $signal) {
        if ($signal) $complianceCount++;
    }
    $compliancePct = $complianceCount / 7;
    $risk += round((1 - $compliancePct) * 25);
    
    // Low branch presence increases risk (max 15 points)
    $branchCount = count($normalized['branches']);
    if ($branchCount <= 1) $risk += 15;
    elseif ($branchCount <= 2) $risk += 8;
    elseif ($branchCount <= 3) $risk += 3;
    
    // Low Google rating increases risk (max 15 points)
    $googleRating = $normalized['reputationSignals']['googleRating'] ?? 0;
    if ($googleRating === 0 || $googleRating === null) {
        $risk += 10; // Unknown = moderate risk
    } elseif ($googleRating < 2) {
        $risk += 15;
    } elseif ($googleRating < 3) {
        $risk += 10;
    } elseif ($googleRating < 4) {
        $risk += 5;
    }
    
    // Complaint signals increase risk (max 15 points)
    $complaints = $normalized['complaints'];
    if ($complaints['depositRefund']) $risk += 4;
    if ($complaints['transferDelay']) $risk += 4;
    if ($complaints['misrepresentation']) $risk += 7;
    
    // Hidden fees increase risk (max 10 points)
    if ($normalized['operationalIndicators']['hiddenFees']) $risk += 10;
    
    // Low domain age increases risk
    $domainAge = $normalized['reputationSignals']['domainAgeYears'] ?? 0;
    if ($domainAge === 0 || $domainAge === null) $risk += 5;
    elseif ($domainAge < 5) $risk += 3;
    
    // Clamp to 0-100
    return max(0, min(100, $risk));
}

/**
 * Determine Institutional Grade based on Trust and Risk scores
 * 
 * @param int $trust Trust score
 * @param int $risk Risk score
 * @return string Grade (A, B, or C)
 */
function calculateGrade($trust, $risk) {
    if ($trust > 75 && $risk < 40) return 'A';
    if ($trust > 60) return 'B';
    return 'C';
}

/**
 * Get complete scoring for a normalized auction
 * 
 * @param array $normalized Normalized auction data
 * @return array ['trust' => int, 'risk' => int, 'grade' => string]
 */
function getScoring($normalized) {
    $trust = calculateTrustScore($normalized);
    $risk = calculateRiskScore($normalized);
    $grade = calculateGrade($trust, $risk);
    
    return [
        'trust' => $trust,
        'risk' => $risk,
        'grade' => $grade
    ];
}

/**
 * Get all auctions with their scores, normalized and scored
 * 
 * @return array Array of auctions with normalized data and scores
 */
function getAllScoredAuctions() {
    $normalized = normalizeAllAuctions();
    $scored = [];
    
    foreach ($normalized as $auction) {
        $scores = getScoring($auction);
        $auction['scores'] = $scores;
        $scored[] = $auction;
    }
    
    return $scored;
}

/**
 * Get a single scored auction by ID
 * 
 * @param int $id Auction ID
 * @return array|null Scored auction data or null
 */
function getScoredAuctionById($id) {
    $normalized = getNormalizedAuctionById($id);
    if ($normalized === null) return null;
    
    $scores = getScoring($normalized);
    $normalized['scores'] = $scores;
    
    return $normalized;
}

/**
 * Get grade color class for display
 * 
 * @param string $grade Grade letter
 * @return string Tailwind color class
 */
function getGradeColor($grade) {
    switch ($grade) {
        case 'A': return 'text-emerald-400';
        case 'B': return 'text-yellow-400';
        case 'C': return 'text-red-400';
        default: return 'text-slate-400';
    }
}

/**
 * Get grade background color class
 * 
 * @param string $grade Grade letter
 * @return string Tailwind background color class
 */
function getGradeBgColor($grade) {
    switch ($grade) {
        case 'A': return 'bg-emerald-500/20 border-emerald-500/30';
        case 'B': return 'bg-yellow-500/20 border-yellow-500/30';
        case 'C': return 'bg-red-500/20 border-red-500/30';
        default: return 'bg-slate-500/20 border-slate-500/30';
    }
}

/**
 * Get trust score color based on value
 * 
 * @param int $score Trust score
 * @return string Tailwind color class
 */
function getTrustColor($score) {
    if ($score >= 75) return 'text-emerald-400';
    if ($score >= 50) return 'text-yellow-400';
    return 'text-red-400';
}

/**
 * Get risk score color based on value
 * 
 * @param int $score Risk score
 * @return string Tailwind color class
 */
function getRiskColor($score) {
    if ($score < 30) return 'text-emerald-400';
    if ($score < 60) return 'text-yellow-400';
    return 'text-red-400';
}
