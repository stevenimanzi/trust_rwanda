<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'category_id',
        'category',
        'title',
        'description',
        'price',
        'price_unit',
        'image_url',
        'stock_quantity',
        'is_fresh_produce',
        'harvest_date',
        'expiry_date',
        'batch_number',
        'is_visible',
        'views',
        'is_flash_deal',
        'discount_percent',
        'views_count',
        'promo_status',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
