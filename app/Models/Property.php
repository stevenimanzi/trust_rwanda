<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'owner_id',
        'property_type',
        'listing_type',
        'title',
        'description',
        'price',
        'price_period',
        'address',
        'district',
        'sector',
        'latitude',
        'longitude',
        'status',
        'is_verified',
        'youtube_video_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function features()
    {
        return $this->hasMany(PropertyFeature::class, 'property_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }
}
