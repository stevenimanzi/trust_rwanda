@extends('layouts.app')

@section('content')
<style>
    /* ════════ DESIGN SYSTEM VARIABLES (REF. INSPIRED) ════════ */
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        --primary: #1e3a8a;
        --primary-hover: #1d4ed8;
        --mega-dark: #0f172a;
        --mega-gray: #f8fafc;
        --mega-border: #e2e8f0;
        --mega-yellow: #f59e0b;
        --radius-lg: 20px;
        --radius-md: 12px;
        --radius-sm: 8px;
        --mega-red: #ef4444;
    }

    body {
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--mega-dark);
    }

    /* ════════ HERO ROW GRID ════════ */
    .hero-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 24px;
        margin-bottom: 30px;
    }

    .hero-banner-main {
        grid-column: span 8;
        border-radius: var(--radius-lg);
        height: 420px;
        position: relative;
        overflow: hidden;
        color: white;
        display: flex;
        align-items: center;
        padding: 40px 60px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        transition: background-image 0.5s ease-in-out;
        background-size: cover !important;
        background-position: center !important;
    }

    .hero-banner-side {
        grid-column: span 4;
        border-radius: var(--radius-lg);
        height: 420px;
        position: relative;
        overflow: hidden;
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        background-size: cover !important;
        background-position: center !important;
    }

    /* main content */
    .banner-main-content {
        width: 70%;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .banner-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        align-self: flex-start;
        text-transform: uppercase;
        color: #fff;
    }

    .banner-title {
        font-size: 2.4rem;
        font-weight: 850;
        line-height: 1.25;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }

    .banner-subtitle {
        font-size: 0.95rem;
        opacity: 0.95;
        margin-bottom: 24px;
        line-height: 1.6;
        max-width: 520px;
        text-shadow: 0 1px 5px rgba(0,0,0,0.15);
    }

    .btn-shop-now-pill {
        background: white;
        color: var(--mega-dark);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .btn-shop-now-pill:hover {
        background: var(--mega-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(0,0,0,0.2);
    }

    /* side content */
    .banner-side-content { z-index: 2; }

    .btn-link-shop {
        color: #f59e0b;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
        font-size: 0.9rem;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .btn-link-shop:hover {
        color: #fbbf24;
        border-bottom-color: currentColor;
    }

    /* Carousel dots indicator */
    .carousel-dots {
        position: absolute;
        bottom: 30px;
        left: 60px;
        display: flex;
        gap: 8px;
        z-index: 3;
    }
    .dot-node {
        width: 8px; height: 8px;
        background: rgba(255,255,255,0.4);
        border-radius: 50%;
        cursor: pointer;
        transition: 0.3s;
    }
    .dot-node.active {
        width: 24px;
        background: white;
        border-radius: 4px;
    }

    /* 📱 Mobile Responsive Overrides for Hero section */
    @media (max-width: 991px) {
        .hero-grid {
            grid-template-columns: 1fr;
        }
        .hero-banner-main {
            grid-column: span 12;
        }
        .hero-banner-side {
            grid-column: span 12;
            height: 280px;
        }
    }

    @media (max-width: 768px) {
        .hero-banner-main {
            padding: 40px 30px;
            height: auto;
            min-height: 380px;
        }
        .banner-main-content {
            width: 100%;
        }
        .carousel-dots {
            bottom: 20px;
            left: 30px;
        }
        .banner-title {
            font-size: 1.85rem;
        }
    }

    /* ════════ SECONDARY 3-COL PROMO GRID ════════ */
    .promo-three-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin: 30px 0 50px 0;
    }

    @media (max-width: 991px) {
        .promo-three-grid { grid-template-columns: 1fr; }
    }

    .promo-card-custom {
        border-radius: var(--radius-lg);
        padding: 30px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 180px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        background-size: cover !important;
        background-position: center !important;
    }

    .promo-card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
    }

    .promo-card-text {
        width: 85%;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .promo-card-title {
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 12px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .promo-card-custom:hover .promo-card-title {
        transform: translateX(4px);
    }

    .btn-shop-link-white {
        color: white;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-bottom: 1.5px solid transparent;
        transition: 0.2s;
    }
    .promo-card-custom:hover .btn-shop-link-white {
        border-bottom-color: white;
    }

    /* ════════ PREMIUM CATEGORY CARDS ════════ */
    .sec-header {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 2px solid var(--mega-border);
        padding-bottom: 12px; margin-bottom: 24px;
    }
    .sec-header h4 { font-weight: 800; font-size: 1.35rem; margin: 0; }

    .premium-cat-card {
        display: flex;
        align-items: center;
        padding: 24px;
        border-radius: var(--radius-md);
        text-decoration: none !important;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid rgba(255,255,255,0.4);
        height: 100%;
    }
    .premium-cat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }
    .cat-content {
        position: relative;
        z-index: 2;
    }
    .cat-icon-premium {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .premium-cat-card:hover .cat-icon-premium {
        transform: scale(1.1) rotate(5deg);
    }
    .cat-bg-icon {
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 6rem;
        opacity: 0.08;
        transform: rotate(-15deg);
        transition: all 0.5s ease;
        z-index: 1;
    }
    .premium-cat-card:hover .cat-bg-icon {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.15;
    }

    /* ════════ TRENDING PRODUCTS WITH TABS ════════ */
    .trending-header-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid var(--mega-border);
        padding-bottom: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .trending-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--mega-dark);
        margin: 0;
    }

    .trending-tabs-list {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .trending-tabs-list::-webkit-scrollbar {
        display: none;
    }

    .trending-tab-btn {
        border: none;
        background: none;
        font-size: 0.92rem;
        font-weight: 700;
        color: #64748b;
        padding: 8px 4px;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .trending-tab-btn:hover {
        color: var(--mega-dark);
    }

    .trending-tab-btn.active {
        color: var(--primary);
        font-weight: 800;
    }

    .trending-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -14px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--primary);
    }

    .trending-grid-custom {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }
    @media(max-width: 1200px) { .trending-grid-custom { grid-template-columns: repeat(4, 1fr); } }
    @media(max-width: 992px)  { .trending-grid-custom { grid-template-columns: repeat(3, 1fr); } }
    @media(max-width: 768px)  { .trending-grid-custom { grid-template-columns: repeat(2, 1fr); } }

    .trending-item-card {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    /* Custom Product Card (Premium Style) */
    .trend-card-custom {
        background: white;
        border: 1px solid var(--mega-border);
        border-radius: var(--radius-md);
        padding: 15px;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .trend-card-custom:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .t-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 50px;
    }

    .t-badge.sale { background: #fee2e2; color: #ef4444; }
    .t-badge.new { background: #dcfce7; color: #15803d; }

    .t-img-box {
        background: #f8fafc;
        border-radius: var(--radius-md);
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        padding: 15px;
        cursor: pointer;
        overflow: hidden;
        position: relative;
    }

    .t-img-box img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .trend-card-custom:hover .t-img-box img {
        transform: scale(1.06);
    }

    .t-stock-lbl {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .t-prod-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--mega-dark);
        line-height: 1.35;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 38px;
        text-decoration: none;
    }

    .t-prod-title:hover {
        color: var(--primary);
    }

    .t-stars-row {
        color: var(--mega-yellow);
        font-size: 0.72rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .t-stars-row span {
        color: #64748b;
        font-weight: 600;
        margin-left: 4px;
    }

    .t-price-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .t-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .t-price-current {
        font-size: 1.12rem;
        font-weight: 800;
        color: var(--primary);
    }

    .t-price-original {
        font-size: 0.8rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .btn-order-now-full {
        background-color: #0f172a;
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        padding: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-order-now-full:hover {
        background-color: #1e293b;
    }

    /* ════════ FLASH DEALS ════════ */
    .flash-section-wrap { border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--mega-border); margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .flash-top-bar { background: var(--mega-red); padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .flash-top-bar h4 { color: white; font-weight: 800; font-size: 1.2rem; margin: 0; display: flex; align-items: center; gap: 10px; }
    .flash-live-badge { background: var(--mega-yellow); color: #1a1a1a; font-size: 0.65rem; font-weight: 800; padding: 3px 10px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; animation: blink-badge 1.6s infinite; }
    @keyframes blink-badge { 0%,100%{opacity:1} 50%{opacity:0.55} }
    .flash-countdown { display: flex; align-items: center; gap: 6px; color: white; }
    .flash-countdown .ends-lbl { font-size: 0.78rem; opacity: 0.8; margin-right: 4px; }
    .cd-box { background: rgba(0,0,0,0.25); border-radius: 6px; padding: 5px 10px; text-align: center; min-width: 48px; }
    .cd-box .cd-num { font-size: 1.15rem; font-weight: 800; line-height: 1; display: block; }
    .cd-box .cd-lbl { font-size: 0.58rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.5px; }
    .cd-colon { font-weight: 800; font-size: 1rem; opacity: 0.5; }
    .flash-cards-body { background: white; padding: 20px; }
    .flash-scroll-row { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none; }
    .flash-scroll-row::-webkit-scrollbar { display: none; }
    .flash-card { min-width: 180px; max-width: 180px; flex-shrink: 0; border: 1px solid var(--mega-border); border-radius: var(--radius-md); padding: 13px; position: relative; background: white; transition: 0.3s; display: flex; flex-direction: column; }
    .flash-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); border-color: var(--mega-red); }
    .flash-disc-tag { position: absolute; top: 8px; left: 8px; background: var(--mega-red); color: white; font-size: 0.63rem; font-weight: 800; padding: 2px 8px; border-radius: 50px; }
    .flash-card-img { height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; cursor: pointer; }
    .flash-card-img img { max-height: 100%; max-width: 100%; object-fit: contain; transition: 0.3s; }
    .flash-card-title { font-size: 0.8rem; font-weight: 700; color: var(--mega-dark); margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3; flex-grow: 1; }
    .flash-sold-wrap { margin-bottom: 8px; }
    .flash-sold-labels { display: flex; justify-content: space-between; font-size: 0.63rem; color: #888; margin-bottom: 3px; }
    .flash-sold-bar { height: 5px; background: #eee; border-radius: 50px; overflow: hidden; }
    .flash-sold-fill { height: 100%; background: linear-gradient(90deg, var(--mega-yellow), var(--mega-red)); border-radius: 50px; }
    .flash-price-row { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
    .flash-price-new { font-size: 0.95rem; font-weight: 800; color: var(--mega-red); }
    .flash-price-old { font-size: 0.72rem; color: #aaa; text-decoration: line-through; display: block; line-height: 1; margin-bottom: 1px; }
    .flash-add-btn { width: 28px; height: 28px; background: var(--mega-gray); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--mega-dark); font-size: 0.8rem; cursor: pointer; transition: 0.2s; flex-shrink: 0; }
    .flash-add-btn:hover { background: var(--mega-red); color: white; }

    /* ════════ SPONSORED ADS ════════ */
    .ad-slot-native {
        position: relative;
        background: white;
        border: 1px solid var(--mega-border);
        border-radius: var(--radius-lg);
        padding: 30px;
        margin: 40px 0;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .ad-tag {
        position: absolute;
        top: 0; left: 20px;
        background: var(--mega-dark);
        color: white; font-size: 0.6rem; font-weight: 800;
        text-transform: uppercase; padding: 4px 12px;
        border-radius: 0 0 10px 10px; letter-spacing: 1px;
    }
    .ad-native-inner { display: flex; align-items: center; gap: 20px; }
    .ad-native-icon { width: 50px; height: 50px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem; }
    .ad-native-copy { flex-grow: 1; }
    .ad-native-copy h6 { font-weight: 800; font-size: 1.1rem; margin-bottom: 4px; }
    .ad-native-copy p { font-size: 0.85rem; color: #64748b; margin: 0; }
    .ad-native-cta { background: var(--mega-dark); color: white; text-decoration: none !important; padding: 10px 24px; border-radius: 50px; font-weight: 700; font-size: 0.8rem; transition: 0.3s; border: 2px solid var(--mega-dark); }
    .ad-native-cta:hover { background: transparent; color: var(--mega-dark); }

    /* ════════ REVIEWS SECTION ════════ */
    .reviews-bg { background: white; border: 1px solid var(--mega-border); border-radius: var(--radius-lg); padding: 36px 28px; margin: 40px 0; }
    .reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    @media(max-width: 992px) { .reviews-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width: 576px) { .reviews-grid { grid-template-columns: 1fr; } }
    .review-card { background: var(--mega-gray); border-radius: var(--radius-md); padding: 22px 20px; border: 1px solid var(--mega-border); position: relative; transition: 0.3s; }
    .review-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.05); transform: translateY(-3px); }
    .rv-quote { font-size: 3rem; line-height: 1; color: var(--primary); opacity: 0.08; position: absolute; top: 12px; right: 16px; font-family: Georgia, serif; font-weight: 900; }
    .rv-stars { color: var(--mega-yellow); font-size: 0.78rem; margin-bottom: 10px; }
    .rv-text { font-size: 0.84rem; color: #555; line-height: 1.65; margin-bottom: 16px; font-style: italic; }
    .rv-author { display: flex; align-items: center; gap: 10px; }
    .rv-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 800; color: white; flex-shrink: 0; }
    .rv-name  { font-size: 0.85rem; font-weight: 700; margin-bottom: 1px; }
    .rv-loc   { font-size: 0.7rem; color: #888; }
    .rv-badge { margin-left: auto; font-size: 0.6rem; color: #27ae60; font-weight: 700; background: rgba(39,174,96,0.1); padding: 2px 8px; border-radius: 50px; flex-shrink: 0; }

    /* Recently viewed */
    #recentlySection { display: none; margin-bottom: 40px; }
    .recently-row { display: flex; gap: 14px; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none; }
    .recently-mini {
        min-width: 138px; max-width: 138px; flex-shrink: 0; border: 1px solid var(--mega-border); border-radius: var(--radius-md);
        padding: 10px; background: white; text-decoration: none; color: var(--mega-dark); display: flex; flex-direction: column; transition: 0.3s;
    }
    .recently-mini:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.05); border-color: var(--primary); color: var(--mega-dark); }
    .rm-img { height: 88px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
    .rm-img img { max-height: 100%; max-width: 100%; object-fit: contain; }
    .rm-title { font-size: 0.73rem; font-weight: 700; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 6px; }
    .rm-price { font-size: 0.8rem; font-weight: 800; color: var(--mega-red); margin-top: auto; }

    /* Why shop with us */
    .trust-section { background: white; border: 1px solid var(--mega-border); border-radius: var(--radius-lg); padding: 36px 28px; margin: 40px 0; }
    .trust-cards-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    @media(max-width: 992px) { .trust-cards-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 480px)  { .trust-cards-grid { grid-template-columns: 1fr 1fr; gap: 12px; } }
    .trust-card { background: var(--mega-gray); border-radius: var(--radius-md); padding: 24px 18px; text-align: center; border: 1px solid var(--mega-border); transition: 0.3s; position: relative; overflow: hidden; }
    .trust-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--primary-gradient); transform: scaleX(0); transition: 0.3s; }
    .trust-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.05); }
    .trust-card:hover::after { transform: scaleX(1); }
    .trust-icon-wrap { width: 50px; height: 50px; border-radius: 50%; margin: 0 auto 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .ti-blue   { background: rgba(30,58,138,0.08); color: var(--primary); }
    .ti-green  { background: rgba(16,185,129,0.08); color: #10b981; }
    .ti-yellow { background: rgba(245,158,11,0.08); color: #f59e0b; }
    .ti-red    { background: rgba(239,68,68,0.08); color: #ef4444; }
    .trust-title { font-size: 0.9rem; font-weight: 800; }

    /* Dynamic stats bar */
    .stats-bar-container { margin-top: 30px; }
    .stats-bar { display: grid; grid-template-columns: repeat(4, 1fr); background: var(--mega-gray); border-radius: var(--radius-md); border: 1px solid var(--mega-border); overflow: hidden; }
    @media(max-width: 768px) { .stats-bar { grid-template-columns: repeat(2, 1fr); } }
    .stat-cell { padding: 24px 20px; text-align: center; border-right: 1px solid var(--mega-border); }
    .stat-cell:last-child { border-right: none; }
    @media(max-width: 768px) { .stat-cell:nth-child(2) { border-right: none; } .stat-cell:nth-child(3), .stat-cell:nth-child(4) { border-top: 1px solid var(--mega-border); } }
    .stat-icon { font-size: 1.5rem; margin-bottom: 8px; color: var(--primary); }
    .stat-num { display: block; font-size: 1.6rem; font-weight: 800; color: var(--mega-dark); margin-bottom: 4px; }
    .stat-lbl { font-size: 0.72rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

    /* App coming soon bar */
    .app-coming-soon-bar {
        position: relative;
        height: 70px;
        background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 25px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    .app-bar-content { display: flex; align-items: center; gap: 15px; z-index: 2; }
    .app-icon-stack { position: relative; font-size: 1.3rem; color: #3b82f6; display: flex; align-items: center; justify-content: center; }
    .pulse-ring { position: absolute; width: 32px; height: 32px; border: 2px solid #3b82f6; border-radius: 50%; animation: appPulse 2s infinite; }
    .app-text-info { display: flex; flex-direction: column; line-height: 1.2; }
    .app-badge { font-size: 0.58rem; text-transform: uppercase; font-weight: 800; color: #ffb800; letter-spacing: 1px; }
    .app-main-msg { margin: 0; color: white; font-size: 0.9rem; }
    .app-actions { display: flex; align-items: center; gap: 20px; z-index: 2; }
    .store-icons { display: flex; gap: 12px; font-size: 1.1rem; color: rgba(255, 255, 255, 0.4); }
    .notify-btn { background: white; color: #0f172a; text-decoration: none !important; font-weight: 800; font-size: 0.72rem; padding: 8px 18px; border-radius: 50px; transition: 0.3s; white-space: nowrap; }
    .notify-btn:hover { background: #3b82f6; color: white; transform: translateY(-2px); }
    .shimmer-effect { position: absolute; top: 0; left: -100%; width: 50%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent); transform: skewX(-20deg); animation: shimmerMove 6s infinite; z-index: 1; }
    @keyframes shimmerMove { 0% { left: -100%; } 30% { left: 150%; } 100% { left: 150%; } }
    @keyframes appPulse { 0% { transform: scale(0.8); opacity: 0.8; } 100% { transform: scale(1.4); opacity: 0; } }
    @media (max-width: 768px) {
        .app-coming-soon-bar { height: auto; padding: 15px; flex-direction: column; text-align: center; gap: 15px; }
        .store-icons { display: none; }
        .app-main-msg { font-size: 0.8rem; }
    }

    /* ════════ REAL ESTATE Showcase ════════ */
    .property-grid-custom {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 50px;
    }
    @media(max-width: 992px) { .property-grid-custom { grid-template-columns: 1fr 1fr; } }
    @media(max-width: 576px) { .property-grid-custom { grid-template-columns: 1fr; } }

    .prop-card-custom {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--mega-border);
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .prop-card-custom:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        border-color: var(--primary);
    }
    .prop-badge-status {
        position: absolute;
        top: 15px; left: 15px;
        background: var(--primary);
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 50px;
        text-transform: uppercase;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .prop-badge-status.rent {
        background: #3b82f6;
    }
    .prop-badge-status.sale {
        background: #10b981;
    }
    .prop-img-box {
        height: 180px;
        overflow: hidden;
        background: #f1f5f9;
        cursor: pointer;
    }
    .prop-img-box img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .prop-card-custom:hover .prop-img-box img {
        transform: scale(1.06);
    }
    .prop-info-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .prop-loc-row {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .prop-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--mega-dark);
        text-decoration: none !important;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prop-title:hover {
        color: var(--primary);
    }
    .prop-specs {
        display: flex;
        gap: 12px;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px dashed var(--mega-border);
    }
    .prop-specs span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .prop-price-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .prop-price-val {
        font-size: 1.15rem;
        font-weight: 900;
        color: var(--primary);
    }
    .prop-price-subtext {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
    }
    .prop-actions-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .btn-prop-primary {
        background: #0f172a;
        color: white;
        border: none;
        padding: 8.5px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: var(--radius-sm);
        text-decoration: none !important;
        text-align: center;
        transition: 0.2s;
    }
    .btn-prop-primary:hover {
        background: #1e293b;
        color: white;
    }
    .btn-prop-wa {
        background: #25d366;
        color: white;
        border: none;
        padding: 8.5px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: var(--radius-sm);
        text-decoration: none !important;
        text-align: center;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    .btn-prop-wa:hover {
        background: #20ba5a;
        color: white;
    }

    /* ════════ NEIGHBORHOODS ════════ */
    .neighborhood-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 50px;
    }
    @media(max-width: 992px) { .neighborhood-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width: 480px) { .neighborhood-grid { grid-template-columns: 1fr; } }

    .nb-card {
        position: relative;
        height: 140px;
        border-radius: var(--radius-md);
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        background-size: cover !important;
        background-position: center !important;
        transition: all 0.3s ease;
    }
    .nb-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .nb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15,23,42,0.2) 0%, rgba(15,23,42,0.85) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px;
        color: white;
    }
    .nb-name {
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 2px;
    }
    .nb-count {
        font-size: 0.72rem;
        opacity: 0.8;
        font-weight: 600;
    }

    /* ════════ SAFETY HUB ════════ */
    .safety-banner-wrap {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        border-radius: var(--radius-lg);
        padding: 40px;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .safety-banner-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .safety-banner-content {
        flex: 1;
        min-width: 280px;
    }
    .safety-badge {
        background: rgba(37, 99, 235, 0.2);
        border: 1px solid rgba(37, 99, 235, 0.3);
        color: #3b82f6;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: inline-block;
    }
    .safety-banner-content h4 {
        font-size: 1.45rem;
        font-weight: 850;
        margin-bottom: 12px;
        line-height: 1.3;
    }
    .safety-banner-content p {
        font-size: 0.85rem;
        opacity: 0.85;
        line-height: 1.6;
        margin: 0;
    }
    .safety-checklist {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        min-width: 320px;
    }
    @media(max-width: 576px) { .safety-checklist { grid-template-columns: 1fr; min-width: 100%; } }
    .safety-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .safety-item i {
        color: #10b981;
        font-size: 1.1rem;
    }

    /* ════════ HOW IT WORKS ════════ */
    .workflow-section {
        background: white;
        border: 1px solid var(--mega-border);
        border-radius: var(--radius-lg);
        padding: 36px 28px;
        margin: 40px 0;
    }
    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    @media(max-width: 768px) { .workflow-grid { grid-template-columns: 1fr; } }
    .workflow-step {
        text-align: center;
        padding: 15px;
    }
    .workflow-step-icon {
        width: 60px; height: 60px;
        background: rgba(30, 58, 138, 0.05);
        color: var(--primary);
        font-size: 1.6rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        transition: all 0.3s ease;
    }
    .workflow-step:hover .workflow-step-icon {
        transform: scale(1.1) rotate(5deg);
        background: var(--primary);
        color: white;
    }
    .workflow-step h5 {
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--mega-dark);
    }
    .workflow-step p {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }
</style>

<div class="container mt-1 pt-1">
    <!-- ════════ HERO ROW GRID (SEAMLESS FULL COVER ARTWORK) ════════ -->
    <div class="hero-grid">
        <!-- Main Left Banner: Dynamic Carousel for Key Service Pillars -->
        <div class="hero-banner-main" id="heroMainSlider" style="background-image: linear-gradient(90deg, #064e3b 0%, rgba(6, 78, 59, 0.75) 50%, rgba(6, 78, 59, 0.25) 100%), url('https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop');">
            <div class="banner-main-content">
                <span class="banner-badge" id="slideBadge">{{__('fresh_foods') ?: 'Farmers Market'}}</span>
                <h1 class="banner-title" id="slideTitle">Rwandan Fresh<br>Harvest Market</h1>
                <p class="banner-subtitle" id="slideDesc">Straight from local farms to your home. Direct sourcing, fast organic produce delivery, and guaranteed local rates.</p>
                <a href="{{url('/')}}/farmers-market" class="btn-shop-now-pill" id="slideLink">Shop Farm Fresh <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="carousel-dots">
                <span class="dot-node active" onclick="switchHeroSlide(0)"></span>
                <span class="dot-node" onclick="switchHeroSlide(1)"></span>
                <span class="dot-node" onclick="switchHeroSlide(2)"></span>
                <span class="dot-node" onclick="switchHeroSlide(3)"></span>
            </div>
        </div>

        <!-- Side Right Banner: Second Hand Hub (Full Cover Charcoal Artwork) -->
        <div class="hero-banner-side" style="background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 100%), url('https://images.unsplash.com/photo-1553440569-bcc63803a83d?q=80&w=800&auto=format&fit=crop');">
            <div class="banner-side-content">
                <span class="banner-badge" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2);">Verified Pre-Owned</span>
                <h3 class="fw-800 fs-4 mb-2">Used & Second<br>Hand Market</h3>
                <p class="small opacity-75 mb-4" style="line-height: 1.6;">Save money on certified pre-owned vehicles, laptops, and local furniture with guaranteed checks.</p>
                <a href="{{url('/')}}/products?category=second-hand" class="btn-link-shop">Explore Deals <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- ════════ SECONDARY 3-COL PROMO ROW (FULL COVER ARTWORK) ════════ -->
    <div class="promo-three-grid">
        <!-- Column 1: Fresh Foods Market -->
        <div class="promo-card-custom" style="background-image: linear-gradient(135deg, rgba(6, 78, 59, 0.95) 0%, rgba(6, 78, 59, 0.7) 100%), url('https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=600&auto=format&fit=crop');" onclick="window.location.href='{{url('/')}}/farmers-market'">
            <div class="promo-card-text">
                <h4 class="promo-card-title">Fresh Foods<br>Marketplace</h4>
                <a href="{{url('/')}}/farmers-market" class="btn-shop-link-white">Browse Harvest <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <!-- Column 2: Real Estate Market -->
        <div class="promo-card-custom" style="background-image: linear-gradient(135deg, rgba(11, 30, 71, 0.95) 0%, rgba(11, 30, 71, 0.7) 100%), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop');" onclick="window.location.href='{{url('/')}}/real_estate'">
            <div class="promo-card-text">
                <h4 class="promo-card-title">Rwandan Real<br>Estate Listings</h4>
                <a href="{{url('/')}}/real_estate" class="btn-shop-link-white">Find Properties <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <!-- Column 3: Affiliate Referrals -->
        <div class="promo-card-custom" style="background-image: linear-gradient(135deg, rgba(59, 7, 100, 0.95) 0%, rgba(59, 7, 100, 0.7) 100%), url('https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?q=80&w=600&auto=format&fit=crop');" onclick="window.location.href='{{url('/')}}/affiliate'">
            <div class="promo-card-text">
                <h4 class="promo-card-title">Affiliate & Earn<br>Commission</h4>
                <a href="{{url('/')}}/affiliate" class="btn-shop-link-white">Invite Friends <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <?php
    $homeCatTitles = [
        'rw' => [
            'farmers'     => 'Abahinzi & Umusaruro',
            'real_estate' => 'Imitungo Itimukanwa',
            'fashion'     => 'Imyambaro & Imideli',
            'electronics' => 'Elegitoroniki',
            'second_hand' => 'Ibyakoreshejwe',
            'affiliate'   => 'Invite & Earn',
            'all'         => 'Ibihari Byose'
        ],
        'en' => [
            'farmers'     => 'Farmers & Harvest',
            'real_estate' => 'Real Estate',
            'fashion'     => 'Fashion & Clothing',
            'electronics' => 'Electronics Shop',
            'second_hand' => 'Second Hand',
            'affiliate'   => 'Invite & Earn',
            'all'         => 'All Products'
        ],
        'sw' => [
            'farmers'     => 'Wakulima na Mavuno',
            'real_estate' => 'Mali Isiyohamishika',
            'fashion'     => 'Mitindo na Mavazi',
            'electronics' => 'Elektroniki',
            'second_hand' => 'Bidhaa Zilizotumika',
            'affiliate'   => 'Washirika (Earn)',
            'all'         => 'Bidhaa Zote'
        ]
    ];
    $currentLang = app()->getLocale();
    $homeCatData = $homeCatTitles[$currentLang] ?? $homeCatTitles['en'];
    ?>

    <!-- Shop by Categories Premium Cards -->
    <div class="sec-header border-0 mb-4 d-flex justify-content-between align-items-end">
        <div>
            <span class="text-primary fw-bold small text-uppercase tracking-wider mb-1 d-block">Explore</span>
            <h3 class="fw-800 mb-0" style="font-size: 1.8rem; letter-spacing: -0.5px;">{{__('best_shop_categories')}}</h3>
        </div>
    </div>
    
    <div class="row g-4 mb-2">
        <!-- Farmers Market -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/farmers-market" class="premium-cat-card" style="background: linear-gradient(145deg, #ecfdf5 0%, #d1fae5 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium text-success"><i class="bi bi-flower1"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['farmers']}}</h5>
                    <span class="text-success small fw-semibold">Fresh Local Produce &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-basket text-success"></i></div>
            </a>
        </div>
        <!-- Real Estate -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/real_estate" class="premium-cat-card" style="background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium text-primary"><i class="bi bi-buildings"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['real_estate']}}</h5>
                    <span class="text-primary small fw-semibold">Verified Properties &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-house-door text-primary"></i></div>
            </a>
        </div>
        <!-- Electronics -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/products?category=electronics" class="premium-cat-card" style="background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium" style="color: #4f46e5;"><i class="bi bi-laptop"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['electronics']}}</h5>
                    <span class="small fw-semibold" style="color: #4f46e5;">Top Tech Deals &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-cpu" style="color: #4f46e5;"></i></div>
            </a>
        </div>
        <!-- Fashion -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/products?category=fashion" class="premium-cat-card" style="background: linear-gradient(145deg, #fff1f2 0%, #ffe4e6 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium" style="color: #e11d48;"><i class="bi bi-handbag"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['fashion']}}</h5>
                    <span class="small fw-semibold" style="color: #e11d48;">Latest Trends &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-tags" style="color: #e11d48;"></i></div>
            </a>
        </div>
        <!-- Second Hand -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/products?category=second-hand" class="premium-cat-card" style="background: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium text-secondary"><i class="bi bi-recycle"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['second_hand']}}</h5>
                    <span class="text-secondary small fw-semibold">Quality Used Goods &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-bag text-secondary"></i></div>
            </a>
        </div>
        <!-- Affiliate -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/affiliate" class="premium-cat-card" style="background: linear-gradient(145deg, #fffbeb 0%, #fef3c7 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium text-warning"><i class="bi bi-share-fill"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['affiliate']}}</h5>
                    <span class="text-warning small fw-semibold">Earn Commissions &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-cash-coin text-warning"></i></div>
            </a>
        </div>
        <!-- All Products -->
        <div class="col-md-4 col-sm-6">
            <a href="{{url('/')}}/products" class="premium-cat-card" style="background: linear-gradient(145deg, #fdf4ff 0%, #fae8ff 100%);">
                <div class="cat-content">
                    <div class="cat-icon-premium" style="color: #c026d3;"><i class="bi bi-grid-fill"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{$homeCatData['all']}}</h5>
                    <span class="small fw-semibold" style="color: #c026d3;">Browse Catalog &rarr;</span>
                </div>
                <div class="cat-bg-icon"><i class="bi bi-collection" style="color: #c026d3;"></i></div>
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- ════════ FLASH DEALS SECTION ════════ -->
    <div class="flash-section-wrap">
        <div class="flash-top-bar">
            <h4><i class="bi bi-lightning-charge-fill text-warning"></i> {{__('flash_deals')}} <span class="flash-live-badge">{{__('live_now')}}</span></h4>
            <div class="flash-countdown">
                <span class="ends-lbl">{{__('ends_in')}}</span>
                <div class="cd-box"><span class="cd-num" id="cd-h">24</span><span class="cd-lbl">Hrs</span></div>
                <span class="cd-colon">:</span>
                <div class="cd-box"><span class="cd-num" id="cd-m">00</span><span class="cd-lbl">Min</span></div>
                <span class="cd-colon">:</span>
                <div class="cd-box"><span class="cd-num" id="cd-s">00</span><span class="cd-lbl">Sec</span></div>
            </div>
        </div>
        <div class="flash-cards-body">
            <div class="flash-scroll-row">
                <?php if (!empty($activeFlash)):
                    foreach ($activeFlash as $p):
                        $disc = $p['discount_percent'] > 0 ? $p['discount_percent'] : 20;
                        $old_price = round($p['price'] / (1 - $disc / 100));
                        $stock = (int)$p['stock_quantity'];
                        $sold_percent = ($stock > 20) ? 15 : (100 - ($stock * 5)); 
                        if($sold_percent > 98) $sold_percent = 98;
                ?>
                <div class="flash-card">
                    <span class="flash-disc-tag">-{{$disc}}%</span>
                    <div class="flash-card-img" onclick="window.location.href='products/{{$p['id']}}'">
                        <img src="{{kura_product_image_url($p->image_url, 'https://placehold.co/200')}}" onerror="this.src='https://placehold.co/200?text=Product+Image';">
                    </div>
                    <!-- Shop Name Badge -->
                    <div class="t-shop-badge" style="font-size: 0.65rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                        <i class="bi bi-shop text-primary"></i>
                        <span>{{$p['shop_name'] ?? 'Verified Seller'}}</span>
                    </div>
                    <div class="flash-card-title">{{$p['title']}}</div>
                    <div class="flash-sold-wrap">
                        <div class="flash-sold-labels"><span><i class="bi bi-fire text-danger"></i> {{$sold_percent}}% Sold</span><span>{{$stock}} left</span></div>
                        <div class="flash-sold-bar"><div class="flash-sold-fill" style="width:{{$sold_percent}}%"></div></div>
                    </div>
                    <div class="flash-price-row">
                        <div>
                            <span class="flash-price-old">{{number_format($old_price)}} Rwf</span>
                            <span class="flash-price-new text-primary fw-800">{{number_format($p['price'])}} Rwf</span>
                        </div>
                        <button class="flash-add-btn" onclick="addToCart({{$p['id']}}, this)"><i class="bi bi-cart-plus"></i></button>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <p class="text-muted p-4">{{__('no_active_flash_deals')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- ════════ TRENDING PRODUCTS (REF. DESIGN LAYOUT) ════════ -->
    <div class="trending-header-wrap">
        <h4 class="trending-title">Trending Products</h4>
        <div class="trending-tabs-list">
            <button class="trending-tab-btn active" onclick="filterCategory('all', this)">All Products</button>
            <button class="trending-tab-btn" onclick="filterCategory('electronics', this)">Electronics</button>
            <button class="trending-tab-btn" onclick="filterCategory('fashion', this)">Fashion</button>
            <button class="trending-tab-btn" onclick="filterCategory('building', this)">Construction</button>
            <button class="trending-tab-btn" onclick="filterCategory('food', this)">Farm Fresh</button>
        </div>
    </div>

    <div class="trending-grid-custom">
        <?php foreach ($trending as $p): 
            $badge = $p['is_flash_deal'] ? '<span class="t-badge sale">Sale</span>' : (($p['views_count'] > 100) ? '<span class="t-badge new">Hot</span>' : '');
            $stock = $p['stock_quantity'] > 0 ? $p['stock_quantity'] : 14; 
            $old_price = ($p['discount_percent'] > 0) ? round($p['price'] / (1 - $p['discount_percent'] / 100)) : null;
        ?>
        <div class="trending-item-card" data-category="{{$p['category']}}">
            <div class="trend-card-custom">
                {!! $badge !!}
                <div class="t-img-box" onclick="window.location.href='products/{{$p['id']}}'">
                    <img src="{{kura_product_image_url($p->image_url, 'https://placehold.co/200')}}" onerror="this.src='https://placehold.co/200?text=Product';">
                </div>
                <div class="t-stock-lbl">
                    <i class="bi bi-box-seam"></i> In stock {{$stock}} items
                </div>
                <!-- Shop Name Badge -->
                <div class="t-shop-badge" style="font-size: 0.73rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                    <i class="bi bi-shop text-primary"></i>
                    <span>{{$p['shop_name'] ?? 'Verified Seller'}}</span>
                </div>
                <a href="products/{{$p['id']}}" class="t-prod-title">{{$p['title']}}</a>
                <div class="t-stars-row">
                    {!! kura_rating_icon_html((float) ($p['avg_rating'] ?? 0)) !!}
                    <span>({{$p['rating_count'] ?? 1}})</span>
                </div>
                <div class="t-price-footer">
                    <div class="t-price-row">
                        <span class="t-price-current">{{number_format($p['price'])}} Rwf</span>
                        @if($old_price)
                            <span class="t-price-original">{{number_format($old_price)}} Rwf</span>
                        @endif
                    </div>
                    <button class="btn-order-now-full" onclick="addToCart({{$p['id']}}, this)">
                        <i class="bi bi-bag-plus"></i> Order Now
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="container">
    <!-- ════════ SPONSORED BANNER ════════ -->
    <div class="ad-slot-native">
        <span class="ad-tag">{{__('sponsored')}}</span>
        <div class="ad-native-inner">
            <div class="ad-native-icon"><i class="bi bi-stars"></i></div>
            <div class="ad-native-copy">
                <h6>{{__('advertise_business_title')}}</h6>
                <p>{{__('advertise_business_copy')}}</p>
            </div>
            <a href="{{url('/')}}/advertise" class="ad-native-cta">{{__('get_started')}}</a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- ════════ NEW ARRIVALS GRID ════════ -->
    <div class="sec-header">
        <h4>{{__('new_products')}}</h4>
        <a href="{{url('/')}}/products&sort=newest" class="text-decoration-none text-muted small">{{__('view_all')}} <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="trending-grid-custom">
        <?php foreach(collect($newArrivals)->take(5) as $p): 
            $stock = $p['stock_quantity'] > 0 ? $p['stock_quantity'] : 8;
            $old_price = ($p['discount_percent'] > 0) ? round($p['price'] / (1 - $p['discount_percent'] / 100)) : null;
        ?>
        <div class="trend-card-custom">
            <span class="t-badge new">New</span>
            <div class="t-img-box" onclick="window.location.href='products/{{$p['id']}}'">
                <img src="{{kura_product_image_url($p->image_url, 'https://placehold.co/200')}}" onerror="this.src='https://placehold.co/200?text=Product';">
            </div>
            <div class="t-stock-lbl">
                <i class="bi bi-box-seam"></i> In stock {{$stock}} items
            </div>
            <!-- Shop Name Badge -->
            <div class="t-shop-badge" style="font-size: 0.73rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                <i class="bi bi-shop text-primary"></i>
                <span>{{$p['shop_name'] ?? 'Verified Seller'}}</span>
            </div>
            <a href="products/{{$p['id']}}" class="t-prod-title">{{$p['title']}}</a>
            <div class="t-stars-row">
                {!! kura_rating_icon_html((float) ($p['avg_rating'] ?? 0)) !!}
                <span>({{$p['rating_count'] ?? 1}})</span>
            </div>
            <div class="t-price-footer">
                <div class="t-price-row">
                    <span class="t-price-current">{{number_format($p['price'])}} Rwf</span>
                    @if($old_price)
                        <span class="t-price-original">{{number_format($old_price)}} Rwf</span>
                    @endif
                </div>
                <button class="btn-order-now-full" onclick="addToCart({{$p['id']}}, this)">
                    <i class="bi bi-bag-plus"></i> Order Now
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<?php
$secTitles = [
    'rw' => [
        'real_estate_title' => 'Imitungo Itimukanwa Iheruka',
        'real_estate_sub' => 'Reba amazu ndetse n\'ibibanza bigurishwa cyangwa bikodeshwa mu Rwanda',
        'contact_agent' => 'Vugana na Nyirabyo',
        'view_details' => 'Reba Ibirambuye',
        'rent' => 'Gukodesha',
        'sale' => 'Kugura',
        'beds' => 'Ibyumba',
        'baths' => 'Ubwiherero',
        
        'neighborhoods_title' => 'Ahantu Hagezweho Hagurishwa cyangwa Hakodeshwa',
        'neighborhoods_sub' => 'Shakisha imitungo mu bice bikunzwe mu mujyi wa Kigali no mu Rwanda',
        'listings_count' => 'Imitungo ihari',
        
        'workflow_title' => 'Uko Trust Rwanda Ikora',
        'workflow_sub' => 'Uburyo bworoshye bwo kugura, kugurisha, no kubona inyungu nk\'umushyigikiro',
        'workflow_buy_title' => '1. Gura mu Mutekano',
        'workflow_buy_desc' => 'Shakisha ibiribwa bishya, ibikoresho bya elegitoroniki, cyangwa inzu zizewe mu Rwanda.',
        'workflow_sell_title' => '2. Gurisha vuba',
        'workflow_sell_desc' => 'Fungura iduka ryawe ucururize abakiriya benshi ku rates nziza cyane.',
        'workflow_earn_title' => '3. Winjiza Komisiyo',
        'workflow_earn_desc' => 'Sangiza abandi link yawe maze ubone komisiyo igeze kuri 10% kuri buri deal ifunzwe.',
        
        'safety_title' => 'Gura Ibiribwa ndetse n\'Imitungo ku Mutekano 100%',
        'safety_desc' => 'Muri Trust Rwanda dushyira imbere umutekano w\'umukiriya. Ntugakore payment utarabona ibyo ugura ngo ubyemeze!',
        'safety_item_1' => 'Sura mbere yo kwishyura',
        'safety_item_2' => 'Escrow / Kurinda Abaguzi',
        'safety_item_3' => 'Amaduka yemejwe na Trust',
        'safety_item_4' => 'Gufasha abaguzi 24/7'
    ],
    'en' => [
        'real_estate_title' => 'Latest Property Listings',
        'real_estate_sub' => 'Explore residential homes, ghettos, and commercial land for rent or sale',
        'contact_agent' => 'Contact Owner',
        'view_details' => 'View Details',
        'rent' => 'For Rent',
        'sale' => 'For Sale',
        'beds' => 'Beds',
        'baths' => 'Baths',
        
        'neighborhoods_title' => 'Browse Popular Locations',
        'neighborhoods_sub' => 'Find verified listings in Rwanda\'s most sought-after neighborhoods',
        'listings_count' => 'properties',
        
        'workflow_title' => 'How Trust Rwanda Works',
        'workflow_sub' => 'An easy-to-use secure marketplace for buyers, sellers, and affiliate promoters',
        'workflow_buy_title' => '1. Find & Purchase',
        'workflow_buy_desc' => 'Browse organic produce, high-end electronics, or certified housing deals.',
        'workflow_sell_title' => '2. Sell & Rent Out',
        'workflow_sell_desc' => 'Open your digital store, upload listings, and reach verified local buyers.',
        'workflow_earn_title' => '3. Share & Earn Cash',
        'workflow_earn_desc' => 'Promote products and listings. Earn up to 10% commission on closed sales.',
        
        'safety_title' => '100% Trust & Buyer Protection Guarantee',
        'safety_desc' => 'Your financial safety is our absolute priority. Never send mobile money payments before physical verification.',
        'safety_item_1' => 'Verify Before Payment',
        'safety_item_2' => 'Secured Escrow Deals',
        'safety_item_3' => 'Verified Vendor Shops',
        'safety_item_4' => '24/7 Support Hotline'
    ],
    'sw' => [
        'real_estate_title' => 'Mali Mpya Isiyohamishika',
        'real_estate_sub' => 'Gundua nyumba za kuishi, vyumba, na ardhi kwa ajili ya kupangisha au kuuza',
        'contact_agent' => 'Mawasiliano',
        'view_details' => 'Angalia Maelezo',
        'rent' => 'Ya Kupangisha',
        'sale' => 'Ya Kuuzwa',
        'beds' => 'Vyumba',
        'baths' => 'Bafu',
        
        'neighborhoods_title' => 'Vinjari Maeneo Maarufu',
        'neighborhoods_sub' => 'Tafuta mali zilizothibitishwa katika maeneo maarufu nchini Rwanda',
        'listings_count' => 'mali zilizopo',
        
        'workflow_title' => 'Jinsi Trust Rwanda Inavyofanya Kazi',
        'workflow_sub' => 'Soko rahisi na salama kwa wanunuzi, wauzaji, na washirika wa uuzaji',
        'workflow_buy_title' => '1. Tafuta na Ununue',
        'workflow_buy_desc' => 'Pata chakula kibichi cha shambani, vifaa vya kielektroniki, au nyumba zilizothibitishwa.',
        'workflow_sell_title' => '2. Uza na Kupangisha',
        'workflow_sell_desc' => 'Fungua duka lako la kidijitali na ufikie maelfu ya wateja nchini Rwanda.',
        'workflow_earn_title' => '3. Shiriki na Upate Hela',
        'workflow_earn_desc' => 'Tangaza bidhaa na kupata hadi 10% ya tume ya mauzo yaliyofanikiwa.',
        
        'safety_title' => 'Dhamana Salama ya Mnunuzi 100%',
        'safety_desc' => 'Usalama wako wa kifedha ndio kipaumbele chetu kikuu. Usilipe kamwe kabla ya kuona bidhaa au nyumba.',
        'safety_item_1' => 'Thibitisha Kabla ya Kulipa',
        'safety_item_2' => 'Ulinzi wa Escrow Salama',
        'safety_item_3' => 'Maduka ya Wauzaji Salama',
        'safety_item_4' => 'Msaada wa Simu 24/7'
    ]
];
$secData = $secTitles[$currentLang] ?? $secTitles['en'];
?>

<!-- ════════ REAL ESTATE SHOWCASE SECTION (NEW!) ════════ -->
<div class="container mb-5">
    <div class="sec-header">
        <div>
            <h4 class="mb-1">{{$secData['real_estate_title']}}</h4>
            <p class="text-muted small mb-0">{{$secData['real_estate_sub']}}</p>
        </div>
        <a href="{{url('/')}}/real_estate" class="text-decoration-none text-muted small">{{__('view_all') ?: 'View All'}} <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="property-grid-custom">
        <?php if (!empty($realEstateListings)):
            foreach ($realEstateListings as $prop):
                $isRent = ($prop->listing_type === 'rent');
                $badgeText = $isRent ? $secData['rent'] : $secData['sale'];
                $badgeClass = $isRent ? 'rent' : 'sale';
                $beds = $prop->features->where('feature_name', 'Bedrooms')->first()->feature_value ?? '-'; 
                $baths = $prop->features->where('feature_name', 'Bathrooms')->first()->feature_value ?? '-';
                $imageUrl = $prop->images->first()->image_url ?? null;
                $imageUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl)) : 'https://placehold.co/400x250?text=Property';
        ?>
        <div class="prop-card-custom">
            <span class="prop-badge-status {{$badgeClass}}">{{$badgeText}}</span>
            <div class="prop-img-box" onclick="window.location.href='{{ route('properties.show', $prop->id) }}'">
                <img src="{{ $imageUrl }}" onerror="this.src='https://placehold.co/400x250?text=Property';">
            </div>
            <div class="prop-info-body">
                <div class="prop-loc-row">
                    <i class="bi bi-geo-alt-fill text-danger"></i> {{ $prop->district }}, {{ $prop->sector }}
                </div>
                <a href="{{ route('properties.show', $prop->id) }}" class="prop-title">{{$prop->title}}</a>
                
                <div class="prop-specs">
                    <span><i class="bi bi-door-open"></i> {{$beds}} {{$secData['beds']}}</span>
                    <span><i class="bi bi-droplet"></i> {{$baths}} {{$secData['baths']}}</span>
                </div>

                <div class="prop-price-footer">
                    <div>
                        <span class="prop-price-val">{{number_format($prop->price)}} Rwf</span>
                        <span class="prop-price-subtext">{{$isRent ? '/ month' : ''}}</span>
                    </div>
                    <div class="prop-actions-row">
                        <a href="{{ route('properties.show', $prop->id) }}" class="btn-prop-primary">{{$secData['view_details']}}</a>
                        <a href="https://wa.me/{{preg_replace('/[^0-9]/', '', $prop->owner->phone ?? '250796194401')}}?text=Hello,%20I%20am%20interested%20in%20your%20property%20listing:%20{{urlencode($prop->title)}}" target="_blank" class="btn-prop-wa">
                            <i class="bi bi-whatsapp"></i> {{$secData['contact_agent']}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
            <!-- Seed Fallback listings if db is empty -->
            <?php
            $fallbacks = [
                [
                    'title' => 'Modern 3 Bedroom House in Kibagabaga',
                    'price' => 1200000,
                    'is_rent' => true,
                    'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'title' => 'Commercial Land for Sale in Gahanga',
                    'price' => 35000000,
                    'is_rent' => false,
                    'img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'title' => 'Luxury Apartment with Pool in Nyarutarama',
                    'price' => 2500000,
                    'is_rent' => true,
                    'img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'title' => 'Beautiful Villa Near Musanze Volcanoes',
                    'price' => 120000000,
                    'is_rent' => false,
                    'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600&auto=format&fit=crop'
                ]
            ];
            foreach ($fallbacks as $prop):
                $beds = rand(3, 5); $baths = rand(2, 4);
            ?>
            <div class="prop-card-custom">
                <span class="prop-badge-status {{$prop['is_rent'] ? 'rent' : 'sale'}}">{{$prop['is_rent'] ? $secData['rent'] : $secData['sale']}}</span>
                <div class="prop-img-box">
                    <img src="{{$prop['img']}}">
                </div>
                <div class="prop-info-body">
                    <div class="prop-loc-row">
                        <i class="bi bi-geo-alt-fill text-danger"></i> Kigali, Rwanda
                    </div>
                    <a href="{{url('/')}}/real_estate" class="prop-title">{{$prop['title']}}</a>
                    <div class="prop-specs">
                        <span><i class="bi bi-door-open"></i> {{$beds}} {{$secData['beds']}}</span>
                        <span><i class="bi bi-droplet"></i> {{$baths}} {{$secData['baths']}}</span>
                    </div>
                    <div class="prop-price-footer">
                        <div>
                            <span class="prop-price-val">{{number_format($prop['price'])}} Rwf</span>
                            <span class="prop-price-subtext">{{$prop['is_rent'] ? '/ month' : ''}}</span>
                        </div>
                        <div class="prop-actions-row">
                            <a href="{{url('/')}}/real_estate" class="btn-prop-primary">{{$secData['view_details']}}</a>
                            <a href="https://wa.me/250796194401?text=Hello,%20I%20am%20interested%20in%20your%20property%20listing:%20{{urlencode($prop['title'])}}" target="_blank" class="btn-prop-wa">
                                <i class="bi bi-whatsapp"></i> {{$secData['contact_agent']}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>



<!-- ════════ HOW IT WORKS SECTION (NEW!) ════════ -->
<div class="container mb-5">
    <div class="workflow-section">
        <div class="sec-header" style="border-bottom-color:#eee; padding-bottom:15px; margin-bottom:30px;">
            <div class="text-center w-100">
                <h4 class="mb-1 text-center w-100" style="font-size:1.5rem;">{{$secData['workflow_title']}}</h4>
                <p class="text-muted small mb-0 text-center w-100">{{$secData['workflow_sub']}}</p>
            </div>
        </div>
        <div class="workflow-grid">
            <div class="workflow-step">
                <div class="workflow-step-icon"><i class="bi bi-cart-check"></i></div>
                <h5>{{$secData['workflow_buy_title']}}</h5>
                <p>{{$secData['workflow_buy_desc']}}</p>
            </div>
            <div class="workflow-step">
                <div class="workflow-step-icon"><i class="bi bi-shop-window"></i></div>
                <h5>{{$secData['workflow_sell_title']}}</h5>
                <p>{{$secData['workflow_sell_desc']}}</p>
            </div>
            <div class="workflow-step">
                <div class="workflow-step-icon"><i class="bi bi-wallet2"></i></div>
                <h5>{{$secData['workflow_earn_title']}}</h5>
                <p>{{$secData['workflow_earn_desc']}}</p>
            </div>
        </div>
    </div>
</div>

<!-- ════════ ESCROW & SAFETY HUB SECTION (NEW!) ════════ -->
<div class="container mb-5">
    <div class="safety-banner-wrap">
        <div class="safety-banner-inner">
            <div class="safety-banner-content">
                <span class="safety-badge"><i class="bi bi-shield-lock-fill"></i> Buyer Protection</span>
                <h4>{{$secData['safety_title']}}</h4>
                <p>{{$secData['safety_desc']}}</p>
            </div>
            <div class="safety-checklist">
                <div class="safety-item"><i class="bi bi-check-circle-fill"></i> {{$secData['safety_item_1']}}</div>
                <div class="safety-item"><i class="bi bi-check-circle-fill"></i> {{$secData['safety_item_2']}}</div>
                <div class="safety-item"><i class="bi bi-check-circle-fill"></i> {{$secData['safety_item_3']}}</div>
                <div class="safety-item"><i class="bi bi-check-circle-fill"></i> {{$secData['safety_item_4']}}</div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- ════════ CUSTOMER REVIEWS ════════ -->
    <div class="reviews-bg">
        <div class="sec-header" style="border-bottom-color:#ddd;">
            <h4>{{__('what_our_customers_say')}}</h4>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="color:var(--mega-yellow);font-size:0.82rem;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></span>
                <span style="font-size:0.78rem;color:#888;font-weight:600;">4.8 / 5 from 3,200+ reviews</span>
            </div>
        </div>
        <div class="reviews-grid">
            <div class="review-card">
                <div class="rv-quote">"</div>
                <div class="rv-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p class="rv-text">Ordered roofing sheets and they arrived in Musanze the next day. Quality was exactly as shown. Will definitely order again!</p>
                <div class="rv-author">
                    <div class="rv-avatar" style="background:#1e3a8a;">JM</div>
                    <div>
                        <div class="rv-name">Jean Marie K.</div>
                        <div class="rv-loc"><i class="bi bi-geo-alt-fill" style="font-size:0.6rem;"></i> Musanze, Rwanda</div>
                    </div>
                    <span class="rv-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                </div>
            </div>

            <div class="review-card">
                <div class="rv-quote">"</div>
                <div class="rv-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p class="rv-text">The best platform for fresh farm produce. I ordered 20kg of avocados and tomatoes for my restaurant; they were delivered within 3 hours in Kigali!</p>
                <div class="rv-author">
                    <div class="rv-avatar" style="background:#10b981;">AW</div>
                    <div>
                        <div class="rv-name">Alice W.</div>
                        <div class="rv-loc"><i class="bi bi-geo-alt-fill" style="font-size:0.6rem;"></i> Kigali, Rwanda</div>
                    </div>
                    <span class="rv-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                </div>
            </div>

            <div class="review-card">
                <div class="rv-quote">"</div>
                <div class="rv-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p class="rv-text">Buying electronics online in Rwanda used to be risky, but Trust Rwanda changed that. Got my Dell laptop with a warranty card and original packaging.</p>
                <div class="rv-author">
                    <div class="rv-avatar" style="background:#f59e0b;">EC</div>
                    <div>
                        <div class="rv-name">Emmanuel C.</div>
                        <div class="rv-loc"><i class="bi bi-geo-alt-fill" style="font-size:0.6rem;"></i> Huye, Rwanda</div>
                    </div>
                    <span class="rv-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" id="recentlySection">
    <div class="sec-header"><h4>{{__('recently_viewed')}}</h4><button onclick="clearRecent()" class="btn btn-sm border-0 text-muted">{{__('clear')}}</button></div>
    <div class="recently-row" id="recentlyRow"></div>
</div>

<div class="container">
    <!-- ════════ WHY SHOP WITH US / TRUST MODULE ════════ -->
    <div class="trust-section">
        <div class="sec-header"><h4>{{__('why_shop_with_kura')}}</h4></div>
        <div class="trust-cards-grid">
            <div class="trust-card"><div class="trust-icon-wrap ti-blue"><i class="bi bi-truck"></i></div><div class="trust-title">{{__('fast_delivery')}}</div></div>
            <div class="trust-card"><div class="trust-icon-wrap ti-green"><i class="bi bi-shield-check"></i></div><div class="trust-title">{{__('protection_100')}}</div></div>
            <div class="trust-card"><div class="trust-icon-wrap ti-yellow"><i class="bi bi-arrow-repeat"></i></div><div class="trust-title">{{__('returns_7_day')}}</div></div>
            <div class="trust-card"><div class="trust-icon-wrap ti-red"><i class="bi bi-headset"></i></div><div class="trust-title">{{__('support_24_7')}}</div></div>
        </div>

        <div class="stats-bar-container">
            <div class="stats-bar">
                <div class="stat-cell">
                    <i class="bi bi-box-seam stat-icon"></i>
                    <span class="stat-num" data-target="{{$displayProducts}}">0</span>
                    <span class="stat-lbl">{{__('active_products')}}</span>
                </div>
                <div class="stat-cell">
                    <i class="bi bi-shop stat-icon"></i>
                    <span class="stat-num" data-target="{{$displayVendors}}">0</span>
                    <span class="stat-lbl">{{__('verified_shops')}}</span>
                </div>
                <div class="stat-cell highlight">
                    <i class="bi bi-emoji-smile stat-icon"></i>
                    <span class="stat-num" data-target="{{$displayClients}}">0</span>
                    <span class="stat-lbl">{{__('happy_clients')}}</span>
                </div>
                <div class="stat-cell">
                    <i class="bi bi-geo-alt stat-icon"></i>
                    <span class="stat-num" data-target="30">0</span>
                    <span class="stat-lbl">{{__('districts_covered')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-4">
    <!-- ════════ MOBILE APP ANNOUNCEMENT ════════ -->
    <div class="app-coming-soon-bar">
        <div class="app-bar-content">
            <div class="app-icon-stack">
                <i class="bi bi-phone-vibrate"></i>
                <div class="pulse-ring"></div>
            </div>
            <div class="app-text-info text-center text-md-start">
                <span class="app-badge">{{__('coming_soon')}}</span>
                <p class="app-main-msg">{{__('mobile_app_coming')}}</p>
            </div>
        </div>
        <div class="app-actions">
            <div class="store-icons">
                <i class="bi bi-apple"></i>
                <i class="bi bi-google-play"></i>
            </div>
            <a href="https://wa.me/250796194401?text=Notify me when the Trust Rwanda App is live!" class="notify-btn">
                {{__('notify_me')}} <i class="bi bi-bell-fill ms-1"></i>
            </a>
        </div>
        <div class="shimmer-effect"></div>
    </div>
</div>

<script>
    /* ── HERO CAROUSEL ROTATING LOGIC ── */
    const heroSlidesData = [
        {
            badge: "Farmers Market",
            title: "Rwandan Fresh<br>Harvest Market",
            desc: "Straight from local farms to your home. Direct sourcing, fast organic produce delivery, and guaranteed local rates.",
            img: "https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop",
            link: "farmers-market",
            btnText: "Shop Farm Fresh",
            overlay: "#064e3b"
        },
        {
            badge: "Real Estate Market",
            title: "Verified Housing<br>& Lands for Sale",
            desc: "Find your dream home or commercial plot in Kigali, Musanze, and across Rwanda. Safe, verified property listings.",
            img: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop",
            link: "real_estate",
            btnText: "Find Properties",
            overlay: "#0b1e47"
        },
        {
            badge: "Multi-vendor Tech",
            title: "Modern Electronics<br>& Appliances Shop",
            desc: "Explore top laptops, smartphones, home screens, and accessories with active local dealer warranties.",
            img: "https://images.unsplash.com/photo-1468495244123-6c6c332eeece?q=80&w=1200&auto=format&fit=crop",
            link: "products?category=electronics",
            btnText: "Shop Electronics",
            overlay: "#1e1b4b"
        },
        {
            badge: "Invite & Earn",
            title: "Affiliate Referrals<br>& Commissions",
            desc: "Earn cash by inviting clients to Trust Rwanda. Get up to 10% cash commission on every successful deal you close.",
            img: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=1200&auto=format&fit=crop",
            link: "profile",
            btnText: "Join Affiliate Now",
            overlay: "#3b0764"
        }
    ];

    let activeHeroSlideIdx = 0;
    let heroSliderTimer = null;

    function switchHeroSlide(index) {
        activeHeroSlideIdx = index;
        const banner = document.getElementById('heroMainSlider');
        if (!banner) return;
        
        const badgeEl = banner.querySelector('#slideBadge');
        const titleEl = banner.querySelector('#slideTitle');
        const descEl = banner.querySelector('#slideDesc');
        const linkEl = banner.querySelector('#slideLink');
        const dots = banner.querySelectorAll('.dot-node');
        
        const data = heroSlidesData[index];
        
        // Apply Changes with full-bleed covers
        banner.style.backgroundImage = `linear-gradient(90deg, ${data.overlay} 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.25) 100%), url('${data.img}')`;
        badgeEl.textContent = data.badge;
        titleEl.innerHTML = data.title;
        descEl.textContent = data.desc;
        linkEl.href = data.link;
        linkEl.innerHTML = `${data.btnText} <i class="bi bi-arrow-right"></i>`;
        
        // Update Carousel dot indicators
        dots.forEach((dot, idx) => {
            if (idx === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        // Reset timer
        startHeroAutoplay();
    }

    function startHeroAutoplay() {
        if (heroSliderTimer) clearInterval(heroSliderTimer);
        heroSliderTimer = setInterval(() => {
            let next = (activeHeroSlideIdx + 1) % heroSlidesData.length;
            switchHeroSlide(next);
        }, 6000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        startHeroAutoplay();
    });

    /* ── TABS CATEGORY FILTERING ── */
    function filterCategory(categorySlug, btnElement) {
        const tabs = document.querySelectorAll('.trending-tab-btn');
        tabs.forEach(t => t.classList.remove('active'));
        btnElement.classList.add('active');

        const cards = document.querySelectorAll('.trending-grid-custom .trending-item-card');
        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            if (categorySlug === 'all' || cat === categorySlug) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 200);
            }
        });
    }

    /* ── FLASH DEALS COUNTDOWN ── */
    (function () {
        function updateFlashTimer() {
            const now = new Date().getTime();
            const cycle = 48 * 60 * 60 * 1000; 
            const startTimestamp = 1704067200000; 
            const elapsed = (now - startTimestamp) % cycle;
            const remaining = cycle - elapsed;

            const hours = Math.floor(remaining / (1000 * 60 * 60));
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

            const hEl = document.getElementById('cd-h');
            const mEl = document.getElementById('cd-m');
            const sEl = document.getElementById('cd-s');

            if (hEl && mEl && sEl) {
                hEl.textContent = String(hours).padStart(2, '0');
                mEl.textContent = String(minutes).padStart(2, '0');
                sEl.textContent = String(seconds).padStart(2, '0');
            }
        }
        setInterval(updateFlashTimer, 1000);
        updateFlashTimer();
    })();

    /* ── DYNAMIC STATS COUNTER ── */
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.stat-num');
        const speed = 200;

        const animate = (counter) => {
            const value = +counter.getAttribute('data-target');
            const data = +counter.innerText.replace('+', '').replace('k', '').replace('%', '');
            const time = value / speed;

            if (data < value) {
                const nextValue = Math.ceil(data + time);
                if (value >= 1000) {
                    counter.innerText = (nextValue / 1000).toFixed(1) + 'k';
                } else {
                    counter.innerText = nextValue;
                }
                setTimeout(() => animate(counter), 10);
            } else {
                if (value >= 1000) {
                    counter.innerText = (value / 1000).toFixed(1) + 'k+';
                } else if (counter.nextElementSibling && counter.nextElementSibling.textContent.includes('Satisfied')) {
                    counter.innerText = value + '%';
                } else {
                    counter.innerText = value + '+';
                }
            }
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    });
</script>

@endsection
