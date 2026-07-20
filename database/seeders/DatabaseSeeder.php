<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->truncate();
        DB::table('properties')->truncate();
        DB::table('property_images')->truncate();
        DB::table('categories')->truncate();
        
        $now = Carbon::now();

        // Ensure users exist
        $adminId = DB::table('users')->where('role', 'admin')->value('id');
        if (!$adminId) {
            $adminId = DB::table('users')->insertGetId([
                'full_name' => 'Admin User',
                'email' => 'admin@trustrwanda.com',
                'phone' => '0780000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => $now
            ]);
        }

        $vendorId = DB::table('users')->where('role', 'vendor')->value('id');
        if (!$vendorId) {
            $vendorId = DB::table('users')->insertGetId([
                'full_name' => 'IMANZI STORE',
                'email' => 'vendor@trustrwanda.com',
                'phone' => '0780000002',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'shop_name' => 'IMANZI STORE',
                'created_at' => $now
            ]);
        }

        $ownerId = DB::table('users')->where('role', 'real_estate_owner')->value('id');
        if (!$ownerId) {
            $ownerId = DB::table('users')->insertGetId([
                'full_name' => 'Real Estate Owner',
                'email' => 'owner@trustrwanda.com',
                'phone' => '0780000003',
                'password' => Hash::make('password'),
                'role' => 'real_estate_owner',
                'created_at' => $now
            ]);
        }

        // Categories
        $categories = [
            ['id' => 1, 'name' => 'Electronics (Computers & Audio)', 'slug' => 'electronics', 'icon_class' => 'bi-laptop', 'type' => 'general'],
            ['id' => 2, 'name' => 'Mobile Phones & Tablets', 'slug' => 'mobile-phones', 'icon_class' => 'bi-phone', 'type' => 'general'],
            ['id' => 3, 'name' => 'Second Hand (Furniture & Autos)', 'slug' => 'second-hand', 'icon_class' => 'bi-recycle', 'type' => 'general'],
            ['id' => 4, 'name' => 'Farmers Market: Fresh Fruits', 'slug' => 'fruits', 'icon_class' => 'bi-apple', 'type' => 'farm'],
            ['id' => 5, 'name' => 'Farmers Market: Vegetables', 'slug' => 'vegetables', 'icon_class' => 'bi-basket', 'type' => 'farm'],
            ['id' => 6, 'name' => 'Farmers Market: Dairy', 'slug' => 'dairy', 'icon_class' => 'bi-cup', 'type' => 'farm'],
        ];
        DB::table('categories')->insert($categories);

        // Products
        $products = [
            // Electronics
            [
                'id' => 1, 'user_id' => $vendorId, 'category_id' => 1, 'category' => 'electronics', 
                'title' => 'MacBook Pro 16" M3 Max', 'description' => 'The ultimate pro laptop with the M3 Max chip.',
                'price' => 3500000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=MacBook+Pro',
                'stock_quantity' => 10, 'is_fresh_produce' => 0, 'created_at' => $now
            ],
            [
                'id' => 2, 'user_id' => $vendorId, 'category_id' => 1, 'category' => 'electronics', 
                'title' => 'Sony WH-1000XM5 Headphones', 'description' => 'Industry leading noise canceling headphones.',
                'price' => 400000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Sony+Headphones',
                'stock_quantity' => 20, 'is_fresh_produce' => 0, 'created_at' => $now
            ],
            [
                'id' => 3, 'user_id' => $vendorId, 'category_id' => 1, 'category' => 'electronics', 
                'title' => 'Samsung 65" 4K Smart TV', 'description' => 'Ultra HD Smart LED TV with vibrant colors.',
                'price' => 950000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Samsung+TV',
                'stock_quantity' => 5, 'is_fresh_produce' => 0, 'created_at' => $now
            ],

            // Mobile Phones
            [
                'id' => 4, 'user_id' => $vendorId, 'category_id' => 2, 'category' => 'mobile-phones', 
                'title' => 'iPhone 15 Pro Max', 'description' => 'Titanium frame, A17 Pro chip, 48MP camera.',
                'price' => 1800000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=iPhone+15',
                'stock_quantity' => 25, 'is_fresh_produce' => 0, 'created_at' => $now
            ],
            [
                'id' => 5, 'user_id' => $vendorId, 'category_id' => 2, 'category' => 'mobile-phones', 
                'title' => 'Samsung Galaxy S24 Ultra', 'description' => 'Galaxy AI is here. 200MP camera.',
                'price' => 1650000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Galaxy+S24',
                'stock_quantity' => 15, 'is_fresh_produce' => 0, 'created_at' => $now
            ],

            // Second Hand
            [
                'id' => 6, 'user_id' => $vendorId, 'category_id' => 3, 'category' => 'second-hand', 
                'title' => 'Used Sofa Set (Good Condition)', 'description' => 'Complete living room sofa set. Slightly used.',
                'price' => 150000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Used+Sofa',
                'stock_quantity' => 1, 'is_fresh_produce' => 0, 'created_at' => $now
            ],
            [
                'id' => 7, 'user_id' => $vendorId, 'category_id' => 3, 'category' => 'second-hand', 
                'title' => 'Used Toyota RAV4 2015', 'description' => 'Clean title, well maintained SUV.',
                'price' => 18000000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Toyota+RAV4',
                'stock_quantity' => 1, 'is_fresh_produce' => 0, 'created_at' => $now
            ],

            // Farmers Market
            [
                'id' => 8, 'user_id' => $vendorId, 'category_id' => 4, 'category' => 'fruits', 
                'title' => 'Fresh Rwandan Avocados', 'description' => 'Organically grown hass avocados.',
                'price' => 2000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/22c55e/ffffff?text=Fresh+Avocados',
                'stock_quantity' => 100, 'is_fresh_produce' => 1, 'created_at' => $now
            ],
            [
                'id' => 9, 'user_id' => $vendorId, 'category_id' => 5, 'category' => 'vegetables', 
                'title' => 'Organic Carrots', 'description' => 'Freshly harvested carrots from the Northern province.',
                'price' => 1500, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/22c55e/ffffff?text=Organic+Carrots',
                'stock_quantity' => 50, 'is_fresh_produce' => 1, 'created_at' => $now
            ],
            [
                'id' => 10, 'user_id' => $vendorId, 'category_id' => 6, 'category' => 'dairy', 
                'title' => 'Fresh Cow Milk (1 Liter)', 'description' => 'Pasteurized full cream milk from local farms.',
                'price' => 1000, 'price_unit' => 'RWF', 'image_url' => 'https://placehold.co/600x400/22c55e/ffffff?text=Fresh+Milk',
                'stock_quantity' => 200, 'is_fresh_produce' => 1, 'created_at' => $now
            ],
        ];
        DB::table('products')->insert($products);

        // Properties (Real Estate)
        $properties = [
            [
                'id' => 1, 'owner_id' => $ownerId, 'property_type' => 'house', 'listing_type' => 'sale',
                'title' => 'Modern 4-Bedroom Villa in Nyarutarama', 'description' => 'Luxurious villa with a swimming pool and scenic views.',
                'price' => 250000000, 'price_period' => 'once', 'address' => 'KG 9 Ave, Nyarutarama',
                'district' => 'Gasabo', 'sector' => 'Remera', 'status' => 'available', 'is_verified' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'id' => 2, 'owner_id' => $ownerId, 'property_type' => 'apartment', 'listing_type' => 'rent',
                'title' => 'Fully Furnished 2-Bedroom Apartment', 'description' => 'Located in the heart of Kigali. Includes Wi-Fi and cleaning services.',
                'price' => 800000, 'price_period' => 'month', 'address' => 'KN 3 Rd, Kiyovu',
                'district' => 'Nyarugenge', 'sector' => 'Kiyovu', 'status' => 'available', 'is_verified' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'id' => 3, 'owner_id' => $ownerId, 'property_type' => 'land', 'listing_type' => 'sale',
                'title' => 'Commercial Plot in Kicukiro Centre', 'description' => 'Prime location for a commercial building. 30x40 meters.',
                'price' => 45000000, 'price_period' => 'once', 'address' => 'KK 15 Rd',
                'district' => 'Kicukiro', 'sector' => 'Kicukiro', 'status' => 'available', 'is_verified' => 0,
                'created_at' => $now, 'updated_at' => $now
            ]
        ];
        DB::table('properties')->insert($properties);

        // Property Images
        $propertyImages = [
            ['id' => 1, 'property_id' => 1, 'image_url' => 'https://placehold.co/800x600/1e3a8a/ffffff?text=Luxury+Villa', 'sort_order' => 0],
            ['id' => 2, 'property_id' => 2, 'image_url' => 'https://placehold.co/800x600/1e3a8a/ffffff?text=Furnished+Apartment', 'sort_order' => 0],
            ['id' => 3, 'property_id' => 3, 'image_url' => 'https://placehold.co/800x600/1e3a8a/ffffff?text=Commercial+Land', 'sort_order' => 0],
        ];
        DB::table('property_images')->insert($propertyImages);
    }
}
