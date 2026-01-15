<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'full_name',
        'document',
        'email',
        'phone',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(SubscriptionReport::class);
    }
}
