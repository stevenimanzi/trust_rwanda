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
        return asset('assets/images/products/' . $path);
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

$navCategories = [
    'rw' => [
        'farmers' => [
            'title' => 'Abahinzi & Umusaruro',
            'icon' => 'bi-flower1',
            'link' => 'farmers-market',
            'subs' => [
                'farmers-market' => 'Umusaruro Wose',
                'farmers-market?category=fruits' => 'Imbuto Nshya (Fruits)',
                'farmers-market?category=vegetables' => 'Imboga Zicyeye',
                'farmers-market?category=grains' => 'Impeke & Ibinyampeke',
                'farmers-market?category=dairy' => 'Amata & Ubuki (Dairy)',
                'farmers-market?category=meat' => 'Inyama & Amatungo Magufi',
            ]
        ],
        'real_estate' => [
            'title' => 'Imitungo Itimukanwa',
            'icon' => 'bi-buildings',
            'link' => 'real_estate',
            'subs' => [
                'real_estate' => 'Imitungo Yose',
                'real_estate?category=rent-house' => 'Inzu Zikodeshwa (Houses)',
                'real_estate?category=rent-apartment' => 'Fleti Zikodeshwa (Apartments)',
                'real_estate?category=rent-guest-house' => 'Inzu z\'Abashyitsi (Guest House)',
                'real_estate?category=rent-ghetto' => 'Ghetto Zikodeshwa',
                'real_estate?category=sale-house' => 'Inzu Zigurishwa',
                'real_estate?category=sale-land' => 'Ibibanza Bigurishwa (Lands)',
            ]
        ],
        'electronics' => [
            'title' => 'Elegitoroniki',
            'icon' => 'bi-laptop',
            'link' => 'products?category=electronics',
            'subs' => [
                'products?category=electronics' => 'Elegitoroniki Yose',
                'products?category=mobile-phones' => 'Telefoni Zikoreshwa',
                'products?category=laptops-computers' => 'Orudinateri & Laptops',
                'products?category=tablets' => 'Tabuleti (Tablets)',
                'products?category=smartwatches' => 'Isaha z\'Ubwenge (Smartwatch)',
                'products?category=accessories' => 'Ibikoresho by\'Umva & Umugozi',
                'products?category=tv-systems' => 'Televiziyo & Indangururamajwi',
                'products?category=speakers-audio' => 'Imizindaro (Speakers)',
                'products?category=gaming' => 'Imikino ya Videwo (Gaming)',
                'products?category=smart-home' => 'Ibikoresho By\'ubwenge byo mu rugo',
            ]
        ],
        'second_hand' => [
            'title' => 'Ibyakoreshejwe',
            'icon' => 'bi-recycle',
            'link' => 'products?category=second-hand',
            'subs' => [
                'products?category=second-hand' => 'Ibyakoreshejwe Byose',
                'products?category=used-vehicles' => 'Imodoka & Moto zikoreshejwe',
                'products?category=used-mobile-phones' => 'Telefoni za Caguwa',
                'products?category=used-laptops' => 'Laptops Zikoreshejwe',
                'products?category=used-televisions' => 'TV Zikoreshejwe',
                'products?category=used-furniture' => 'Intebe & Ameza Zikoreshejwe',
                'products?category=used-electronics' => 'Elegitoroniki Zikoreshejwe',
            ]
        ],
        'affiliate' => [
            'title' => 'Invite & Earn',
            'icon' => 'bi-share-fill',
            'link' => 'affiliate',
            'subs' => [
                'affiliate' => 'Iyandikishe',
                'affiliate?tab=products' => 'Ibikorwa Byo Kwinjiza',
                'affiliate?tab=how' => 'Uko Bikora',
                'affiliate?tab=tools' => 'Ibikoresho by\'Idirishya',
            ]
        ],
        'nearby_shops' => [
            'title' => 'Amaduka Hafi Yanjye',
            'icon' => 'bi-geo-alt-fill',
            'link' => 'nearby-shops',
            'subs' => [
                'nearby-shops' => 'Amaduka Yose ya Hafi'
            ]
        ]
    ],
    'en' => [
        'farmers' => [
            'title' => 'Farmers & Harvest',
            'icon' => 'bi-flower1',
            'link' => 'farmers-market',
            'subs' => [
                'farmers-market' => 'All Harvest',
                'farmers-market?category=fruits' => 'Fresh Fruits',
                'farmers-market?category=vegetables' => 'Fresh Vegetables',
                'farmers-market?category=grains' => 'Cereals & Grains',
                'farmers-market?category=dairy' => 'Dairy & Honey',
                'farmers-market?category=meat' => 'Meat & Poultry',
            ]
        ],
        'real_estate' => [
            'title' => 'Real Estate',
            'icon' => 'bi-buildings',
            'link' => 'real_estate',
            'subs' => [
                'real_estate' => 'All Properties',
                'real_estate?category=rent-house' => 'Houses for Rent',
                'real_estate?category=rent-apartment' => 'Apartments for Rent',
                'real_estate?category=rent-guest-house' => 'Guest Houses',
                'real_estate?category=rent-ghetto' => 'Ghettos for Rent',
                'real_estate?category=sale-house' => 'Houses for Sale',
                'real_estate?category=sale-land' => 'Lands & Plots for Sale',
            ]
        ],
        'electronics' => [
            'title' => 'Electronics',
            'icon' => 'bi-laptop',
            'link' => 'products?category=electronics',
            'subs' => [
                'products?category=electronics' => 'All Electronics',
                'products?category=mobile-phones' => 'Smartphones & Tablets',
                'products?category=laptops-computers' => 'Laptops & Computers',
                'products?category=tablets' => 'Tablets & E-readers',
                'products?category=smartwatches' => 'Smartwatches & Wearables',
                'products?category=accessories' => 'Accessories & Headphones',
                'products?category=tv-systems' => 'TV & Home Systems',
                'products?category=speakers-audio' => 'Speakers & Audio',
                'products?category=gaming' => 'Gaming & Consoles',
                'products?category=smart-home' => 'Smart Home Devices',
            ]
        ],
        'second_hand' => [
            'title' => 'Second Hand',
            'icon' => 'bi-recycle',
            'link' => 'products?category=second-hand',
            'subs' => [
                'products?category=second-hand' => 'All Used Goods',
                'products?category=used-vehicles' => 'Used Vehicles & Motos',
                'products?category=used-mobile-phones' => 'Used Mobile Phones',
                'products?category=used-laptops' => 'Used Laptops & Computers',
                'products?category=used-televisions' => 'Used Televisions',
                'products?category=used-furniture' => 'Pre-owned Furniture',
                'products?category=used-electronics' => 'Used Electronics',
            ]
        ],
        'affiliate' => [
            'title' => 'Invite & Earn',
            'icon' => 'bi-share-fill',
            'link' => 'affiliate',
            'subs' => [
                'affiliate' => 'Affiliate Dashboard',
                'affiliate?tab=products' => 'Products to Share',
                'affiliate?tab=how' => 'How It Works',
                'affiliate?tab=tools' => 'Marketing Tools',
            ]
        ],
        'nearby_shops' => [
            'title' => 'Near Me Shops',
            'icon' => 'bi-geo-alt-fill',
            'link' => 'nearby-shops',
            'subs' => [
                'nearby-shops' => 'All Nearby Shops'
            ]
        ]
    ],
    'sw' => [
        'farmers' => [
            'title' => 'Wakulima na Mavuno',
            'icon' => 'bi-flower1',
            'link' => 'farmers-market',
            'subs' => [
                'farmers-market' => 'Mavuno Yote',
                'farmers-market?category=fruits' => 'Matunda Mapya',
                'farmers-market?category=vegetables' => 'Mboga Mpya',
                'farmers-market?category=grains' => 'Nafaka',
                'farmers-market?category=dairy' => 'Maziwa na Asali',
                'farmers-market?category=meat' => 'Nyama na Kuku',
            ]
        ],
        'real_estate' => [
            'title' => 'Mali isiyohamishika',
            'icon' => 'bi-buildings',
            'link' => 'real_estate',
            'subs' => [
                'real_estate' => 'Mali Zote',
                'real_estate?category=rent-house' => 'Nyumba za Kupanga',
                'real_estate?category=rent-apartment' => 'Fleti za Kupanga',
                'real_estate?category=rent-guest-house' => 'Nyumba za Wageni',
                'real_estate?category=rent-ghetto' => 'Ghetto za Kupanga',
                'real_estate?category=sale-house' => 'Nyumba za Kuuza',
                'real_estate?category=sale-land' => 'Viwanja vya Kuuza',
            ]
        ],
        'electronics' => [
            'title' => 'Elektroniki',
            'icon' => 'bi-laptop',
            'link' => 'products?category=electronics',
            'subs' => [
                'products?category=electronics' => 'Elektroniki Zote',
                'products?category=mobile-phones' => 'Simu na Vidonge',
                'products?category=laptops-computers' => 'Kompyuta na Laptops',
                'products?category=tablets' => 'Vidonge (Tablets)',
                'products?category=smartwatches' => 'Saa za Kisasa (Smartwatch)',
                'products?category=accessories' => 'Vifaa vya Masikio & Headphones',
                'products?category=tv-systems' => 'TV na Mifumo ya Sauti',
                'products?category=speakers-audio' => 'Spika na Sauti',
                'products?category=gaming' => 'Michezo (Gaming)',
                'products?category=smart-home' => 'Vifaa vya Nyumbani vya Kisasa',
            ]
        ],
        'second_hand' => [
            'title' => 'Bidhaa Zilizotumika',
            'icon' => 'bi-recycle',
            'link' => 'products?category=second-hand',
            'subs' => [
                'products?category=second-hand' => 'Bidhaa Zote',
                'products?category=used-vehicles' => 'Magari na Pikipiki Zilizotumika',
                'products?category=used-mobile-phones' => 'Simu Zilizotumika',
                'products?category=used-laptops' => 'Kompyuta Zilizotumika',
                'products?category=used-televisions' => 'TV Zilizotumika',
                'second-hand?category=used-furniture' => 'Samani Zilizotumika',
                'products?category=used-electronics' => 'Elektroniki Zilizotumika',
            ]
        ],
        'affiliate' => [
            'title' => 'Invite & Earn',
            'icon' => 'bi-share-fill',
            'link' => 'affiliate',
            'subs' => [
                'affiliate' => 'Dashibodi ya Washirika',
                'affiliate?tab=products' => 'Bidhaa za Kushiriki',
                'affiliate?tab=how' => 'Jinsi Inavyofanya Kazi',
                'affiliate?tab=tools' => 'Zana za Uuzaji',
            ]
        ],
        'nearby_shops' => [
            'title' => 'Maduka Karibu Nami',
            'icon' => 'bi-geo-alt-fill',
            'link' => 'nearby-shops',
            'subs' => [
                'nearby-shops' => 'Maduka Yote ya Karibu'
            ]
        ]
    ]
];

