<?php
/**
 * Auction Atlas - Logo Helper Functions
 * 
 * Maps auction house names to logo files
 */

/**
 * Map of auction house names to logo filenames
 */
function getAuctionHouseLogos(): array {
    return [
        'ambassador-auction.png' => ['Ambassador Auctioneers'],
        'Aucor.png' => ['Aucor Auctioneers', 'Aucor Auctioneers Bloemfontein'],
        'auction-nation.png' => ['Auction Nation', 'Auction Nation Cape Town'],
        'auction-operation.png' => ['Auction Operation'],
        'auctioninc.png' => ['AuctionInc'],
        'bernadi-auctioneers.png' => ['Bernardi Auctioneers'],
        'bidders choice.png' => ['Bidders Choice'],
        'cahi.png' => ['Cahi Auctioneers'],
        'gobid.png' => ['GoBid'],
        'grand-oak-auctions.png' => ['Grand Oak Auctions'],
        'highstreet-auction.png' => ['High Street Auctions'],
        'Johannesburg-auctioneers.png' => ['Johannesburg Auctioneers'],
        'marietjie-keet.png' => ['Marietjie Keet Auctioneers'],
        'nuco.png' => ['Nuco Auctioneers'],
        'old-johanessburg-warehouse-auctions.png' => ['Old Johannesburg Warehouse Auctioneers'],
        'prime-auctions.png' => ['Prime Auctions'],
        'sa-group-auctions.png' => ['SA Auction Group', 'SA Group'],
        'stephen-welz.png' => ['Stephan Welz & Co'],
        'strauss&co.png' => ['Strauss & Co Fine Art Auctioneers'],
        'traveling-auctioneers.png' => ['Traveling Auctioneers SA'],
        'vans-auctions.png' => ['Vans Auctioneers'],
        'wct-auctions.png' => ['WCT Auctions'],
        'westgate-walding-auctions.png' => ['Westgate Walding Auctioneers'],
        'wh-auctions.png' => ['WH Auctioneers'],
    ];
}

/**
 * Get logo URL for an auction house
 */
function getAuctionHouseLogo(string $auctionHouseName): ?string {
    $logos = getAuctionHouseLogos();
    
    foreach ($logos as $filename => $names) {
        foreach ($names as $name) {
            if (stripos($auctionHouseName, $name) !== false || stripos($name, $auctionHouseName) !== false) {
                return '/assets/logos/' . $filename;
            }
        }
    }
    
    return null;
}

/**
 * Get placeholder logo URL
 */
function getPlaceholderLogo(): string {
    return '/assets/icons/auction-atlas.svg';
}

/**
 * Get logo (returns logo URL or placeholder)
 */
function getLogo(string $auctionHouseName): string {
    $logo = getAuctionHouseLogo($auctionHouseName);
    return $logo ?? getPlaceholderLogo();
}
