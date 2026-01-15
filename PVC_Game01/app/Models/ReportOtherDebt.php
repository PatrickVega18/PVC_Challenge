<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportOtherDebt extends Model
{
    protected $fillable = [
        'subscription_report_id',
        'entity',
        'currency',
        'amount',
        'expiration_days',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(SubscriptionReport::class, 'subscription_report_id');
    }
}
