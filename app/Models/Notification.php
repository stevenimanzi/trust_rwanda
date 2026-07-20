<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    // The migration used useCurrent() for created_at, but didn't add updated_at.
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'created_at'
    ];
}
