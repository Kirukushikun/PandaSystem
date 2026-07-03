<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Users who may submit PAN requests for this department.
     */
    public function requestors(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('is_requestor', true);
    }

    /**
     * Users who act as Division Head for this department. A department
     * can have more than one head (e.g. co-heads / backup approvers).
     */
    public function heads(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('is_head', true);
    }
}
