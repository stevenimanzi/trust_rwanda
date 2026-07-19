<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAnalytic extends Model
{
    use HasFactory;

    protected $table = 'ad_analytics';

    public $timestamps = false;

    protected $fillable = [
        'ad_id',
        'event_type',
        'user_ip',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function ad()
    {
        return $this->belongsTo(ActiveAd::class, 'ad_id');
    }
}
