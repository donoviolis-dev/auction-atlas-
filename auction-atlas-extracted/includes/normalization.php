<?php
/**
 * Auction Atlas - Data Normalization Layer
 * 
 * Normalizes raw auction data into a consistent format
 * for use across all scoring and display systems.
 */

require_once __DIR__ . '/functions.php';

/**
 * Normalize a single auction record into a standardized format
 * 
 * @param array $auction Raw auction data from JSON
 * @return array Normalized auction data
 */
function normalizeAuction($auction) {
    return [
        'id' => $auction['id'] ?? null,
        'name' => $auction['auction_house_name'] ?? '',
        'legal_name' => $auction['legal_name'] ?? '',
        'website' => $auction['website_url'] ?? '',
        'address' => $auction['address'] ?? '',
        'city' => $auction['city'] ?? '',
        'province' => $auction['province'] ?? '',
        'email' => $auction['contact_email'] ?? '',
        'phone' => $auction['contact_phone'] ?? '',
        'yearsOperating' => parseYears($auction['years_operating'] ?? '0'),
        'yearsOperatingRaw' => $auction['years_operating'] ?? '',
        'companyType' => $auction['company_type'] ?? '',
        'branches' => $auction['collection_locations'] ?? [],
        'provinces' => $auction['provincial_reach'] ?? [],
        'categories' => array_map('normalizeCategory', $auction['categories_auctioned'] ?? []),
        'auctionTypes' => $auction['auction_types'] ?? [],
        'auctionFormat' => $auction['auction_format'] ?? '',
        'auctionFrequency' => $auction['auction_frequency'] ?? '',
        'nationalOrRegional' => $auction['national_or_regional'] ?? '',
        'bestForTags' => $auction['best_for_tags'] ?? [],
        'buyerPremium' => parsePremium($auction['operational_metrics']['buyer_premium_percent'] ?? null),
        'buyerPremiumRaw' => $auction['operational_metrics']['buyer_premium_percent'] ?? 'N/A',
        'deposit' => $auction['operational_metrics']['deposit_percent'] ?? $auction['operational_metrics']['deposit_amount'] ?? 'N/A',
        'adminFees' => $auction['operational_metrics']['admin_fees'] ?? 'N/A',
        'vatOnPremium' => $auction['operational_metrics']['vat_on_premium'] ?? 'N/A',
        'hiddenFees' => $auction['operational_metrics']['hidden_fees'] ?? 'N/A',
        'transferDays' => $auction['operational_metrics']['avg_transfer_days'] ?? 'N/A',
        'refundTime' => $auction['operational_metrics']['avg_refund_time'] ?? 'N/A',
        'paymentWindow' => $auction['operational_metrics']['payment_window_days'] ?? 'N/A',
        'inspectionOffered' => $auction['operational_metrics']['inspection_offered'] ?? 'N/A',
        'clearanceRate' => $auction['operational_metrics']['clearance_rate'] ?? 'N/A',
        'storagePenalties' => $auction['operational_metrics']['storage_penalties'] ?? 'N/A',
        'settlementFlexibility' => $auction['operational_metrics']['settlement_flexibility'] ?? 'N/A',
        'discountCompetitiveness' => $auction['operational_metrics']['discount_competitiveness'] ?? 'N/A',
        
        // Compliance signals
        'complianceSignals' => [
            'https' => ($auction['https_secure'] ?? '') === 'Yes',
            'vatRegistered' => ($auction['vat_registered'] ?? '') === 'Yes',
            'estateLicense' => ($auction['estate_license'] ?? '') === 'Yes',
            'popiaPolicy' => ($auction['popia_policy'] ?? '') === 'Yes',
            'termsPage' => ($auction['terms_page'] ?? '') === 'Yes',
            'refundPolicy' => ($auction['refund_policy'] ?? '') === 'Yes',
            'licensingClaims' => ($auction['licensing_claims'] ?? '') === 'Yes',
        ],
        
        // Reputation signals
        'reputationSignals' => [
            'googleRating' => $auction['reputation_signals']['google_rating'] ?? null,
            'googleReviewCount' => $auction['reputation_signals']['google_review_count'] ?? null,
            'domainAgeYears' => $auction['reputation_signals']['domain_age_years'] ?? null,
            'mediaMentionsCount' => $auction['reputation_signals']['media_mentions_count'] ?? null,
            'mediaMentionsSample' => $auction['reputation_signals']['media_mentions_sample'] ?? [],
            'socialMediaFollowers' => $auction['reputation_signals']['social_media_followers'] ?? [],
        ],
        
        // Operational indicators
        'operationalIndicators' => [
            'inspectionOffered' => ($auction['operational_metrics']['inspection_offered'] ?? '') === 'Yes',
            'settlementFlexibility' => ($auction['operational_metrics']['settlement_flexibility'] ?? '') === 'Yes',
            'hiddenFees' => ($auction['operational_metrics']['hidden_fees'] ?? '') === 'Yes',
            'clearanceRate' => $auction['operational_metrics']['clearance_rate'] ?? 'Moderate',
            'storagePenalties' => ($auction['operational_metrics']['storage_penalties'] ?? '') === 'Yes',
        ],
        
        // Complaints
        'complaints' => [
            'depositRefund' => ($auction['complaints_incidents']['deposit_refund'] ?? '') === 'Yes',
            'transferDelay' => ($auction['complaints_incidents']['transfer_delay'] ?? '') === 'Yes',
            'misrepresentation' => ($auction['complaints_incidents']['misrepresentation'] ?? '') === 'Yes',
            'disputeRatio' => $auction['complaints_incidents']['dispute_ratio'] ?? null,
            'rightOfReply' => ($auction['complaints_incidents']['right_of_reply'] ?? '') === 'Yes',
        ],
        
        // Intelligence fields (pre-existing if any)
        'intelligenceFields' => $auction['intelligence_fields'] ?? [],
    ];
}

/**
 * Normalize all auction data
 * 
 * @return array Array of normalized auction records
 */
function normalizeAllAuctions() {
    $data = loadAuctionData();
    $normalized = [];
    foreach ($data as $auction) {
        $normalized[] = normalizeAuction($auction);
    }
    return $normalized;
}

/**
 * Get normalized auction by ID
 * 
 * @param int $id Auction ID
 * @return array|null Normalized auction data or null
 */
function getNormalizedAuctionById($id) {
    $auction = getAuctionById($id);
    if ($auction === null) return null;
    return normalizeAuction($auction);
}
