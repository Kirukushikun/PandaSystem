<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RequestorModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

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
        'has_allowances' => 'boolean',
    ];

    // Define encryptable fields once
    protected array $encryptable = [
        'date_hired',
        'employment_status',
        'division',
        'doe_from',
        'doe_to',
        'wage_no',
        'action_reference_data',
        'remarks',
        'prepared_by',
        'approved_by',
    ];


    /** 
     * Auto-encrypt attributes before saving 
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable) && !is_null($value)) {
            if (! $this->isEncrypted($value)) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $value = Crypt::encryptString((string) $value);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /** 
     * Auto-decrypt attributes when accessing 
     */
    public function getAttribute($key){

        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && !is_null($value)) {
            try {
                $decrypted = Crypt::decryptString($value);

                // Try to JSON-decode arrays
                $decoded = json_decode($decrypted, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $decrypted = $decoded;
                }

                // Parse date fields into Carbon
                if (in_array($key, ['date_hired', 'doe_from', 'doe_to']) && !empty($decrypted)) {
                    return Carbon::parse($decrypted);
                }

                return $decrypted;
            } catch (\Exception $e) {
                return $value; // Skip if not encrypted yet
            }
        }

        return $value;
    }

    private function isEncrypted($value): bool
    {
        return is_string($value) && str_starts_with($value, 'eyJ'); // typical base64 start
    }

    public function requestor()
    {
        return $this->belongsTo(RequestorModel::class, 'request_id', 'id');
    }
}
