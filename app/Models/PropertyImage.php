<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $table = 'property_images';

    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'image_url',
        'sort_order',
        'alt_text',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
