<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{   
    use HasFactory;
    
    protected $table = 'employees';

    protected $fillable = [
        'company_id',
        'full_name',
        'farm',
        'position',
        'department',
        'hasOngoing'
    ];

    protected $cast = [
        'hasOngoing' => 'boolean'
    ];

}
