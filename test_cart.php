<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cart = [8 => 1];
$ids = array_map('intval', array_keys($cart));
$products = App\Models\Product::whereIn('id', $ids)->where('is_visible', 1)->get();
$availableProductIds = $products->pluck('id')->toArray();

var_dump($availableProductIds);
echo "In array (string '8'): "; var_dump(in_array("8", $availableProductIds));
echo "In array (int 8): "; var_dump(in_array(8, $availableProductIds));
