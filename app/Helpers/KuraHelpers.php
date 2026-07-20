<?php

if (!function_exists('kura_rating_icon_html')) {
    function kura_rating_icon_html(float $avgRating): string {
        $avgRating = max(0, min(5, $avgRating));
        $fullStars = (int) floor($avgRating);
        $halfStar = (($avgRating - $fullStars) >= 0.5) ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        return str_repeat('<i class="bi bi-star-fill text-warning"></i>', $fullStars)
            . ($halfStar ? '<i class="bi bi-star-half text-warning"></i>' : '')
            . str_repeat('<i class="bi bi-star text-warning"></i>', $emptyStars);
    }
}

if (!function_exists('kura_product_image_url')) {
    function kura_product_image_url($path, $fallback = '') {
        if (!$path) return asset('assets/images/placeholder.png');
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        return asset('assets/uploads/products/' . ltrim($path, '/'));
    }
}

if (!function_exists('kura_csrf_input')) {
    function kura_csrf_input($scope='default') {
        return csrf_field();
    }
}

if (!function_exists('kura_csrf_token')) {
    function kura_csrf_token($scope='default') {
        return csrf_token();
    }
}

if (!function_exists('kura_logo_image_url')) {
    function kura_logo_image_url($path, $fallback = '') {
        return asset($path ? 'assets/images/' . $path : 'assets/images/KURA-Logo.jpg');
    }
}

if (!function_exists('imanzi_language_url')) {
    function imanzi_language_url($langCode) {
        $queryParams = request()->query();
        $queryParams['lang'] = $langCode;
        return url()->current() . '?' . http_build_query($queryParams);
    }
}

if (!function_exists('imanzi_language_label')) {
    function imanzi_language_label($langCode) {
        $labels = ['en' => 'English', 'rw' => 'Kinyarwanda', 'fr' => 'Fran�ais'];
        return $labels[$langCode] ?? strtoupper($langCode);
    }
}


if (!function_exists('kura_default_logo_image_url')) {
    function kura_default_logo_image_url() {
        return asset('assets/images/TrustRwanda-Logo.png');
    }
}

