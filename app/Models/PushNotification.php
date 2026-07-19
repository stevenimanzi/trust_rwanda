<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $table = 'push_notifications';

    protected $fillable = [
        'title',
        'message',
        'icon',
        'image',
        'action_url',
        'target_users',
        'status',
        'scheduled_at',
        'sent_at',
        'recipient_count',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
