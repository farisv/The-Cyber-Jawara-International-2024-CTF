<?php

require 'vendor/autoload.php';

use League\Uri\Uri;
use League\Uri\UriResolver;
use Symfony\Component\Intl\Countries;

function get_country_image_path($country_code) {
    $base = '/var/www/html/repository/country/';
    $svg_file = strtolower($country_code) . '.png';

    $base_path = Uri::createFromString($base);
    $svg_file_path = Uri::createFromString($svg_file);

    $resolved_path = UriResolver::resolve($svg_file_path, $base_path);

    // Protect against path traversal
    if (strpos($resolved_path->getPath(), $base) !== 0) {
        // Invalid directory, return fallback
        return '/var/www/html/repository/country/id.png';
    } else {
        return (string) $resolved_path;
    }
}

function get_countries_with_code() {
    return Countries::getNames();
}

function get_country_name($country_code, string $locale = 'en') {
    $country_code = strtoupper($country_code);
    if (Countries::exists($country_code)) {
        return Countries::getName($country_code, $locale);
    }
    return 'N/A';
}