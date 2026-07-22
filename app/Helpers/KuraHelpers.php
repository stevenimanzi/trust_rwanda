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
        $localFallback = asset('assets/images/TrustRwanda-Logo.png');
        if (!$path) return $localFallback;

        $path = str_replace('\\', '/', trim($path));
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;

        $path = preg_replace('#^/?public/#', '', ltrim($path, '/'));
        $candidates = [];

        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'storage/')) {
            $candidates[] = $path;
        } elseif (str_starts_with($path, 'products/')) {
            $candidates[] = 'storage/'.$path;
            $candidates[] = 'assets/uploads/'.$path;
        } else {
            $candidates[] = 'assets/uploads/products/'.basename($path);
            $candidates[] = 'storage/products/'.basename($path);
        }

        foreach ($candidates as $candidate) {
            if (is_file(public_path($candidate))) {
                return asset($candidate);
            }
        }

        return $localFallback;
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
        if (!$path) return asset('assets/images/TrustRwanda-Logo.png');
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;

        $path = preg_replace('#^/?public/#', '', ltrim(str_replace('\\', '/', $path), '/'));
        foreach ([$path, 'assets/uploads/logos/'.basename($path), 'assets/images/'.basename($path)] as $candidate) {
            if (is_file(public_path($candidate))) return asset($candidate);
        }

        return asset('assets/images/TrustRwanda-Logo.png');
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

