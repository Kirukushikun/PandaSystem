<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = "correction_log";

    protected $fillable = [
        'request_id',
        'origin',
        'header',
        'body'
    ];

    // protected $casts = [
    //     'details' => 'array',
    // ];
}
