<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCreditCard extends Model
{
    protected $fillable = [
        'subscription_report_id',
        'bank',
        'currency',
        'line',
        'used',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(SubscriptionReport::class, 'subscription_report_id');
    }
}
