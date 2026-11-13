<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketingContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'website',
        'phone',
        'business_type',
        'verification_status',
        'consent_status',
        'consent_source',
        'consent_date',
        'consent_ip',
        'subscription_status',
        'unsubscribe_token',
        'unsubscribed_at',
        'meta',
    ];

    protected $casts = [
        'consent_status' => 'boolean',
        'consent_date' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($contact) {
            if (empty($contact->unsubscribe_token)) {
                $contact->unsubscribe_token = Str::random(40) . bin2hex(random_bytes(16));
            }
            if (empty($contact->consent_date) && $contact->consent_status) {
                $contact->consent_date = now();
            }
        });
    }

    public function emails()
    {
        return $this->hasMany(MarketingEmail::class, 'contact_id');
    }

    public function events()
    {
        return $this->hasMany(MarketingEmailEvent::class, 'contact_id');
    }

    public function consentLogs()
    {
        return $this->hasMany(MarketingConsentLog::class, 'contact_id');
    }

    /**
     * Check if contact is legally permitted to receive marketing emails
     */
    public function canReceiveMarketing(): bool
    {
        if (!$this->consent_status) {
            return false;
        }

        if ($this->subscription_status !== 'subscribed') {
            return false;
        }

        // Check central suppression list
        return !MarketingSuppression::where('email', strtolower($this->email))->exists();
    }

    public function getUnsubscribeUrlAttribute(): string
    {
        return route('marketing.unsubscribe', ['token' => $this->unsubscribe_token]);
    }
}
