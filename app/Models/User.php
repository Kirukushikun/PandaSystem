<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'role',
        'access',
        'esign'
    ];

    protected $casts = [
        'access' => 'array',
    ];

    protected $attributes = [
        'access' => '{"RQ_Module":true,"DH_Module":true,"HRP_Module":true,"HRA_Module":true,"FA_Module":true}',
    ];


}
