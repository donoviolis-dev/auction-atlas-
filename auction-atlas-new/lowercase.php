<?php
/**
 * URL Lowercase Redirect Handler
 * Converts uppercase URLs to lowercase for SEO consistency
 */

// Get the requested URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Convert to lowercase
$lowercaseUrl = strtolower($url);

// Redirect to lowercase URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = 'auctionatlas.co.za';
$baseUrl = $protocol . '://' . $host;

$redirectUrl = $baseUrl . '/' . ltrim($lowercaseUrl, '/');

// 301 redirect to lowercase URL
header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $redirectUrl);
exit;
