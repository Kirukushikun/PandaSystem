<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparerModel extends Model
{
    protected $table = "preparer";

    protected $fillable = [
        'request_id',
        'date_hired',
        'employment_status',
        'division',
        'date_of_effectivity',
        'action_reference_data',
        'remarks',
        'prepared_by',
        'approved_by'
    ];

    protected $casts = [
        'date_hired' => 'datetime',
        'date_of_effectivity' => 'datetime',
        'action_reference_data' => 'array'
    ];
}
