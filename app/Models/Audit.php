<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audits';

    protected $fillable = [
        'user_id',
        'name',
        'module',
        'action',
    ];

    public function user()
    {
    	return $this->belongsto('App\Models\User');
    }
}
