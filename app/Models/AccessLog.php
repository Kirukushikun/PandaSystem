<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $table = "access_logs";

    protected $fillable = [
        'email',
        'success',
        'ip_address',
        'user_agent'
    ];
}
