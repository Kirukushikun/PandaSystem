<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'company_id',
        'full_name',
        'farm',
        'position',
        'hasOngoing'
    ];

    protected $cast = [
        'hasOngoing' => 'boolean'
    ];

}
