<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'contact_id',
        'recipient_email',
        'recipient_name',
        'subject',
        'body_html',
        'body_text',
        'ses_message_id',
        'status',
        'generation_mode',
        'ai_prompt_used',
        'openai_tokens',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'complained_at',
        'bounce_type',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'complained_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(MarketingCampaign::class, 'campaign_id');
    }

    public function contact()
    {
        return $this->belongsTo(MarketingContact::class, 'contact_id');
    }

    public function events()
    {
        return $this->hasMany(MarketingEmailEvent::class, 'email_id');
    }
}