// Fallback logic for langData
$langData = $navCategories[App::getLocale()] ?? $navCategories['en'];

if (!function_exists('isActive')) {
    function isActive($routeKey, $category = null) {
        $currentRoute = $_GET['route'] ?? '';
        if ($currentRoute === '' || $currentRoute === 'home') $currentRoute = 'home';
        
        if ($category !== null) {
            return ($currentRoute === $routeKey && ($_GET['category'] ?? '') === $category) ? 'active' : '';
        }
        return ($currentRoute === $routeKey && !isset($_GET['category'])) ? 'active' : '';
    }
}

if (!function_exists('isActiveLink')) {
    function isActiveLink($link) {
        $currentRoute = $_GET['route'] ?? '';
        if ($currentRoute === '' || $currentRoute === 'home') $currentRoute = 'home';

        $linkParts = parse_url($link);
        parse_str($linkParts['query'] ?? '', $linkParams);
        
        if (isset($linkParams['route']) && $linkParams['route'] === $currentRoute) {
            if (isset($linkParams['category'])) {
                return (isset($_GET['category']) && $_GET['category'] === $linkParams['category']) ? 'active' : '';
            }
            return !isset($_GET['category']) ? 'active' : '';
        }
        return '';
    }
}
?>

<?php
$cart = session('cart', []);
$cartCount = is_array($cart) ? array_sum($cart) : 0;
$hideNav = false;
$currentUser = auth()->user();
$userRole = $currentUser ? $currentUser->role : 'guest';
$isDashboardUser = false;
$siteLogo = asset('assets/uploads/logos/TrustRwanda-Logo.png?v=2');
$siteFavicon = asset('assets/uploads/logos/TrustRwanda-Logo.png?v=2');
$siteName = 'Trust Rwanda';
$metaDesc = 'Shop premium real estate, electronics, and more.';
$supportEmail = 'support@trustrwanda.com';
$cleanPhone = '250780000000';
$footerCategories = [];
?><!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ config('app.name', 'Trust Rwanda') }} | Rwanda's Trusted Marketplace</title>
    
    <meta name="description" content="{{$metaDesc}}">
    <meta property="og:image" content="{{$siteLogo}}">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Trust Rwanda') }}">
    <meta name="mobile-web-app-capable" content="yes">
    
    <link rel="icon" type="image/x-icon" href="{{$siteFavicon}}">
    <link rel="apple-touch-icon" href="{{$siteFavicon}}">
    <link rel="manifest" href="{{url('/')}}/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/2.4.0/Control.FullScreen.css" />

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @yield('styles')
    
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-hover: #1d4ed8;
            --secondary: #0f172a;
            --nav-height: 110px;
            --glass: rgba(255, 255, 255, 0.98);
            --radius-md: 14px;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
            padding-top: {{$hideNav ? '0' : 'calc(var(--nav-height) + 10px)'}};
            margin: 0;
            overflow-x: hidden;
            max-width: 100%;
        }
        html {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* ════════ NAVBAR PRO (SPACING & DROPDOWNS) ════════ */
        .navbar-pro {
            background: var(--glass);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: fixed; top: 0; left: 0; right: 0; z-index: 1050;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            display: flex; flex-direction: column;
            width: 100%;
            padding: 0;
        }
        
        /* Spacing between items in top row */
        .navbar-shell {
            max-width: 1440px;
            margin: 0 auto;
            padding: 12px 30px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px; /* Enhanced separation */
        }

        .brand-logo { text-decoration: none !important; display: flex; align-items: center; white-space: nowrap; }
        .logo-img { height: 48px; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .brand-logo:hover .logo-img { transform: scale(1.04); }

        /* Search Form Styling */
        .header-search-form {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            border-radius: 50px;
            padding: 2px 6px 2px 16px;
            width: 480px; /* Generous width */
            transition: all 0.2s ease;
        }
        .header-search-form:focus-within {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.08);
        }
        .header-search-form input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.88rem;
            color: var(--secondary);
            font-weight: 500;
        }
        .header-search-form button {
            border: none;
            background: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .header-search-form button:hover {
            background: var(--primary-hover);
        }

        .nav-link-pro {
            color: #64748b; font-weight: 700; padding: 0.6rem 0.9rem; 
            border-radius: 12px; transition: 0.3s; 
            text-decoration: none; white-space: nowrap !important; font-size: 0.9rem;
            display: flex; align-items: center; gap: 8px;
        }
        .nav-link-pro:hover { color: var(--primary); background: #f1f5f9; }
        .nav-link-pro.active { color: var(--primary); background: #eff6ff; }

        .icon-btn-pro {
            width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
            border-radius: 12px; border: 1.5px solid #e2e8f0; color: var(--secondary);
            position: relative; transition: 0.2s; background: white; text-decoration: none;
            flex-shrink: 0;
        }
        .icon-btn-pro:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }
        
        .badge-count {
            position: absolute; top: -5px; right: -5px; background: #ef4444; color: white;
            font-size: 0.62rem; font-weight: 800; width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; border: 2px solid white;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
        }

        .btn-join {
            background: linear-gradient(135deg, var(--primary), #3b82f6); 
            color: white !important; font-weight: 800; 
            border-radius: 12px; padding: 0.55rem 1.3rem; text-decoration: none; transition: 0.3s;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
            white-space: nowrap !important;
            border: none;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
        }
        .btn-join:hover { 
            background: linear-gradient(135deg, #1d4ed8, var(--primary)); 
            transform: translateY(-1px); 
            box-shadow: 0 8px 16px rgba(30, 58, 138, 0.3);
            color: white !important;
        }

        .navbar .dropdown {
            position: relative;
        }

        .navbar .dropdown-menu {
            z-index: 1060 !important;
        }

        /* ════════ MARKETPLACE SUBBAR & DROPDOWNS ════════ */
        .market-subbar {
            border-top: 1px solid #f1f5f9;
            background: #ffffff;
            width: 100%;
            padding: 8px 0;
            z-index: 1040;
        }
        
        .market-subbar-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 15px;
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }
        .market-subbar-container::-webkit-scrollbar {
            display: none;
        }
        
        @media (min-width: 992px) {
            .market-subbar,
            .market-subbar-container {
                overflow: visible !important;
            }
        }
        
        /* Dropdown Structure inside pills */
        .market-dropdown {
            position: relative;
        }
        
        .market-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .market-pill-btn:hover, .market-pill-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.15);
        }
        .market-pill-btn.affiliate-pill {
            background: #fef3c7;
            color: #b45309;
            border-color: #fde68a;
        }
        .market-pill-btn.affiliate-pill:hover, .market-pill-btn.affiliate-pill.active {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
        }

        /* Dropdown Card overlay */
        .market-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none; /* Controlled strictly via toggle class .show */
            min-width: 280px;
            padding: 8px;
            margin-top: 8px;
            background-color: white;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: var(--radius-md);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(10px);
            animation: slideDownMenu 0.15s ease-out;
        }

        /* Toggle Display State via JS (Disable hover) */
        .market-dropdown-menu.show {
            display: block !important;
        }

        @keyframes slideDownMenu {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .market-dropdown-menu .dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            color: #475569;
            font-weight: 600;
            font-size: 0.82rem;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .market-dropdown-menu .dropdown-item:hover {
            background: #eff6ff;
            color: var(--primary);
            transform: translateX(2px);
        }

        /* 📱 Responsive overrides */
        @media (max-width: 991px) {
            :root { --nav-height: 160px; }
            .navbar-pro { height: auto; }
            .logo-img { height: 36px; }
            .navbar-collapse { 
                background: white; 
                padding: 1rem; 
                border-radius: 16px; 
                margin-top: 10px; 
                border: 1px solid #f1f5f9; 
                box-shadow: 0 15px 30px rgba(0,0,0,0.08); 
            }
            .navbar-shell { padding: 8px 15px; gap: 15px; }
            .d-mobile-full { width: 100%; display: flex; flex-direction: column; gap: 8px; margin-top: 10px; }
            .d-mobile-full a, .d-mobile-full .dropdown, .d-mobile-full button { width: 100%; text-align: center; }
            .d-mobile-full .dropdown-toggle { max-width: 100% !important; justify-content: center; }
            .dropdown-menu { width: 100% !important; border: 1px solid #eee; margin-top: 5px !important; }
            
            .market-dropdown-menu {
                position: static;
                width: 100% !important;
                display: none;
                padding: 15px;
                box-shadow: none;
                border: none;
                background: #f8fafc;
            }
        }
    </style>
    <!-- Leaflet mapping library loaded globally (No API keys required) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
</head>
<body>

@if(!$hideNav)
    <nav class="navbar navbar-expand-lg navbar-pro">
        <!-- Top Row -->
        <div class="navbar-shell">
            <a class="brand-logo" href="{{route('home')}}">
                <img src="{{$siteLogo}}" alt="{{ config('app.name', 'Trust Rwanda') }}" class="logo-img">
            </a>

            <!-- Search Bar Desktop (Center) -->
            <form action="{{url('/')}}/index.php" method="GET" class="header-search-form d-none d-lg-flex">
                
                <input type="text" name="q" placeholder="Search for anything..." value="{{$_GET['q'] ?? ''}}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

            <!-- Actions Separation -->
            <div class="d-flex align-items-center gap-3">
                <!-- 🌐 Direct Visible Language Switcher Pill -->
                <div class="dropdown">
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3 py-1.5 d-flex align-items-center gap-1.5 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.78rem; border-color: #e2e8f0; color: #475569; background: white;">
                        <i class="bi bi-globe"></i> <span>{{strtoupper(App::getLocale())}}</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.55rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2 mt-2" style="border-radius: 12px; min-width: 140px; z-index: 1060;">
                        @foreach(['rw', 'en', 'sw'] as $langCode)
                            <li>
                                <a class="dropdown-item rounded-3 py-2 fw-bold d-flex align-items-center gap-2 {{$langCode === App::getLocale() ? 'bg-primary-subtle text-primary' : 'text-secondary'}}" href="{{imanzi_language_url($langCode)}}" style="font-size: 0.8rem;">
                                    <i class="bi bi-globe text-muted"></i>
                                    <span>{{imanzi_language_label($langCode)}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Shopping Cart -->
                <a href="{{url('/')}}/cart" class="icon-btn-pro border shadow-sm">
                    <i class="bi bi-bag-heart fs-5"></i>
                    <span class="badge-count cart-badge" style="{{($cartCount > 0) ? 'display:flex' : 'display:none'}}">{{$cartCount}}</span>
                </a>

                <!-- Hamburger menu button (Mobile only) -->
                <button class="navbar-toggler border-0 p-0 shadow-none d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                    <i class="bi bi-list fs-3 text-secondary"></i>
                </button>
            </div>

            <!-- Desktop Drawer Actions -->
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1 d-lg-none">
                    <li class="nav-item"><a class="nav-link-pro {{isActive('home')}}" href="{{route('home')}}"><i class="bi bi-house"></i> {{__('home')}}</a></li>
                    <li class="nav-item"><a class="nav-link-pro {{isActive('products')}}" href="{{url('/')}}/products"><i class="bi bi-grid"></i> Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link-pro" href="{{url('/')}}/profile"><i class="bi bi-person"></i> My Dashboard</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 ms-lg-auto d-mobile-full">
                    <!-- Dynamic Logged User Actions -->
                    @if($currentUser)
                        <a href="{{url('/')}}/affiliate" class="btn btn-sm btn-outline-warning rounded-pill fw-bold px-3 py-2 text-decoration-none d-none d-lg-inline-flex align-items-center gap-1" style="font-size: 0.8rem; border-color: #f59e0b; color: #b45309;">
                            <i class="bi bi-gift-fill text-warning"></i> Invite & Earn
                        </a>

                        <div class="dropdown">
                            <a href="#" class="icon-btn-pro p-0 border-0 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{urlencode($currentUser['full_name'])}}&background=1e3a8a&color=fff&bold=true" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 18px; min-width: 220px; z-index: 1060;">
                                <?php if($userRole === 'admin'): ?>
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{url('/')}}/admin/dashboard"><i class="bi bi-shield-lock-fill me-2"></i> {{__('admin_panel')}}</a></li>
                                @elseif($userRole === 'vendor')
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{url('/')}}/vendor/dashboard"><i class="bi bi-shop-window me-2"></i> {{__('vendor_panel')}}</a></li>
                                @elseif($userRole === 'real_estate_owner')
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{url('/')}}/property_owner/dashboard"><i class="bi bi-houses me-2"></i> Owner Dashboard</a></li>
                                @endif
                                <li><a class="dropdown-item rounded-3 py-2" href="{{url('/')}}/profile"><i class="bi bi-person-circle me-2"></i> {{__('account_settings')}}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 text-danger fw-bold py-2" href="{{url('/')}}/api/logout"><i class="bi bi-power me-2"></i> {{__('sign_out')}}</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{url('/')}}/login" class="btn btn-login-pro text-secondary fw-bold" style="font-size: 0.85rem;">{{__('log_in')}}</a>
                        <a href="{{url('/')}}/register" class="btn-join">{{__('join_kura')}}</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search Bar Mobile Row -->
        <div class="w-100 px-3 pb-2 d-lg-none">
            <form action="{{url('/')}}/index.php" method="GET" class="header-search-form w-100">
                
                <input type="text" name="q" placeholder="Search for anything..." value="{{$_GET['q'] ?? ''}}" style="height: 38px;">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <!-- Bottom Row: Category dropdown sub-bar -->
        <div class="market-subbar">
            <div class="market-subbar-container">
                
                <!-- 🏠 Home Pill Link -->
                <a href="{{route('home')}}" class="market-pill-btn {{isActive('home')}}">
                    <i class="bi bi-house-door-fill text-primary" style="font-size: 0.85rem;"></i> <span>{{__('home')}}</span>
                </a>
                
                <!-- DYNAMIC PORTAL NODES (Farmers, Real Estate, Electronics, Second Hand, Affiliate) -->
                <?php foreach ($langData as $key => $cat): 
                    $pillClass = ($key === 'affiliate') ? 'affiliate-pill' : '';
                    $isActive = isActiveLink($cat['link']);
                ?>
                    <div class="market-dropdown">
                        <a href="#" class="market-pill-btn {{$pillClass}} {{$isActive}}">
                            <i class="bi {{$cat['icon']}}"></i> <span>{{$cat['title']}}</span>
                            <i class="bi bi-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                        <div class="market-dropdown-menu">
                            @foreach($cat['subs'] as $subLink => $subTitle)
                                <a href="{{url('/') . '/' . $subLink}}" class="dropdown-item py-2 fw-semibold">
                                    {{$subTitle}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </nav>
@endif

<script>
    // Click-to-toggle dropdowns on both mobile and desktop (Fully robust)
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('.market-dropdown');
        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.market-pill-btn');
            const menu = dropdown.querySelector('.market-dropdown-menu');
            
            if (trigger && menu) {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close all other dropdown menus
                    dropdowns.forEach(d => {
                        if (d !== dropdown) {
                            const otherMenu = d.querySelector('.market-dropdown-menu');
                            if (otherMenu) otherMenu.classList.remove('show');
                        }
                    });
                    
                    // Toggle current menu
                    menu.classList.toggle('show');
                });
            }
        });
        
        // Close menus when clicking outside
        document.addEventListener('click', (e) => {
            dropdowns.forEach(d => {
                const menu = d.querySelector('.market-dropdown-menu');
                if (menu && menu.classList.contains('show')) {
                    if (!d.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                }
            });
        });
    });

    window.updateCartUI = function(newCount) {
        const badges = document.querySelectorAll('.badge-count');
        badges.forEach(badge => {
            badge.innerText = newCount;
            badge.style.display = newCount > 0 ? 'flex' : 'none';
        });
    }
