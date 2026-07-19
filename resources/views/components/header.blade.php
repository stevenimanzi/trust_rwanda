@php
    $sysSettings = \App\Models\SystemSetting::pluck('setting_value', 'setting_key')->toArray();
    $siteName = $sysSettings['site_name'] ?? 'Trust Rwanda';
    $metaDesc = $sysSettings['meta_description'] ?? "Shop premium real estate, electronics, second-hand products, and more on {$siteName}.";
    $siteLogo = !empty($sysSettings['site_logo']) ? asset($sysSettings['site_logo']) : asset('assets/uploads/logos/TrustRwanda-Logo.jpg');
    $siteFavicon = !empty($sysSettings['site_favicon']) ? asset($sysSettings['site_favicon']) : asset('assets/uploads/logos/TrustRwanda-Logo.png');
    $currentUser = auth()->user();
    $userRole = $currentUser ? $currentUser->role : 'guest';
    $cartCount = session('cart') ? array_sum(session('cart')) : 0;
    $currentLang = app()->getLocale();
    $hideNav = false; // We can set this dynamically if needed

    $navCategories = [
        'rw' => [
            'farmers' => [
                'title' => 'Abahinzi & Umusaruro',
                'icon' => 'bi-flower1',
                'link' => route('farmers.market'),
                'subs' => [
                    route('farmers.market') => 'Umusaruro Wose',
                    route('farmers.market', ['category' => 'fruits']) => 'Imbuto Nshya (Fruits)',
                    route('farmers.market', ['category' => 'vegetables']) => 'Imboga Zicyeye',
                    route('farmers.market', ['category' => 'grains']) => 'Impeke & Ibinyampeke',
                    route('farmers.market', ['category' => 'dairy']) => 'Amata & Ubuki (Dairy)',
                    route('farmers.market', ['category' => 'meat']) => 'Inyama & Amatungo Magufi',
                ]
            ],
            'real_estate' => [
                'title' => 'Imitungo Itimukanwa',
                'icon' => 'bi-buildings',
                'link' => route('real_estate'),
                'subs' => [
                    route('real_estate') => 'Imitungo Yose',
                    route('real_estate', ['category' => 'rent-house']) => 'Inzu Zikodeshwa (Houses)',
                    route('real_estate', ['category' => 'rent-apartment']) => 'Fleti Zikodeshwa (Apartments)',
                    route('real_estate', ['category' => 'rent-guest-house']) => 'Inzu z\'Abashyitsi (Guest House)',
                    route('real_estate', ['category' => 'rent-ghetto']) => 'Ghetto Zikodeshwa',
                    route('real_estate', ['category' => 'sale-house']) => 'Inzu Zigurishwa',
                    route('real_estate', ['category' => 'sale-land']) => 'Ibibanza Bigurishwa (Lands)',
                ]
            ],
            'electronics' => [
                'title' => 'Elegitoroniki',
                'icon' => 'bi-laptop',
                'link' => route('products.index', ['category' => 'electronics']),
                'subs' => [
                    route('products.index', ['category' => 'electronics']) => 'Elegitoroniki Yose',
                    route('products.index', ['category' => 'mobile-phones']) => 'Telefoni Zikoreshwa',
                    route('products.index', ['category' => 'laptops-computers']) => 'Orudinateri & Laptops',
                    route('products.index', ['category' => 'tablets']) => 'Tabuleti (Tablets)',
                    route('products.index', ['category' => 'smartwatches']) => 'Isaha z\'Ubwenge (Smartwatch)',
                    route('products.index', ['category' => 'accessories']) => 'Ibikoresho by\'Umva & Umugozi',
                    route('products.index', ['category' => 'tv-systems']) => 'Televiziyo & Indangururamajwi',
                    route('products.index', ['category' => 'speakers-audio']) => 'Imizindaro (Speakers)',
                    route('products.index', ['category' => 'gaming']) => 'Imikino ya Videwo (Gaming)',
                    route('products.index', ['category' => 'smart-home']) => 'Ibikoresho By\'ubwenge byo mu rugo',
                ]
            ],
            'second_hand' => [
                'title' => 'Ibyakoreshejwe',
                'icon' => 'bi-recycle',
                'link' => route('products.index', ['category' => 'second-hand']),
                'subs' => [
                    route('products.index', ['category' => 'second-hand']) => 'Ibyakoreshejwe Byose',
                    route('products.index', ['category' => 'used-vehicles']) => 'Imodoka & Moto zikoreshejwe',
                    route('products.index', ['category' => 'used-mobile-phones']) => 'Telefoni za Caguwa',
                    route('products.index', ['category' => 'used-laptops']) => 'Laptops Zikoreshejwe',
                    route('products.index', ['category' => 'used-televisions']) => 'TV Zikoreshejwe',
                    route('products.index', ['category' => 'used-furniture']) => 'Intebe & Ameza Zikoreshejwe',
                    route('products.index', ['category' => 'used-electronics']) => 'Elegitoroniki Zikoreshejwe',
                ]
            ],
            'affiliate' => [
                'title' => 'Invite & Earn',
                'icon' => 'bi-share-fill',
                'link' => route('affiliate.index'),
                'subs' => [
                    route('affiliate.index') => 'Iyandikishe',
                    route('affiliate.index', ['tab' => 'products']) => 'Ibikorwa Byo Kwinjiza',
                    route('affiliate.index', ['tab' => 'how']) => 'Uko Bikora',
                    route('affiliate.index', ['tab' => 'tools']) => 'Ibikoresho by\'Idirishya',
                ]
            ],
            'nearby_shops' => [
                'title' => 'Amaduka Hafi Yanjye',
                'icon' => 'bi-geo-alt-fill',
                'link' => route('nearby.shops'),
                'subs' => [
                    route('nearby.shops') => 'Amaduka Yose ya Hafi'
                ]
            ]
        ],
        'en' => [
            'farmers' => [
                'title' => 'Farmers & Harvest',
                'icon' => 'bi-flower1',
                'link' => route('farmers.market'),
                'subs' => [
                    route('farmers.market') => 'All Harvest',
                    route('farmers.market', ['category' => 'fruits']) => 'Fresh Fruits',
                    route('farmers.market', ['category' => 'vegetables']) => 'Fresh Vegetables',
                    route('farmers.market', ['category' => 'grains']) => 'Cereals & Grains',
                    route('farmers.market', ['category' => 'dairy']) => 'Dairy & Honey',
                    route('farmers.market', ['category' => 'meat']) => 'Meat & Poultry',
                ]
            ],
            'real_estate' => [
                'title' => 'Real Estate',
                'icon' => 'bi-buildings',
                'link' => route('real_estate'),
                'subs' => [
                    route('real_estate') => 'All Properties',
                    route('real_estate', ['category' => 'rent-house']) => 'Houses for Rent',
                    route('real_estate', ['category' => 'rent-apartment']) => 'Apartments for Rent',
                    route('real_estate', ['category' => 'rent-guest-house']) => 'Guest Houses',
                    route('real_estate', ['category' => 'rent-ghetto']) => 'Ghettos for Rent',
                    route('real_estate', ['category' => 'sale-house']) => 'Houses for Sale',
                    route('real_estate', ['category' => 'sale-land']) => 'Lands & Plots for Sale',
                ]
            ],
            'electronics' => [
                'title' => 'Electronics',
                'icon' => 'bi-laptop',
                'link' => route('products.index', ['category' => 'electronics']),
                'subs' => [
                    route('products.index', ['category' => 'electronics']) => 'All Electronics',
                    route('products.index', ['category' => 'mobile-phones']) => 'Smartphones & Tablets',
                    route('products.index', ['category' => 'laptops-computers']) => 'Laptops & Computers',
                    route('products.index', ['category' => 'tablets']) => 'Tablets & E-readers',
                    route('products.index', ['category' => 'smartwatches']) => 'Smartwatches & Wearables',
                    route('products.index', ['category' => 'accessories']) => 'Accessories & Headphones',
                    route('products.index', ['category' => 'tv-systems']) => 'TV & Home Systems',
                    route('products.index', ['category' => 'speakers-audio']) => 'Speakers & Audio',
                    route('products.index', ['category' => 'gaming']) => 'Gaming & Consoles',
                    route('products.index', ['category' => 'smart-home']) => 'Smart Home Devices',
                ]
            ],
            'second_hand' => [
                'title' => 'Second Hand',
                'icon' => 'bi-recycle',
                'link' => route('products.index', ['category' => 'second-hand']),
                'subs' => [
                    route('products.index', ['category' => 'second-hand']) => 'All Used Goods',
                    route('products.index', ['category' => 'used-vehicles']) => 'Used Vehicles & Motos',
                    route('products.index', ['category' => 'used-mobile-phones']) => 'Used Mobile Phones',
                    route('products.index', ['category' => 'used-laptops']) => 'Used Laptops & Computers',
                    route('products.index', ['category' => 'used-televisions']) => 'Used Televisions',
                    route('products.index', ['category' => 'used-furniture']) => 'Pre-owned Furniture',
                    route('products.index', ['category' => 'used-electronics']) => 'Used Electronics',
                ]
            ],
            'affiliate' => [
                'title' => 'Invite & Earn',
                'icon' => 'bi-share-fill',
                'link' => route('affiliate.index'),
                'subs' => [
                    route('affiliate.index') => 'Affiliate Dashboard',
                    route('affiliate.index', ['tab' => 'products']) => 'Products to Share',
                    route('affiliate.index', ['tab' => 'how']) => 'How It Works',
                    route('affiliate.index', ['tab' => 'tools']) => 'Marketing Tools',
                ]
            ],
            'nearby_shops' => [
                'title' => 'Near Me Shops',
                'icon' => 'bi-geo-alt-fill',
                'link' => route('nearby.shops'),
                'subs' => [
                    route('nearby.shops') => 'All Nearby Shops'
                ]
            ]
        ],
        'sw' => [
            'farmers' => [
                'title' => 'Wakulima na Mavuno',
                'icon' => 'bi-flower1',
                'link' => route('farmers.market'),
                'subs' => [
                    route('farmers.market') => 'Mavuno Yote',
                    route('farmers.market', ['category' => 'fruits']) => 'Matunda Mapya',
                    route('farmers.market', ['category' => 'vegetables']) => 'Mboga Mpya',
                    route('farmers.market', ['category' => 'grains']) => 'Nafaka',
                    route('farmers.market', ['category' => 'dairy']) => 'Maziwa na Asali',
                    route('farmers.market', ['category' => 'meat']) => 'Nyama na Kuku',
                ]
            ],
            'real_estate' => [
                'title' => 'Mali isiyohamishika',
                'icon' => 'bi-buildings',
                'link' => route('real_estate'),
                'subs' => [
                    route('real_estate') => 'Mali Zote',
                    route('real_estate', ['category' => 'rent-house']) => 'Nyumba za Kupanga',
                    route('real_estate', ['category' => 'rent-apartment']) => 'Fleti za Kupanga',
                    route('real_estate', ['category' => 'rent-guest-house']) => 'Nyumba za Wageni',
                    route('real_estate', ['category' => 'rent-ghetto']) => 'Ghetto za Kupanga',
                    route('real_estate', ['category' => 'sale-house']) => 'Nyumba za Kuuza',
                    route('real_estate', ['category' => 'sale-land']) => 'Viwanja vya Kuuza',
                ]
            ],
            'electronics' => [
                'title' => 'Elektroniki',
                'icon' => 'bi-laptop',
                'link' => route('products.index', ['category' => 'electronics']),
                'subs' => [
                    route('products.index', ['category' => 'electronics']) => 'Elektroniki Zote',
                    route('products.index', ['category' => 'mobile-phones']) => 'Simu na Vidonge',
                    route('products.index', ['category' => 'laptops-computers']) => 'Kompyuta na Laptops',
                    route('products.index', ['category' => 'tablets']) => 'Vidonge (Tablets)',
                    route('products.index', ['category' => 'smartwatches']) => 'Saa za Kisasa (Smartwatch)',
                    route('products.index', ['category' => 'accessories']) => 'Vifaa vya Masikio & Headphones',
                    route('products.index', ['category' => 'tv-systems']) => 'TV na Mifumo ya Sauti',
                    route('products.index', ['category' => 'speakers-audio']) => 'Spika na Sauti',
                    route('products.index', ['category' => 'gaming']) => 'Michezo (Gaming)',
                    route('products.index', ['category' => 'smart-home']) => 'Vifaa vya Nyumbani vya Kisasa',
                ]
            ],
            'second_hand' => [
                'title' => 'Bidhaa Zilizotumika',
                'icon' => 'bi-recycle',
                'link' => route('products.index', ['category' => 'second-hand']),
                'subs' => [
                    route('products.index', ['category' => 'second-hand']) => 'Bidhaa Zote',
                    route('products.index', ['category' => 'used-vehicles']) => 'Magari na Pikipiki Zilizotumika',
                    route('products.index', ['category' => 'used-mobile-phones']) => 'Simu Zilizotumika',
                    route('products.index', ['category' => 'used-laptops']) => 'Kompyuta Zilizotumika',
                    route('products.index', ['category' => 'used-televisions']) => 'TV Zilizotumika',
                    route('products.index', ['category' => 'used-furniture']) => 'Samani Zilizotumika',
                    route('products.index', ['category' => 'used-electronics']) => 'Elektroniki Zilizotumika',
                ]
            ],
            'affiliate' => [
                'title' => 'Invite & Earn',
                'icon' => 'bi-share-fill',
                'link' => route('affiliate.index'),
                'subs' => [
                    route('affiliate.index') => 'Dashibodi ya Washirika',
                    route('affiliate.index', ['tab' => 'products']) => 'Bidhaa za Kushiriki',
                    route('affiliate.index', ['tab' => 'how']) => 'Jinsi Inavyofanya Kazi',
                    route('affiliate.index', ['tab' => 'tools']) => 'Zana za Uuzaji',
                ]
            ],
            'nearby_shops' => [
                'title' => 'Maduka Karibu Nami',
                'icon' => 'bi-geo-alt-fill',
                'link' => route('nearby.shops'),
                'subs' => [
                    route('nearby.shops') => 'Maduka Yote ya Karibu'
                ]
            ]
        ]
    ];

    $langData = $navCategories[$currentLang] ?? $navCategories['en'];

    if (!function_exists('isActive')) {
        function isActive($routeName, $category = null) {
            if (request()->routeIs($routeName)) {
                if ($category !== null) {
                    return request()->query('category') === $category ? 'active' : '';
                }
                return !request()->has('category') ? 'active' : '';
            }
            return '';
        }
    }

    if (!function_exists('isActiveLink')) {
        function isActiveLink($link) {
            $currentUrl = request()->fullUrl();
            $linkPath = parse_url($link, PHP_URL_PATH);
            $currentPath = parse_url($currentUrl, PHP_URL_PATH);
            
            if ($linkPath !== $currentPath) return '';
            
            $linkQuery = parse_url($link, PHP_URL_QUERY);
            $currentQuery = parse_url($currentUrl, PHP_URL_QUERY);
            
            parse_str($linkQuery ?? '', $linkParams);
            parse_str($currentQuery ?? '', $currentParams);
            
            if (isset($linkParams['category'])) {
                return ($currentParams['category'] ?? '') === $linkParams['category'] ? 'active' : '';
            }
            return !isset($currentParams['category']) ? 'active' : '';
        }
    }
