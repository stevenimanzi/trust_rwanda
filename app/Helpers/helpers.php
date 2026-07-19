<?php

if (!function_exists('kura_product_image_url')) {
    function kura_product_image_url($storedPath, $fallback = 'https://placehold.co/300x300?text=No+Image') {
        if (empty($storedPath)) {
            return $fallback;
        }
        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }
        $normalized = str_replace('\\', '/', $storedPath);
        if (strpos($normalized, 'public/') === 0) {
            $normalized = substr($normalized, 7);
        }
        if (strpos($normalized, 'assets/') === 0) {
            return asset($normalized);
        }
        if (strpos($normalized, 'uploads/') === 0) {
            return asset('assets/' . $normalized);
        }
        return asset('assets/uploads/products/' . basename($normalized));
    }
}

if (!function_exists('kura_logo_image_url')) {
    function kura_logo_image_url($storedPath, $fallback = 'https://placehold.co/200x200?text=Logo') {
        if (empty($storedPath)) {
            return $fallback;
        }
        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }
        $normalized = str_replace('\\', '/', $storedPath);
        if (strpos($normalized, 'public/') === 0) {
            $normalized = substr($normalized, 7);
        }
        if (strpos($normalized, 'assets/') === 0) {
            return asset($normalized);
        }
        if (strpos($normalized, 'uploads/') === 0) {
            return asset('assets/' . $normalized);
        }
        return asset('assets/uploads/logos/' . basename($normalized));
    }
}