</script>

        <main class='flex-grow-1'>
            @yield('content')
        </main>
<style>
    /* ════════ DASHBOARD FOOTER FIX ════════ */
    .dashboard-footer {
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 2rem;
        transition: margin-left 0.3s ease;
    }
    @media (min-width: 992px) {
        .dashboard-footer {
            /* Exactly offsets the 280px sidebar from the pro dashboard design */
            margin-left: 280px; 
        }
    }

    /* ════════ PUBLIC MEGASTORE FOOTER ════════ */
    /* ════════ MEGA FOOTER REDESIGN (REF 02) ════════ */
    .mega-footer {
        background-color: #111114; /* Deep, premium black */
        color: #a0a0a0;
        padding-top: 4rem;
        padding-bottom: 2rem;
        margin-top: 6rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        position: relative;
    }
    
    /* Floating Newsletter */
    .floating-newsletter-wrapper {
        position: absolute;
        top: -30px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        z-index: 10;
    }
    .floating-newsletter {
        background: #ffffff;
        border-radius: 50px;
        padding: 6px;
        display: flex;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .floating-newsletter input {
        border: none;
        background: transparent;
        padding: 0 24px;
        flex-grow: 1;
        outline: none;
        color: #333;
        font-size: 0.95rem;
    }
    .floating-newsletter input::placeholder {
        color: #a0a0a0;
    }
    .floating-newsletter button {
        background: #3b82f6; /* Trust Rwanda Blue */
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s ease;
    }
    .floating-newsletter button:hover {
        background: #2563eb;
    }

    .mega-footer-heading {
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    .mega-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .mega-footer-links li {
        margin-bottom: 0.8rem;
    }
    .mega-footer-links a {
        color: #a0a0a0;
        text-decoration: none;
        transition: color 0.3s ease;
        font-size: 0.9rem;
    }
    .mega-footer-links a:hover {
        color: #ffffff;
    }

    /* Partner Row */
    .partner-row {
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem 0;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .partner-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .partner-list li {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .partner-list li i {
        font-size: 0.6rem;
    }

    /* Bottom Copyright */
    .mega-footer-bottom {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }
    .mega-footer-bottom .bottom-links {
        display: flex;
        gap: 1.5rem;
    }
    .mega-footer-bottom .bottom-links a {
        color: #a0a0a0;
        text-decoration: none;
    }
    .mega-footer-bottom .bottom-links a:hover {
        color: #fff;
    }

    /* ════════ REAL-TIME NOTIFICATIONS (TOASTS) ════════ */
    #toastStack { position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .toasty { 
        background: #1e293b; color: white; padding: 14px 22px; border-radius: 12px; 
        display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        animation: fadeUp 0.4s ease forwards; border-left: 4px solid #3b82f6; 
        min-width: 280px;
    }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div id="toastStack"></div>

@if($isDashboardUser)
    
    <footer class="dashboard-footer mt-auto">
        <div class="container-fluid px-0">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small">
                <div class="text-muted mb-2 mb-md-0 fw-medium">
                    &copy; {{date('Y')}} {{$siteName}}. {{__('all_rights_reserved')}}
                </div>
                <div class="text-muted">
                    {{__('designed_and_developed_by')}} <a href="https://stevenimanzi.kesug.com" target="_blank" class="text-decoration-none fw-bold text-primary">Steven IMANZI</a>
                </div>
            </div>
        </div>
    </footer>

@else

    <footer class="mega-footer">
        
        <!-- Floating Newsletter Box -->
        <div class="floating-newsletter-wrapper">
            <form class="floating-newsletter" method="POST" action="{{ route('newsletter.subscribe') }}">
                @csrf
                <input type="email" name="subscribe_email" placeholder="Enter email address" required>
                <button type="submit">SUBSCRIBE</button>
            </form>
            @if(session('newsletter_msg'))
                <div class="mt-2 text-center" style="font-size: 0.85rem; font-weight: 600; color: #10b981; background: #fff; padding: 5px 15px; border-radius: 20px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    {!! session('newsletter_msg') !!}
                </div>
            @endif
            @error('subscribe_email')
                <div class="mt-2 text-center text-danger" style="font-size: 0.85rem; font-weight: 600; background: #fff; padding: 5px 15px; border-radius: 20px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="container" style="padding-top: 1rem;">
            <!-- Main Columns -->
            <div class="row g-4">
                <!-- Brand Column -->
                <div class="col-lg-4 pe-lg-5">
                    <h5 class="fw-bolder mb-3 text-white" style="letter-spacing: 1px;">TRUST RWANDA</h5>
                    <p style="line-height: 1.8; font-size: 0.9rem;">
                        Rwanda's premier digital marketplace. We supply the global market with the necessary tools to shop, sell, and manage real estate seamlessly.
                    </p>
                    <a href="#" class="text-white text-decoration-none mt-2 d-inline-block" style="font-size: 0.9rem;">read more &rarr;</a>
                </div>

                <!-- Discover -->
                <div class="col-lg-2 col-6">
                    <h6 class="mega-footer-heading">Discover</h6>
                    <ul class="mega-footer-links">
                        <li><a href="{{route('products.index', ['category' => 'real-estate'])}}">Real Estate</a></li>
                        <li><a href="{{route('property_owner.register')}}">Sell Property</a></li>
                        <li><a href="{{route('products.index', ['category' => 'electronics'])}}">Electronics</a></li>
                        <li><a href="{{route('pages.help')}}">Help & Support</a></li>
                    </ul>
                </div>

                <!-- About -->
                <div class="col-lg-2 col-6">
                    <h6 class="mega-footer-heading">About</h6>
                    <ul class="mega-footer-links">
                        <li><a href="{{route('pages.staff')}}">Staff</a></li>
                        <li><a href="{{route('pages.team')}}">Team</a></li>
                        <li><a href="{{route('pages.careers')}}">Careers</a></li>
                        <li><a href="{{route('pages.blog')}}">Blog</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 col-6">
                    <h6 class="mega-footer-heading">Resources</h6>
                    <ul class="mega-footer-links">
                        <li><a href="{{route('pages.security')}}">Security</a></li>
                        <li><a href="{{route('pages.global')}}">Global</a></li>
                        <li><a href="{{route('pages.charts')}}">Charts</a></li>
                        <li><a href="{{route('pages.privacy')}}">Privacy</a></li>
                    </ul>
                </div>

                <!-- Social -->
                <div class="col-lg-2 col-6">
                    <h6 class="mega-footer-heading">Social</h6>
                    <ul class="mega-footer-links">
                        <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
                        <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
                        <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                        <li><a href="https://plus.google.com" target="_blank">Googleplus</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Row -->
            <div class="mega-footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1.5rem; margin-top: 3rem;">
                <div>
                    Copyright &copy; {{date('Y')}} All rights reserved | This platform designed and developed by <a href="https://stevenimanzi.kesug.com" target="_blank" class="text-white text-decoration-none hover-primary">Steven IMANZI</a>
                </div>
                <div class="bottom-links mt-3 mt-md-0">
                    <a href="{{route('pages.terms')}}">Terms</a>
                    <a href="{{route('pages.privacy')}}">Privacy</a>
                    <a href="{{route('pages.compliances')}}">Compliances</a>
                </div>
            </div>
        </div>
    </footer>

@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS (for maps) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/2.4.0/Control.FullScreen.min.js"></script>

@yield('scripts')

<script>
    window.KURA_BASE_PATH = '{{url('/')}}';
    window.KURA_PUSH_PUBLIC_KEY = '{{getenv('KURA_VAPID_PUBLIC_KEY') ?: '', ENT_QUOTES, 'UTF-8'}}';
    window.KURA_CSRF = {
        push_subscribe: '{{kura_csrf_token('push_subscribe'), ENT_QUOTES, 'UTF-8'}}'
    };
    window.KURA_I18N = {
        added_to_cart: "{{__('item_added_to_cart_toast') ?? 'Item added to your Trust Rwanda cart!', ENT_QUOTES, 'UTF-8'}}",
        notice: "{{__('notice_label') ?? 'Notice:', ENT_QUOTES, 'UTF-8'}}",
        saved_wishlist: "{{__('saved_to_wishlist') ?? 'Saved to Wishlist!', ENT_QUOTES, 'UTF-8'}}",
        removed_wishlist: "{{__('removed_from_wishlist') ?? 'Removed from Wishlist.', ENT_QUOTES, 'UTF-8'}}"
    };

    /* ── GLOBAL JAVASCRIPT FUNCTIONS ── */

    /* ── LIVE NOTIFICATION SYSTEM ── */
    function showToast(msg, type = 'primary') {
        let stack = document.getElementById('toastStack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'toastStack';
            document.body.appendChild(stack);
        }
        const t = document.createElement('div');
        t.className = 'toasty';
        const icon = type === 'error' || type === true ? 'bi-exclamation-circle-fill text-danger' : 'bi-check-circle-fill text-success';
        t.innerHTML = `<i class="bi ${icon} fs-5"></i> <span class="small fw-bold">${msg}</span>`;
        stack.appendChild(t);
        setTimeout(() => { 
            t.style.opacity = '0'; 
            t.style.transform = 'translateX(50px)';
            setTimeout(() => t.remove(), 400); 
        }, 5000);
    }
    
    /* ── OPTIMISTIC ADD TO CART (App-like Feel) ── */
    function addToCart(productId, btnElement = null) {
        const btn = btnElement || (window.event ? window.event.currentTarget : null);
        if (!btn || btn.disabled) return;
        
        const originalText = btn.innerHTML;
        
        // 1. OPTIMISTIC UI: Instantly change button and badge
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        btn.classList.add('btn-success', 'text-white');
        btn.disabled = true;

        const badgeList = document.querySelectorAll('.cart-badge, .badge-count');
        badgeList.forEach(badge => {
            let currentCount = parseInt(badge.innerText || 0);
            badge.innerText = currentCount + 1;
            badge.style.display = 'flex';
        });

        const endpoint = "{{ route('cart.add') }}";

        fetch(endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, qty: 1 })
        })
        .then(async response => {
            const text = await response.text();
            try { return JSON.parse(text); } catch (e) { throw new Error("Server Error"); }
        })
        .then(data => {
            if(data.status === 'success') {
                const successMsg = window.KURA_I18N ? window.KURA_I18N.added_to_cart : "Item added to cart successfully!";
                showToast(successMsg);
                
                // 2. SERVER SYNC: Sync exact count
                badgeList.forEach(badge => {
                    badge.innerText = data.cart_count;
                    badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success', 'text-white');
                    btn.disabled = false;
                }, 1500);
            } else {
                // Rollback on failure
                badgeList.forEach(badge => {
                    let currentCount = Math.max(0, parseInt(badge.innerText) - 1);
                    badge.innerText = currentCount;
                    badge.style.display = currentCount > 0 ? 'flex' : 'none';
                });
                if(data.message && data.message.toLowerCase().includes('login')) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    const noticePrefix = window.KURA_I18N ? window.KURA_I18N.notice : "Notice:";
                    showToast(noticePrefix + " " + data.message, 'error');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    /* ── TOGGLE WISHLIST ── */
    function toggleFav(productId) {
        const btn = document.querySelector('.fav-btn-' + productId);
        const basePath = window.KURA_BASE_PATH ? window.KURA_BASE_PATH : '';
        fetch(basePath + '/public/api/wishlist_action.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'product_id=' + productId
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                if (btn) btn.style.color = data.action === 'added' ? '#e02b27' : '#555';
                let msg = data.action === 'added' ? "Saved to Wishlist!" : "Removed from Wishlist.";
                if (window.KURA_I18N) {
                    msg = data.action === 'added' ? window.KURA_I18N.saved_wishlist : window.KURA_I18N.removed_wishlist;
                }
                showToast(msg);
            }
        }).catch(err => console.error(err));
    }
</script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', async () => {
            try {
                const registration = await navigator.serviceWorker.register(`${window.KURA_BASE_PATH}/sw.js`, { scope: `${window.KURA_BASE_PATH}/` });

                if ('PushManager' in window && window.KURA_PUSH_PUBLIC_KEY) {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        const existingSubscription = await registration.pushManager.getSubscription();
                        const subscription = existingSubscription || await registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(window.KURA_PUSH_PUBLIC_KEY)
                        });

                        await fetch(`${window.KURA_BASE_PATH}/public/api/push_subscribe.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': window.KURA_CSRF.push_subscribe
                            },
                            body: JSON.stringify({
                                ...subscription.toJSON(),
                                csrf_token: window.KURA_CSRF.push_subscribe
                            })
                        });
                    }
                }
            } catch (error) {
                console.warn('PWA bootstrap skipped:', error);
            }
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
    }
</script>

@if(!$isDashboardUser)
<script>
    /* ── REAL-TIME HUB SYNC ── */
    let lastSeenSale = 0;
    async function syncPlatformData() {
        try {
            const response = await fetch(window.KURA_BASE_PATH + '/api/realtime_hub.php');
            const data = await response.json();

            // 1. Update Real-Time Stock Labels
            if(data.inventory) {
                data.inventory.forEach(item => {
                    const stockBadges = document.querySelectorAll(`.stock-count-${item.id}`);
                    stockBadges.forEach(badge => {
                        badge.innerText = item.stock_quantity + ' {{__('left_label'), ENT_QUOTES, 'UTF-8'}}';
                    });
                });
            }

            // 2. Update Dynamic Counter Stats
            if (data.stats) {
                const prodStat = document.querySelector('[data-target="{{$displayProducts ?? 0}}"]');
                const vendStat = document.querySelector('[data-target="{{$displayVendors ?? 0}}"]');
                if (prodStat) prodStat.innerText = data.stats.total_products + '+';
                if (vendStat) vendStat.innerText = data.stats.total_vendors;
            }

            // 3. Update Seller Performance
            if(data.topSellers) {
                data.topSellers.forEach(seller => {
                    const sellerCount = document.querySelector(`#seller-count-${seller.id}`);
                    if (sellerCount) sellerCount.innerText = seller.product_count + ' {{__('active_items_label'), ENT_QUOTES, 'UTF-8'}}';
                });
            }

            // 4. Fresh Sale Social Proof
            if(data.last_sale && data.last_sale.id > lastSeenSale) {
                if(lastSeenSale !== 0) { 
                    showToast(`{{__('fresh_sale_toast'), ENT_QUOTES, 'UTF-8'}} ${data.last_sale.title}`);
                }
                lastSeenSale = data.last_sale.id;
            }

        } catch (error) {
            console.warn("KURA Real-time sync paused.");
        }
    }

    // Refresh every 10 seconds to keep InfinityFree stable
    setInterval(syncPlatformData, 10000);
    window.addEventListener('load', syncPlatformData);

    @if(!empty($_SESSION['user']))
    (() => {
        if (!window.EventSource) return;

        let lastSnapshot = null;
        const stream = new EventSource('{{url('/')}}/public/api/live_stream.php');
        stream.addEventListener('heartbeat', (event) => {
            try {
                const payload = JSON.parse(event.data);
                const nextSnapshot = JSON.stringify(payload.snapshot || {});
                if (lastSnapshot && nextSnapshot !== lastSnapshot) {
                    showToast('{{__('live_marketplace_refreshed'), ENT_QUOTES, 'UTF-8'}}');
                }
                lastSnapshot = nextSnapshot;
            } catch (error) {
                console.warn('Realtime stream parse failed.');
            }
        });
    })();
    @endif
</script>
@endif

</body>
</html>
