<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'hasPending',
        'farm',
        'position',
        'role',
        'access',
        'esign',
        'is_confidentiality_approver'
    ];

    protected $casts = [
        'access' => 'array',
        'hasPending' => 'boolean',
        'is_confidentiality_approver' => 'boolean'
    ];

    protected $attributes = [
        'access' => '{"RQ_Module":true,"DH_Module":true,"HRP_Module":true,"HRA_Module":true,"FA_Module":true}',
    ];

    /**
     * Departments this user may submit PAN requests for (as Requestor).
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)->wherePivot('is_requestor', true);
    }

    /**
     * Departments this user is Division Head of. Usually one, but a
     * department may have co-heads so this isn't capped to a single row.
     * Independent of Requestor status (e.g. an overall approver of
     * division heads may head every department without submitting
     * requests for any of them).
     */
    public function headedDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)->wherePivot('is_head', true);
    }

    /**
     * Unfiltered department_user rows for this user, covering both Requestor
     * and Head flags. Only meant for writing (see UseraccessTable::saveDepartments) —
     * use departments()/headedDepartments() for reads.
     */
    public function departmentMemberships(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)->withPivot('is_requestor', 'is_head');
    }
}
