<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'target_audience',
        'template_body',
        'status',
        'recipients_count',
        'opened_count',
        'replied_count',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
