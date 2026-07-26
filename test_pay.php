<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = App\Models\Order::first();
if (!$order) die("No order");

$pc = app()->make(App\Http\Controllers\PaymentController::class);
try {
    $res = $pc->checkout($order->transaction_id);
    echo "Class: " . get_class($res) . "\n";
    if (method_exists($res, 'getTargetUrl')) {
        echo "Target URL: " . $res->getTargetUrl() . "\n";
    }
} catch (\Throwable $e) {
    echo "FATAL EXCEPTION: " . $e->getMessage() . "\n";
}
