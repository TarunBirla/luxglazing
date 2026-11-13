<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'period_label',
        'spend_gbp',
        'impressions',
        'clicks',
        'leads_generated',
        'cost_per_lead_gbp',
        'conversions_won',
    ];
}
