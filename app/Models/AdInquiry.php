<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdInquiry extends Model
{
    use HasFactory;

    protected $table = 'ad_inquiries';

    protected $fillable = [
        'business',
        'name',
        'package',
        'status',
        'message',
    ];
}
