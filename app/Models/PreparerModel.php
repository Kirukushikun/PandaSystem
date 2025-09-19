<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RequestorModel;

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
        'has_allowances',
        'prepared_by',
        'approved_by'
    ];

    protected $casts = [
        'date_hired' => 'datetime',
        'doe_from' => 'datetime',
        'doe_to' => 'datetime',
        'action_reference_data' => 'array',
        'has_allowances' => 'boolean'
    ];

    public function requestor()
    {
        return $this->belongsTo(RequestorModel::class, 'request_id', 'id');
    }
}
