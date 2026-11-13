<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'body_text',
        'category',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function campaigns()
    {
        return $this->hasMany(MarketingCampaign::class, 'template_id');
    }

    /**
     * Render template replacing dynamic placeholders
     */
    public function render(MarketingContact $contact): array
    {
        $variables = [
            '{{name}}' => e($contact->name),
            '{{company}}' => e($contact->company ?? 'your firm'),
            '{{email}}' => e($contact->email),
            '{{website}}' => e($contact->website ?? ''),
            '{{unsubscribe_url}}' => $contact->unsubscribe_url,
        ];

        $subject = str_replace(array_keys($variables), array_values($variables), $this->subject);
        $html = str_replace(array_keys($variables), array_values($variables), $this->body_html);
        $text = $this->body_text ? str_replace(array_keys($variables), array_values($variables), $this->body_text) : strip_tags($html);

        return [
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
        ];
    }
}
