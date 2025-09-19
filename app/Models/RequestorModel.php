<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PreparerModel;

class RequestorModel extends Model
{
    protected $table = 'requestor';

    protected $fillable = [
        'request_no',
        'is_deleted_by',
        'current_handler',
        'request_status',
        'confidentiality',
        'employee_id',
        'employee_name',
        'department',
        'farm',
        'type_of_action',
        'justification',
        'supporting_file_url',
        'supporting_file_name',
        'requested_by',
        'submitted_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->is_deleted_by)) {
                $model->is_deleted_by = [
                    'requestor' => false,
                    'preparer'  => false,
                    'approver'  => false,
                ];
            }
        });
    }

    protected $casts = [
        'is_deleted_by' => 'array',
        'submitted_at' => 'datetime'
    ];

    public function preparer()
    {
        return $this->hasOne(PreparerModel::class, 'request_id', 'id');
    }

}
