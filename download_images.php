<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

$products = Product::all();
$imgDir = public_path('assets/images/products');

if (!file_exists($imgDir)) {
    mkdir($imgDir, 0755, true);
}

echo "Starting image downloads for " . $products->count() . " products...\n";

foreach ($products as $p) {
    if (str_starts_with($p->image_url, 'http')) {
        $url = $p->image_url;
        $fileName = 'real_' . $p->id . '_' . crc32($url) . '.jpg';
        $fullPath = $imgDir . '/' . $fileName;
        
        echo "Downloading for Product ID {$p->id}: {$url} ... ";
        
        try {
            // Using curl to download to avoid allow_url_fopen issues
            $ch = curl_init($url);
            $fp = fopen($fullPath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            
            // Check if file is valid
            if (filesize($fullPath) > 1000) {
                $p->image_url = $fileName;
                $p->save();
                echo "SUCCESS -> saved as {$fileName}\n";
            } else {
                echo "FAILED (file too small)\n";
                unlink($fullPath);
            }
        } catch (\Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
}
echo "Done!\n";
