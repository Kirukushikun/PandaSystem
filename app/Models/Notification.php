<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notifications";

    protected $fillable = [
        'pan_id',
        'ref_no',
        'allowance_expiry',
        'message',
        'days_left',
        'status',
        'is_read',
        'last_notified_at',
        'resolved_at',
        'type'
    ];

    protected $casts = [
        'allowance_expiry'   => 'date',   // automatically cast to Carbon
        'last_notified_at'   => 'datetime',
        'resolved_at'        => 'datetime',
        'is_read'            => 'boolean',
        'days_left'          => 'integer',
    ];
}