@endphp

@if (!$hideNav)
    <nav class="navbar navbar-expand-lg navbar-pro">
        <!-- Top Row -->
        <div class="navbar-shell">
            <a class="brand-logo" href="{{ route('home') }}">
                <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="logo-img">
            </a>

            <!-- Search Bar Desktop (Center) -->
            <form action="{{ route('products.index') }}" method="GET" class="header-search-form d-none d-lg-flex">
                <input type="text" name="q" placeholder="Search for anything..." value="{{ request()->query('q') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

            <!-- Actions Separation -->
            <div class="d-flex align-items-center gap-3">
                <!-- 🌐 Direct Visible Language Switcher Pill -->
                <div class="dropdown">
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3 py-1.5 d-flex align-items-center gap-1.5 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.78rem; border-color: #e2e8f0; color: #475569; background: white;">
                        <i class="bi bi-globe"></i> <span>{{ strtoupper($currentLang) }}</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.55rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2 mt-2" style="border-radius: 12px; min-width: 140px; z-index: 1060;">
                        @foreach (['rw', 'en', 'sw'] as $langCode)
                            <li>
                                <a class="dropdown-item rounded-3 py-2 fw-bold d-flex align-items-center gap-2 {{ $langCode === $currentLang ? 'bg-primary-subtle text-primary' : 'text-secondary' }}" href="{{ request()->fullUrlWithQuery(['lang' => $langCode]) }}" style="font-size: 0.8rem;">
                                    <i class="bi bi-globe text-muted"></i>
                                    <span>
                                        @if ($langCode === 'rw') Kinyarwanda
                                        @elseif ($langCode === 'en') English
                                        @else Kiswahili
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Shopping Cart -->
                <a href="{{ route('cart.index') }}" class="icon-btn-pro border shadow-sm">
                    <i class="bi bi-bag-heart fs-5"></i>
                    <span class="badge-count cart-badge" style="{{ $cartCount > 0 ? 'display:flex' : 'display:none' }}">{{ $cartCount }}</span>
                </a>

                <!-- Hamburger menu button (Mobile only) -->
                <button class="navbar-toggler border-0 p-0 shadow-none d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                    <i class="bi bi-list fs-3 text-secondary"></i>
                </button>
            </div>

            <!-- Desktop Drawer Actions -->
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1 d-lg-none">
                    <li class="nav-item"><a class="nav-link-pro {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="bi bi-house"></i> {{ __('home') }}</a></li>
                    <li class="nav-item"><a class="nav-link-pro {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}"><i class="bi bi-grid"></i> Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link-pro" href="{{ route('profile') }}"><i class="bi bi-person"></i> My Dashboard</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 ms-lg-auto d-mobile-full">
                    <!-- Dynamic Logged User Actions -->
                    @if ($currentUser)
                        <a href="{{ route('affiliate.index') }}" class="btn btn-sm btn-outline-warning rounded-pill fw-bold px-3 py-2 text-decoration-none d-none d-lg-inline-flex align-items-center gap-1" style="font-size: 0.8rem; border-color: #f59e0b; color: #b45309;">
                            <i class="bi bi-gift-fill text-warning"></i> Invite & Earn
                        </a>

                        <div class="dropdown">
                            <a href="#" class="icon-btn-pro p-0 border-0 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->full_name) }}&background=1e3a8a&color=fff&bold=true" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 18px; min-width: 220px; z-index: 1060;">
                                @if($userRole === 'admin')
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{ route('home') }}"><i class="bi bi-shield-lock-fill me-2"></i> {{ __('admin_panel') }}</a></li>
                                @elseif($userRole === 'vendor')
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{ route('home') }}"><i class="bi bi-shop-window me-2"></i> {{ __('vendor_panel') }}</a></li>
                                @elseif($userRole === 'real_estate_owner')
                                    <li><a class="dropdown-item rounded-3 mb-1 bg-primary-subtle text-primary fw-bold py-2" href="{{ route('home') }}"><i class="bi bi-houses me-2"></i> Owner Dashboard</a></li>
                                @endif
                                <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile') }}"><i class="bi bi-person-circle me-2"></i> {{ __('account_settings') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 text-danger fw-bold py-2" href="{{ route('logout') }}"><i class="bi bi-power me-2"></i> {{ __('sign_out') }}</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login-pro text-secondary fw-bold" style="font-size: 0.85rem;">{{ __('log_in') }}</a>
                        <a href="{{ route('register') }}" class="btn-join">{{ __('join_kura') }}</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search Bar Mobile Row -->
        <div class="w-100 px-3 pb-2 d-lg-none">
            <form action="{{ route('products.index') }}" method="GET" class="header-search-form w-100">
                <input type="text" name="q" placeholder="Search for anything..." value="{{ request()->query('q') }}" style="height: 38px;">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <!-- Bottom Row: Category dropdown sub-bar -->
        <div class="market-subbar">
            <div class="market-subbar-container">
                
                <!-- 🏠 Home Pill Link -->
                <a href="{{ route('home') }}" class="market-pill-btn {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill text-primary" style="font-size: 0.85rem;"></i> <span>{{ __('home') }}</span>
                </a>
                
                <!-- DYNAMIC PORTAL NODES (Farmers, Real Estate, Electronics, Second Hand, Affiliate) -->
                @foreach ($langData as $key => $cat)
                    @php
                        $pillClass = ($key === 'affiliate') ? 'affiliate-pill' : '';
                        $isActive = isActiveLink($cat['link']);
                    @endphp
                    <div class="market-dropdown">
                        <a href="#" class="market-pill-btn {{ $pillClass }} {{ $isActive }}">
                            <i class="bi {{ $cat['icon'] }}"></i> <span>{{ $cat['title'] }}</span>
                            <i class="bi bi-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                        <div class="market-dropdown-menu">
                            @foreach ($cat['subs'] as $subLink => $subTitle)
                                <a href="{{ $subLink }}" class="dropdown-item py-2 fw-semibold">
                                    {{ $subTitle }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </nav>
@endif
