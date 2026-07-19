<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 1. My Products Index page
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        $search = $request->query('q', '');
        
        $query = Product::where('user_id', $vendorId)->where('category', '!=', 'real-estate');
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')->get();
        
        // Calculate Aggregates
        $lowStock = 0;
        $totalValue = 0;
        foreach ($products as $p) {
            if ($p->stock_quantity < 5) {
                $lowStock++;
            }
            $totalValue += ($p->price * $p->stock_quantity);
        }

        // AJAX response support
        if ($request->ajax() || $request->query('ajax') == 1) {
            return response()->json([
                'kpis' => [
                    'total' => $products->count(),
                    'value' => number_format((float)$totalValue),
                    'low' => $lowStock
                ],
                'products' => $products
            ]);
        }

        return view('vendor.products', compact('products', 'search', 'lowStock', 'totalValue'));
    }

    // 2. Create Product Form
    public function create()
    {
        $categories = Category::where('slug', '!=', 'real-estate')->get();
        return view('vendor.product_add', compact('categories'));
    }

    // 3. Store Product
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:160',
            'category' => 'required|string|max:60',
            'price' => 'required|numeric|min:1',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string|max:4000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $vendorId = Auth::id();
        $title = $request->input('title');
        $category = $request->input('category');
        if ($category === 'real-estate') {
            return redirect()->back()->with('error', 'Vendors are not authorized to manage real estate. Please use a property owner account.')->withInput();
        }

        $price = $request->input('price');
        $priceUnit = $request->input('price_unit');
        $stock = $request->input('stock_quantity');
        $description = $request->input('description');
        $isFresh = $request->has('is_fresh') ? 1 : 0;

        // Find category id
        $catObj = Category::where('slug', $category)->first();
        $categoryId = $catObj ? $catObj->id : null;

        // Image upload logic
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $fileNameOnly = "PROD_" . time() . "_" . bin2hex(random_bytes(4)) . '.' . $ext;
            
            $uploadDir = public_path('assets/uploads/products');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $file->move($uploadDir, $fileNameOnly);

            Product::create([
                'user_id' => $vendorId,
                'category_id' => $categoryId,
                'category' => $category,
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'price_unit' => $priceUnit,
                'stock_quantity' => $stock,
                'image_url' => $fileNameOnly,
                'is_fresh_produce' => $isFresh,
                'is_visible' => 1,
            ]);

            return redirect()->route('vendor.products')->with('msg', 'Item successfully synchronized with storefront.');
        }

        return redirect()->back()->with('error', 'Featured media node is missing.')->withInput();
    }

    // 4. Edit Product Form
    public function edit($id)
    {
        $vendorId = Auth::id();
        $product = Product::where('id', $id)
            ->where('user_id', $vendorId)
            ->where('category', '!=', 'real-estate')
            ->firstOrFail();
        $categories = Category::where('slug', '!=', 'real-estate')->get();
        
        return view('vendor.product_edit', compact('product', 'categories'));
    }

    // 5. Update Product
    public function update(Request $request, $id)
    {
        $vendorId = Auth::id();
        $product = Product::where('id', $id)
            ->where('user_id', $vendorId)
            ->where('category', '!=', 'real-estate')
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:160',
            'category' => 'required|string|max:60',
            'price' => 'required|numeric|min:1',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string|max:4000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $category = $request->input('category');
        if ($category === 'real-estate') {
            return redirect()->back()->with('error', 'Vendors are not authorized to manage real estate. Please use a property owner account.');
        }

        $catObj = Category::where('slug', $category)->first();
        $categoryId = $catObj ? $catObj->id : null;

        $updateData = [
            'title' => $request->input('title'),
            'category_id' => $categoryId,
            'category' => $category,
            'price' => $request->input('price'),
            'price_unit' => $request->input('price_unit'),
            'stock_quantity' => $request->input('stock_quantity'),
            'description' => $request->input('description'),
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ];

        // Handle Image upload if exists
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $fileNameOnly = "PROD_" . time() . "_" . bin2hex(random_bytes(4)) . '.' . $ext;
            
            $uploadDir = public_path('assets/uploads/products');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $file->move($uploadDir, $fileNameOnly);
            $updateData['image_url'] = $fileNameOnly;
        }

        $product->update($updateData);

        return redirect()->route('vendor.products.edit', $product->id)->with('msg', 'Product updated successfully.');
    }

    // 6. Delete Product
    public function destroy(Request $request)
    {
        $pid = $request->input('product_id');
        $vendorId = Auth::id();
        
        $product = Product::where('id', $pid)
            ->where('user_id', $vendorId)
            ->where('category', '!=', 'real-estate')
            ->first();
        if ($product) {
            \App\Models\OrderItem::where('product_id', $product->id)->delete();
            $product->delete();
            return redirect()->route('vendor.products')->with('msg', 'Product deleted successfully.');
        }

        return redirect()->route('vendor.products')->with('error', 'Product not found.');
    }

    // 6b. Bulk Delete Products
    public function bulkDestroy(Request $request)
    {
        $vendorId = Auth::id();
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return redirect()->route('vendor.products')->with('error', 'No products selected for deletion.');
        }

        $productsToDelete = Product::whereIn('id', $productIds)
            ->where('user_id', $vendorId)
            ->where('category', '!=', 'real-estate')
            ->pluck('id');

        if ($productsToDelete->count() > 0) {
            \App\Models\OrderItem::whereIn('product_id', $productsToDelete)->delete();
            $deletedCount = Product::whereIn('id', $productsToDelete)->delete();
            return redirect()->route('vendor.products')->with('msg', "$deletedCount product(s) deleted successfully.");
        }

        return redirect()->route('vendor.products')->with('error', 'Could not delete the selected products.');
    }

    // 7. Quick Update via catalog screen
    public function quickUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        $vendorId = Auth::id();
        $pid = $request->input('product_id');
        
        $product = Product::where('id', $pid)->where('user_id', $vendorId)->first();
        if ($product) {
            $product->update([
                'price' => $request->input('price'),
                'stock_quantity' => $request->input('stock'),
            ]);
            return redirect()->route('vendor.products')->with('msg', 'Quick update completed successfully.');
        }

        return redirect()->route('vendor.products')->with('error', 'Quick update failed.');
    }

    // 8. Stock alerts (Inventory Management page)
    public function inventoryMgmt(Request $request)
    {
        $vendorId = Auth::id();
        
        if ($request->isMethod('post') && $request->has('update_stock')) {
            $request->validate([
                'product_id' => 'required|integer',
                'stock_qty' => 'required|integer|min:0',
            ]);
            
            $pid = $request->input('product_id');
            $newStock = $request->input('stock_qty');
            
            $product = Product::where('id', $pid)->where('user_id', $vendorId)->first();
            if ($product) {
                $product->update([
                    'stock_quantity' => $newStock,
                ]);
                return redirect()->route('vendor.inventory')->with('msg', 'Stock levels synchronized successfully.');
            } else {
                return redirect()->route('vendor.inventory')->with('error', 'Product not found.');
            }
        }

        $products = Product::where('user_id', $vendorId)->get();

        // Summary Aggregates
        $lowStockCount = 0;
        $outOfStockCount = 0;
        foreach ($products as $p) {
            if ($p->stock_quantity <= 0) {
                $outOfStockCount++;
            } elseif ($p->stock_quantity <= 5) {
                $lowStockCount++;
            }
        }

        return view('vendor.inventory_mgmt', compact('products', 'lowStockCount', 'outOfStockCount'));
    }
}
