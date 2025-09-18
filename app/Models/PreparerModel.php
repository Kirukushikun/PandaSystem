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
        'doe_from',
        'doe_to',
        'wage_no',
        'action_reference_data',
        'remarks',
        'prepared_by',
        'approved_by'
    ];

    protected $casts = [
        'date_hired' => 'datetime',
        'doe_from' => 'datetime',
        'doe_to' => 'datetime',
        'action_reference_data' => 'array'
    ];
}
