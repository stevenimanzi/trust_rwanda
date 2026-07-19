<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';

    protected $fillable = [
        'order_id',
        'vendor_id',
        'recipient',
        'message_body',
        'gateway_response',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
