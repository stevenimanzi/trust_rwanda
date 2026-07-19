<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'shop_name',
        'shop_logo',
        'shop_description',
        'logo_url',
        'latitude',
        'longitude',
        'is_verified',
        'reset_token',
        'token_expiry',
        'subscription_status',
        'subscription_expires_at',
        'otp_code',
        'otp_expiry',
    ];

    protected $hidden = [
        'password',
        'reset_token',
        'otp_code',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Accessor to keep compatibility with $user['name'] if needed
    public function getNameAttribute()
    {
        return $this->full_name;
    }
}
