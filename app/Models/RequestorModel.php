<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PreparerModel;
use Illuminate\Support\Facades\Crypt;

class RequestorModel extends Model
{
    protected $table = 'requestor';

    protected $fillable = [
        // 🔹 Basic Info
        'request_no',
        'confidentiality',

        // 🔹 Employee Details
        'employee_id',
        'employee_name',
        'farm',
        'department',
        'type_of_action',
        'justification',

        // 🔹 Attachments
        'supporting_file_url',
        'supporting_file_name',

        // 🔹 Workflow & Status
        'request_status',
        'current_handler',
        'is_deleted_by',

        // 🔹 References
        'requested_by',
        'requestor_id',
        'divisionhead_id',
        'hr_id',
        'approver_id',

        // 🔹 Timestamps
        'submitted_at',
    ];

    // Auto-encrypt on set
    public function setAttribute($key, $value)
    {
        $encryptable = [
            'justification',

            'requested_by',
            'requestor_id',
            'divisionhead_id',
            'hr_id',
        ];

        if (in_array($key, $encryptable) && !is_null($value)) {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    // Auto-decrypt on get
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        $encryptable = [
            'justification',

            'requested_by',
            'requestor_id',
            'divisionhead_id',
            'hr_id',
        ];

        if (in_array($key, $encryptable) && !is_null($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value; // In case value isn't encrypted yet
            }
        }

        return $value;
    }

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

    public static function createWithGeneratedRequestNo(array $attributes): self
    {
        return DB::transaction(function () use ($attributes) {
            $farmCode = $attributes['farm'] ?? 'GEN';
            $year = now()->year;

            $attributes['request_no'] = 'TMP-' . Str::upper(Str::random(12));

            $request = static::create($attributes);

            $request->request_no = static::buildRequestNo($farmCode, $year, $request->id);
            $request->save();

            return $request->fresh();
        });
    }

    public static function buildRequestNo(string $farmCode, int $year, int $id): string
    {
        return 'PAN-' . $farmCode . '-' . $year . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    }

    public function preparer()
    {
        return $this->hasOne(PreparerModel::class, 'request_id', 'id')->latestOfMany();
    }

}
