<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'postcode',
        'address',
        'service_type',
        'project_type',
        'property_type',
        'estimated_budget',
        'timeframe',
        'stage',
        'source',
        'ai_score',
        'ai_classification',
        'ai_qualification_summary',
        'ai_recommended_action',
        'estimated_value',
        'assigned_to',
        'notes',
    ];

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function tasks()
    {
        return $this->hasMany(TaskReminder::class);
    }

    /**
     * Compute AI score dynamically if not set
     */
    public function calculateAiScore(): array
    {
        $score = 40; // baseline

        // Budget check
        if (str_contains($this->estimated_budget ?? '', '50,000') || str_contains($this->estimated_budget ?? '', '15,000')) {
            $score += 25;
        } elseif (str_contains($this->estimated_budget ?? '', '6,000')) {
            $score += 15;
        }

        // Timeframe check
        if (stripos($this->timeframe ?? '', 'Immediate') !== false || stripos($this->timeframe ?? '', '1 month') !== false) {
            $score += 25;
        } elseif (stripos($this->timeframe ?? '', '3 months') !== false) {
            $score += 15;
        }

        // Project type
        if ($this->project_type === 'Commercial' || $this->project_type === 'Trade') {
            $score += 10;
        }

        // Postcode / Contact validity
        if (!empty($this->phone) && !empty($this->postcode)) {
            $score += 10;
        }

        $score = min(98, max(20, $score));

        $classification = 'Warm';
        if ($score >= 75) {
            $classification = 'Hot';
        } elseif ($score < 45) {
            $classification = 'Cold';
        }

        return [
            'score' => $score,
            'classification' => $classification,
        ];
    }
}
