<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = collect([]);
        $total = 0;

        if (!empty($cart)) {
            $ids = array_map('intval', array_keys($cart));
            if (!empty($ids)) {
                $products = Product::whereIn('id', $ids)->where('is_visible', 1)->get();
                \Log::info('Cart Index Debug', ['cart' => $cart, 'ids' => $ids, 'products_count' => $products->count()]);
                $availableProductIds = $products->pluck('id')->toArray();
                
                // Cleanup unavailable items
                foreach ($cart as $productId => $qty) {
                    if (!in_array($productId, $availableProductIds)) {
                        unset($cart[$productId]);
                    }
                }
                session(['cart' => $cart]);

                foreach ($products as $p) {
                    $qty = $cart[$p->id] ?? 0;
                    $total += $p->price * $qty;
                }
            }
        }

        return view('store.cart', compact('products', 'cart', 'total'));
    }

    public function add(Request $request)
    {
        $pid = (int) $request->input('product_id', 0);
        $qty = max(1, (int) $request->input('qty', 1));
        $isAjax = $request->ajax() || $request->has('ajax') || $request->wantsJson();

        if ($pid <= 0) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Invalid product ID']);
            }
            return back();
        }

        $product = Product::where('id', $pid)->where('is_visible', 1)->first();
        if (!$product) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Product not found']);
            }
            return back();
        }

        if ($product->stock_quantity <= 0) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Product is out of stock']);
            }
            return back();
        }

        $cart = session('cart', []);
        $currentQty = $cart[$pid] ?? 0;
        if ($currentQty + $qty > $product->stock_quantity) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Not enough stock available']);
            }
            return back();
        }

        $cart[$pid] = $currentQty + $qty;
        session(['cart' => $cart]);
        session()->save();

        $count = array_sum($cart);
        \Log::info('Cart Add Debug', ['pid' => $pid, 'qty' => $qty, 'cart' => session('cart')]);

        if ($request->ajax() || $request->isJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['status' => 'success', 'cart_count' => $count]);
        }

        return redirect()->back()->with('msg', 'Item added to your bag!');
    }

    public function update(Request $request)
    {
        $pid = (int) $request->input('product_id', 0);
        $qty = (int) $request->input('qty', 1);

        $cart = session('cart', []);
        if ($pid > 0) {
            if ($qty > 0) {
                $product = Product::find($pid);
                if ($product && $qty <= $product->stock_quantity) {
                    $cart[$pid] = $qty;
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Insufficient stock']);
                }
            } else {
                unset($cart[$pid]);
            }
            session(['cart' => $cart]);
            session()->save();
        }

        $count = array_sum($cart);
        return response()->json(['status' => 'success', 'cart_count' => $count]);
    }

    public function remove(Request $request)
    {
        $pid = (int) $request->input('product_id', 0);
        $cart = session('cart', []);
        if ($pid > 0 && isset($cart[$pid])) {
            unset($cart[$pid]);
            session(['cart' => $cart]);
            session()->save();
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'cart_count' => array_sum($cart)]);
        }

        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        // Dev helper: Auto-login first user if guest
        if (auth()->guest()) {
            $user = User::first();
            if ($user) {
                auth()->login($user);
            } else {
                return redirect()->route('home')->with('error', 'Please login to checkout.');
            }
        }

        $user = auth()->user();
        $cart = session('cart', []);
        $total = 0;

        if (empty($cart)) {
            return redirect()->route('products.index');
        }

        $cartItems = [];
        $productIds = array_map('intval', array_keys($cart));
        
        if (!empty($productIds)) {
            $products = Product::with('vendor')
                ->whereIn('id', $productIds)
                ->where('is_visible', 1)
                ->get();

            $availableProductIds = $products->pluck('id')->toArray();
            
            // Clean cart
            foreach ($cart as $productId => $qty) {
                if (!in_array($productId, $availableProductIds)) {
                    unset($cart[$productId]);
                }
            }
            session(['cart' => $cart]);

            $productsById = $products->keyBy('id');

            $vendorGroups = [];

            foreach ($cart as $id => $qty) {
                $productId = (int)$id;
                if (!isset($productsById[$productId])) {
                    continue;
                }
                $p = $productsById[$productId];
                $lineTotal = $p->price * $qty;
                $total += $lineTotal;
                
                $vId = $p->user_id;
                if (!isset($vendorGroups[$vId])) {
                    $vendorGroups[$vId] = [
                        'shop_name' => $p->vendor ? $p->vendor->shop_name : 'TrustRwanda Shop',
                        'items' => [],
                        'subtotal' => 0
                    ];
                }
                
                $vendorGroups[$vId]['items'][] = ['product' => $p, 'qty' => $qty];
                $vendorGroups[$vId]['subtotal'] += $lineTotal;
            }
        }

        return view('store.checkout', compact('vendorGroups', 'total', 'user'));
    }

    public function placeOrder(Request $request)
    {
        if (auth()->guest()) {
            $user = User::first();
            if ($user) {
                auth()->login($user);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Session expired. Please login.']);
            }
        }

        $user = auth()->user();
        $cart = session('cart', []);

        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Cart is empty.']);
        }

        $address = $request->input('address', 'Pickup at Store');
        $locationLat = trim((string)$request->input('location_lat', ''));
        $locationLng = trim((string)$request->input('location_lng', ''));
        
        if ($locationLat !== '' && $locationLng !== '') {
            $address .= "\n📍 Maps: https://maps.google.com/?q={$locationLat},{$locationLng}";
        }
        $phone = $request->input('contact_phone', $user->phone);

        $totalAmount = 0;
        $vendorGroups = [];

        // Group items by vendor
        foreach ($cart as $pid => $qty) {
            $p = Product::where('id', $pid)->first();
            if ($p) {
                $lineTotal = ($p->price * $qty);
                $totalAmount += $lineTotal;
                $vId = $p->user_id;

                if (!isset($vendorGroups[$vId])) {
                    $vData = User::find($vId);
                    $vendorGroups[$vId] = [
                        'items' => [],
                        'subtotal' => 0,
                        'email' => $vData->email ?? '',
                        'phone' => $vData->phone ?? '',
                        'shop' => $vData->shop_name ?? 'Vendor'
                    ];
                }
                $vendorGroups[$vId]['items'][] = [
                    'id' => $p->id,
                    'title' => $p->title,
                    'qty' => $qty,
                    'price' => $p->price
                ];
                $vendorGroups[$vId]['subtotal'] += $lineTotal;
            }
        }

        DB::beginTransaction();

        try {
            $transactionId = 'KURA_' . strtoupper(bin2hex(random_bytes(4)));
            $firstOrderId = null;
            $whatsappLinks = [];

            foreach ($vendorGroups as $vId => $group) {
                // Save Order per vendor
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $group['subtotal'],
                    'payment_method' => 'whatsapp',
                    'payment_status' => 'pending',
                    'delivery_status' => 'pending',
                    'delivery_address' => $address,
                    'delivery_phone' => $phone,
                    'transaction_id' => $transactionId
                ]);

                if (!$firstOrderId) {
                    $firstOrderId = $order->id;
                }

                $waItemsList = "";

                foreach ($group['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'vendor_id' => $vId,
                        'quantity' => $item['qty'],
                        'price_at_purchase' => $item['price']
                    ]);

                    // Affiliate Commission logic
                    $refUserId = (int) session('ref_user_id', 0);
                    if ($refUserId > 0 && $refUserId !== (int)$user->id) {
                        $referrer = User::find($refUserId);
                        if ($referrer) {
                            $commissionPct = 10;
                            $lineTotal = ($item['price'] * $item['qty']);
                            $commissionAmount = $lineTotal * ($commissionPct / 100);

                            AffiliateCommission::create([
                                'referrer_id' => $refUserId,
                                'buyer_id' => $user->id,
                                'order_id' => $order->id,
                                'product_id' => $item['id'],
                                'product_price' => $item['price'],
                                'commission_amount' => $commissionAmount,
                                'status' => 'pending'
                            ]);
                        }
                    }

                    $waItemsList .= "✅ {$item['qty']}x {$item['title']} (" . number_format($item['price']) . " RWF)\n";

                    // Decrement product stock quantity
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['qty']);
                    }
                }

                $vCleanPhone = preg_replace('/[^0-9]/', '', $group['phone']);
                if (strpos($vCleanPhone, '0') === 0) {
                    $vCleanPhone = '250' . substr($vCleanPhone, 1);
                }

                // WhatsApp message payload
                $waMsg  = "⭐ *NEW ORDER RECEIVED* ⭐\n";
                $waMsg .= "------------------------------------------\n";
                $waMsg .= "🆔 *Order ID:* #{$order->id}\n";
                $waMsg .= "👤 *Customer:* {$user->full_name}\n";
                $waMsg .= "------------------------------------------\n";
                $waMsg .= "🛍️ *ITEMS:*\n{$waItemsList}";
                $waMsg .= "💰 *TOTAL:* " . number_format($group['subtotal']) . " RWF\n";
                $waMsg .= "📍 *DELIVERY:* {$address}\n";
                $waMsg .= "------------------------------------------\n";
                $waMsg .= "🚀 _Process this now via KURA PRO Panel._";

                $whatsappLinks[] = [
                    'shop_name' => $group['shop'],
                    'url' => "https://wa.me/{$vCleanPhone}?text=" . urlencode($waMsg),
                    'subtotal' => $group['subtotal']
                ];
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('ref_user_id');

            session(['latest_order_secure' => [
                'id' => $transactionId, // Using transaction ID as the order reference
                'address' => $address,
                'links' => $whatsappLinks
            ]]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function success()
    {
        $latestOrder = session('latest_order_secure');
        if (!$latestOrder) {
            return redirect()->route('products.index');
        }

        return view('store.order_success', compact('latestOrder'));
    }
}
