<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'purpose',
        'subject',
        'content',
        'template_id',
        'sending_mode',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'target_criteria',
        'sender_email',
        'sender_name',
        'total_contacts',
        'emails_generated',
        'emails_queued',
        'emails_sent',
        'emails_delivered',
        'emails_opened',
        'emails_clicked',
        'emails_bounced',
        'emails_complained',
        'openai_tokens_used',
        'openai_cost_usd',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'target_criteria' => 'array',
        'openai_cost_usd' => 'decimal:4',
    ];

    public function template()
    {
        return $this->belongsTo(MarketingTemplate::class, 'template_id');
    }

    public function emails()
    {
        return $this->hasMany(MarketingEmail::class, 'campaign_id');
    }

    public function events()
    {
        return $this->hasMany(MarketingEmailEvent::class, 'campaign_id');
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->emails_delivered == 0) return 0.0;
        return round(($this->emails_opened / $this->emails_delivered) * 100, 2);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->emails_delivered == 0) return 0.0;
        return round(($this->emails_clicked / $this->emails_delivered) * 100, 2);
    }

    public function getBounceRateAttribute(): float
    {
        if ($this->emails_sent == 0) return 0.0;
        return round(($this->emails_bounced / $this->emails_sent) * 100, 2);
    }

    public function getComplaintRateAttribute(): float
    {
        if ($this->emails_sent == 0) return 0.0;
        return round(($this->emails_complained / $this->emails_sent) * 100, 2);
    }
}
