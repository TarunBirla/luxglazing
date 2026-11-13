<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingSuppression extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'reason',
        'source',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Suppress an email address across the platform
     */
    public static function suppress(string $email, string $reason, ?string $source = null, array $metadata = []): self
    {
        $normalized = strtolower(trim($email));

        // Update contact status if exists
        MarketingContact::where('email', $normalized)->update([
            'subscription_status' => $reason === 'unsubscribe' ? 'unsubscribed' : ($reason === 'complaint' ? 'complained' : 'suppressed'),
            'unsubscribed_at' => now(),
        ]);

        return static::updateOrCreate(
            ['email' => $normalized],
            [
                'reason' => $reason,
                'source' => $source,
                'metadata' => $metadata,
                'created_at' => now(),
            ]
        );
    }

    public static function isSuppressed(string $email): bool
    {
        return static::where('email', strtolower(trim($email)))->exists();
    }
}
