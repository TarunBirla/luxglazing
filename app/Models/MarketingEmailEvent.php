<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingEmailEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'email_id',
        'contact_id',
        'ses_message_id',
        'event_type',
        'event_timestamp',
        'recipient_email',
        'metadata',
    ];

    protected $casts = [
        'event_timestamp' => 'datetime',
        'metadata' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(MarketingCampaign::class, 'campaign_id');
    }

    public function email()
    {
        return $this->belongsTo(MarketingEmail::class, 'email_id');
    }

    public function contact()
    {
        return $this->belongsTo(MarketingContact::class, 'contact_id');
    }
}
