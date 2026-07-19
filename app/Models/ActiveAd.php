<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveAd extends Model
{
    use HasFactory;

    protected $table = 'active_ads';

    protected $fillable = [
        'title',
        'description',
        'content_type',
        'content_url',
        'html_content',
        'placement',
        'target_url',
        'start_date',
        'end_date',
        'status',
        'display_count',
        'click_count',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function analytics()
    {
        return $this->hasMany(AdAnalytic::class, 'ad_id');
    }
}
