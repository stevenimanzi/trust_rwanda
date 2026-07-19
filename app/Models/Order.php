<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'delivery_status',
        'delivery_address',
        'delivery_phone',
        'transaction_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
